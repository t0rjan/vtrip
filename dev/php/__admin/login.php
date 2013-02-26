<?php
/************************************
*   熊店
*   @file:/server/bearshop/__admin/login.php
*   @intro:
*   @author:new_shop
*   @email:new_shop@163.com    
*   @date:Tue Feb 09 22:43:29 CST 2010
************************************/

include('./__global.php');


class adm_login extends admin_ctrl 
{
    public function adm_login()
    {

        $this->need_login = false;
        parent::__construct();
    }
    
    public function run()
    {
        global $adm;


        if($this->input('go') == 1)
        {
            $user = $this->input('user');
            $passwd = $this->input('passwd');
            $vali = intval($this->input('vali'));
            
            if(!preg_match('/^[a-z]{5,15}$/' , $user) || !preg_match('/^[a-zA-Z0-9]{6,15}$/' , $passwd))
            {
                $adm['alert'] = 'xx';
                return ;
            }
            
            //验证码

            if($this->get_session('vcode') != $vali)
            {
                $adm['alert'] = '验证码错误';
                return;
            }
            
            
            $access_config = ml_factory::load_standard_conf('adminAccess');
            

            if(!isset($access_config[$user]))
            {
                $adm['alert'] = '用户名错误';
                return ;
            }
            if(md5($passwd) != $access_config[$user]['pw'])
            {
                $adm['alert'] = '用户名密码错误';
                return ;
            }
            else 
            {
                $this->set_session('uid' , 1);
                $this->set_session('level' , $access_config[$user]['level']);
                $this->_redirect('index.php');
            }
                
            
            return ;
        }
        
        
    }
    
    
}
new adm_login();

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>登录管理后台</title>
<link href="./css.css" rel="stylesheet" type="text/css">
</head>
<body>


<br>
<br>
<br>
<br>
<table width="360" border="0" align="center" cellpadding="0" cellspacing="0" class="loginbox">
  <tr>
    <th colspan="2" align="left" class="fonttitle" scope="col"><span class="f18">登录后台管理系统</span><br>
      <span class="f10">Log in background management system</span></th>
  </tr>
  <tr>
    <td class="td_1">
    <div class="loginbox2">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <form name="login" method="post" action="?go=1">
        <tr>
          <td width="83" align="right">用户名：</td>
          <td width="253"><input name="user" type="text" class="input01" size="25"></td>
        </tr>
        <tr>
          <td width="83" align="right">密码：</td>
          <td><input name="passwd" type="password" class="input01" size="25"></td>
        </tr>
        <tr>
          <td width="83" align="right">验证码：</td>
          <td><input name="vali" type="text" class="input02" size="25"> 
            <img src="./validate.php?<?php echo time();?>" align="absmiddle"/> <font color="Red"><?php echo $adm['alert']; ?></font></td>
        </tr>
        <tr align="center">
          <td height="30" colspan="2"><input name="Submit" type="submit" class="button01" value="登录">
          </td>
        </tr>
      </form>
    </table>
    </div></td>
  </tr>
</table>
</body>
</html>