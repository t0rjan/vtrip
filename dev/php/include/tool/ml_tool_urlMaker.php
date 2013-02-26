<?php
class ml_tool_urlMaker
{
    static public function op_index(){
        return '/';
        //return '/page/lb/lb_home.php';
    }

    static public function guang_basic()
    {
        $path = '/guang/';
        return $path;
    }
    static public function guang_ctg($ctg_name , $order = 'hot')
    {

        return '/guang/'.$ctg_name.'/'.$order;
    }
    static public function guang_tag($tgname , $order = 'hot', $tj_rid='')
    {
        //        return '/page/lm/guang_tag.php?tag='.$tgid.'&order='.$order;
        if($tj_rid)
            return '/guang/word/'.$tgname.'?tj_rid='.$tj_rid;
        else
            return '/guang/word/'.$tgname;
    }
    static public function guang_ctgtag($ctgname , $tgname , $order = 'hot', $tj_rid='')
    {
        //    return '/page/lm/guang_ctgtag.php?ctgtag='.$ctg.'_'.$tgid.'&order=hot';
        if($tj_rid)
            return '/guang/'.$ctgname.'/'.$tgname.'/'.$order.'?tj_rid='.$tj_rid;
        else
            return '/guang/'.$ctgname.'/'.$tgname.'/'.$order;
    }
    
    /*单品最终页url*/
    static public function show_goods($rid, $frm='')
    {
        if($frm){
            $frm = '?frm='.$frm;
            $url = '/goods/'.$rid.$frm;
        }else{
            if(defined('ML_SHOW_GOODS_FRM')){
                $url = '/goods/'.$rid.'?frm='.ML_SHOW_GOODS_FRM;
            }else{
                $url = '/goods/'.$rid;
            }
        }
        
        return $url;
    }

    static public function my_fav_page($uid)
    {
        //        return '/page/user/favourite.php?uid='.$uid;
        return '/home/fav/u/'.$uid;
    }

    //    她的宝贝分享
    static public function person_index($uid)
    {
        //        return '/page/user/share_pic.php?uid='.$uid;
        return '/home/share/u/'.$uid;
    }
    static public function person_likeme($uid) {
        return '/attitude/u/'.$uid;
    }
    // 碎碎念
    static public function person_feed($uid)
    {
        //        return '/page/user/feed.php?uid='.$uid;
        return '/home/all/u/'.$uid;
    }
    static public function error_del($rid)
    {
        return '/page/content/error_del.php?rid='.$rid;
    }
    static public function myCollection($uid)
    {
        return '/page/collection/myCollection.php?uid='.$uid;
    }
    static public function col_content($cid,$uid)
    {
        return '/page/collection/collectionContent.php?uid='.$uid.'&col_id='.$cid;
    }

    static public function fansList($uid)
    {
        return '/home/fans/u/'.$uid;
    }

    static public function followList($uid)
    {
        //return '/page/relation/followList.php?uid='.$uid;
        return '/home/follow/u/'.$uid;
    }
    
    static public function recommend_friend()
    {
        return '/page/relation/recommend_friend.php';
    }

    static public function searchTag($tag){
        return '/search/goods/'.$tag;
        //        return '/page/search/search_gd.php?searchKey='.$tag;
    }

    static public function searchUser($searchKey, $where) {
        return '/search/users/'.$searchKey. '?where='. $where;;
        //    return '/page/search/search_user.php?searchKey='.$searchKey;
    }

    static public function searchGd($searchKey, $sort){
        return '/search/goods/'.$searchKey. '?sort='. $sort;
        //return '/page/search/search_gd.php?searchKey='.$searchKey;
    }
    /**
     * 生成一级导航的红色，不知道放在这里合适不
     * @xinhua
     */
    static public function firstnav($index){

        $rul=$_SERVER['PHP_SELF'];
        $rul_arr=explode("/", $rul);
        $final="";
        if($rul_arr[count($rul_arr)-1]=="shop_index.php" && $index === 'shop'){
            $final = 'cur';
        }else if($rul_arr[count($rul_arr)-1]=="brand.php" && $index === 'brand'){
            $final = 'cur';
        }else if($rul_arr['1']=="guang" && $index === 'guang'){
            $final = 'cur';
        }
        else if($rul_arr['1']==="index.php" && $index === 'op_index'){
            $final = 'cur';
        }
        else if($rul_arr['1']=="lookbook" && is_integer($index)){
            $class_id = substr($rul_arr['2'] , 6);
            if($class_id == $index)
                $final = 'cur';
        }
        return $final;
    }


    /**
     * lookbook url
     * wangtao5@
     */
    static public function lb_home() {
        return '/lookbook/home';
    }

    static public function lb_album_list($cid='', $page=1){
        return '/lookbook/class_'.$cid;
        //        return '/page/lb/lb_album_list.php?class_id='.$cid.'&page='.$page;
    }
    static public function lb_album_show($cid, $aid , $pic_id = 0){
        if(ml_tool_ua::is_sinaMobileRead())
            return '/page/lb/lb_album_show.php?class_id='.$cid.'&album_id='.$aid.($pic_id ? '&pic_id='.$pic_id : '');
        else
            return '/lookbook/class_'.$cid.'/album_'.$aid.($pic_id ? '#!pid='.$pic_id : '');
        //    return '/page/lb/lb_album_show.php?class_id='.$cid.'&album_id='.$aid.($pic_id ? '&pic_id='.$pic_id : '');
    }
    static public function lb_filter_list($cid, $tids, $page=1){
        return '/lookbook/class_'.$cid.'/filterlist_'.$tids.'/p_'.$page;
        //return '/page/lb/lb_filter_list.php?class_id='.$cid.'&tag_ids='.$tids.'&page='.$page;
    }
    static public function lb_filter_show($cid, $tids, $page=1){
        return "/lookbook/class_$cid/filtershow_$tids/p_$page";
        //return '/page/lb/lb_filter_show.php?class_id='.$cid.'&tag_ids='.$tids.'&page='.$page;
    }
    static public function lb_piclist_show($cid, $aid, $pid){
        return "/lookbook/class_$cid/album_$aid/#!pid=$pid";
        //return '/page/lb/lb_filter_show.php?class_id='.$cid.'&tag_ids='.$tids.'&page='.$page;
    }
    static public function tinyurl($tiny)
    {
        return '/t/'.$tiny;
    }
    static public function brand($type){
        return '/page/brand/brand.php?type='.$type;
    }

    static public function guang_filter( $page_id, $tgname ,$maxPrice,$minPrice,$color,$filter, $order = 'hot',$ctgname = '' , $sex='')
    {
        switch ($page_id) {
            case 'guang_ctgtag':
                $url = '/guang/'.$ctgname.'/'.$tgname.'/'.$order;
                break;
            case 'guang_new':
                $url = '/guang?order='.$order;
                break;
            case 'guang_tag':
                $url = '/guang/word/'.$tgname.'/'.$order;
                break;
            case 'guang_ctg':
                $url = '/guang/'.$tgname.'/'.$order;
                break;
                    
        }
        $url_plus = "";
        if (!empty($maxPrice)){
            $url_plus.="&maxPrice=".$maxPrice;
        }
        if (!empty($minPrice)){
            $url_plus.="&minPrice=".$minPrice;
        }
        if (!empty($color)){
            $url_plus.="&color=".$color;
        }
        if (!empty($sex)){
            $url_plus.="&sex=".$sex;
        }
        if (!empty($filter)){
            $url_plus.="&filter=".$filter;
        }
        if($page_id!='guang_new'){
            $url_plus = substr($url_plus, 1);
            $url_plus = "?".$url_plus;
        }
        return $url.$url_plus;
    }
    
    static public function weibo_url($weibo_uid) {
        
        return 'http://weibo.com/u/'.$weibo_uid;
    }
}

