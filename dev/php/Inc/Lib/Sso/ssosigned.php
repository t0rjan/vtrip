<?php
/**
 * 签名管理类
 *
 * 此文件同时存在于svn如下两个位置，变更时请同时变更
 * https://svn.intra.sina.com.cn/sso/SessionServer/trunk/library/
 * https://svn.intra.sina.com.cn/sso/BSSO/trunk/module/sso
 */

class SSOSigned {
    const E_ENTRY_IS_EMPTY = 1;
    const E_KEY_IS_EMPTY = 2;
    const E_SIGNED_DIGIST = 3;
    const E_SIGNED_EXPIRED = 4;

    public static function makeSigned($data, $key, &$result = null) {
        return self::_makeSigned($data, $key, $result);
    }

    public static function checkSigned($signed, $arrData, $key, $time_to_live) {
        unset($arrData["signed"]);
        $s = self::_makeSigned($arrData, $key, $result, false);
        if ($signed != $s) {
            throw new Exception("signed ($signed) check fail", self::E_SIGNED_DIGIST);
        }
        if ($arrData["ctime"] + $time_to_live < time()) {
            throw new Exception("signed ($signed) check fail", self::E_SIGNED_EXPIRED);
        }
        return true;
    }

    private static function _makeSigned($data, $key, &$result = null, $fill_ctime = true) {
        $arr = $data;
        if (!is_array($data)) {
            parse_str($data, $arr);
        }
        if ($arr["entry"]) {
            $entry = $arr["entry"];
        }else {
            $arr["entry"] = $entry;
        }
        if (!$entry) {
            throw new Exception("entry is empty", self::E_ENTRY_IS_EMPTY);
        }
        if (strlen($key) === 0) {
            throw new Exception("key is empty", self::E_KEY_IS_EMPTY);
        }
        if ($fill_ctime && !$arr["ctime"]) {
            $arr["ctime"] = time();
        }
        ksort($arr, SORT_STRING);
        $str = self::raw_http_build_query($arr);
        $signed = md5($str. "&key=$key");
        $result = $str. "&signed=$signed";
        return $signed;
    }
    public static function raw_http_build_query($arrQuery) {
        $arrtmp = array();
        foreach ($arrQuery as $key=>$val) {
            $arrtmp[] = self::rawurlencode($key)."=".self::rawurlencode($val);
        }
        return implode("&", $arrtmp);
    }

    public static function rawurlencode($str) {
        return str_replace('~','%7E',rawurlencode($str));
    }
}
