<?php defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');

require_once('./application/libraries/REST_Controller.php');

/**
 * Tourist Area API controller
 *
 * Validation is missign
 */
class Areas extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('area_model');
        $this->load->model('order_model');
        $this->load->model('auth_model');
        $this->load->model('user_model');
        $this->load->model('shop_model');
    }

    public function test_post()
    {
        $variable = $this->post();
        $variable = $variable['pos'];
        $this->response(array('status' => true, 'id' => $variable), 200);
        //$this->response($this->area_model->findAreas());
    }

    public function index_get()
    {
        $this->response($this->area_model->get_all());
        //$this->response($this->area_model->findAreas());
    }

    // Rest API - post
    // input: $key - the name of area
    // output: area data records with name like $key
    public function find_post($key = '')
    {
        $request = $this->post();
        $key = $request['key'];
        $this->response($this->area_model->findAreas($key));
    }

    public function edit_post($id = NULL)
    {
        if (!$id) {
            $this->response(array('status' => false, 'error_message' => 'No ID was provided.'), 400);
        }

        $this->response($this->area_model->getAreaById($id));
    }

    public function save_post($id = NULL)
    {
        if (!$id) {
            $new_id = $this->area_model->addNewArea($this->post());
            $this->response(array('status' => true, 'id' => $new_id, 'message' => sprintf('Area #%d has been created.', $new_id)), 200);
        } else {
            $this->area_model->update($this->post(), $id);
            $this->response(array('status' => true, 'message' => sprintf('Area #%d has been updated.', $id)), 200);
        }
    }

    public function changeStatus_post($id = NULL)
    {
        if (!$id) {
            $new_id = $this->area_model->addNewArea($this->post());
            $this->response(array('status' => true, 'id' => $new_id, 'message' => sprintf('Area #%d has been created.', $new_id)), 200);
        } else {
            $area = $this->post();
            if ($area['status'] == 1) {
                $this->area_model->update($this->post(), $id);
                $this->response(array('status' => true, 'message' => sprintf('Area #%d has been updated.', $id)), 200);
            } else if (!$this->area_model->getParentCourseByAreaId($id, '1')) {
                $this->area_model->update($this->post(), $id);
                $this->response(array('status' => true, 'message' => sprintf('Area #%d has been updated.', $id)), 200);
            } else {
                $this->response(array('status' => false, 'message' => sprintf('Area #%d is using now.', $id)), 200);
            }

        }
    }

    public function changeCourseStatus_post($id = NULL)
    {
        if (!$id) {
            $new_id = $this->area_model->addNewArea($this->post());
            $this->response(array('status' => true, 'id' => $new_id, 'message' => sprintf('Course #%d has been created.', $new_id)), 200);
        } else {
            $area = $this->post();
            if ($area['status'] != 1) {
                $this->area_model->update($this->post(), $id);
                $this->response(array('status' => true, 'message' => sprintf('Course #%d has been updated.', $id)), 200);
            } else if ($this->area_model->getAreaStatusByCourseId($id, '0')) {
                $this->area_model->update($this->post(), $id);
                $this->response(array('status' => true, 'message' => sprintf('Course #%d has been updated.', $id)), 200);
            } else {
                $this->response(array('status' => false, 'message' => sprintf('All areas in Course #%d have to be available.', $id)), 200);
            }

        }
    }

    public function remove_post($id = NULL)
    {
        if ($this->area_model->getParentCourseByAreaId($id)) {
            $this->response(array('status' => false, 'message' => sprintf('Area #%d is using now.', $id)), 200);
        } else {
            if ($this->area_model->delete($id)) {
                $this->response(array('status' => true, 'message' => sprintf('Area #%d has been deleted.', $id)), 200);
            } else {

                $this->response(array('status' => false, 'error_message' => 'This Area does not exist!'), 404);
            }
        }
    }

    public function upload_post($id = NULL)
    {
        $error = false;
        $files = array();
        $uploaddir = 'uploads/';
        $tt = time();
        $ext = explode(".", $_FILES[0]['name']);
        $nn = rand(1000, 9999);
        $filename = 'ayoubc' . $nn . $tt . '.' . $ext[1];
//        var_dump($_FILES);
        foreach ($_FILES as $file) {
//            if (move_uploaded_file($file['tmp_name'], $uploaddir . (basename($file['name'])))) {
            if (move_uploaded_file($file['tmp_name'], $uploaddir . $filename)) {
//                $files[] = $file['name'];
                $files[] = $file['name'];
            } else {
                $error = true;
            }
            break;
        }
        if (!$error) {
//            $this->response(array('status' => true, 'file' => $files[0]), 200);
            $this->response(array('status' => true, 'file' => $filename, 'originfile' => $files[0]), 200);
        } else {
            $this->response(array('status' => false, 'error_message' => 'There was an error uploading your files!'), 404);
        }
    }

///////////////////////////////////////////////////////////
////////////////    External APIs
///////////////////////////////////////////////////////////

    // Rest API - post
    // input: the absolute position of person
    // output: area name
    public function getAreaIdByPosition_post()
    {
        $request = $this->post();
        $lng = $request['pos'][0];
        $lat = $request['pos'][1];
        $all_areas = $this->area_model->getAreas('', 'all', 1); // 1-available, 2-disable
        $id = -1;
        if (count($all_areas) > 0) {
            foreach ($all_areas as $item) {
                $pos = json_decode($item->info);
                $pos = $pos->position;
                $lng1 = $pos[0][0];
                $lng2 = $pos[1][0];
                $lat1 = $pos[0][1];
                $lat2 = $pos[1][1];
                if ($lng < $lng1) continue;
                if ($lng > $lng2) continue;
                if ($lat < $lat1) continue;
                if ($lat > $lat2) continue;
                $id = $item->id;
                $name = $item->name;
                break;
            }
        }
        if ($id == -1) $this->response(array('status' => false, 'id' => $id), 200);
        else $this->response(array('status' => true, 'id' => $id, 'name' => $name), 200);
    }

    public function getAllCourseInfos_post()
    {
        $request = $this->post();
        $phone = $request['phone'];
        $all_courses = $this->area_model->getCourses('all', 1); // 1-available, 2-disable
        if (count($all_courses) == 0) {
            $this->response(array('status' => false, 'Courses' => '-1'), 200);
        } else {
            $i = 0;
            $course_list = array();
            foreach ($all_courses as $item) {
                if ($item->status == 0) continue;
                $all_areas = json_decode($item->point_list);
                $courseInfo = json_decode($item->info);
                $j = 0;
                $name = '';
                $areas = array();
                if (count($all_areas) > 0) {
                    foreach ($all_areas as $areaItem) {
                        $areaData = $this->area_model->getAreaById($areaItem->id);
                        $j++;
                        if ($j == 1) $name = $areaData->name;
                        else $name = $name . ' - ' . $areaData->name;
                        array_push(
                            $areas,
                            array(
                                'id' => $areaData->id,
                                'name' => $areaData->name,
                                'cost' => round((floatval($areaData->price) * floatval($areaData->discount_rate) -
                                            floatval($this->order_model->calculateMyPrice($phone, $areaData->id))) * 100) / 100,
                                'discount_rate' => $areaData->discount_rate,
                                'attractionCnt' => count(json_decode($areaData->point_list))
                            )
                        );
                    }
                }
                $i++;
                array_push(
                    $course_list,
                    array(
                        'id' => $item->id,
                        'name' => $name,    //  $item->name || $name
                        'image' => base_url() . 'uploads/' . $courseInfo->overay,
                        'cost' => round((floatval($item->price) * floatval($item->discount_rate) -
                                    floatval($this->order_model->calculateMyPrice($phone, $item->id))) * 100) / 100,
                        'discount_rate' => $item->discount_rate,
                        'scenic_areas' => $areas
                    )
                );

            }
            //var_dump($course_list);
            $this->response(array('status' => true, 'Courses' => $course_list), 200);
        }
    }

    public function getAllAreaInfos_post()
    {
        $request = $this->post();
        $all_areas = $this->area_model->getAreas('', 'all', 1); // 1-available, 2-disable
        if (count($all_areas) == 0) {
            $this->response(array('status' => false, 'Areas' => '-1'), 200);
        } else {
            $i = 0;
            $areas = array();
            foreach ($all_areas as $item) {
                $i++;
                $areainfo = json_decode($item->info);
                array_push(
                    $areas,
                    array(
                        'id' => $item->id,
                        'name' => $item->name,
                        'cost' => $item->price,
                        'discount_rate' => $item->discount_rate,
                        'audio' => base_url() . 'uploads/' . $areainfo->audio
                    )
                );
            }
            $this->response(array('status' => true, 'Areas' => $areas), 200);
        }
    }

    public function getMyOrderInfos_post()
    {
        $request = $this->post();
        //$this->response(array('status' => true, 'Orders' => '1'), 200);
        $mobile = $request['phone'];
        $orders = $this->order_model->getMyOrderInfos($mobile);
        if ($orders == '-1') {
            $this->response(array('status' => false, 'Orders' => $orders), 200);
        } else {
            $this->response(array('status' => true, 'Orders' => $orders['Auths']), 200);
        }
    }

    public function getMyAreaInfos_post()
    {
        $request = $this->post();
        $mobile = $request['phone'];
        $areaItems = $this->area_model->getAreas('', 'all', 1);
        $courseItems = $this->area_model->getCourses('', 1);
        if (count($areaItems) == 0) {
            $this->response(array('status' => false, 'MyAreas' => '-1'), 200);
        } else {
            $i = 0;
            $Auths = array();
            foreach ($areaItems as $item) {
                $i++;
                // get last order item as same as areaItem or courseItem
                $Ids = array();
                array_push($Ids, $item->id);
                if (count($courseItems) > 0) {
                    foreach ($courseItems as $csitem) {
                        $arInfos = json_decode($csitem->point_list);
                        if (sizeof($arInfos) == 0) continue;

                        foreach ($arInfos as $aritem) {
                            if ($item->id == $aritem->id) {
                                array_push($Ids, $csitem->id);
                                break;
                            }
                        }
                    }
                }

                $lastOrder = $this->order_model->getOrderByAreaIds($Ids, $mobile);
                if (count($lastOrder) == 0) continue;

                if ($lastOrder->status != '1') {
                    if ($lastOrder->status == 3 || $lastOrder->status == 2) continue;
                    if ($lastOrder->ordertype == '2' || $lastOrder->ordertype == '4') {
                        $status_ret = $this->order_model->getBuyStatusById($lastOrder->areaid, 1, $mobile); // 0-attr, 1-area, 2- course
                    } else if ($lastOrder->ordertype == '3') {
                        $status_ret = $this->order_model->getBuyStatusById($lastOrder->attractionid, 0, $mobile); // 0-attr, 1-area, 2- course
                    } else if ($lastOrder->ordertype == '1') {
                        $status_ret = $this->order_model->getBuyStatusById($lastOrder->areaid, 2, $mobile); // 0-attr, 1-area, 2- course
                    }
                    //$status_ret = $lastOrder->status;
                    if ($status_ret == '4') { // 1-using, 2-unpaid, 3-canceled, 4-expired
                        $status_ret = 2; // 2-expired
                    } else if ($status_ret == '1') {
                        $status_ret = 1; // 1-using
                    } else {
                        continue;
                    }
                } else {
                    $status_ret = 1;
                }

                $area_info = json_decode($item->info);
                array_push(
                    $Auths,
                    array(
                        'areaid' => $item->id,
                        'id' => $lastOrder->id,
                        'name' => $item->name,
                        'cost' => round(floatval($item->price)*floatval($item->discount_rate)*100)/100,//intval($this->order_model->calculateMyPrice($mobile, $item->id) * 100) / 100,
                        'paid_price' => $item->price,
                        'discount_rate' => $item->discount_rate,
                        'image' => base_url() . 'uploads/' . $area_info->overay,
                        'order_time' => $lastOrder->ordered_time,
                        'state' => $status_ret,
                        'type' => $item->type
                    )
                );
            }
            $this->response(array('status' => true, 'MyAreas' => $Auths), 200);
        }
    }

    public function getAreaInfoById_post()
    {
        $request = $this->post();
        $id = $request['id'];
        $phone = $request['phone'];
        $item = $this->area_model->getAreaById($id);
        if (count($item) == 0) {
            $this->response(array('status' => false, 'CurArea' => '-1'), 200);
        } else if ($item->type == 1) {
            $this->response(array('status' => false, 'CurArea' => '-1'), 200);
        } else {
            $curDate = date_create(date("Y-m-d"));
            date_modify($curDate, "-15 days");
            $itemInfo = json_decode($item->info);
            $attractions = json_decode($item->point_list);
            $i = 0;
            $attractionList = array();
            if (count($attractions) > 0) {
                foreach ($attractions as $atts) {
                    $i++;
                    if ($atts->trial == '1') {
                        $buy_state = 1;//trial
                    } else if ($phone == '') {
                        $buy_state = 3;//unpaid
                    } else {
                        $buy_state = $this->order_model->getStatusByAttractionId($atts->id, $phone);
                    }
                    array_push(
                        $attractionList,
                        array(
                            'id' => $atts->id,
                            'name' => $atts->name,
                            'position' => json_decode($atts->position),
                            'cost' => $atts->price,
                            'discount_rate' => $atts->discount_rate,
                            'buy_state' => $buy_state,// 1-trial, 2-paid, 3-unpaid
                            'audio_files' => [
                                base_url() . 'uploads/' . $atts->audio_1,
                                base_url() . 'uploads/' . $atts->audio_2,
                                base_url() . 'uploads/' . $atts->audio_3,
                            ],
                            'image' => base_url() . 'uploads/' . $atts->image
                        )
                    );
                }
            }
            $scenic_area = [
                'id' => $item->id,
                'name' => $item->name,
                'position' => [($itemInfo->position[0][0] + $itemInfo->position[1][0]) / 2,
                    ($itemInfo->position[0][1] + $itemInfo->position[1][1]) / 2],
                'top_right' => ($itemInfo->position[1]),
                'bottom_left' => ($itemInfo->position[0]),
                'overlay' => base_url() . 'uploads/' . $itemInfo->overay,
                'image' => base_url() . 'uploads/' . $itemInfo->overay,
                'audio' => base_url() . 'uploads/' . $itemInfo->audio,
                'zoom' => '10',
                'cost' => $item->price,
                'discount_rate' => $item->discount_rate,
                'attractionCnt' => count($attractionList),
                'attractions' => $attractionList,
                'zoom'=>($itemInfo->zoom)
            ];
            $this->response(array('status' => true, 'CurArea' => $scenic_area), 200);
        }
    }

    public function setAreaBuyOrder_post()
    {
        $request = $this->post();
        $areaid = $request['id'];
        $phone = $request['phone'];
        $cost = $request['cost'];
        $type = $request['type'];
        $shopid = $request['shop'];

        $init['num'] = $this->auth_model->getCount() + 1;
        $date = new DateTime();
        if ($phone == '' || $type == '') {
            $this->response(array('status' => false, 'result' => '-1'), 200);
        } else {
            $user = $this->user_model->getOrderUserByPhone($phone);
            if (count($user) == 0) {
                $userInfo = [
                    'mobile' => $phone
                ];
                $this->user_model->addNewOrderUser($userInfo);
            }
            if ($type == '1' || $type == '2') { // 1-course, 2-area
                $areaItem = $this->area_model->getAreaById($areaid);
                $shopItem = $this->shop_model->getShopById($shopid);
                if (count($areaItem) == 0) {
                    $this->response(array('status' => false, 'result' => 'The area is not exist.'), 200);
                    return;
                }
                if ($areaItem->type != $type) {
                    $this->response(array('status' => false, 'result' => 'The course or area type is mismatch.'), 200);
                    return;
                }
                if (count($shopItem) == 0) {
                    $this->response(array('status' => false, 'result' => 'The shop is not exist.'), 200);
                    return;
                }
                $authOrderItem = [
                    "value" => sprintf("%'.011d", time()),
                    "code" => floor($cost * 100) / 100,
                    "userphone" => $phone,
                    "ordertype" => $type, // 1,2 - course or area
                    "status" => '2',// 2- ordered but unpaid
                    "areaid" => $areaid,
                    "attractionid" => 0,
                    "authid" => $shopid,
                    "ordered_time" => $date->format('Y-m-d H:i:s'),
                ];
                $this->order_model->addBuyOrder($authOrderItem);
            } else if ($type == '3') {  // 3-attraction
                $area = explode('_', $areaid);
                $areaItem = $this->area_model->getAreaById($area[0]);
                if (count($areaItem) == 0) {
                    $this->response(array('status' => false, 'result' => '-1'), 200);
                    return;
                }
                $authOrderItem = [
                    "value" => sprintf("%'.011d", time()),
                    "code" => floor($cost * 100) / 100,
                    "userphone" => $phone,
                    "ordertype" => $type, // 3- attraction
                    "status" => '2', // 2-ordered but unpaid
                    "areaid" => $area[0],
                    "authid" => $shopid,
                    "attractionid" => $areaid,
                    "ordered_time" => $date->format('Y-m-d H:i:s'),
                ];
                $this->order_model->addBuyOrder($authOrderItem);
            } else { // 4-authorization code
                if ($shopid != '') {
                    $authOrderItem = [
                        "code" => $areaid,
                        "userphone" => $phone,
                        "ordertype" => $type, // 4-authorization
                        "status" => '1', // ordered and paid
                        "authid" => $shopid,
                        "paid_time" => $date->format('Y-m-d H:i:s')
                    ];
                    $result = $this->order_model->addAuthOrder($authOrderItem);
                    if ($result == 0) {
                        $this->response(array('status' => false, 'result' => '-1'), 200);
                    } else {
                        $this->response(array('status' => true, 'result' => $authOrderItem['code']), 200);
                    }
                }
                return;
            }
            $this->response(array('status' => true, 'result' => $authOrderItem['value']), 200);
        }
    }

    public function setPayOrder_post()
    {
        $request = $this->post();
        $value = $request['id'];// areaid  or courseid or attractionid
        //$value = explode("_", $value);
        //$value = $value[0];
        $phone = $request['phone'];
        $shopid = $request['shop'];

        $result = $this->order_model->addPayOrder($value, $phone, $shopid);
        $result[0]['state'] = 1;   // 1- paid
        if ($result == NULL) {
            $this->response(array('status' => false, 'result' => '-1'), 200);
        } else {
            $this->response(array('status' => true, 'result' => $result[0]), 200);
        }
    }

    public function setCancelOrder_post()
    {
        $request = $this->post();
        $valueid = $request['id'];
        $phone = $request['phone'];

        $result = $this->order_model->cancelBuyOrder($valueid, $phone);
        if ($phone == '' || $valueid == '') {
            $this->response(array('status' => false, 'result' => '-1'), 200);
        } else if (count($result) == 0) {
            $this->response(array('status' => false, 'result' => '-1'), 200);
        } else {
            $this->response(array('status' => true, 'result' => $result), 200);
        }
    }
}

/* End of file Areas.php */
/* Location: ./application/controllers/api/Areas.php */