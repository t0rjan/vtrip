<?php

include_once '../../__global.php';


class aj_mytrip_addcomment extends ml_api_controller {
    
    private $dest_uid;
    private $trip_id;
    private $photo_id;
    private $comment;
   
    
    function initParam() {
        $this->dest_uid = (int)$this->input('dest_uid');
        $this->trip_id = (int)$this->input('trip_id');
        $this->photo_id = (int)$this->input('photo_id');
        $this->comment = $this->input('comment');

    }
    function checkParam() {
        
    }
    
    function main() {

    	$oCmt = new ml_model_dbTripComment();
    	$oCmt->addComment($this->getOperateUid() , $this->dest_uid , $this->trip_id , $this->photo_id , $this->comment);
        $this->api_output(ML_RCODE_SUCC);
    }
}

new aj_createtrip();