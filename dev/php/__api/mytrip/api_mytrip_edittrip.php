<?php

include_once '../../__global.php';
include_once SERVER_ROOT_PATH.'/include/config/dataRule/ml_datarule_content.php';
class aj_edittrip extends ml_api_controller {
    
    private $uid;
    private $id;
    private $title;
    private $startDate;
    private $days;
    private $destination;
   
    
    function initParam() {
        $this->id = $this->input('id');
        $this->uid = $this->input('uid');
        $this->title = $this->input('title');
        $this->startDate = $this->input('startDate');
        $this->days = (int)$this->input('days');
        $this->destination = $this->input('dest');
    }
    function checkParam() {
        $this->check_user_permission($this->uid);
        
        $titleLen = Tool_string::str_width($this->title);
        if($titleLen < ML_TRIP_TITLE_LEN_MIN || $titleLen > ML_TRIP_TITLE_LEN_MAX)
            $this->api_output(ML_RCODE_PARAM , null , 'titleWrong');
        elseif(ml_tool_dangeroursWordCheck::isDangeroursBasic($this->title))
            $this->api_output(ML_RCODE_PARAM , null , 'titleDanger');
        elseif (date('Y-m-d' , strtotime($this->startDate.' 00:00:00')) != $this->startDate) {
            $this->api_output(ML_RCODE_PARAM , null , 'startDate');
        }
    }
    
    function main() {
        $oTrip = new ml_model_dbTrip();
        
        $oTrip->editTripByid($this->id , $this->uid , $this->startDate , $this->days , $this->title);
        
        
        $this->api_output(ML_RCODE_SUCC);
    }
}

new aj_edittrip();