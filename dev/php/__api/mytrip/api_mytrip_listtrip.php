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
        	$row['trpLsCvr_url'] = ml_tool_picid::pid2url($row['cover_picid'], ML_IMG_SIZE_IPHONECROP30X3);
            $row['trpShwCvr_url'] = ml_tool_picid::pid2url($row['cover_picid'], ML_IMG_SIZE_IPHONECROP32X15);
            $a = ml_tool_picid::pid2wh($row['cover_picid'] , ML_IMG_SIZE_IPHONECROP32X15);
            $row['trpShwCvr_h'] = $a['height'];
        }

        $aData = array(
        	'total' => $total,
        	'rows' => $rows,
        );
        $this->api_output(ML_RCODE_SUCC , $aData);
    }
}

new aj_listtrip();