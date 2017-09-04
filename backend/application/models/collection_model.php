<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class collection_model extends CI_Model
{

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getUserLists($searchType, $name)
    {
        $this->db->select('userId, mobile');
        $this->db->from('user');
        if ($name != '') {
            switch ($searchType) {
                case '0':
                    $likeCriteria = "(mobile  LIKE '%" . $name . "%')";
                    break;
            }
            $this->db->where($likeCriteria);
        }

        $query = $this->db->get();
        $result = $query->result();
        $retData = NULL;
        if (count($result) == 0) {
            return '';
        } else {
            $i = 0;
            foreach ($result as $item) {
                $orders = $this->order_model->getOrderCountByUser($item->mobile);
                if ($orders > 0) {
                    $retData[$i] = $item;
                    $i++;
                }
            }
            return $retData;
        }
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getBuyOrderPaid($mobile)
    {
        $this->db->select('*');
        $this->db->from('tbl_order');
        $this->db->where('userphone', $mobile);
        $this->db->where("ordertype <> '4'");
        $qresult = $this->db->count_all_results();

        return $qresult;
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getAuthOrderPaid($mobile)
    {
        $this->db->select('*');
        $this->db->from('tbl_order');
        $this->db->where('userphone', $mobile);
        $this->db->where('ordertype', '4');
        $qresult = $this->db->count_all_results();
        return $qresult;
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getTotalPaid($id)
    {
        return ($this->getAuthOrderPaid($id) + $this->getBuyOrderPaid($id));
    }
}

/* End of file collection_model.php */
/* Location: .application/models/collection_model.php */
