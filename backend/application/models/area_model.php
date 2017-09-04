<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class area_model extends CI_Model
{
    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getAreas($name = '', $address = 'all', $status = 0)
    {
        $this->db->select('*');
        if ($address != 'all') {
            $addrs = explode("_", $address);
            for ($i = 0; $i < count($addrs); $i++) {
                $likeCriteria = "(address  LIKE '%" . $addrs[$i] . "%')";
                $this->db->where($likeCriteria);
            }
        }
        $this->db->from('tourist_area');
        if ($name != 'all') {
            $likeCriteria = "(name  LIKE '%" . $name . "%')";
            $this->db->where($likeCriteria);
        }
        if ($status == 1) { // if area is available
            $this->db->where('status', 1);
        } elseif ($status == 2) { // if area is disable
            $this->db->where('status', 0);
        }
        $this->db->where('type', 2); //  2-area
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
        $this->db->where('type', 2); //2-area
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function get_all($type = 2)
    {
        $this->db->select('*');
        $this->db->from('tourist_area');

        $this->db->where('type', $type);  // 2-area
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getCourses($name = '', $status = 0)
    {
        $this->db->select('*');
        $this->db->from('tourist_area');
        if ($name != 'all') {
            $likeCriteria = "(name  LIKE '%" . $name . "%')";
            $this->db->where($likeCriteria);
        }
        if ($status == 1) { // if course is available
            $this->db->where('status', 1);
        } elseif ($status == 2) { // if course is disable
            $this->db->where('status', 0);
        }
        $this->db->where('type', 1); //1-course
        $query = $this->db->get();
        $qresult = $query->result();
        if (count($qresult) > 0) {
            $i = 0;
            foreach ($qresult as $item) {
                $newCourseItem = $item;
                $areaItems = json_decode($item->point_list);
                $newareaItems = array();
                if (count($areaItems) > 0) {
                    foreach ($areaItems as $area) {
                        $newarea = $area;
                        $areaData = $this->getAreaById($area->id);
                        if (count($areaData) > 0) {
                            $newarea->name = $areaData->name;
                            array_push($newareaItems, $newarea);
                        }
                    }
                }
                $newCourseItem->point_list = json_encode($newareaItems);
                $newCourses[$i] = $newCourseItem;
                $i++;
            }
        }
        if (isset($newCourses))
            return $newCourses;
        else
            return NULL;
    }

    /**
     * This function is used to get Tourist Area by id
     * @return array $result : This is result
     */
    function getCourseNameByAreaId($id)
    {
        $courseName = '';
        $areaInfo = $this->getAreaById($id);
        if (isset($areaInfo)) {
            if ($areaInfo->type == '1') {  // if xianlu, make name.
                $areas = json_decode($areaInfo->point_list);
                foreach ($areas as $areaItem) {
                    if ($courseName == '') $courseName = $areaItem->name;
                    else $courseName = $courseName . ' - ' . $areaItem->name;
                }
            } else {  // if jingqu, make name.
                $courseName = $areaInfo->name;
            }
        }
        return $courseName;
    }

    /**
     * This function is used to get Tourist Area by id
     * @return array $result : This is result
     */
    function getAreaStatusByCourseId($id, $status ='')
    {
        $courseInfo = $this->getAreaById($id);
        if (isset($courseInfo)) {
            if ($courseInfo->type == '1') {  // if xianlu, check child status
                $areas = json_decode($courseInfo->point_list);
                foreach ($areas as $item) {
                    $area = $this->getAreaById($item->id);
                    if($area->status == $status){
                        return FALSE;
                    }
                }
            }
        }
        return TRUE;
    }

    /**
     * This function is used to get Tourist Area by id
     * @return array $result : This is result
     */
    function getParentCourseByAreaId($id, $status = '')
    {
        $result = FALSE;
        $courses = $this->get_all(1);
        if (count($courses) == 0) return $result;
        foreach ($courses as $item) {
            $course_info = json_decode($item->point_list);
            if (count($course_info) == 0) continue;
            if ($status != '' && $item->status != $status) continue;
            foreach ($course_info as $areainfo) {
                if ($areainfo->id == $id) {
                    $result = TRUE;
                }
            }
        }
        return $result;
    }

    /**
     * This function is used to get Tourist Area by id
     * @return array $result : This is result
     */
    function getAreaById($id)
    {
        $this->db->select('*');
        $this->db->from('tourist_area');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $result = $query->result();
        if (count($result))
            return $result[0];
        else
            return NULL;
    }

    /**
     * This function is used to get Tourist Area by id
     * @return array $result : This is result
     */
    function getAreaByAuthId($authid)
    {
        $this->db->select('*');
        $this->db->from('tbl_authcode as au');
        $this->db->join('tourist_area as ta', 'au.targetid = ta.id');
        $this->db->where('au.id', $authid);
        $query = $this->db->get();
        $result = $query->result();
        if (count($result))
            return $result[0];
        else
            return NULL;
    }

    /**
     * This function is used to add new area to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewArea($areainfo)
    {
        $this->db->trans_start();
        $this->db->insert('tourist_area', $areainfo);

        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

    /**
     * This function is used to add new area to system
     * @return number $insert_id : This is last inserted id
     */
    function addAttraction($pointInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tourist_area', $pointInfo);

        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

    /**
     * This function is used to update the area information
     * @param array $areaInfo : This is users updated information
     * @param number $areaId : This is area id
     */
    function update($areaInfo, $areaId)
    {
        $this->db->where('id', $areaId);
        $this->db->update('tourist_area', $areaInfo);
        return TRUE;
    }

    /**
     * This function is used to delete the user information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    function delete($areaId)
    {
        $this->db->where('id', $areaId);
        $this->db->delete('tourist_area');
        return TRUE;
    }
}


/* End of file area_model.php */
/* Location: .application/models/area_model.php */

