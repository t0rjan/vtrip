<?php
include('./__global.php');
class adm_logout extends admin_ctrl 
{
    function run()
    {
        $this->del_session('uid');
        $this->_redirect('login.php');
    }
}

new adm_logout();
?>