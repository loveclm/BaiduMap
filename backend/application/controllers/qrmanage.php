<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Shop (ShopController)
 * Area Class to control all shop related operations.
 * @author : The jin hu
 * @version : 1.0
 * @since : 12 August 2017
 */
class qrmanage extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('area_model');
        $this->load->model('shop_model');
        $this->isLoggedIn();
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->viewFirst();
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
            $this->global['searchType'] = 0;
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
    public function viewFirst()
    {
        $this->global['pageTitle'] = '二维码管理';

        $qrList = array();
        $qrDBList = $this->shop_model->getQR();

        foreach ($qrDBList as $list) {
            $areaid = $list->targetid;
            $areaInfo = $this->area_model->getAreaById($areaid);
            if (isset($areaInfo)) {
                $courseName = $this->area_model->getCourseNameByAreaId($areaid);
                $areaInfo->name = $courseName;
                if ($this->global['shop_manager_number'] != ''
                    && $this->global['shop_manager_number'] != $list->shopnumber
                ) continue;

                $info = array('target' => $areaInfo->name,
                    'shop' => $list->name,
                    'type' => $list->type == '1' ? '旅游线路' : '景区',
                    'time' => $list->created_time,
                    'id' => $list->id,
                    'targetid' => $areaid,
                    'areatype' => $list->type,
                    'shopid' => $list->shopid
                );
                array_push($qrList, $info);
            }
        }

        $this->global['qrList'] = $qrList;
        $this->global['searchType'] = 0;
        $this->global['searchName'] = '';
        $this->global['searchStatus'] = 0;
        $this->loadViews("qrmanage", $this->global, NULL, NULL);
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
                    'shop' => $list->name,
                    'type' => $list->type == '1' ? '旅游线路' : '景区',
                    'time' => $list->created_time,
                    'id' => $list->id,
                    'targetid' => $areaid,
                    'areatype' => $list->type,
                    'shopid' => $list->shopid
                );
                array_push($qrList, $info);
            }

            $this->global['qrList'] = $qrList;

            $this->global['searchName'] = $name;
            $this->global['searchStatus'] = $status;
            $this->loadViews("qrmanage", $this->global, NULL, NULL);
        }
    }

    /**
     * This function is used to load the user list
     */
    function qr_listing()
    {
        $ret = array(
            'data' => '',
            'status' => 'fail'
        );
        if (!empty($_POST)) {

            $searchType = $_POST['searchType'];
            $name = $_POST['name'];
            $status = $_POST['status'];

            $qrList = array();
            $qrDBList = $this->shop_model->getQR($name, $status, $searchType);
            foreach ($qrDBList as $list) {
                $areaid = $list->targetid;
                $areaInfo = $this->area_model->getAreaById($areaid);
                if (isset($areaInfo)) {
                    $courseName = $this->area_model->getCourseNameByAreaId($areaid);
                    if ($searchType == 1 && $name != 'all' && strstr($courseName, $name) == false) continue; // 0-shopname search, 1-coursename search
                    $areaInfo->name = $courseName;
                    if ($this->global['shop_manager_number'] != ''
                        && $this->global['shop_manager_number'] != $list->shopnumber
                    ) continue;

                    $info = array('target' => $areaInfo->name,
                        'shop' => $list->name,
                        'type' => $list->type == '1' ? '旅游线路' : '景区',
                        'time' => $list->created_time,
                        'id' => $list->id,
                        'targetid' => $areaid,
                        'areatype' => $list->type,
                        'shopid' => $list->shopid
                    );
                    array_push($qrList, $info);
                }
            }


            $ret['data'] = $this->output_qr($qrList);
            $ret['status'] = 'success';
        }
        echo json_encode($ret);
    }

    function output_qr($qrList)
    {
        $output_html = '';

        $qrCount = count($qrList);
        for ($i = 0; $i < $qrCount; $i++) {
            $qr = $qrList[$i];
            $output_html .= '<tr>';
            if ($this->global['shop_manager_number'] == '') {
                $output_html .= '<td>' . $qr['shop'] . '</td>';
            }
            $output_html .= '<td>' . $qr['type'] . '</td>';
            $output_html .= '<td>' . $qr['target'] . '</td>';
            $output_html .= '<td>' . $qr['time'] . '</td>';
            $output_html .= '<td>';
            $output_html .= '<a href="#" onclick="showQR(\'' . base_url() . '\',\'' . $qr['id'] . '\')">查看 &nbsp;</a>';
            $output_html .= '</td>';
            $output_html .= '</tr>';
        }

        return $output_html;
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
    function courselisting($name, $status)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '旅游线路管理';
            $this->global['courseList'] = $this->area_model->getCourses($name, $status);
            $this->global['searchName'] = $name;
            $this->global['searchStatus'] = $status;

            $this->loadViews("course", $this->global, NULL, NULL);
        }
    }

    /**
     * This function is used to load the edit form
     */
    function editcourse($id)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {

            $this->global['pageTitle'] = '编辑旅游线路';
            $this->global['areaList'] = $this->area_model->getAreas();
            $this->global['course'] = $this->area_model->getAreaById($id);

            $this->loadViews("course-add", $this->global, NULL, NULL);
        }
    }


    function pageNotFound()
    {
        $this->global['pageTitle'] = 'CodeInsect : 404 - Page Not Found';

        $this->loadViews("404", $this->global, NULL, NULL);
    }
}

/* End of file qrmanage.php */
/* Location: .application/controllers/qrmanage.php */


?>