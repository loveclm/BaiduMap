<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Shop (ShopController)
 * Area Class to control all shop related operations.
 * @author : The jin hu
 * @version : 1.0
 * @since : 12 August 2017
 */
class authmanage extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('auth_model');
        $this->load->model('area_model');
        $this->isLoggedIn();
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->listing('0', '', '0');
    }

    /**
     * This function is used to load the user list
     */
    function listing($searchType, $name, $status)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '授权码管理';
            $auths = $this->auth_model->getAuths($searchType, $name, $status);
            $authList = NULL;
            if (count($auths) > 0) {
                $i = 0;
                foreach ($auths as $list) {
                    $areaid = $list->targetid;
                    $areaInfo = $this->area_model->getAreaById($areaid);
                    if ($this->global ['shop_manager_number'] != ''
                        && $this->global ['shop_manager_number'] != $list->shopnumber
                    ) continue;

                    $list->tourName = $this->area_model->getCourseNameByAreaId($areaid);
                    if ($searchType == 1 && strstr($name, $list->tourName) == false) continue; // 0-shopname search, 1-coursename search
                    $authList[$i] = $list;
                    $i++;
                }
            }
            $this->global['authList'] = $authList;
            $this->global['searchType'] = $searchType;
            $this->global['searchName'] = $name == 'ALL' ? '' : utf8_decode($name);
            $this->global['searchStatus'] = $status;
            $this->loadViews("authmanage", $this->global, NULL, NULL);
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
            $status = $_POST['status'];
            $auths = $this->auth_model->getAuths($searchType, $name, $status);
            $authList = NULL;
            if (count($auths) > 0) {
                $i = 0;
                foreach ($auths as $list) {
                    $areaid = $list->targetid;
                    $areaInfo = $this->area_model->getAreaById($areaid);
                    if ($this->global ['shop_manager_number'] != ''
                        && $this->global ['shop_manager_number'] != $list->shopnumber
                    ) continue;
                    $list->tourName = $this->area_model->getCourseNameByAreaId($areaid);
                    //var_dump($name.',,,,,'.$list->tourName.',,,,,,'.strstr($list->tourName, $name));
                    if ($searchType == 1 && $name != 'ALL' && strstr($list->tourName, $name) == false) continue; // 0-shopname search, 1-coursename search
                    $authList[$i] = $list;
                    $i++;
                }
            }
            $ret['data'] = $this->output_auth($authList);
            $ret['status'] = 'success';
        }
        echo json_encode($ret);
    }

    function output_auth($authList)
    {
        $output_html = '';
        $authCount = count($authList);
        for ($i = 0; $i < $authCount; $i++) {
            $item = $authList[$i];

            $output_html .= '<tr>';
            if ($this->global['shop_manager_number'] == '') {
                $output_html .= '<td>' . $item->shopName . '</td>';
            }
            $output_html .= '<td>' . $item->tourName . '</td>';
            $output_html .= '<td>' . $this->auth_model->getOrderTotal($item->id) . '</td>';
            $output_html .= '<td>' . $this->auth_model->getOrderUsed($item->id) . '</td>';
            $output_html .= '<td>' . $item->created . '</td>';
            $output_html .= '<td>' . ($item->status != 0 ? ($item->money > 0 ? '先付款' : '后付款') : '') . '</td>';
            $output_html .= '<td>' . ($item->money > 0 ? $item->money : '') . '</td>';
            $output_html .= '<td>';
            $output_html .= '<a href="' . base_url() . 'authDetail/' . $item->id . '/0">查看 &nbsp;</a>';
            if ($item->money == 0 && $this->global['shop_manager_number'] == '') {
                $output_html .= '<a href="#" onclick="showSelect(' . $item->id . ')">付款方式 &nbsp;</a>';
            }
            $output_html .= '</td>';
            $output_html .= '</tr>';
        }

        return $output_html;
    }


    /**
     * This function is used to load the edit form
     */
    function detail($id, $status)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '查看详情';
            $this->global['authList'] = $this->auth_model->getAuthOrders($id, $status);
            $this->global['searchStatus'] = $status;
            $this->global['authid'] = $id;

            $this->loadViews("authdetail", $this->global, NULL, NULL);
        }
    }

    /**
     * This function is used to load the edit form
     */
    function addmoney($money, $id)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $result = $this->auth_model->updateMoney($id, $money);
            return redirect('authmanage');
        }
    }

    /**
     * This function is used to load the edit form
     */
    function orderdetail($id)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '订单详情';
            $this->global['orderitem'] = $this->auth_model->getAuthOrder($id);

            $this->loadViews("authorderdetail", $this->global, NULL, NULL);
        }
    }

    function pageNotFound()
    {
        $this->global['pageTitle'] = 'CodeInsect : 404 - Page Not Found';

        $this->loadViews("404", $this->global, NULL, NULL);
    }
}

/* End of file authmanage.php */
/* Location: .application/controllers/authmanage.php */


?>