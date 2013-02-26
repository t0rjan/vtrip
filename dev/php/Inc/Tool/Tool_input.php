<?php
class Tool_input
{
    static  function input($key , $method = '' , $default = null)
    {
        $method = strtoupper($method);
        if('G' == $method || 'GET' == $method)
        {
            $value = isset($_GET[$key]) ? $_GET[$key] : $default;
        }
        if('P' == $method || 'POST' == $method)
        {
            $value = isset($_POST[$key]) ? $_POST[$key] : $default;
        }
        else
        {
            $value = isset($_GET[$key])
            ? $_GET[$key]
            : (isset($_POST[$key])
                    ? $_POST[$key]
                    : $default);
        }
    
        return trim($value);
    }
    static function param_base($string, $option){
        if(!$option)
            return true;
        extract($option);
        if(empty($string)){
            if($is_must)
                return false;
            else
                return true;
        }
        
        switch ($type){
            case ML_DATATYPE_DIGIT:
                if(!self::length_limit($string, $len_limit))
                    return false;
                else
                    return ctype_digit($string);
                break;
            case ML_DATATYPE_ALPHA:
                if(!self::length_limit($string, $len_limit))
                    return false;
                else
                    return ctype_alpha($string);
                break;
            case ML_DATATYPE_ALNUM:
                if(!self::length_limit($string, $len_limit))
                    return false;
                else
                    return ctype_alnum($string);
                break;
            case ML_DATATYPE_FLOAT:
                return filter_var($string, FILTER_VALIDATE_FLOAT);
            case ML_DATATYPE_EMAIL:
                return filter_var($string, FILTER_VALIDATE_EMAIL);
                break;
            case ML_DATATYPE_URL:
                return filter_var($string, FILTER_VALIDATE_URL);
                break;
            case ML_DATATYPE_INARRAY:
                if(!is_array($in))
                    return false;
                return in_array($string, $in);
                break;
            default:
                $result = self::preg_filter($type, $string,  $reg);
                if(!$result)
                    return false;
                return true;
        }    
    }
    
    static function preg_filter($type, $string, $reg){
        $reg_array = array(
            ML_DATATYPE_URL_WEIBO => '/^http[s]?:\/\/([\w-]+\.)+[\w-]+(\/[\w-.\/\?%&=]*)?$/u',
            ML_DATATYPE_USER_PASSWORD => '/[0-9a-zA-Z]{6,15}/',
            ML_DATATYPE_USER_NICK     => '/^[\x{4e00}-\x{9fa5}\x{3040}-\x{317f}\x{AC00}-\x{D7A3a}a-zA-Z0-9_]+$/u',
        );
        if(in_array($type,$reg_array))
            return preg_match($reg_array[$type], $string);
        return preg_match($reg, $string);
    }
    static function length_limit($string, $len_limit){
        if(!is_array($len_limit))
            return true;
        $str_len = strlen($string);
        if(isset($len_limit['eq']) && ($str_len !== $len_limit['eq']))
            return false;
        if(isset($len_limit['gt']) && ($str_len < $len_limit['gt']))
            return false;
        if(isset($len_limit['lt']) && ($str_len > $len_limit['lt']))
            return false;
        return true;
    }
}
?>