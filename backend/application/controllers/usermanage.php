<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Shop (ShopController)
 * Area Class to control all shop related operations.
 * @author : The jin hu
 * @version : 1.0
 * @since : 12 August 2017
 */
class usermanage extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('collection_model');
        $this->load->model('order_model');
        $this->load->model('area_model');
        $this->load->model('shop_model');
        $this->isLoggedIn();
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->userCollectListing('0', 'ALL');
    }

    /**
     * This function is used to load the user list
     */
    function userCollectListing($searchType, $name)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '用户管理';
            if ($name == 'ALL') $name = '';
            $this->global['userList'] = $this->collection_model->getUserLists($searchType, $name);
            $this->global['searchType'] = $searchType;
            $this->global['searchName'] = $name;
            $this->loadViews("usermanage", $this->global, NULL, NULL);
        }
    }

    function pageNotFound()
    {
        $this->global['pageTitle'] = 'CodeInsect : 404 - Page Not Found';

        $this->loadViews("404", $this->global, NULL, NULL);
    }
}

/* End of file usermanage.php */
/* Location: .application/controllers/usermanage.php */


?>