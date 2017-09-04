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
        //$this->db->where($likeCriteria);
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
            'ta.name as tour_area, ta.point_list as point_list, ta.type as tour_point, ' .
            'au.shopid as shop_name, ta.type as type, od.status as status, od.ordered_time as ordered_time');
        $this->db->from('tbl_order as od');
        $this->db->join('tbl_authcode as au', 'od.authid = au.id');
        $this->db->join('tourist_area as ta', 'au.targetid = ta.id');
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
        foreach ($Ids as $id) {
            $this->db->or_where('areaid', $id);  // all courses
        }
        $this->db->where('userphone', $phone);
        $this->db->order_by('ordered_time', 'desc');
        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) return NULL;
        return $result[0];
    }

    function getStatusByAttractionId($id, $phone)
    {
        $areaIds = $this->getAreaIdsByAttractionId($id);
        $buystatus = 3;//unpaid
        if ($this->getBuyStatusById($id, 0, $phone) == '1') {//if used attraction
            $buystatus = 2; // paid
        }
        foreach ($areaIds as $ids) {
            if ($this->getBuyStatusById($ids, 1, $phone) == '1') {// if used area
                $buystatus = 2;
            }
        }
        return $buystatus;
    }

    /**
     * This function is used to add new shop to system
     * @return number $insert_id : This is last inserted id
     */
    function getBuyStatusById($id, $type, $phone)
    {
        $this->db->select('*');
        $this->db->from('tbl_order');
        if ($type == 0)  // 0-attraction, 1-area, 2-course
            $this->db->where('attractionid', $id);
        else
            $this->db->where('areaid', $id);

        $this->db->where('userphone', $phone);
        $this->db->order_by('ordered_time', 'DESC');

        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) return 0;// unused
        return $this->setStatusByOrderId($result[0]->id);
    }

    function setStatusByOrderId($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_order');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $qresult = $query->result();
        if (count($qresult) == 0) return 0;
        $item = $qresult[0];
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
                if ($Types == '4') { // auth order
                    $areaitem = $this->area_model->getAreaByAuthId($item->authid);
                    if (count($areaitem) == 0) continue;
                    $area_info = json_decode($areaitem->info);
                    array_push(
                        $Auths,
                        array(
                            'id' => $item->value,
                            'name' => $areaitem->name,
                            'image' => base_url() . 'uploads/' . $area_info->overay,
                            'pay_method' => 2, // auth order
                            'value' => $item->code,
                            'cost' => $areaitem->price,
                            'discount_rate' => $areaitem->discount_rate,
                            'order_time' => $item->ordered_time,
                            'paid_time' => $item->paid_time,
                            'expiration_time' => date_format(date_create($item->ordered_time), "Y.m.d") .
                                ' - ' . date_format(date_create($item->expiration_time), "Y.m.d"),
                            'canceled_time' => $item->canceled_time,
                            'state' => $item->status
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
                            'name' => $areaitem->name,
                            'image' => base_url() . 'uploads/' . $area_info->overay,
                            'pay_method' => 1, // buy order
                            'value' => $item->code,
                            'cost' => $areaitem->price,
                            'discount_rate' => $areaitem->discount_rate,
                            'order_time' => $item->ordered_time,
                            'paid_time' => $item->paid_time,
                            'expiration_time' => date_format(date_create($item->ordered_time), "Y.m.d") .
                                ' - ' . date_format(date_create($item->expiration_time), "Y.m.d"),
                            'canceled_time' => $item->canceled_time,
                            'state' => $item->status
                        )
                    );
                    $total_price += $item->code;
                } else { //buy attraction
                    $areaitem = $this->area_model->getAreaById($item->areaid);
                    if (count($areaitem) == 0) continue;
                    $attritem = json_decode($areaitem->point_list);
                    $attr_id = explode('_', $item->attractionid);
                    $attritem = $attritem[$attr_id[1] - 1];
                    array_push(
                        $Auths,
                        array(
                            'id' => $item->value,
                            'name' => $attritem->name,
                            'image' => base_url() . 'uploads/' . $attritem->image,
                            'pay_method' => 1, // buy order
                            'value' => $item->code,
                            'cost' => $attritem->price,
                            'discount_rate' => $attritem->discount_rate,
                            'order_time' => $item->ordered_time,
                            'paid_time' => $item->paid_time,
                            'expiration_time' => date_format(date_create($item->ordered_time), "Y.m.d") .
                                ' - ' . date_format(date_create($item->expiration_time), "Y.m.d"),
                            'canceled_time' => $item->canceled_time,
                            'state' => $item->status
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
    function addAuthOrder($authInfo)
    {
        $this->db->select('*');
        $this->db->from('tbl_order');
        $this->db->where('value', $authInfo['value']);
        $this->db->where('ordertype', $authInfo['ordertype']);
        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) return FALSE;
        $OrderInfo = $result['0'];
        $OrderInfo->userphone = $authInfo['userphone'];
        var_dump($OrderInfo);
        $shop = $this->getShopIdByAuthId($OrderInfo->authid);
        if (count($shop) == 0) $OrderInfo->authid = 0;
        else if ($shop->shopid != $authInfo['authid'])
            $OrderInfo->authid = 0;
        $OrderInfo->paid_time = $authInfo['paid_time'];
        $this->db->where('id', $OrderInfo->id);
        $this->db->update('tbl_order', $OrderInfo);

        return $this->setStatusByOrderId($OrderInfo->id);;
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
        $this->db->where('authid', $shopid);
        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) return FALSE;
        $OrderInfo = $result['0'];
        if ($this->setStatusByOrderId($OrderInfo->id) == 3) return FALSE;
        $date = new DateTime();
        $OrderInfo->paid_time = date_format($date, "Y-M-d H:i:s");
        $this->db->where('id', $OrderInfo->id);
        $this->db->update('tbl_order', $OrderInfo);
        return $this->setStatusByOrderId($OrderInfo->id);
    }
}



/* End of file order_model.php */
/* Location: .application/models/order_model.php */
