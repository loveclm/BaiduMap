<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class shop_model extends CI_Model
{

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getShops($name = '', $address = 'all', $status = 0, $shopnumber='')
    {
        $this->db->select('*');
        $this->db->from('shop');
        if ($name != 'all') {
            $likeCriteria = "(name  LIKE '%" . $name . "%')";
            $this->db->where($likeCriteria);
        }
        if ($status == 1) {
            $this->db->where('status', 0);
        } elseif ($status == 2) {
            $this->db->where('status', 1);
        }
        if ($address != 'all') {
            $likeCriteria = "(address_1  LIKE '%" . $address . "%')";
            $this->db->where($likeCriteria);
        }
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
    function findAreas($name = '')
    {
        $this->db->select('*');
        $this->db->from('tourist_area');
        if ($name != '') {
            $likeCriteria = "(name  LIKE '%" . $name . "%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('type', 1);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    /**
     * This function is used to get Tourist Area by id
     * @return array $result : This is result
     */
    function getShopById($id)
    {
        $this->db->select('*');
        $this->db->from('shop');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $result = $query->result();
        if (count($result)>0) return $result[0];
        return NULL;
    }

    /**
     * This function is used to get Tourist Area by id
     * @return array $result : This is result
     */
    function getShopIdByNumber($number)
    {
        $this->db->select('id');
        $this->db->from('shop');
        $this->db->where('phonenumber', $number);
        $query = $this->db->get();
        $result = $query->result();
        if (count($result)>0) return $result[0];
        return NULL;
    }

    /**
     * This function is used to add new shop to system
     * @return number $insert_id : This is last inserted id
     */
    function add($shopInfo)
    {
        $this->db->trans_start();
        $this->db->insert('shop', $shopInfo);

        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

    /**
     * This function is used to update the shop information
     * @param array $shopInfo : This is shops updated information
     * @param number $shopId : This is shop id
     */
    function update($shopInfo, $shopId)
    {
        $this->db->where('id', $shopId);
        $this->db->update('shop', $shopInfo);
        $authItems=$this->auth_model->getAuthsByShopId($shopId);
        if(count($authItems)>0){
            foreach($authItems as $item){
                $newItem = $item;
                $newItem->status=$shopInfo['status'];
                $this->db->where('id', $newItem->id);
                $this->db->update('tbl_authcode', $newItem);
            }
        }
        $qrItems=$this->getQRByShopId($shopId);
        if(count($qrItems)>0){
            foreach($qrItems as $item){
                $newItem = $item;
                $newItem->status=$shopInfo['status'];
                $this->db->where('id', $newItem->id);
                $this->db->update('qrcode', $newItem);
            }
        }
        return TRUE;
    }

    /**
     * This function is used to delete the shop information
     * @param number $shopId : This is shop id
     * @return boolean $result : TRUE / FALSE
     */
    function delete($shopId)
    {
        $this->db->where('id', $shopId);
        $this->db->delete('shop');
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

    function addAuthOrder($authOrderInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_order', $authOrderInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    function checkQR($authInfo)
    {
        $this->db->select('*');
        $this->db->from('qrcode');
        $this->db->where('shopid', $authInfo['shopid']);
        $this->db->where('targetid', $authInfo['targetid']);
        $query = $this->db->get();
        $result = $query->result();
        if (count($result) > 0) {
            return $result[0]->id;
        }
        return -1;
    }

    function getQRByShopId($shopid)
    {
        $this->db->select('*');
        $this->db->from('qrcode');
        $this->db->where('shopid', $shopid);
        $query = $this->db->get();
        $result = $query->result();
        if (count($result) > 0) {
            return $result;
        }
        return NULL;
    }


    /**
     * This function is used to add new shop to system
     * @return number $insert_id : This is last inserted id
     */
    function addQR($authInfo)
    {
        $id = $this->checkQR($authInfo);
        if ($id == -1) {
            $this->db->trans_start();
            $this->db->insert('qrcode', $authInfo);
            $insert_id = $this->db->insert_id();
            $this->db->trans_complete();
        } else {
            $this->db->where('id', $id);
            $this->db->update('qrcode', $authInfo);
        }
        return;
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getQR($name = '', $type = 0, $searchType=0)
    {
        $this->db->select('sh.name, sh.id as shopid, sh.phonenumber as shopnumber, qr.type, qr.created_time, qr.data, qr.id, qr.targetid');
        $this->db->from('qrcode as qr');
        $this->db->join('shop as sh', 'sh.id = qr.shopid');
        if ($searchType==0 && $name != 'all') {
            $likeCriteria = "(sh.name  LIKE '%" . $name . "%')";
            $this->db->where($likeCriteria);
        }

        if ($type != 0) {
            $this->db->where('qr.type', $type);
        }
        $this->db->where('qr.status', 0);
        $this->db->order_by('qr.created_time','DESC');

        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }
}

/* End of file shop_model.php */
/* Location: ./application/models/shop_model.php */