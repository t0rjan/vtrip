<?php
/**
 * @fileoverview    淘宝sdk封装类
 * @important       
 * @date            2012-05-23
 * @author  gaojian3<gaojian3@staff.sina.com.cn>
 *
 */
include(SERVER_ROOT_PATH . '/3rd/taobao/TopSdk.php');
include(SERVER_ROOT_PATH . '/include/config/ml_taobao_config.php');

class ml_model_openapi_topsdk {
    
    protected $c;
    protected $req;
    protected $param;
    
    public function __construct() {
        
        $this->c = new TopClient;
        $this->c->appkey = ML_TAOBAO_READAPI_APPKEY;
        $this->c->secretKey = ML_TAOBAO_READAPI_APPSECRET;
        $this->c->format = "json";
    }
    public function set_data($name,$value){
        $this->$name=$value;
    }


    public function search($param){
        //改为淘宝客搜索
        $this->req = new TaobaokeItemsGetRequest;
        $this->fields='num_iid,title,nick,pic_url,price,click_url,commission,commission_rate,commission_num,commission_volume,shop_click_url,seller_credit_score,item_location,volume';
        $this->req->setPid(ML_TAOBAO_ALMM_PID);
        $this->set_data('param',$param);
        $this->setParam();
        return $this->exec();
    }
    
    public function getOneGoods($param){
        $this->req = new ItemGetRequest;
        $this->fields='num_iid,title,nick,pic_url,cid,price,type,delist_time,post_fee,score,volume,has_discount,num, is_prepay, promoted_service, ww_status, list_time';
        $this->set_data('param',$param);
        $this->setParam();
        return $this->exec();
    }
    
    public function getGoodsList($param){
        //一次不超过20
        $this->req = new ItemsListGetRequest;
        $this->fields='num_iid,title,nick,pic_url,cid,price,type,delist_time,post_fee,score,volume,has_discount,num, is_prepay, promoted_service, ww_status, list_time';
        $this->set_data('param',$param);
        $this->setParam();
        return $this->exec();
    }
    public function convertTaobaoke($param){
        //淘宝客链接每次最多40
        $this->req = new TaobaokeItemsConvertRequest;
        $this->req->setPid(OPENAPI_TAOBAO_ALMM_PID);
        $this->fields='num_iid,title,nick,pic_url,price,click_url,commission,commission_rate,commission_num,commission_volume,shop_click_url,seller_credit_score,item_location,volume';
        $this->set_data('param',$param);
        $this->setParam();
        $arr=$this->exec();
        if(isset($arr['taobaoke_items']['taobaoke_item'] ) && count($arr['taobaoke_items']['taobaoke_item']) >=1 ){
            foreach($arr['taobaoke_items']['taobaoke_item']  as  $val){
                $rs[$val['num_iid']] = $val;
            }
            return $rs;
        }else{
            return false;
        }
    }
    
    public function getCateName($param){
        $this->req = new ItemcatsGetRequest;
        $this->fields='cid,parent_cid,name,is_parent';
        $this->set_data('param',$param);
        $this->setParam();
        $arr=$this->exec();
        
        foreach($arr['item_cats']['item_cat'] as $val){
            $rs[$val['cid']]=$val['name'];
        }
        return $rs;
    }
    
    public function getShopInfo($param){
        $this->req = new ShopGetRequest;
        $this->fields='sid,cid,title,nick,desc,bulletin,pic_path,created,modified,shop_score';
        $this->set_data('param',$param);
        $this->setParam();
        return $this->exec();
    }
    
    public function getUserInfo($param){
        $this->req = new UsersGetRequest;
        $this->fields="user_id,nick,sex,buyer_credit,seller_credit,location";
        $this->set_data('param',$param);
        $this->setParam();
        return $this->exec();
    }
    
    
    
    
    
    /****************设置***************************/
    
    public function setParam(){
        
        if(!empty($this->param['fields'])){
            $this->req->setFields($this->param['fields']);
        }else{
            $this->req->setFields($this->fields);  //默认条件
        }
        
        if(!empty($this->param['nicks'])){
            $this->req->setNicks($this->param['nicks']);
        }
        if(!empty($this->param['nick'])){
            $this->req->setNick($this->param['nick']);
        }
        //默认1
        if(!empty($this->param['pagenum'])){
            $this->req->setPageNo($this->param['pagenum']);
        }
        //默认40
        if(!empty($this->param['pagesize'])){
            $this->req->setPageSize($this->param['pagesize']);
        }
        
        if(!empty($this->param['title'])){
            $this->req->setQ($this->param['title']);
        }
        if(!empty($this->param['orderby'])){
            $this->req->setOrderBy($this->param['orderby']);
        }
        
        //多个id
        if(!empty($this->param['numIids'])){
            $this->req->setNumIids($this->param['numIids']);
        }
        
        if(!empty($this->param['isMobile'])){
            $this->req->setIsMobile($this->param['isMobile']);
        }
        
        if(!empty($this->param['cids'])){
            $this->req->setCids($this->param['cids']);
        }
        
        //一个id
        if(!empty($this->param['numIid'])){
            $this->req->setNumIid($this->param['numIid']);
        }

        //一个id
        if(!empty($this->param['keyword'])){
            $this->req->setKeyword($this->param['keyword']);
        }
        //一个id
        if(!empty($this->param['area'])){
            $this->req->setArea($this->param['area']);
        }
        //一个id
        if(!empty($this->param['sort'])){
            $this->req->setSort($this->param['sort']);
        }
        //一个id
        if(!empty($this->param['mall_item'])){
            $this->req->setSort($this->param['mall_item']);
        }
        //一个id
        if(!empty($this->param['start_price'])){
            $this->req->setStartPrice($this->param['start_price']);
        }
        if(!empty($this->param['end_price'])){
            $this->req->setEndPrice($this->param['end_price']);
        }

        //后续可继续加。。。
        
    }
    
    public function exec(){
        $resp=$this->c->execute($this->req);
        return $this->object2Array($resp);
    }
    
    static public function object2Array($rs){
        
        if (is_object($rs)){
            $rs = get_object_vars($rs);
        }
        if(is_array($rs)){
            return array_map(__METHOD__, $rs);
            //var_dump($rs);die();
        }else{
            return $rs;
        }
    }

}