<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class auth_model extends CI_Model
{

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getAuths($searchType = 0, $name = '', $status = 0)
    {
        $this->db->select('au.id as id, sh.name as shopName, tr.name as tourName, ' .
            ' au.created_time as created, au.targetid as targetid, au.price as money, ' .
            ' sh.address_1 as total, au.codecount as count, sh.address_2 as used, ' .
            ' au.status as status, sh.phonenumber as shopnumber');
        $this->db->from('tbl_authcode as au');
        $this->db->join('shop as sh', 'au.shopid = sh.id');
        $this->db->join('tourist_area as tr', 'au.targetid = tr.id');
        if ($searchType == 0 && $name != 'ALL') {
            $likeCriteria = "(sh.name  LIKE '%" . $name . "%')";
            $this->db->where($likeCriteria);
        } else {
            //$likeCriteria = "(tr.name  LIKE '%" . $name . "%')";
            //$this->db->where($likeCriteria);
        }
        if ($status == 2) {
            $this->db->where('au.price', 0);
        } elseif ($status == 1) {
            $this->db->where("au.price > '0'");
        }
        $this->db->where('sh.status','0');

        //$this->db->where("au.status",'0');
        $this->db->order_by('au.created_time', 'desc');
        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0)
            return NULL;
        else
            return $result;
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getOrderTotal($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_order');
        $this->db->where('authid', $id);
        $qresult = $this->db->count_all_results();
        return $qresult;
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getAuthCountByShopId($id)
    {
        $this->db->select('id, codecount');
        $this->db->from('tbl_authcode');
        $this->db->where('shopid', $id);
        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0)
            return '0';
        $qresult = 0;
        foreach ($result as $item) {
            $qresult += $item->codecount;
        }
        return $qresult;
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getAuthsByShopId($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_authcode');
        $this->db->where('shopid', $id);
        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0)
            return NULL;
        else
            return $result;
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getAuthOrdersByAuthId($authid)
    {
        $this->db->select('*');
        $this->db->from('tbl_order');
        $this->db->where('authid', $authid);
        $this->db->order_by('authid');
        $this->db->order_by('areaid');

        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getOrderCount($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_authcode');
        $this->db->where('shopid', $id);
        $query = $this->db->get();
        $qresult = $query->result();
        $result = 0;
        if (count($qresult) > 0) {
            foreach ($qresult as $item) {
                $result += $this->getOrderTotal($item->id);
            }
        }
        return $result;
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getCount()
    {
        $this->db->select('*');
        $this->db->from('tbl_order');
        $qresult = $this->db->count_all_results();
        return $qresult;
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getOrderUsed($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_order');
        $this->db->where('authid', $id);
        $this->db->where("userphone <> '0'");
        $qresult = $this->db->count_all_results();
        return $qresult;
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getAuthOrders($id, $status)
    {
        $this->db->select('id, code, status, ordered_time');
        $this->db->from('tbl_order');
        $this->db->where('authid', $id);
        if ($status == '1')
            $this->db->where('status', $status);
        else if ($status != '0')
            $this->db->where("status <> '1'");
        $this->db->order_by('ordered_time', 'desc');
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getAuthOrder($id)
    {
        $this->db->select('value as number, code, userphone as mobile, ordered_time, status');
        $this->db->from('tbl_order');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $result = $query->result();
        return $result['0'];
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function updateMoney($id, $money)
    {
        $this->db->select('*');
        $this->db->from('tbl_authcode');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) return FALSE;
        $authInfo = $result['0'];
        if ($money == '0') $authInfo->status = 2;
        else if ($money != '0') $authInfo->status = 1;
        $authInfo->price = $money;
        $this->db->select('*');
        $this->db->where('id', $id);
        $this->db->update('tbl_authcode', $authInfo);
        return TRUE;
    }

    /**
     * This function is used to add new shop to system
     * @return number $insert_id : This is last inserted id
     */
    function addAuth($authInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_authcode', $authInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    /**
     * This function is used to get init of AuthCode
     * @return int count($result) + 1 : This is init
     */
    function getAuthInit($authInfo)
    {
        $this->db->select('*');
        $this->db->from('tbl_authcode');
        $this->db->where('shopid', $authInfo['shopid']);
        $this->db->where('targetid', $authInfo['targetid']);
        $result = $this->db->count_all_results();
        return $result + 1;
    }
}

/* End of file auth_model.php */
/* Location: .application/models/auth_model.php */
