<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Shop (ShopController)
 * Area Class to control all shop related operations.
 * @author : The jin hu
 * @version : 1.0
 * @since : 12 August 2017
 */
class shop extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('area_model');
        $this->load->model('shop_model');
        $this->load->model('order_model');
        $this->load->model('auth_model');
        $this->load->model('user_model');
        $this->isLoggedIn();
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->shop();
    }

    /**
     * This function is used to load the user list
     */
    function listing($name, $address, $status)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '景区管理';
            $this->global['areaList'] = $this->area_model->getAreas($name, $address, $status);
            $this->global['searchName'] = $name;
            $this->global['searchAddress'] = $address;
            $this->global['searchStatus'] = $status;

            $this->loadViews("area", $this->global, NULL, NULL);
        }
    }

    /**
     * This function is used to load the edit form
     */
    function edit($id)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '编辑景区';
            $this->global['area'] = $this->area_model->getAreaById($id);

            $this->loadViews("area-add", $this->global, NULL, NULL);
        }
    }

    /**
     * This function used to load the first screen of the shop
     */
    public function shop()
    {
        $this->global['pageTitle'] = '商家管理';
        $this->global['shopList'] = $this->shop_model->getShops('','all',0,$this->global['shop_manager_number']);
        $this->global['areaList'] = $this->area_model->getAreas('', 'all', '1'); //1-available, 2-disable
        $this->global['courseList'] = $this->area_model->getCourses('', '1'); //1-available, 2-disable
        $this->global['searchName'] = '';
        $this->global['searchAddress'] = '';
        $this->global['searchStatus'] = 0;
        $this->loadViews("shop", $this->global, NULL, NULL);
    }


    function shop_listing()
    {
        $ret = array(
            'data' => '',
            'status' => 'fail'
        );
        if (!empty($_POST)) {

            $name = $_POST['name'];
            $status = $_POST['status'];
            $address = $_POST['address'];

            $shopList = $this->shop_model->getShops($name, $address, $status,$this->global['shop_manager_number']);
            $ret['data'] = $this->output_shop($shopList);
            $ret['status'] = 'success';
        }
        echo json_encode($ret);
    }


    function output_shop($shopList)
    {
        $output_html = '';
        $shopCount = count($shopList);
        for ($i = 0; $i < $shopCount; $i++) {
            $shop = $shopList[$i];
            if($shop->id==0) continue;

            $output_html .= '<tr>';
            $output_html .= '<td>' . $shop->name . '</td>';
            $output_html .= '<td>' . ($shop->type == '1' ? '旅行社' : ($shop->type == '2' ? '渠道商' : '')) . '</td>';
            $output_html .= '<td>' . $this->order_model->getAreaCountByShopId($shop->id, 1) . '</td>';
            $output_html .= '<td>' . $this->order_model->getAreaCountByShopId($shop->id, 2) . '</td>';
            $output_html .= '<td>' . $this->auth_model->getAuthCountByShopId($shop->id) . '</td>';
            $output_html .= '<td>' . $shop->address_1 . '</td>';
            $output_html .= '<td>' . ($shop->status == 1 ? '已禁用' : '未禁用') . '</td>';
            $output_html .= '<td>';
            $output_html .= '<a href="' . base_url() . 'showshop/' . $shop->id . '">查看 &nbsp;&nbsp;</a>';
            $output_html .= '<a href="' . base_url() . 'editshop/' . $shop->id . '">编辑 &nbsp;&nbsp;</a>';
            if ($shop->status == '0') {
                $output_html .= '<a href="#" onclick="deleteShopConfirm(' . $shop->id . ')">删除 &nbsp;&nbsp;</a>';
            }
            if ($shop->status == '0') {
                $output_html .= '<a href="#" onclick="deployShopConfirm(' . $shop->id . ')">禁用 &nbsp;&nbsp;</a>';
            } else {
                $output_html .= '<a href="#" onclick="undeployShopConfirm(' . $shop->id . ')">取消禁用 &nbsp;&nbsp;</a>';
            }
            $output_html .= '<a href="#" onclick="showGenerateQR(' . $shop->id . ')">生成二维码 &nbsp;&nbsp;</a>';
            $output_html .= '<a href="#" onclick="showGenerateAuth(' . $shop->id . ')">发放授权码 &nbsp;&nbsp;</a>';

            $output_html .= '</td>';
            $output_html .= '</tr>';
        }

        return $output_html;
    }


    /**
     * This function used to load the first screen of the shop
     */
    public function qr()
    {
        $this->global['pageTitle'] = '二维码管理';

        $qrList = array();
        $qrDBList = $this->shop_model->getQR();

        foreach ($qrDBList as $list) {
            $areaid = $list->targetid;
            $areaInfo = $this->area_model->getAreaById($areaid);

            $info = array('target' => $areaInfo->name,
                'shop' => $list->name,
                'type' => $list->type == '1' ? '旅游线路' : '景区',
                'time' => $list->created_time,
                'id' => $list->id);
            array_push($qrList, $info);
        }

        $this->global['qrList'] = $qrList;

        $this->global['searchName'] = '';
        $this->global['searchStatus'] = 0;
        $this->loadViews("qrmanage", $this->global, NULL, NULL);
    }


    /**
     * This function is used to load the add Course form
     */
    function addShop()
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '新增商家';
            $this->global['areaList'] = $this->area_model->getAreas();
            $this->loadViews("shop-add", $this->global, NULL, NULL);
        }
    }

    /**
     * This function is used to load the user list
     */
    function shoplisting($name, $status)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '商家管理';
            $this->global['shopList'] = $this->shop_model->getShops($name, '', $status);
            $this->global['areaList'] = $this->area_model->getAreas();
            $this->global['courseList'] = $this->area_model->getCourses();
            $this->global['searchName'] = $name;
            $this->global['searchAddress'] = '';
            $this->global['searchStatus'] = $status;
            $this->loadViews("shop", $this->global, NULL, NULL);
        }
    }


    /**
     * This function is used to load the user list
     */
    function qrlisting($name, $status)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '二维码管理';

            $qrList = array();
            $qrDBList = $this->shop_model->getQR($name, $status);

            foreach ($qrDBList as $list) {
                $areaid = $list->targetid;
                $areaInfo = $this->area_model->getAreaById($areaid);

                $info = array('target' => $areaInfo->name,
                    'shop' => $list->name, 'type' => $list->type == '1' ? '旅游线路' : '景区',
                    'time' => $list->created_time,
                    'id' => $list->id);
                array_push($qrList, $info);
            }

            $this->global['qrList'] = $qrList;

            $this->global['searchName'] = $name;
            $this->global['searchStatus'] = $status;
            $this->loadViews("qrmanage", $this->global, NULL, NULL);
        }
    }

    /**
     * This function is used to load the edit form
     */
    function editshop($id)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '编辑商家';
            $this->global['shop'] = $this->shop_model->getShopById($id);

            $this->loadViews("shop-add", $this->global, NULL, NULL);
        }
    }

    /**
     * This function is used to load the edit form
     */
    function showshop($id)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '查看商家';
            $this->global['shop'] = $this->shop_model->getShopById($id);
            $this->global['shopqrcode'] = $this->shop_model->getQRByShopId($id);
            $authcodelist = NULL;
            $auths = $this->auth_model->getAuthsByShopId($id);
            if (count($auths) > 0) {
                $i = 0;
                foreach ($auths as $authItem) {
                    $authCodes = $this->auth_model->getAuthOrdersByAuthId($authItem->id);
                    if (count($authCodes)==0) continue;
                    foreach ($authCodes as $codeItem) {
                        $authcodelist[$i] = [
                            'id' => $i,
                            'value' => $codeItem->code,
                        ];
                        $i++;
                    }
                }
            }
            $this->global['shopauthcode'] = $authcodelist;

            $this->loadViews("shop-show", $this->global, NULL, NULL);
        }
    }


    function pageNotFound()
    {
        $this->global['pageTitle'] = 'CodeInsect : 404 - Page Not Found';

        $this->loadViews("404", $this->global, NULL, NULL);
    }
}

/* End of file shop.php */
/* Location: .application/controllers/shop.php */

?>