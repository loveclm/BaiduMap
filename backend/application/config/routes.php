<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "login_controller";
$route['404_override'] = 'error';

/*********** USER DEFINED ROUTES *******************/

$route['loginMe'] = 'login_controller/loginMe';

$route['area'] = 'area';
$route['addarea'] = 'area/addNew';
$route['editarea/(:any)'] = 'area/edit/$1';
$route['addNewArea'] = 'area/addNewArea';
$route['areaListing/(:any)/(:any)/(:num)'] = "area/listing/$1/$2/$3";

$route['course'] = 'area/course';
$route['addcourse'] = 'area/addCourse';
$route['courseListing/(:any)/(:num)'] = "area/courselisting/$1/$2";
$route['editcourse/(:any)'] = 'area/editcourse/$1';

$route['shop'] = 'shop';
$route['addshop'] = 'shop/addShop';
$route['shopListing/(:any)/(:num)'] = "shop/shoplisting/$1/$2";
$route['editshop/(:any)'] = 'shop/editshop/$1';
$route['showshop/(:any)'] = 'shop/showshop/$1';

$route['qrmanage'] = 'qrmanage';
$route['qrListing/(:any)/(:num)'] = "qrmanage/qrlisting/$1/$2";

$route['authmanage'] = 'authmanage';
$route['authListing/(:num)/(:any)/(:num)'] = 'authmanage/listing/$1/$2/$3';
$route['authDetail/(:num)/(:num)'] = 'authmanage/detail/$1/$2';
$route['authOrderDetail/(:num)'] = 'authmanage/orderdetail/$1';
$route['authAdd/(:num)/(:num)'] = 'authmanage/addmoney/$1/$2';

$route['ordermanage'] = 'ordermanage';
$route['buyListing/(:num)/(:any)/(:any)/(:any)/(:num)'] = 'ordermanage/buyListing/$1/$2/$3/$4/$5';
$route['orderListing/(:num)/(:any)/(:any)/(:any)/(:num)'] = 'ordermanage/authListing/$1/$2/$3/$4/$5';

$route['settlemanage'] = 'settlemanage';
$route['settlebuyListing/(:num)/(:any)/(:any)/(:any)'] = 'settlemanage/buyListing/$1/$2/$3/$4';
$route['settleAuthListing/(:num)/(:any)/(:any)/(:any)'] = 'settlemanage/authListing/$1/$2/$3/$4';
$route['settleBuyDetail/(:num)/(:any)/(:any)/(:any)/(:num)'] = 'settlemanage/settleBuyDetail/$1/$2/$3/$4/$5';
$route['settleAuthDetail/(:num)/(:any)/(:any)/(:any)/(:num)'] = 'settlemanage/settleAuthDetail/$1/$2/$3/$4/$5';
$route['performBuySettle/(:any)/(:num)'] = 'settlemanage/performBuySettle/$1/$2';
$route['performAuthSettle/(:any)/(:num)/(:num)'] = 'settlemanage/performAuthSettle/$1/$2/$3';

$route['Wxpay'] = 'Wxpay';
$route['Wxpay/confirm/(:any)'] = 'Wxpay/confirm/$1';
$route['Wxpay/pay_callback'] = 'Wxpay/pay_callback';

$route['usermanage'] = 'usermanage';
$route['userCoListing/(:num)/(:any)'] = 'usermanage/userCollectListing/$1/$2';

$route['logout'] = 'systemmanage/logout';
$route['roleListing'] = 'systemmanage/roleListing';
$route['userListing'] = 'systemmanage/userListing';
$route['userListing/(:num)'] = "systemmanage/userListing/$1";
$route['addNew'] = "systemmanage/addNew";

$route['addNewUser'] = "systemmanage/addNewUser";
$route['editOld'] = "systemmanage/editOld";
$route['editOld/(:num)'] = "systemmanage/editOld/$1";
$route['editUser'] = "systemmanage/editUser";
$route['deleteUser/(:num)'] = "systemmanage/deleteUser/$1";
$route['deleteRole/(:num)'] = "systemmanage/deleteRole/$1";
$route['loadChangePass'] = "systemmanage/loadChangePass";
$route['changePassword'] = "systemmanage/changePassword";
$route['updateUserPassword'] = "systemmanage/updateUserPassword";
$route['pageNotFound'] = "systemmanage/pageNotFound";
$route['checkEmailExists'] = "systemmanage/checkEmailExists";

$route['forgotPassword'] = "login_controller/forgotPassword";
$route['resetPasswordUser'] = "login_controller/resetPasswordUser";
$route['resetPasswordConfirmUser'] = "login_controller/resetPasswordConfirmUser";
$route['resetPasswordConfirmUser/(:any)'] = "login_controller/resetPasswordConfirmUser/$1";
$route['resetPasswordConfirmUser/(:any)/(:any)'] = "login_controller/resetPasswordConfirmUser/$1/$2";
$route['createPasswordUser'] = "login_controller/createPasswordUser";

/* End of file routes.php */
/* Location: ./application/config/routes.php */