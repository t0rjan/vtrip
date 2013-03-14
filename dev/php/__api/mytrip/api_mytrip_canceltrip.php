<?php

include_once '../../__global.php';
include_once SERVER_ROOT_PATH.'/include/config/dataRule/ml_datarule_content.php';
class aj_canceltrip extends ml_api_controller {
    
    private $uid;
    private $id;
    
   
    
    function initParam() {
        $this->id = $this->input('id');
        $this->uid = $this->input('uid');
    }
    function checkParam() {
        $this->check_user_permission($this->uid);
        
    }
    
    function main() {
        $oTrip = new ml_model_dbTrip();
        
        $oTrip->delTripByid($this->id , $this->uid);
    
        $this->api_output(ML_RCODE_SUCC);
    }
}

new aj_canceltrip();