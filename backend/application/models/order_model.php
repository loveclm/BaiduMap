<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class order_model extends CI_Model
{
    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getBuyOrders($searchType, $name, $stDate, $enDate, $status, $shopnumber = '')
    {//id, number, mobile, price, tour_area, tour_point, shop_name, status, ordered_time
        $this->db->select('od.id as id, od.value as number, od.userphone as mobile, od.code as price,' .
            'ta.name as tour_area, ta.point_list as point_list, od.attractionid as tour_point, od.authid as shop_name, ' .
            'od.status as status, ta.type as type, od.ordered_time as ordered_time');
        $this->db->from('tbl_order as od');
        $this->db->join('tourist_area as ta', 'od.areaid = ta.id');
        switch ($searchType) {
            case '0':
                $likeCriteria = "(od.value  LIKE '%" . $name . "%')";
                break;
            case '1':
                $likeCriteria = "(od.userphone  LIKE '%" . $name . "%')";
                break;
            case '2':
                $likeCriteria = "(ta.name  LIKE '%" . $name . "%')";
                break;
            case '3':
                //$likeCriteria = "(trp.name  LIKE '%" . $name . "%')";
                break;
        }
        $this->db->where($likeCriteria);
        if ($stDate != '') $this->db->where("date(od.ordered_time) >= '" . date($stDate) . "'");
        if ($enDate != '') $this->db->where("date(od.ordered_time) <= '" . date($enDate) . "'");

        if ($status != '0') $this->db->where('od.status', $status);
        $this->db->where("(od.ordertype) <> '4'");
        $this->db->order_by('od.ordered_time', 'desc');

        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getOrders($searchType, $name, $stDate, $enDate, $status)
    {
        $this->db->select('od.id as id, od.value as number, od.userphone as mobile, od.code as price,' .
            'ta.name as tour_area, ta.point_list as point_list, ta.type as tour_point, au.status as auth_status, ' .
            'au.shopid as shop_name, ta.type as type, od.status as status, od.ordered_time as ordered_time');
        $this->db->from('tbl_order as od');
        $this->db->join('tbl_authcode as au', 'od.authid = au.id');
        $this->db->join('tourist_area as ta', 'od.areaid = ta.id');
        switch ($searchType) {
            case '0':
                $likeCriteria = "(od.value  LIKE '%" . $name . "%')";
                break;
            case '1':
                $likeCriteria = "(od.userphone  LIKE '%" . $name . "%')";
                break;
            case '2':
                $likeCriteria = "(ta.name  LIKE '%" . $name . "%')";
                break;
        }
        $this->db->where($likeCriteria);
        if ($stDate != '') $this->db->where("date(od.ordered_time) >= '" . date($stDate) . "'");
        if ($enDate != '') $this->db->where("date(od.ordered_time) <= '" . date($enDate) . "'");

        if ($status != '0') $this->db->where('od.status', $status);
        //$this->db->where("(od.userphone)<>'0'");
        $this->db->where("(od.ordertype)", '4');
        $this->db->order_by('od.ordered_time', 'desc');

        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     * $type==1: scenic area,   $type==2: course
     */
    function getAreaCountByShopId($id, $type)
    {
        $this->db->select('qr.targetid');
        $this->db->from('qrcode as qr');
        $this->db->join('tourist_area as ar', 'qr.targetid = ar.id');
        $this->db->where('ar.status', '1');
        $this->db->where('qr.shopid', $id);
        $this->db->where('qr.type', $type);
        $qresult = $this->db->count_all_results();
        return $qresult;
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getOrdersByUser($phone)
    {
        $this->db->select('*');
        $this->db->from('tbl_order');
        $this->db->where('userphone', $phone);
        $this->db->order_by('ordered_time', 'desc');
        $this->db->order_by('authid');
        $this->db->order_by('areaid');

        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    function getAreaIdsByAttractionId($id)
    {
        $aid = explode('_', $id);
        $areaIds = array();
        array_push($areaIds, $aid[0]);
        $this->db->select('*');
        $this->db->from('tourist_area');
        $this->db->where('type', '1');  // all courses

        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) return $areaIds;
        foreach ($result as $item) {
            $areas = json_decode($item->point_list);
            if (sizeof($areas) == 0) continue;
            foreach ($areas as $aitem) {
                if ($aitem->id == $aid[0]) {
                    array_push($areaIds, $item->id);
                    break;
                }
            }
        }
        return $areaIds;
    }

    function getOrderByAreaIds($Ids, $phone)
    {
        $this->db->select('*');
        $this->db->from('tbl_order');
        $this->db->where('userphone', $phone);
        $this->db->order_by('ordered_time', 'desc');
        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) return NULL;
        foreach ($result as $item) {
            foreach ($Ids as $id) {
                if ($item->areaid == $id) {
                    return $item;
                }
            }
        }
        return NULL;
    }

    function getStatusByAttractionId($id, $mobile = '')
    {
        $areaIds = $this->getAreaIdsByAttractionId($id);
        $buystatus = 3;//unpaid
        if ($this->getBuyStatusById($id, 0, $mobile) == '1') {//if attraction used
            $buystatus = 2; // paid
        }
        foreach ($areaIds as $ids) {
            if ($this->getBuyStatusById($ids, 1, $mobile) == '1') {// if area used
                $buystatus = 2;
            }
        }
        return $buystatus;
    }

    /**
     * This function is used to add new shop to system
     * @return number $insert_id : This is last inserted id
     */
    function getBuyStatusById($id, $type, $phone, $itemid = 0)
    {
        $this->db->select('*');
        $this->db->from('tbl_order');
        if ($type == 0) {        //0:attraction , 1:area , 2:course
            $this->db->where('attractionid', $id);
        } else {
            $this->db->where('areaid', $id);
            $this->db->where('attractionid', 0);
        }
        if ($itemid != 0) $this->db->where('id', $itemid);
        $this->db->where('userphone', $phone);
        $this->db->order_by('ordered_time', 'DESC');
        $query = $this->db->get();
        $result = $query->result();

        if (count($result) == 0) return 2;  //unpaid

        return $this->setStatusByOrderId($result[0]->id);
    }

    function setStatusByOrderId($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_order');
        $this->db->where('id', $id);
        //$this->db->where('status <> 3');

        $query = $this->db->get();
        $qresult = $query->result();
        if (count($qresult) == 0) return 0;
        $item = $qresult[0];

        //if ($item->status != '1') return $item->status;
        if ($item->status == '3') return $item->status;

        $cur_date = new DateTime();
        if ($item->paid_time == NULL) {
            $item->status = 2; // it is only ordered.
            $st_date = date_create($item->ordered_time);
            date_modify($st_date, "+1 day");
            if ($cur_date > $st_date) // after 1day, order is canceled.
                $item->status = 3;
        } else {
            $item->status = 1;
            $en_date = date_create($item->paid_time);
            date_modify($en_date, "+15 day");
            $item->expiration_time = date_format($en_date, "Y-m-d H:i:s");
            if ($cur_date > $en_date)
                $item->status = 4;
        }
        $this->db->where('id', $item->id);
        $this->db->update('tbl_order', $item);
        return $item->status;
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getOrderCountByUser($phone)
    {
        $this->db->select('*');
        $this->db->from('tbl_order');
        $this->db->where('userphone', $phone);
        $qresult = $this->db->count_all_results();
        return $qresult;
    }

    function calculateMyPrice($phone, $areaid = 0, $areatype = 0)
    {
        // get my selected price
        $price = 0;
        $areaItem = $this->area_model->getAreaById($areaid);
        $type = $areaItem->type;
        if ($type == 1) {// course
            $areaInfos = json_decode($areaItem->point_list);

            foreach ($areaInfos as $item) {
                $areaInfo = $this->area_model->getAreaById($item->id);
                //if ($this->getBuyStatusById($item->id, 1, $phone) == 1) {
                //$price += floatval($areaInfo->price) * floatval($areaItem->discount_rate);
                $price += $this->calculateMyPrice($phone, $item->id);
                //}
            }
        } else if ($type == 2) { // area
            $pointInfos = json_decode($areaItem->point_list);
            foreach ($pointInfos as $item) {
                $retStatus = $this->getStatusByAttractionId($item->id, $phone);
                //var_dump($retStatus);
                //var_dump($item);
                if ($retStatus == 2) {
                    if ($item->trial != 1) $price += floatval($item->price);
                    //  var_dump($price);
                }
            }
        }
        return $price;
        // get real rest price
    }

    public function getMyOrderInfos($mobile)
    {
        $orders = $this->getOrdersByUser($mobile);
        if (count($orders) == 0) {
            return '-1';
        } else {
            $i = 0;
            $total_price = 0;
            $Auths = array();
            foreach ($orders as $item) {
                $i++;
                $Types = $item->ordertype;
                //if ($item->status == 3) continue;
                if ($Types == '4') { // auth order
                    $areaitem = $this->area_model->getAreaByAuthId($item->authid);
                    if (count($areaitem) == 0) continue;
                    $area_info = json_decode($areaitem->info);
                    $kind = $areaitem->type;
                    if ($item->attractionid != 0) $kind = 3;
                    array_push(
                        $Auths,
                        array(
                            'id' => $item->value,
                            'name' => $this->area_model->getCourseNameByAreaId($item->areaid),
                            'areaid' => $item->areaid,
                            'attractionid' => $item->attractionid,
                            'order_kind' => $kind,
                            'image' => base_url() . 'uploads/' . $area_info->overay,
                            'pay_method' => 2, // auth order
                            'value' => $item->code,
                            'cost' => round((floatval($areaitem->price) - $this->calculateMyPrice($mobile, $item->areaid))
                                    * floatval($areaitem->discount_rate) * 100) / 100,
                            'paid_price' => $areaitem->price,
                            'discount_rate' => $areaitem->discount_rate,
                            'order_time' => $item->ordered_time,
                            'paid_time' => $item->paid_time,
                            'expiration_time' => date_format(date_create($item->ordered_time), "Y.m.d") .
                                ' - ' . date_format(date_create($item->expiration_time), "Y.m.d"),
                            'cancelled_time' => $item->canceled_time,
                            'state' => $this->getBuyStatusById($item->areaid, 1, $mobile, $item->id)
                        )
                    );
                    $total_price += ($areaitem->price * $areaitem->discount_rate);
                } else if ($Types == '2' || $Types == '1') { //buy course or area suoyou
                    $areaitem = $this->area_model->getAreaById($item->areaid);
                    if (count($areaitem) == 0) continue;
                    $attritem = json_decode($areaitem->point_list);
                    $area_info = json_decode($areaitem->info);
                    array_push(
                        $Auths,
                        array(
                            'id' => $item->value,
                            'name' => $this->area_model->getCourseNameByAreaId($item->areaid),
                            'areaid' => $item->areaid,
                            'attractionid' => $item->attractionid,
                            'order_kind' => $Types,
                            'image' => base_url() . 'uploads/' . $area_info->overay,
                            'pay_method' => 1, // buy order
                            'value' => $item->code,
                            'cost' => round((floatval($item->code) - $this->calculateMyPrice($mobile, $item->areaid)
                                        * floatval($areaitem->discount_rate)) * 100) / 100,

                            //$item->code,//intval($this->calculateMyPrice($mobile, $item->areaid) * 100) / 100,
                            'origin_price' => $areaitem->price,
                            'discount_rate' => $areaitem->discount_rate,
                            'order_time' => $item->ordered_time,
                            'paid_time' => $item->paid_time,
                            'expiration_time' => date_format(date_create($item->ordered_time), "Y.m.d") .
                                ' - ' . date_format(date_create($item->expiration_time), "Y.m.d"),
                            'cancelled_time' => $item->canceled_time,
                            'state' => $this->getBuyStatusById($item->areaid, 1, $mobile, $item->id)
                        )
                    );
                    $total_price += $item->code;
                } else { //buy attraction
                    $areaitem = $this->area_model->getAreaById($item->areaid);
                    if (count($areaitem) == 0) continue;
                    $attritem = json_decode($areaitem->point_list);
                    $attr_id = explode('_', $item->attractionid);
                    $attritem = $attritem[$attr_id[1] - 1];
                    $attrStatus = $this->getBuyStatusById($item->attractionid, 0, $mobile, $item->id);

                    array_push(
                        $Auths,
                        array(
                            'id' => $item->value,
                            'name' => $attritem->name,
                            'areaid' => $item->areaid,
                            'attractionid' => $item->attractionid,
                            'order_kind' => $Types,
                            'image' => base_url() . 'uploads/' . $attritem->image,
                            'pay_method' => 1, // buy order
                            'value' => $item->code,
                            'cost' => round((floatval($item->code)
                                        - floatval((($attrStatus == '1') ? $attritem->price : '0'))) * 100) / 100,

                            //$item->code,//$attritem->price,
                            'discount_rate' => $attritem->discount_rate,
                            'order_time' => $item->ordered_time,
                            'paid_time' => $item->paid_time,
                            'expiration_time' => date_format(date_create($item->ordered_time), "Y.m.d") .
                                ' - ' . date_format(date_create($item->expiration_time), "Y.m.d"),
                            'cancelled_time' => $item->canceled_time,
                            'state' => $this->getBuyStatusById($item->attractionid, 0, $mobile, $item->id)
                        )
                    );
                    $total_price += $item->code;
                }
            }
            $result['Auths'] = $Auths;
            $result['total_price'] = $total_price;
            return $result;
        }
    }

    /**
     * This function is used to add new shop to system
     * @return number $insert_id : This is last inserted id
     */
    function addBuyOrder($OrderInfo)
    {
        $this->db->select('*');
        $this->db->from('tbl_order');
        $this->db->where('code', $OrderInfo['code']);
        $this->db->where('userphone', $OrderInfo['userphone']);
        $this->db->where('ordertype', $OrderInfo['ordertype']);
        $this->db->where('areaid', $OrderInfo['areaid']);
        $this->db->where('attractionid', $OrderInfo['attractionid']);
        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) {
            $this->db->trans_start();
            $this->db->insert('tbl_order', $OrderInfo);
            $insert_id = $this->db->insert_id();
            $this->db->trans_complete();
        } else {
            $this->db->where('id', $result[0]->id);
            $this->db->update('tbl_order', $OrderInfo);
            $insert_id = $result[0]->id;
        }
        $this->setStatusByOrderId($insert_id);
        return $insert_id;
    }

    /**
     * This function is used to add new shop to system
     * @return number $insert_id : This is last inserted id
     */
    function cancelBuyOrder($value, $phone)
    {
        $this->db->select('*');
        $this->db->from('tbl_order');
        $this->db->where('value', $value);
        $this->db->where('userphone', $phone);
        $this->db->where('ordertype <> 4'); // if no auth codes
        $this->db->where('status', '2'); // if unpaid
        $query = $this->db->get();

        $result = $query->result();

        if (count($result) == 0)
            return array();

        $orderInfo = $result[0];
        $orderInfo->status = 3; // canceled
        $date = new DateTime();
        $orderInfo->canceled_time = date_format($date, "Y-m-d H:i:s");
        $this->db->where('id', $orderInfo->id);
        $this->db->update('tbl_order', $orderInfo);
        $insert_id = $result[0]->id;

        $item = $orderInfo;
        $Types = $item->ordertype;
        $Auths = array();
        if ($Types == '2' || $Types == '1') { //buy course or area suoyou
            $areaitem = $this->area_model->getAreaById($item->areaid);
            if (count($areaitem) != 0) {
                $attritem = json_decode($areaitem->point_list);
                $area_info = json_decode($areaitem->info);
                array_push(
                    $Auths,
                    array(
                        'id' => $item->value,
                        'name' => $this->area_model->getCourseNameByAreaId($item->areaid),
                        'areaid' => $item->areaid,
                        'attractionid' => $item->attractionid,
                        'order_kind' => $Types,
                        'image' => base_url() . 'uploads/' . $area_info->overay,
                        'pay_method' => 1, // buy order
                        'value' => $item->code,
                        'cost' => intval($this->calculateMyPrice($phone, $item->areaid) * 100) / 100,
                        'origin_price' => $areaitem->price,
                        'discount_rate' => $areaitem->discount_rate,
                        'order_time' => $item->ordered_time,
                        'paid_time' => $item->paid_time,
                        'expiration_time' => date_format(date_create($item->ordered_time), "Y.m.d") .
                            ' - ' . date_format(date_create($item->expiration_time), "Y.m.d"),
                        'cancelled_time' => $item->canceled_time,
                        'state' => $item->status
                    )
                );
            }
        } else { //buy attraction
            $areaitem = $this->area_model->getAreaById($item->areaid);
            if (count($areaitem) != 0) {
                $attritem = json_decode($areaitem->point_list);
                $attr_id = explode('_', $item->attractionid);
                $attritem = $attritem[$attr_id[1] - 1];
                array_push(
                    $Auths,
                    array(
                        'id' => $item->value,
                        'name' => $attritem->name,
                        'areaid' => $item->areaid,
                        'attractionid' => $item->attractionid,
                        'order_kind' => $Types,
                        'image' => base_url() . 'uploads/' . $attritem->image,
                        'pay_method' => 1, // buy order
                        'value' => $item->code,
                        'cost' => $attritem->price,
                        'discount_rate' => $attritem->discount_rate,
                        'order_time' => $item->ordered_time,
                        'paid_time' => $item->paid_time,
                        'expiration_time' => date_format(date_create($item->ordered_time), "Y.m.d") .
                            ' - ' . date_format(date_create($item->expiration_time), "Y.m.d"),
                        'cancelled_time' => $item->canceled_time,
                        'state' => $item->status
                    )
                );
            }
        }
        return $Auths;
    }

    function getOrderShopIdFromAreaId($shopid, $areaid)
    {
        $orderAreaName = utf8_encode('- ' . $this->area_model->getCourseNameByAreaId($areaid) . ' -');

        $this->db->select('targetid');
        $this->db->from('qrcode');
        $this->db->where('shopid', $shopid);
        $query = $this->db->get();
        $areaIdList = $query->result();
        $areaNames = '';
        if (count($areaIdList) == 0) return 0;
        foreach ($areaIdList as $item) {
            $areaNames .= utf8_encode(' - ' . $this->area_model->getCourseNameByAreaId($item->targetid) . ' - ');
        }
        if (strrpos($areaNames, $orderAreaName) == FALSE) return 0;
        return $shopid;
    }

    /**
     * This function is used to add new shop to system
     * @return number $insert_id : This is last inserted id
     */
    function addAuthOrder($authInfo)
    {
        $this->db->select('*');
        $this->db->from('tbl_order');
        $this->db->where('code', $authInfo['code']);
        $this->db->where('ordertype', $authInfo['ordertype']);
        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) return 0;

        $OrderInfo = $result['0'];
        if ($OrderInfo->userphone != '0') return 0;
        $shop = $this->getShopIdByAuthId($OrderInfo->authid);

        if (count($shop) == 0) $OrderInfo->authid = 0;
        else if ($shop->shopid != $authInfo['authid'])
            return 0; //$OrderInfo->authid = 0;

        if ($this->getBuyStatusById($OrderInfo->areaid, 1, $authInfo['userphone']) == 1)
            return 0;

        $OrderInfo->userphone = $authInfo['userphone'];
        $OrderInfo->paid_time = $authInfo['paid_time'];
        $this->db->where('id', $OrderInfo->id);
        $this->db->update('tbl_order', $OrderInfo);

        return $this->setStatusByOrderId($OrderInfo->id);
    }

    function getShopIdByAuthId($authid)
    {
        $this->db->select('shopid');
        $this->db->from('tbl_authcode');
        $this->db->where('id', $authid);
        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) return NULL;
        return $result[0];
    }

    /**
     * This function is used to add new shop to system
     * @return number $insert_id : This is last inserted id
     */
    function addPayOrder($value, $phone, $shopid)
    {
        $this->db->select('*');
        $this->db->from('tbl_order');
        $this->db->where('value', $value);
        $this->db->where('userphone', $phone);
        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) return NULL;
        $OrderInfo = $result['0'];
        $OrderInfo->status = 1;
        //if ($this->setStatusByOrderId($OrderInfo->id) == 3) return 0;
        $date = new DateTime();
        $OrderInfo->paid_time = date_format($date, "Y-m-d H:i:s");
        $this->db->where('id', $OrderInfo->id);
        $this->db->update('tbl_order', $OrderInfo);
        //$OrderInfo->status = $this->setStatusByOrderId($OrderInfo->id);
        $item = $OrderInfo;
        $mobile = $phone;
        $Types = $item->ordertype;
        $Auths = array();
        if ($Types == '4') { // auth order
            $areaitem = $this->area_model->getAreaByAuthId($item->authid);
            if (count($areaitem) != 0) {
                $area_info = json_decode($areaitem->info);
                $kind = $areaitem->type;
                if ($item->attractionid != 0) $kind = 3;
                array_push(
                    $Auths,
                    array(
                        'id' => $item->value,
                        'name' => $areaitem->name,
                        'areaid' => $item->areaid,
                        'attractionid' => $item->attractionid,
                        'order_kind' => $kind,
                        'image' => base_url() . 'uploads/' . $area_info->overay,
                        'pay_method' => 2, // auth order
                        'value' => $item->code,
                        'cost' => $this->calculateMyPrice($mobile, $item->areaid),
                        'paid_price' => $areaitem->price,
                        'discount_rate' => $areaitem->discount_rate,
                        'order_time' => $item->ordered_time,
                        'paid_time' => $item->paid_time,
                        'expiration_time' => date_format(date_create($item->ordered_time), "Y.m.d") .
                            ' - ' . date_format(date_create($item->expiration_time), "Y.m.d"),
                        'cancelled_time' => $item->canceled_time,
                        'state' => $item->status
                    )
                );
            }
        } else if ($Types == '2' || $Types == '1') { //buy course or area suoyou
            $areaitem = $this->area_model->getAreaById($item->areaid);
            if (count($areaitem) != 0) {
                $attritem = json_decode($areaitem->point_list);
                $area_info = json_decode($areaitem->info);
                array_push(
                    $Auths,
                    array(
                        'id' => $item->value,
                        'name' => $this->area_model->getCourseNameByAreaId($item->areaid),
                        'areaid' => $item->areaid,
                        'attractionid' => $item->attractionid,
                        'order_kind' => $Types,
                        'image' => base_url() . 'uploads/' . $area_info->overay,
                        'pay_method' => 1, // buy order
                        'value' => $item->code,
                        'cost' => $this->calculateMyPrice($mobile, $item->areaid),
                        'origin_price' => $areaitem->price,
                        'discount_rate' => $areaitem->discount_rate,
                        'order_time' => $item->ordered_time,
                        'paid_time' => $item->paid_time,
                        'expiration_time' => date_format(date_create($item->ordered_time), "Y.m.d") .
                            ' - ' . date_format(date_create($item->expiration_time), "Y.m.d"),
                        'cancelled_time' => $item->canceled_time,
                        'state' => $item->status
                    )
                );
            }
        } else { //buy attraction
            $areaitem = $this->area_model->getAreaById($item->areaid);
            if (count($areaitem) != 0) {
                $attritem = json_decode($areaitem->point_list);
                $attr_id = explode('_', $item->attractionid);
                $attritem = $attritem[$attr_id[1] - 1];
                array_push(
                    $Auths,
                    array(
                        'id' => $item->value,
                        'name' => $attritem->name,
                        'areaid' => $item->areaid,
                        'attractionid' => $item->attractionid,
                        'order_kind' => $Types,
                        'image' => base_url() . 'uploads/' . $attritem->image,
                        'pay_method' => 1, // buy order
                        'value' => $item->code,
                        'cost' => $attritem->price,
                        'discount_rate' => $attritem->discount_rate,
                        'order_time' => $item->ordered_time,
                        'paid_time' => $item->paid_time,
                        'expiration_time' => date_format(date_create($item->ordered_time), "Y.m.d") .
                            ' - ' . date_format(date_create($item->expiration_time), "Y.m.d"),
                        'cancelled_time' => $item->canceled_time,
                        'state' => $item->status
                    )
                );
            }
        }
        return $Auths;
    }
}



/* End of file order_model.php */
/* Location: .application/models/order_model.php */
