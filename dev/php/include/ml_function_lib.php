<?php
/**
 *@fileoverview: [群博客]
 *@author: 武光蕊 <guangrui@staff.sina.com.cn>
 *@date: 2010-12-02
 *@function:群博客相关的公共方法
 *@copyright: sina
 *
 */

class gb_function_lib
{
    /**
     * 格式化标签
     *
     * @param string $tag
     * @return array
     */
    public static function  gb_tag_format($tag)
    {

        $tag = str_replace(array("\\","\"","'","<",">","=","?"),"", $tag);
        $output = array();
        $replace = array("，", " ", "　", ";", "；");
        $tag = str_replace($replace, ",",$tag);
      
        $arr = explode(",", $tag);
        for($i = 0; $i < count($arr); $i++)
        {
            //分类只能使用中文英文和数字
            $arr[$i] = String::esc_str4url($arr[$i]);

            if('' == trim($arr[$i])) continue;
            $output[] = String::substr($arr[$i], 0, GB_CONF_GTAG_LENGTH, 'utf-8');
        }
        $output = array_unique($output);
        return $output;
    }

    /**
     * 生成标签云
     *
     * @param array $data = array('tag'=>'','cnt'=> '')
     */
    public static function gb_make_tag_cloud($data)
    {
        foreach ($data as  $v)
        {
            $tag_data[$v['tag']]= $v['cnt'];
        }
        $src_data = $tag_data;
        asort($tag_data );
        $tag_key = array_keys($tag_data);
        $i = 0;
        foreach ($tag_key as $k)
        {
            if($i < min(8 , count($src_data)))
            {
                $src_data[$k] = '<li ><a herf="#">'.$k.'</a></li>';
            }
            if($i>=8 && $i< min(14 , count($src_data)) )
            {
                $src_data[$k] = '<li class="f12"><a herf="#">'.$k.'</a></li>';
            }
            if($i >=14 && $i < min(18,count($src_data) ))
            {
                $src_data[$k] = '<li class="f14"><a herf="#">'.$k.'</a></li>';
            }
            if($i >=18 && $i < min(20 , count($src_data)))
            {
                $src_data[$k] = '<li class="f16"><a herf="#">'.$k.'</a></li>';
            }
            $i++;
        }
        $tag_html = implode('' , $src_data);
        return $tag_html;
    }

    public static function gb_secure_key_encode($key)
    {
        return substr(md5($key.GB_CNF_SECURE_CODE) , 3 , 10);
    }

    public static function gb_secure_key_check($key , $secure_code)
    {
        return self::gb_secure_key_encode($key) == $secure_code;
    }

    public static function gb_admin_cutpage($total_page , $page_now , $url)
    {
        $page_show_num = 2;

        if($page_now <= $page_show_num)
        $page_start = 1;
        elseif ($page_now >= ($total_page-$page_show_num))
        {
            $page_start = $total_page - 2*$page_show_num;
            $page_start = $page_start < 1 ? 1 : $page_start;
        }
        else
        $page_start = $page_now - $page_show_num;
            
        $page_over = $page_start + (2*$page_show_num);
        ob_start();
        ?>
<ul class="pages">
<?php if($page_now > 1){ ?>
    <li class="SG_pgprev"><a
        href="<?php echo str_replace('(*)' , $page_now-1 , $url) ?>"
        title="上页">&lt;&nbsp;上页</a></li>
        <?php if($page_now >$page_show_num+1 && $page_start != 1){ ?>
    <li><a href="<?php echo str_replace('(*)' , 1 , $url) ?>" title="第1页">1</a></li>
    <?php } ?>
    <?php if($page_start > 2){ ?>
    <li class="SG_pgelip">...</li>
    <?php } ?>
    <?php
    $show_num = $page_show_num*2+1;
    for ($i = 0 ; $i< $show_num; $i++){
        $page_num = $page_start + $i;
        if($page_num > $total_page)
        break;
        ?>
        <?php if($page_num == $page_now){ ?>
    <li class="SG_pgon"><?php echo $page_num; ?></li>
    <?php
        }else{
            $page_url = str_replace('(*)' , $page_num , $url);
            ?>
    <li><a href="<?php echo $page_url ?>"
        title="第<?php echo $page_num; ?>页"><?php echo $page_num; ?></a></li>
        <?php } ?>
        <?php } ?>
        <?php if($page_over < $total_page){ ?>
    <li class="SG_pgelip">...</li>
    <?php } ?>
    <?php } ?>
    <?php if($page_now != $total_page){ ?>
    <li class="SG_pgnext"><a
        href="<?php echo str_replace('(*)' , $page_now+1 , $url) ?>"
        title="下页">下页&nbsp;&gt;</a></li>
        <?php } ?>
</ul>
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    public static function gb_front_cutpage($total_page , $page_now , $url)
    {
        $page_show_num = 3;
        $page_start = $page_now - $page_show_num < 1 ? 1 : $page_now - $page_show_num;
        $page_over = $page_now + $page_show_num > $total_page ? $total_page : $page_now + $page_show_num;
        ob_start();
        ?>
<ul class="pages">
<?php if($page_now >1){ ?>
    <li class="SG_pgprev"><a
        href="<?php echo str_replace('(*)' , $page_now-1 , $url) ?>"
        title="上页" target="_parent">&lt;&nbsp;上页</a></li>
        <?php } ?>
        <?php if($page_start != 1){ ?>
    <li class="SG_pgelip">...</li>
    <?php } ?>
    <?php for ($i = $page_start ; $i<=$page_over ; $i++){ ?>
    <?php if($i == $page_now){ ?>
    <li class="SG_pgon"><?php echo $page_now; ?></li>
    <?php
    }else{
        $page_url = str_replace('(*)' , $i , $url);
        ?>
    <li><a href="<?php echo $page_url ?>" title="第<?php echo $i; ?>页"
        target="_parent"><?php echo $i; ?></a></li>
        <?php } ?>
        <?php } ?>
        <?php if($page_over != $total_page){ ?>
    <li class="SG_pgelip">...</li>
    <?php } ?>
    <?php if($page_now != $total_page){ ?>
    <li class="SG_pgnext"><a
        href="<?php echo str_replace('(*)' , $page_now+1 , $url) ?>"
        title="下页">下页&nbsp;&gt;</a></li>
        <?php } ?>
</ul>
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    /**
     * 封装微博用户的昵称和头像
     *
     * 修改了获取数据的方法
     * 陈传文 <chuanwen@staff.sina.com.cn> @date 2011-5-5
     * @param array    $aUid    用户id
     *
     * @return array         返回用户昵称和头像数组
     * array(
     *     uid => array(    //注意此UID.
     *                 'nick' => 'xxxxx',    //昵称
     *                 'protrati' => 'http://ss.sinaimg/sdfsfsdfsdf',    //头像
     *             )
     * )
     * @copyright sina
     */
    public static function get_weibo_user_info(array $aUid)
    {
        if(!is_array($aUid))
        return false;

        $aRs        = array();

        $oWeiboUser = new gb_datamodel_weibo_user(GB_WEIBO_APPKEY, GB_WEIBO_APPSECRET, GB_WEIBO_DUMMY_TOKEN, GB_WEIBO_DUMMY_SECRET);
        $rs            = $oWeiboUser->multi_user_show($aUid);
        if(!$rs)
        {
            foreach ($aUid as $uid)
            $aRs[$uid] = array(
                   'nick' => $uid,
                   'portrait' => '',
                   'portrait_30' => '',
                   'portrait_180' => '',
            );
        }
        else
        {
            $users = $oWeiboUser->get_data();
            foreach ($users as $row)
            {
                $uid = $row['id'];
                $aRs[$uid] = array(
                    'nick' => $row['name'],
                    'portrait' => $row['profile_image_url'],    //此头像大小应该是/50/
                    'portrait_30' => str_replace('/50/', '/30/', $row['profile_image_url']),
                    'portrait_180' => str_replace('/50/', '/180/', $row['profile_image_url']),
                );
            }
            if (count(array_unique($aUid)) !== count($aRs)){ //容错, 把未处理的数据赋默认值
                foreach ($aUid as $u){
                    if (!$aRs[$u]){
                        $aRs[$u] = array('nick' => $u, 'portrait' => '', 'portrait_30' => '', 'portrait_180' => '');
                    }
                }
            }
        }
        return $aRs;
    }
    /**
     * 前台分页
     * @param int $total_rows 总记录数
     * @param int $page_now 当前页数
     * @param string $url 地址 使用 (*) 通配符
     * @param int $pagesize 每页记录数 (默认10)
     * @param array $page_class_array 生成的页码的样式(Class名)数组
     * $page_class_array = array(
     *        'prev'    =>    //上一页样式
     *        'next'    =>    //下一页样式
     *        'on'    =>    //当前页样式
     *        'elip'    =>    //分隔符样式
     * )
     * @return string
     */
    public static function gb_get_pageshow($total_rows , $page_now , $url , $pagesize = 10 , $page_class_array = array()) {

        $pg_prev = isset($page_class_array['prev']) ? $page_class_array['prev'] : "SG_pgprev";
        $pg_next = isset($page_class_array['next']) ? $page_class_array['next'] : "SG_pgnext";
        $pg_elip = isset($page_class_array['elip']) ? $page_class_array['elip'] : "SG_pgelip";
        $pg_on   = isset($page_class_array['on'])   ? $page_class_array['on']   : "SG_pgon";
        $head = '<div class="page"><ul class="pages">';
        $tail = '</ul></div>';
        //不足一页什么都不显示。
        if($total_rows <= $pagesize) {
            return '';
        }
        $page_total = ceil($total_rows/$pagesize);
        if ($page_total < 1) {
            return '';
        } else {
            //上一页
            $prev = $page_now > 1 ? '<li class="'. $pg_prev .'"><a href="'.str_replace('(*)' , ($page_now-1) , $url).'" title="跳转至第 '.($page_now-1).' 页" target="_parent">&lt;&nbsp;上页</a></li>' : '';
            //下一页
            $next = $page_now < $page_total ? '<li class="'. $pg_next .'"><a href="'.str_replace('(*)' , ($page_now+1) , $url).'" title="跳转至第 '.($page_now+1).' 页" target="_parent">下页&nbsp;&gt;</a></li>' : '';

            if ($page_total <= 11) {
                for ( $i=1; $i<=$page_total; $i++) {
                    if ($i == 1) {
                        $title = '跳转至第一页';
                    } elseif($i == $page_total) {
                        $title = '跳转至最后一页';
                    } else {
                        $title = '跳转至第 '.$i.' 页';
                    }
                    $page_list[] = $i == $page_now
                    ? '<li class="'. $pg_on .'" title="当前所在页">'.$i.'</li>'
                    : '<li><a href="'.str_replace('(*)' , $i , $url).'" title="'.$title.'" target="_parent">'.$i.'</a></li>';
                }
            } else {
                if ($page_now <= 6 ) {
                    for ( $i=1; $i<=10; $i++) {
                        if ($i == 1) {
                            $title = '跳转至第 1 页';
                        } else {
                            $title = '跳转至第 '.$i.' 页';
                        }
                        $page_list[] = $i == $page_now
                        ? '<li class="'. $pg_on .'" title="当前所在页">'.$i.'</li>'
                        : '<li><a href="'.str_replace('(*)' , $i , $url).'" title="'.$title.'" target="_parent">'.$i.'</a></li>';
                    }
                    $page_list[] = '<li class="'. $pg_elip .'">...</li>';
                    $page_list[] = '<li><a href="'.str_replace('(*)' , $page_total , $url).'" title="跳转至最后一页" target="_parent">'.$page_total.'</a></li>';
                } elseif( $page_total-6 < $page_now) {
                    $page_list[] = '<li><a href="'.str_replace('(*)' , 1 , $url).'" title="跳转至第一页" target="_parent">1</a></li>';
                    $page_list[] = '<li class="'. $pg_elip .'">...</li>';
                    for ( $i = $page_total-9; $i<=$page_total; $i++)
                    {
                        $title = '跳转至第 '.$i.' 页';
                        $page_list[] = $i == $page_now
                        ? '<li class="'. $pg_on .'" title="当前所在页">'.$i.'</li>'
                        : '<li><a href="'.str_replace('(*)' , $i , $url).'" title="'.$title.'" target="_parent">'.$i.'</a></li>';
                    }
                } else {
                    $min  = max($page_now-4,1);
                    $max  = min($page_now+5,$page_total);
                    $diff = 8 - ($max - $min);
                    if ($diff < 0 && $max != $page_total) {
                        $max = $max+$diff;
                    }
                    $page_list[] = '<li><a href="'.str_replace('(*)' , 1 , $url).'" title="跳转至第一页" target="_parent">1</a></li>';
                    $page_list[] = '<li class="'. $pg_elip .'">...</li>';
                    for ( $i=$min; $i<=$max; $i++) {
                        $title = '跳转至第 '.$i.' 页';
                        $page_list[] = $i == $page_now
                        ? '<li class="'. $pg_on .'" title="当前所在页">'.$i.'</li>'
                        : '<li><a href="'.str_replace('(*)' , $i , $url).'" title="'.$title.'" target="_parent">'.$i.'</a></li>';
                    }
                    if ($max != $page_total) {
                        $page_list[] = '<li class="'. $pg_elip .'" title="'.$title.'">...</li>';
                        $page_list[] = '<li><a href="'.str_replace('(*)' , $page_total , $url).'"  title="跳转至最后一页" target="_parent">'.$page_total.'</a></li>';
                    }
                }
            }

            return $head.$prev.implode('',$page_list).$next.$tail;
        }
    }


    /**
     * 获得虚假数据
     *
     * @param int $pid
     * @param int $rs
     * @return unknown
     */
    public static function getVitrulCnt($pid,$rs) {
        $bot = substr(sprintf("%u", crc32($pid)),0,3);
        $bot = (int)$bot;
        if( $bot > 500) {
            $bot = $bot%100 + 100;
        }
        $rs = $bot + $rs;
        return $rs;
    }

    /**
     * 获取pvdb里的分享数
     *
     * @param char $pid
     * @return unknown
     */
    public static function getShart($pid) {
        $mc = new Memcache;
        $rs = $mc->connect('pvdb21204.vader.matrix.sina.com.cn', 21204);
        $data = $mc->get($pid);
        return $data;
    }
    /**
     * 获取pvdb里的分享数
     *
     * @param char $pid
     * @return unknown
     */
    public static function getSharts($pids) {
        $mc = new Memcache;
        $rs = $mc->connect('pvdb21204.vader.matrix.sina.com.cn', 21204);
        $data = $mc->getMulti($pids);
        return $data;
    }
}
?>