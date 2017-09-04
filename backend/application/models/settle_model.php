<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class settle_model extends CI_Model
{
    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getBuySettles($searchType, $name, $stDate, $enDate, $shopnumber='')
    {
        if ($enDate == '') {
            $date = date_create(date("Y-m-1"));
        } else {
            $date = date_create($enDate);
            date_modify($date, "-" . (intval(date_format($date, "j")) - 1) . " days");
        }

        $date = date_modify($date, "+1 month");
        $date_now = date_format($date, "Y-m-d");
        $settleList = NULL;
        for ($mon = 0; $mon < 12; $mon++) {
            $date_s = date_create($date_now);
            $date_e = date_create($date_now);
            date_modify($date_s, "-" . ($mon + 1) . " month");
            date_modify($date_e, "-" . ($mon) . " month");
            if($stDate!='' && date_create($stDate)>$date_e)
                continue;
            $month_name = date_format($date_s, "Y-n");
            $shops = $this->getShops($searchType, $name, 0, $shopnumber);
            if (count($shops) > 0) {
                $shopList = NULL;
                $j = 0;
                foreach ($shops as $sh) {
                    // get buy price paid
                    $sh->address_2 = $this->getBuyPriceByShopId($sh->id,
                        date_format($date_s, "Y-m-d"), date_format($date_e, "Y-m-d"));
                    // get buy status
                    $sh->status = $this->getBuyStatus($sh->id,$month_name);
                    if ($sh->address_2->price != '') {
                        $shopList[$j] = $sh;
                        $j++;
                    }
                }
                if (count($shopList) > 0) {
                    $settleList[$mon] = [
                        'month_name' => $month_name,
                        'shops' => $shopList
                    ];
                }
            }
        }
        if (isset($settleList) > 0)
            return $settleList;
        else
            return NULL;
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getAuthSettles($searchType, $name, $stDate, $enDate, $shopnumber)
    {
        if ($enDate == '') {
            $date = date_create(date("Y-m-1"));
        } else {
            $date = date_create($enDate);
            date_modify($date, "-" . (intval(date_format($date, "j")) - 1) . " days");
        }

        $date = date_modify($date, "+1 month");
        $date_now = date_format($date, "Y-m-d");
        for ($mon = 0; $mon < 12; $mon++) {
            $date_s = date_create($date_now);
            $date_e = date_create($date_now);
            date_modify($date_s, "-" . ($mon + 1) . " month");
            date_modify($date_e, "-" . ($mon) . " month");
            if($stDate!='' && date_create($stDate)>$date_e)
                continue;
            $month_name = date_format($date_s, "Y-n");
            $shops = $this->getShops($searchType, $name, 0, $shopnumber);
            if (count($shops) > 0) {
                $shopList = null;
                $j = 0;
                foreach ($shops as $sh) {
                    // get code count paid
                    $sh->address_2 = $this->getAuthCountByShopId($sh->id,
                        date_format($date_s, "Y-m-d"), date_format($date_e, "Y-m-d"));
                    // get status
                    $sh->status = $this->getAuthStatus($sh->id, $month_name);
                    if (isset($sh->address_2->price)) {
                        $shopList[$j] = $sh;
                        $j++;
                    }
                }
                if (count($shopList)>0) {
                    $settleList[$mon] = [
                        'month_name' => $month_name,
                        'shops' => $shopList
                    ];
                }
            }
        }
        if (isset($settleList) > 0)
            return $settleList;
        else
            return NULL;
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getBuyPriceByShopId($shopid, $date_s, $date_e)
    {
        $this->db->select("sum(code) as price");
        $this->db->from('tbl_order');
        $this->db->where("authid", $shopid);
        $this->db->where("ordertype <> 4");
        $this->db->where("date(paid_time) >= date('" . $date_s . "')");
        $this->db->where("date(paid_time) < date('" . $date_e . "')");
        $query = $this->db->get();
        $qresult = $query->result();
        if (count($qresult) > 0) {
            return $qresult[0];
        }else
            return '0';
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getAuthCountByShopId($shopid, $date_s, $date_e)
    {

        $this->db->select("count(od.code) as codeCount, sum(ar.price) as price");
        $this->db->from('tbl_order as od');
        $this->db->join('tbl_authcode as au', 'od.authid = au.id');
        $this->db->join('tourist_area as ar', 'od.areaid = ar.id');
        $this->db->where("au.shopid", $shopid);
        $this->db->where("date(od.paid_time) >= date('" . $date_s . "')");
        $this->db->where("date(od.paid_time) < date('" . $date_e . "')");
        $this->db->where("od.ordertype",'4');
        $this->db->where("od.status <> '0'");
        $query = $this->db->get();
        $qresult = $query->result();

        if (count($qresult) > 0)
            return $qresult[0];
        else
            return '';
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getBuyStatus($shopid, $month_name)
    {
        $this->db->select("status");
        $this->db->from('buy_settlement');
        $this->db->where("shopid", $shopid);
        $this->db->where("settlement_date",$month_name);
        $query = $this->db->get();
        $qresult = $query->result();
        if (count($qresult) > 0)
            return $qresult[0];
        else
            return '';
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getAuthStatus($shopid, $month_name)
    {
        $this->db->select("status");
        $this->db->from('auth_settlement');
        $this->db->where("shopid", $shopid);
        $this->db->where("settlement_date",$month_name);
        $query = $this->db->get();
        $qresult = $query->result();
        if (count($qresult) > 0)
            return $qresult[0];
        else
            return '';
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getShops($searchType = '', $name = '', $status = 0, $shopnumber='')
    {
        $this->db->select('*');
        $this->db->from('shop');
        switch ($searchType) {
            case '0':
                $likeCriteria = "(phonenumber  LIKE '%" . $name . "%')";
                break;
            case '1':
                $likeCriteria = "(name  LIKE '%" . $name . "%')";
                break;
        }
        $this->db->where($likeCriteria);
        if($shopnumber!='')
            $this->db->where('phonenumber', $shopnumber);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function updateAuthSettle($shopInfo)
    {
        $this->db->select('*');
        $this->db->from('auth_settlement');
        $this->db->where('shopid', $shopInfo->shopid);
        $this->db->where('settlement_date', $shopInfo->settlement_date);
        $query = $this->db->get();
        $qresult = $query->result();
        if(count($qresult)>0){
            $this->db->where('shopid', $shopInfo->shopid);
            $this->db->where('settlement_date', $shopInfo->settlement_date);
            $this->db->update('auth_settlement', $shopInfo);
            $insert_id = $this->db->insert_id();
        } else{
            $this->db->trans_start();
            $this->db->insert('auth_settlement', $shopInfo);
            $insert_id = $this->db->insert_id();
            $this->db->trans_complete();
        }
        return $insert_id;
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function updateBuySettle($shopInfo)
    {
        $this->db->select('*');
        $this->db->from('buy_settlement');
        $this->db->where('shopid', $shopInfo->shopid);
        $this->db->where('settlement_date', $shopInfo->settlement_date);
        $query = $this->db->get();
        $qresult = $query->result();
        if(count($qresult)>0){
            $this->db->where('shopid', $shopInfo->shopid);
            $this->db->where('settlement_date', $shopInfo->settlement_date);
            $this->db->update('buy_settlement', $shopInfo);
            $insert_id = $this->db->insert_id();
        } else{
            $this->db->trans_start();
            $this->db->insert('buy_settlement', $shopInfo);
            $insert_id = $this->db->insert_id();
            $this->db->trans_complete();
        }
        return $insert_id;
    }
}

/* End of file settle_model.php */
/* Location: .application/models/settle_model.php */
?>