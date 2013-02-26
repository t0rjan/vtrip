<?php
/**
 * SSOConfig class file.
 *
 * @package SSOClinent
 * @author lijunjie <junjie2@staff.sina.com.cn>
 * @author liuzhiyu <zhiyu@staff.sina.com.cn>
 * @copyright Copyright (c) 2011 SINA R&D Centre
 * @version $Id: $
 */

/**
 * ！！！注意增加常量时，如果程序中需要使用，为了兼容性，请先用defined检查，切记！！！
 */
//include_once( 'SSOCookie.php' ); 

class SSOConfig {
    /**
     * 服务名称，产品名称，应该和entry保持一致
     */
    const SERVICE            = 'blog';
    /**
     * 应用产品entry, 获取用户详细信息使用，由统一注册颁发的
     */
    const ENTRY                = 'blog';
    /**
     * 应用产品pin, 获取用户详细信息使用，由统一注册颁发的
     */
    const PIN                = 'acad46794c0b2cbddf3ef977df7e9e';
    /**
     * domain of cookie, 您域名所在的根域，如“.sina.com.cn”，“.51uc.com”
     */
    const COOKIE_DOMAIN        = WEIBO_COOKIE_DOMAIN;
    /**
     * 如果只需要根据sina.com.cn域的cookie就可以信任用户身份的话，可以设置为false，这样不需要验证service ticket，省一次http的调用
     */
    const USE_SERVICE_TICKET= false;
    /**
     * 使用RSA加密cookie验证
     * 如果需要使用请设置为true
     */
    const USE_RSA_SIGN        = true;
}

?>
