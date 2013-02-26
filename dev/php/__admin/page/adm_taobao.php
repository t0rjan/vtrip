<?php

include('../__global.php');

class adm_taobao extends admin_ctrl
{
    function run(){die('xxx');}
    function page_findbykeys()
    {
        $tags = $this->input('tag');
        $area = $this->input('area');
        $minprice = $this->input('minprice' , '' , 1);
        $maxprice = $this->input('maxprice' , '' , 999);
        $sort = $this->input('sort');
        $mall_item = $this->input('mall_item') ? true : false;
        //$data['pager_url'] = http_build_query($_REQUEST);
        $page = $this->input('p');
        
        if($tags)
        {
            $search['keyword']=$tags;
            $search['start_price']=$minprice;
            $search['end_price']=$maxprice;
            $search['sort']=$sort;
            $search['area']=$area;
            $search['mall_item']=$mall_item;
            $search['pagenum']=$page;
            $search['pagesize']=50;


            $this->top =new ml_model_openapi_topsdk();
            $goods=$this->top->search($search);

            $total=$goods['total_results'];
            $goods=$goods['taobaoke_items']['taobaoke_item'];
        }
        $data['tag'] = $tags;
        $data['sort'] = $sort;
        $data['minprice'] = $minprice;
        $data['maxprice'] = $maxprice;
        $data['area'] = $area;
        $data['goods'] = $goods;
        $data['page'] = $page;
        $data['total'] = $total;
        $this->output($data);
    }
    
    /**
     * 申请处理接口
     *
     */
    protected function api_add_autofetch()
    {
        $oAdmin = new ml_model_admin_dbCommon();
        foreach ($_POST['v'] as  $value) {
            
            if(!empty($value['ischeck']))
            {
                if($oAdmin->autofetch_add($value['url'],$value['iid'],$value['tag'],$value['class']))
                {
                    $id = $oAdmin->insert_id();
                    ml_tool_queue_admin::add_fakedata($id);
                }

            }
        }

        

        $url = parse_url($_SERVER['HTTP_REFERER']);

        parse_str($url['query'],$a);

        $a['p'] >1 ? $a['p']++ : $a['p']=2;
        $query = http_build_query($a);
        $this->_redirect($url['path'].'?'.$query);
    }
}

new adm_taobao();
?>