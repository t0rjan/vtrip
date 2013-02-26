<?php

include_once '../../__global.php';


class aj_listtripphoto extends ml_controller {
    
    private $uid;
    private $trip_id;
    private $page;
   
    
    function initParam() {
        $this->uid = $this->input('uid');
        $this->trip_id = $this->input('trip_id');
        $this->page = $this->input('page' ,'get' , 1);
    }
    function checkParam() {

    }
    
    function main() {
        $oPhoto = new ml_model_dbTripPhoto();
        $oPhoto->getCountByTripId($this->trip_id , $uid);
        $total = $oPhoto->get_data();

        $oPhoto->listPhotoByTripId($this->trip_id , $uid , $this->page);
        $rows = $oPhoto->get_data();
        foreach ($rows as &$row) {
            $wh = ml_tool_picid::pid2wh($row['pic_id']);
            $row['width_pin'] = $wh['width'];
            $row['height_pin'] = $wh['height'];
            $row['img_url_pin'] = ml_tool_picid::pid2url($row['pic_id']);

            $wh = ml_tool_picid::pid2wh($row['pic_id'] , ML_IMG_SIZE_PIC);
            $row['width_pic'] = $wh['width'];
            $row['height_pic'] = $wh['height'];
            $row['img_url_pic'] = ml_tool_picid::pid2url($row['pic_id'] , ML_IMG_SIZE_PIC);
        }

        $aData = array(
        	'total' => $total,
        	'rows' => $rows,
        );
        $this->api_output(ML_RCODE_SUCC , $aData);
    }
}

new aj_listtripphoto();