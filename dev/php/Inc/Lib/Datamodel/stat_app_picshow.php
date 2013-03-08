<?php
function generateImageUrl($pid, $type = 'bmiddle')
    {
        if (empty($pid)) {
            return null;
        }
        //计算url
        if ($pid [9] == 'w') {
            $hv = crc32($pid);
            $zone = ($hv & 3) + 1;
            $ext = ($pid [21] == 'g' ? 'gif' : 'jpg');
            $picUrl = "http://ww{$zone}.sinaimg.cn/{$type}/{$pid}.{$ext}";
        } else {
            $zone = (hexdec(substr($pid, - 2)) % 16) + 1;
            $picUrl = "http://ss{$zone}.sinaimg.cn/{$type}/{$pid}&690";
        }
        return $picUrl;
    }
function save_html($page , $s)
{
	$fp = fopen('./app_pic_show_'.$page.'.html', 'w');
	fwrite($fp, $s);
	fclose($fp);
}

$a = file('source_pic_lise.log');
$n = 0;
$page = 0;
$html = '';

foreach($a as $row)
{
	$n+=1;
	list($sid , $src , $pids) = explode("\t", $row);
	$aPid = explode(",", $pids);
	$html.=='<font color="red"><b>'.$src.' '.$sid.'</b></font><br/>';
	foreach ($$aPid as $key => $value) {
		$html.='<img src="'.generateImageUrl($value , 'small').'"/>';
	}

	if ($n > 10) {
		save_html($page , $html);
		$n = 0;
		$html='';
		$page +=1;
	}
}
?>