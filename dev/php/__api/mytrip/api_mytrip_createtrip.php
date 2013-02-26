<?php

include_once '../../__global.php';


class aj_createtrip extends ml_controller {
    
    private $uid;
    private $tripTitle;
    private $startDate;
    private $days;
    private $destination;
   
    
    function initParam() {
        Tool_logger::debugLog('dd' , serialize($_POST));
        $this->uid = $this->input('uid');
        $this->tripTitle = $this->input('title');
        $this->startDate = $this->input('startDate');
        $this->days = $this->input('days');
        $this->destination = $this->input('dest');
    }
    function checkParam() {
        
    }
    
    function main() {
        $oTrip = new ml_model_dbTrip();
        
        $oTrip->addTripByUid($this->uid , $this->startDate , $this->days , $this->tripTitle);
        
        
        $this->api_output(ML_RCODE_SUCC);
    }
}

new aj_createtrip();