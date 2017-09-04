<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Shop (ShopController)
 * Area Class to control all shop related operations.
 * @author : The jin hu
 * @version : 1.0
 * @since : 12 August 2017
 */
class ordermanage extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('order_model');
        $this->load->model('shop_model');
        $this->isLoggedIn();
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->buyListing();
    }

    /**
     * This function is used to load the user list
     */
    function buyListing($searchType = '0', $name = 'ALL', $stDate = '', $enDate = '', $status = '0')
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '订单管理';
            if ($name == 'ALL') $name = '';
            if ($stDate == '0') $stDate = '';
            if ($enDate == '0') $enDate = '';
            $this->global['buyList'] = $this->order_model->getBuyOrders($searchType, $name, $stDate, $enDate, $status);
            $this->global['authList'] = $this->global['buyList'];
            $this->global['showList'] = '1';
            $this->global['searchType'] = $searchType;
            $this->global['searchName'] = $name;
            $this->global['startDate'] = $stDate;
            $this->global['endDate'] = $enDate;
            $this->global['searchStatus'] = $status;
            $this->global['searchTypeAuth'] = $searchType;
            $this->global['searchNameAuth'] = $name;
            $this->global['startDateAuth'] = $stDate;
            $this->global['endDateAuth'] = $enDate;
            $this->global['searchStatusAuth'] = $status;
            $this->loadViews("ordermanage", $this->global, NULL, NULL);
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
            $status = $_POST['status'];
            if ($name == 'ALL') $name = '';
            if ($stDate == '0') $stDate = '';
            if ($enDate == '0') $enDate = '';

            $buyList = $this->order_model->getBuyOrders($searchType, $name, $stDate, $enDate, $status);

            $ret['data'] = $this->output_buy_listing($buyList);
            $ret['status'] = 'success';
        }
        echo json_encode($ret);
    }

    function output_buy_listing($buyList)
    {
        $output_html = '';

        $Count = count($buyList);
        for ($i = 0; $i < $Count; $i++) {
            $item = $buyList[$i];
            $shop = $this->shop_model->getShopById($item->shop_name);
            if (count($shop) > 0)
                if ($shop->status != 0) continue;
            if ($this->global['shop_manager_number'] != '') {
                if (count($shop) == 0) continue;
                if ($shop->phonenumber != $this->global['shop_manager_number']) continue;
            }

            $output_html .= '<tr>';
            $output_html .= '<td>' . $item->number . '</td>';
            $output_html .= '<td>' . $item->mobile . '</td>';
            $output_html .= '<td>' . $item->price . '</td>';

            $point_listitem = json_decode($item->point_list);
            $cs_name = '';
            if (count($point_listitem) > 0) {
                foreach ($point_listitem as $pointitem) {
                    if ($cs_name == '') $cs_name = $pointitem->name;
                    else $cs_name = $cs_name . ' - ' . $pointitem->name;
                }
            }
            $output_html .= '<td>' . (($item->type == 1) ? $cs_name : $item->tour_area) . '</td>';
            $output_html .= '<td>';
            if ($item->tour_point != 0) {
                $attr_id = explode('_', $item->tour_point);
                $item->tour_point = $attr_id[1];
                $pointitem = $point_listitem[$item->tour_point - 1];
            }
            $output_html .= (($item->tour_point == 0) ? '所有' : $pointitem->name);
            $output_html .= '</td>';
            $shopitem = $this->shop_model->getShopById($item->shop_name);
            if ($this->global['shop_manager_number'] == '') {
                $output_html .= '<td>' . (isset($shopitem->name) ? $shopitem->name : '') . '</td>';
            }
            $output_html .= '<td>';
            $output_html .= ($item->status == '1' ? '使用中' : ($item->status == '2' ? '未付款' :
                ($item->status == '3' ? '已取消' : ($item->status == '4' ? '已过期' : ''))));
            $output_html .= '</td>';
            $output_html .= '<td>' . $item->ordered_time . '</td>';
            $output_html .= '</tr>';
        }

        return $output_html;
    }

    /**
     * This function is used to load the user list
     */
    function authListing($searchType, $name, $stDate, $enDate, $status)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '订单管理';
            if ($name == 'ALL') $name = '';
            if ($stDate == '0') $stDate = '';
            if ($enDate == '0') $enDate = '';
            $this->global['authList'] = $this->order_model->getOrders($searchType, $name, $stDate, $enDate, $status);
            $this->global['buyList'] = $this->global['authList'];
            $this->global['showList'] = '2';
            $this->global['searchType'] = $searchType;
            $this->global['searchName'] = $name;
            $this->global['startDate'] = $stDate;
            $this->global['endDate'] = $enDate;
            $this->global['searchStatus'] = $status;
            $this->global['searchTypeAuth'] = $searchType;
            $this->global['searchNameAuth'] = $name;
            $this->global['startDateAuth'] = $stDate;
            $this->global['endDateAuth'] = $enDate;
            $this->global['searchStatusAuth'] = $status;
            $this->loadViews("ordermanage", $this->global, NULL, NULL);
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
            $status = $_POST['status'];
            if ($name == 'ALL') $name = '';
            if ($stDate == '0') $stDate = '';
            if ($enDate == '0') $enDate = '';

            $authList = $this->order_model->getOrders($searchType, $name, $stDate, $enDate, $status);

            $ret['data'] = $this->output_auth_listing($authList);
            $ret['status'] = 'success';
        }
        echo json_encode($ret);
    }

    function output_auth_listing($authList)
    {
        $output_html = '';

        $authCount = count($authList);
        for ($i = 0; $i < $authCount; $i++) {
            $item = $authList[$i];
            $shop = $this->shop_model->getShopById($item->shop_name);
            if (count($shop) > 0)
                if ($shop->status != 0) continue;
            if ($this->global['shop_manager_number'] != '') {
                if (count($shop) == 0) continue;
                if ($shop->phonenumber != $this->global['shop_manager_number']) continue;
            }

            $output_html .= '<tr>';
            $output_html .= '<td>' . $item->number . '</td>';
            $output_html .= '<td>' . $item->mobile . '</td>';
            $output_html .= '<td>' . $item->price . '</td>';
            $output_html .= '<td>';
            $output_html .= ($item->status == '1' ? '先付款' : ($item->status == '2' ? '后付款' : '后付款'));
            $output_html .= '</td>';

            $point_listitem = json_decode($item->point_list);
            $cs_name = '';
            if (count($point_listitem) > 0) {
                foreach ($point_listitem as $pointitem) {
                    if ($cs_name == '') $cs_name = $pointitem->name;
                    else $cs_name = $cs_name . ' - ' . $pointitem->name;
                }
            }
            $output_html .= '<td>' . (($item->type == 1) ? $cs_name : $item->tour_area) . '</td>';

            $sh = $this->shop_model->getShopById($item->shop_name);
            if ($this->global['shop_manager_number'] == '') {
                $output_html .= '<td>' . ((isset($sh->name)) ? $sh->name : '') . '</td>';
            }
            $output_html .= '<td>' . $item->ordered_time . '</td>';
            $output_html .= '</tr>';
        }

        return $output_html;
    }

    function pageNotFound()
    {
        $this->global['pageTitle'] = 'CodeInsect : 404 - Page Not Found';

        $this->loadViews("404", $this->global, NULL, NULL);
    }
}

/* End of file ordermanage.php */
/* Location: .application/controllers/ordermanage.php */


?>