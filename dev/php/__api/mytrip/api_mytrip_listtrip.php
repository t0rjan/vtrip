<?php

include_once '../../__global.php';


class aj_listtrip extends ml_controller {
    
    private $uid;
    private $page;
   
    
    function initParam() {
        $this->uid = $this->input('uid');
        $this->page = $this->input('page' ,'get' , 1);
    }
    function checkParam() {

    }
    
    function main() {
        $oTrip = new ml_model_dbTrip();
        $oTrip->getTripCountByUid($this->uid);
        $total = $oTrip->get_data();
        $oTrip->getTripListByUid($this->uid , $this->page);
        $rows = $oTrip->get_data();
        foreach ($rows as &$row) {
        	$row['img_url'] = ml_tool_picid::pid2url($row['cover_picid']);
        }

        $aData = array(
        	'total' => $total,
        	'rows' => $rows,
        );
        $this->api_output(ML_RCODE_SUCC , $aData);
    }
}

new aj_listtrip();