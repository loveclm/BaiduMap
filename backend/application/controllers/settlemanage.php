<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Shop (ShopController)
 * Area Class to control all shop related operations.
 * @author : The jin hu
 * @version : 1.0
 * @since : 12 August 2017
 */
class settlemanage extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('settle_model');
        $this->load->model('order_model');
        $this->load->model('shop_model');
        $this->isLoggedIn();
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->buyListing('0', 'ALL', '', '', '0');
    }

    /**
     * This function is used to load the user list
     */
    function buyListing($searchType, $name, $stDate, $enDate)
    {

        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '结算管理';
            if ($name == 'ALL') $name = '';
            if ($stDate == '0') $stDate = '';
            if ($enDate == '0') $enDate = '';
            $this->global['buyList'] = $this->settle_model->getBuySettles($searchType,
                $name, $stDate, $enDate, $this->global['shop_manager_number']);

            $this->global['authList'] = $this->global['buyList'];
            $this->global['showList'] = '1';
            $this->global['searchType'] = $searchType;
            $this->global['searchName'] = $name;
            $this->global['startDate'] = $stDate;
            $this->global['endDate'] = $enDate;
            $this->global['searchTypeAuth'] = $searchType;
            $this->global['searchNameAuth'] = $name;
            $this->global['startDateAuth'] = $stDate;
            $this->global['endDateAuth'] = $enDate;
            $this->loadViews("settlemanage", $this->global, NULL, NULL);
        }
    }

    function buy_listing()
    {
        $ret = array(
            'data' => '',
            'status' => 'fail'
        );
        if (!empty($_POST)) {

            $searchType = $_POST['searchType'];
            $name = $_POST['name'];
            $stDate = $_POST['stDate'];
            $enDate = $_POST['enDate'];
            if ($name == 'ALL') $name = '';
            if ($stDate == '0') $stDate = '';
            if ($enDate == '0') $enDate = '';

            $buyList = $this->settle_model->getBuySettles($searchType,
                $name, $stDate, $enDate, $this->global['shop_manager_number']);

            $ret['data'] = $this->output_buy_listing($buyList);
            $ret['status'] = 'success';
        }
        echo json_encode($ret);
    }

    function output_buy_listing($buyList)
    {
        $output_html = '';
        $Count = count($buyList);
        $sumTotal = 0;
        $sumSettled = 0;
        $sumFee = 0;
        for ($i = 0; $i <= $Count; $i++) {
            if (!isset($buyList[$i])) continue;
            $item = $buyList[$i];
            $shops = $item['shops'];
            $j = 0;

            $output_html .= '<tr>';
            $output_html .= '<td rowspan="' . (count($shops) > 0 ? count($shops) : '1') .
                '" style="vertical-align:middle;">' . $item['month_name'] . '</td>';
            foreach ($shops as $sh) {
                $j++;
                $price = floatval($sh->address_2->price);
                $status = $sh->status != "" ? $sh->status->status : "";
                $settle = $price * floatval($sh->discount_rate);
                $fee = $price - $settle;
                $sumTotal += $price;
                $sumFee += $fee;
                $sumSettled += $settle;

                $output_html .= (($j != 1) ? '<tr>' : '');
                if ($this->global['shop_manager_number'] == '') {
                    $output_html .= '<td>' . $sh->phonenumber . '</td>';
                    $output_html .= '<td>' . $sh->name . '</td>';
                }
                $output_html .= '<td>' . $price . '</td>';
                $output_html .= '<td>' . $fee . '</td>';
                $output_html .= '<td>' . $settle . '</td>';
                $output_html .= '<td>' . ($status == 0 ? '未结算' : '已结算') . '</td>';

                $output_html .= '<td>';
                $output_html .= '<a href="#" onclick="settleBuyDetail(\'' . base_url() . '\',\''.$sh->id.'\')">查看订单&nbsp;&nbsp;</a>';

                if ($this->global['shop_manager_number'] == '') {
                    if ($status == 0) {
                        $output_html .= '<a href="#" onclick="showConfirmBuy(\'' . $item['month_name'] . '\',\'' . $sh->id . '\')">结算&nbsp;&nbsp;</a>';
                    }
                }
                $output_html .= '</td>';
                $output_html .= (($j != 1) ? '</tr>' : '');
            }
            $output_html .= '</tr>';
        }
        $output_html .= '<tr style="background: #fd8f23;">';
        $output_html .= '<td>合计</td>';
        if ($this->global['shop_manager_number'] == '') {
            $output_html .= '<td>---</td>';
            $output_html .= '<td>---</td>';
        }
        $output_html .= '<td>' . $sumTotal . '</td>';
        $output_html .= '<td>' . $sumFee . '</td>';
        $output_html .= '<td>' . $sumSettled . '</td>';
        $output_html .= '<td>---</td>';
        $output_html .= '<td>---</td>';
        $output_html .= '</tr>';

        return $output_html;
    }

    /**
     * This function is used to load the user list
     */
    function authListing($searchType, $name, $stDate, $enDate)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '结算管理';
            if ($name == 'ALL') $name = '';
            if ($stDate == '0') $stDate = '';
            if ($enDate == '0') $enDate = '';
            $this->global['authList'] = $this->settle_model->getAuthSettles($searchType,
                $name, $stDate, $enDate, $this->global['shop_manager_number']);
            $this->global['buyList'] = $this->global['authList'];
            $this->global['showList'] = '2';
            $this->global['searchType'] = $searchType;
            $this->global['searchName'] = $name;
            $this->global['startDate'] = $stDate;
            $this->global['endDate'] = $enDate;
            $this->global['searchTypeAuth'] = $searchType;
            $this->global['searchNameAuth'] = $name;
            $this->global['startDateAuth'] = $stDate;
            $this->global['endDateAuth'] = $enDate;
            $this->loadViews("settlemanage", $this->global, NULL, NULL);
        }
    }

    function auth_listing()
    {
        $ret = array(
            'data' => '',
            'status' => 'fail'
        );
        if (!empty($_POST)) {

            $searchType = $_POST['searchType'];
            $name = $_POST['name'];
            $stDate = $_POST['stDate'];
            $enDate = $_POST['enDate'];
            if ($name == 'ALL') $name = '';
            if ($stDate == '0') $stDate = '';
            if ($enDate == '0') $enDate = '';

            $authList = $this->settle_model->getAuthSettles($searchType,
                $name, $stDate, $enDate, $this->global['shop_manager_number']);

            $ret['data'] = $this->output_auth_listing($authList);
            $ret['status'] = 'success';
        }
        echo json_encode($ret);
    }

    function output_auth_listing($authList)
    {
        $output_html = '';

        $Count = count($authList);
        $sumTotal = 0;
        $sumCount = 0;
        for ($i = 0; $i <= $Count; $i++) {
            if (!isset($authList[$i])) continue;
            $item = $authList[$i];
            $shops = $item['shops'];
            $j = 0;

            $output_html .= '<tr>';
            $output_html .= '<td rowspan="' . (count($shops) > 0 ? count($shops) : '1') .
                '" style="vertical-align:middle;">' . $item['month_name'] . '</td>';
            foreach ($shops as $sh) {
                $j++;
                $price = floatval($sh->address_2->price);
                $status = $sh->status != "" ? $sh->status->status : "";
                if (isset($sh->address_2->codeCount))
                    $codeCount = floatval($sh->address_2->codeCount);
                else
                    $codeCount = 0;
                $sumTotal += $price;
                $sumCount += $codeCount;

                $output_html .= (($j != 1) ? '<tr>' : '');
                if ($this->global['shop_manager_number'] == '') {
                    $output_html .= '<td>' . $sh->phonenumber . '</td>';
                    $output_html .= '<td>' . $sh->name . '</td>';
                }
                $output_html .= '<td>' . $codeCount . '</td>';
                $output_html .= '<td>' . $price . '</td>';
                $output_html .= '<td>' . ($status == 0 ? '未结算' : '已结算') . '</td>';

                $output_html .= '<td>';
                $output_html .= '<a href="#" onclick="settleAuthDetail(\'' . base_url() . '\',\''.$sh->id.'\')">查看订单&nbsp;&nbsp;</a>';

                if ($this->global['shop_manager_number'] == '') {
                    if ($status == 0) {
                        $output_html .= '<a href="#" onclick="showConfirmAuth(\'' . $item['month_name'] . '\',\'' . $sh->id . '\')">结算&nbsp;&nbsp;</a>';
                    }
                }
                $output_html .= '</td>';
                $output_html .= (($j != 1) ? '</tr>' : '');
            }
            $output_html .= '</tr>';
        }
        $output_html .= '<tr style="background: #fd8f23;">';
        $output_html .= '<td>合计</td>';
        if ($this->global['shop_manager_number'] == '') {
            $output_html .= '<td>---</td>';
            $output_html .= '<td>---</td>';
        }
        $output_html .= '<td>' . $sumCount . '</td>';
        $output_html .= '<td>' . $sumTotal . '</td>';
        $output_html .= '<td>---</td>';
        $output_html .= '<td>---</td>';
        $output_html .= '</tr>';

        return $output_html;
    }

    /**
     * This function is used to load the user list
     */
    function settleBuyDetail($searchType, $name, $stDate, $enDate, $shopid)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '订单管理';
            if ($name == 'ALL') $name = '';
            if ($stDate == '0') $stDate = '';
            if ($enDate == '0') $enDate = '';
            $this->global['buyList'] = $this->order_model->getBuyOrders($searchType, $name, $stDate, $enDate, '0');
            $this->global['authList'] = $this->global['buyList'];
            $this->global['showList'] = '1';
            $this->global['searchType'] = $searchType;
            $this->global['searchName'] = $name;
            $this->global['startDate'] = $stDate;
            $this->global['endDate'] = $enDate;
            $this->global['searchTypeAuth'] = $searchType;
            $this->global['searchNameAuth'] = $name;
            $this->global['startDateAuth'] = $stDate;
            $this->global['endDateAuth'] = $enDate;
            $this->global['shop_id'] = $shopid;
            $this->loadViews("settlebuydetail", $this->global, NULL, NULL);
        }
    }

    /**
     * This function is used to load the user list
     */
    function settleAuthDetail($searchType, $name, $stDate, $enDate, $shopid)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '订单管理';
            if ($name == 'ALL') $name = '';
            if ($stDate == '0') $stDate = '';
            if ($enDate == '0') $enDate = '';
            $this->global['authList'] = $this->order_model->getOrders($searchType, $name, $stDate, $enDate, '0');
            $this->global['buyList'] = $this->global['authList'];
            $this->global['showList'] = '2';
            $this->global['searchType'] = $searchType;
            $this->global['searchName'] = $name;
            $this->global['startDate'] = $stDate;
            $this->global['endDate'] = $enDate;
            $this->global['searchTypeAuth'] = $searchType;
            $this->global['searchNameAuth'] = $name;
            $this->global['startDateAuth'] = $stDate;
            $this->global['endDateAuth'] = $enDate;
            $this->global['shop_id'] = $shopid;
            $this->loadViews("settleauthdetail", $this->global, NULL, NULL);
        }
    }

    function performAuthSettle($mon_name, $shopid, $price)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $setInfo = [
                'settlement_date' => $mon_name,
                'shopid' => $shopid,
                'price' => $price,
                'status' => '1'
            ];
            $result = $this->settle_model->updateAuthSettle($setInfo);

            return redirect('settleAuthListing/0/ALL/0/0');
        }
    }

    function performBuySettle($mon_name, $shopid)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $setInfo = [
                'settlement_date' => $mon_name,
                'shopid' => $shopid,
                'status' => '1'
            ];
            $result = $this->settle_model->updateBuySettle($setInfo);

            return redirect('settlemanage');
        }
    }

    function confirmPay($orderId = "")
    {
//先确认用户是否登录
        $this->ensureLogin();
//通过订单编号获取订单数据
        $order = $this->wxpay_model->get($orderId);
//验证订单是否是当前用户
        $this->_verifyUser($order);

//取得支付所需要的订单数据
        $orderData = $this->returnOrderData[$orderId];
//取得jsApi所需要的数据
        $wxJsApiData = $this->wxpay_model->wxPayJsApi($orderData);
//将数据分配到模板去，在js里使用
        $this->smartyData['wxJsApiData'] = json_encode($wxJsApiData, JSON_UNESCAPED_UNICODE);
        $this->smartyData['order'] = $orderData;
        $this->displayView('wxpay/confirm.tpl');
//        var_dump($_GET);
    }

    function pageNotFound()
    {
        $this->global['pageTitle'] = 'CodeInsect : 404 - Page Not Found';

        $this->loadViews("404", $this->global, NULL, NULL);
    }
}

/* End of file settlemanage.php */
/* Location: .application/controllers/settlemanage.php */


?>