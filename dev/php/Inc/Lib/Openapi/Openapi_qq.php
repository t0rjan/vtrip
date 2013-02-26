<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Converts to and from JSON format.
 *
 * JSON (JavaScript Object Notation) is a lightweight data-interchange
 * format. It is easy for humans to read and write. It is easy for machines
 * to parse and generate. It is based on a subset of the JavaScript
 * Programming Language, Standard ECMA-262 3rd Edition - December 1999.
 * This feature can also be found in  Python. JSON is a text format that is
 * completely language independent but uses conventions that are familiar
 * to programmers of the C-family of languages, including C, C++, C#, Java,
 * JavaScript, Perl, TCL, and many others. These properties make JSON an
 * ideal data-interchange language.
 *
 * This package provides a simple encoder and decoder for JSON notation. It
 * is intended for use with client-side Javascript applications that make
 * use of HTTPRequest to perform server communication functions - data can
 * be encoded into JSON notation for use in a client-side javascript, or
 * decoded from incoming Javascript requests. JSON format is native to
 * Javascript, and can be directly eval()'ed with no further parsing
 * overhead
 *
 * All strings should be in ASCII or UTF-8 format!
 *
 * LICENSE: Redistribution and use in source and binary forms, with or
 * without modification, are permitted provided that the following
 * conditions are met: Redistributions of source code must retain the
 * above copyright notice, this list of conditions and the following
 * disclaimer. Redistributions in binary form must reproduce the above
 * copyright notice, this list of conditions and the following disclaimer
 * in the documentation and/or other materials provided with the
 * distribution.
 *
 * THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESS OR IMPLIED
 * WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN
 * NO EVENT SHALL CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS
 * OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR
 * TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE
 * USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH
 * DAMAGE.
 *
 * @category
 * @package     Services_JSON
 * @author      Michal Migurski <mike-json@teczno.com>
 * @author      Matt Knapp <mdknapp[at]gmail[dot]com>
 * @author      Brett Stimmerman <brettstimmerman[at]gmail[dot]com>
 * @copyright   2005 Michal Migurski
 * @version     CVS: $Id: JSON.php 305040 2010-11-02 23:19:03Z alan_k $
 * @license     http://www.opensource.org/licenses/bsd-license.php
 * @link        http://pear.php.net/pepr/pepr-proposal-show.php?id=198
 */

/**
 * Marker constant for Services_JSON::decode(), used to flag stack state
 */
define('SERVICES_JSON_SLICE',   1);

/**
 * Marker constant for Services_JSON::decode(), used to flag stack state
 */
define('SERVICES_JSON_IN_STR',  2);

/**
 * Marker constant for Services_JSON::decode(), used to flag stack state
 */
define('SERVICES_JSON_IN_ARR',  3);

/**
 * Marker constant for Services_JSON::decode(), used to flag stack state
 */
define('SERVICES_JSON_IN_OBJ',  4);

/**
 * Marker constant for Services_JSON::decode(), used to flag stack state
 */
define('SERVICES_JSON_IN_CMT', 5);

/**
 * Behavior switch for Services_JSON::decode()
 */
define('SERVICES_JSON_LOOSE_TYPE', 16);

/**
 * Behavior switch for Services_JSON::decode()
 */
define('SERVICES_JSON_SUPPRESS_ERRORS', 32);

/**
 * Behavior switch for Services_JSON::decode()
 */
define('SERVICES_JSON_USE_TO_JSON', 64);

/**
 * Converts to and from JSON format.
 *
 * Brief example of use:
 *
 * <code>
 * // create a new instance of Services_JSON
 * $json = new Services_JSON();
 *
 * // convert a complexe value to JSON notation, and send it to the browser
 * $value = array('foo', 'bar', array(1, 2, 'baz'), array(3, array(4)));
 * $output = $json->encode($value);
 *
 * print($output);
 * // prints: ["foo","bar",[1,2,"baz"],[3,[4]]]
 *
 * // accept incoming POST data, assumed to be in JSON notation
 * $input = file_get_contents('php://input', 1000000);
 * $value = $json->decode($input);
 * </code>
 */
class Services_JSON
{
   /**
    * constructs a new JSON instance
    *
    * @param    int     $use    object behavior flags; combine with boolean-OR
    *
    *                           possible values:
    *                           - SERVICES_JSON_LOOSE_TYPE:  loose typing.
    *                                   "{...}" syntax creates associative arrays
    *                                   instead of objects in decode().
    *                           - SERVICES_JSON_SUPPRESS_ERRORS:  error suppression.
    *                                   Values which can't be encoded (e.g. resources)
    *                                   appear as NULL instead of throwing errors.
    *                                   By default, a deeply-nested resource will
    *                                   bubble up with an error, so all return values
    *                                   from encode() should be checked with isError()
    *                           - SERVICES_JSON_USE_TO_JSON:  call toJSON when serializing objects
    *                                   It serializes the return value from the toJSON call rather 
    *                                   than the object it'self,  toJSON can return associative arrays, 
    *                                   strings or numbers, if you return an object, make sure it does
    *                                   not have a toJSON method, otherwise an error will occur.
    */
    function Services_JSON($use = 0)
    {
        $this->use = $use;
        $this->_mb_strlen            = function_exists('mb_strlen');
        $this->_mb_convert_encoding  = function_exists('mb_convert_encoding');
        $this->_mb_substr            = function_exists('mb_substr');
    }
    // private - cache the mbstring lookup results..
    var $_mb_strlen = false;
    var $_mb_substr = false;
    var $_mb_convert_encoding = false;
    
   /**
    * convert a string from one UTF-16 char to one UTF-8 char
    *
    * Normally should be handled by mb_convert_encoding, but
    * provides a slower PHP-only method for installations
    * that lack the multibye string extension.
    *
    * @param    string  $utf16  UTF-16 character
    * @return   string  UTF-8 character
    * @access   private
    */
    function utf162utf8($utf16)
    {
        // oh please oh please oh please oh please oh please
        if($this->_mb_convert_encoding) {
            return mb_convert_encoding($utf16, 'UTF-8', 'UTF-16');
        }

        $bytes = (ord($utf16{0}) << 8) | ord($utf16{1});

        switch(true) {
            case ((0x7F & $bytes) == $bytes):
                // this case should never be reached, because we are in ASCII range
                // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                return chr(0x7F & $bytes);

            case (0x07FF & $bytes) == $bytes:
                // return a 2-byte UTF-8 character
                // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                return chr(0xC0 | (($bytes >> 6) & 0x1F))
                     . chr(0x80 | ($bytes & 0x3F));

            case (0xFFFF & $bytes) == $bytes:
                // return a 3-byte UTF-8 character
                // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                return chr(0xE0 | (($bytes >> 12) & 0x0F))
                     . chr(0x80 | (($bytes >> 6) & 0x3F))
                     . chr(0x80 | ($bytes & 0x3F));
        }

        // ignoring UTF-32 for now, sorry
        return '';
    }

   /**
    * convert a string from one UTF-8 char to one UTF-16 char
    *
    * Normally should be handled by mb_convert_encoding, but
    * provides a slower PHP-only method for installations
    * that lack the multibye string extension.
    *
    * @param    string  $utf8   UTF-8 character
    * @return   string  UTF-16 character
    * @access   private
    */
    function utf82utf16($utf8)
    {
        // oh please oh please oh please oh please oh please
        if($this->_mb_convert_encoding) {
            return mb_convert_encoding($utf8, 'UTF-16', 'UTF-8');
        }

        switch($this->strlen8($utf8)) {
            case 1:
                // this case should never be reached, because we are in ASCII range
                // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                return $utf8;

            case 2:
                // return a UTF-16 character from a 2-byte UTF-8 char
                // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                return chr(0x07 & (ord($utf8{0}) >> 2))
                     . chr((0xC0 & (ord($utf8{0}) << 6))
                         | (0x3F & ord($utf8{1})));

            case 3:
                // return a UTF-16 character from a 3-byte UTF-8 char
                // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                return chr((0xF0 & (ord($utf8{0}) << 4))
                         | (0x0F & (ord($utf8{1}) >> 2)))
                     . chr((0xC0 & (ord($utf8{1}) << 6))
                         | (0x7F & ord($utf8{2})));
        }

        // ignoring UTF-32 for now, sorry
        return '';
    }

   /**
    * encodes an arbitrary variable into JSON format (and sends JSON Header)
    *
    * @param    mixed   $var    any number, boolean, string, array, or object to be encoded.
    *                           see argument 1 to Services_JSON() above for array-parsing behavior.
    *                           if var is a strng, note that encode() always expects it
    *                           to be in ASCII or UTF-8 format!
    *
    * @return   mixed   JSON string representation of input var or an error if a problem occurs
    * @access   public
    */
    function encode($var)
    {
        return $this->encodeUnsafe($var);
    }
    /**
    * encodes an arbitrary variable into JSON format without JSON Header - warning - may allow XSS!!!!)
    *
    * @param    mixed   $var    any number, boolean, string, array, or object to be encoded.
    *                           see argument 1 to Services_JSON() above for array-parsing behavior.
    *                           if var is a strng, note that encode() always expects it
    *                           to be in ASCII or UTF-8 format!
    *
    * @return   mixed   JSON string representation of input var or an error if a problem occurs
    * @access   public
    */
    function encodeUnsafe($var)
    {
        // see bug #16908 - regarding numeric locale printing
        $lc = setlocale(LC_NUMERIC, 0);
        setlocale(LC_NUMERIC, 'C');
        $ret = $this->_encode($var);
        setlocale(LC_NUMERIC, $lc);
        return $ret;
        
    }
    /**
    * PRIVATE CODE that does the work of encodes an arbitrary variable into JSON format 
    *
    * @param    mixed   $var    any number, boolean, string, array, or object to be encoded.
    *                           see argument 1 to Services_JSON() above for array-parsing behavior.
    *                           if var is a strng, note that encode() always expects it
    *                           to be in ASCII or UTF-8 format!
    *
    * @return   mixed   JSON string representation of input var or an error if a problem occurs
    * @access   public
    */
    function _encode($var) 
    {
         
        switch (gettype($var)) {
            case 'boolean':
                return $var ? 'true' : 'false';

            case 'NULL':
                return 'null';

            case 'integer':
                return (int) $var;

            case 'double':
            case 'float':
                return  (float) $var;

            case 'string':
                // STRINGS ARE EXPECTED TO BE IN ASCII OR UTF-8 FORMAT
                $ascii = '';
                $strlen_var = $this->strlen8($var);

               /*
                * Iterate over every character in the string,
                * escaping with a slash or encoding to UTF-8 where necessary
                */
                for ($c = 0; $c < $strlen_var; ++$c) {

                    $ord_var_c = ord($var{$c});

                    switch (true) {
                        case $ord_var_c == 0x08:
                            $ascii .= '\b';
                            break;
                        case $ord_var_c == 0x09:
                            $ascii .= '\t';
                            break;
                        case $ord_var_c == 0x0A:
                            $ascii .= '\n';
                            break;
                        case $ord_var_c == 0x0C:
                            $ascii .= '\f';
                            break;
                        case $ord_var_c == 0x0D:
                            $ascii .= '\r';
                            break;

                        case $ord_var_c == 0x22:
                        case $ord_var_c == 0x2F:
                        case $ord_var_c == 0x5C:
                            // double quote, slash, slosh
                            $ascii .= '\\'.$var{$c};
                            break;

                        case (($ord_var_c >= 0x20) && ($ord_var_c <= 0x7F)):
                            // characters U-00000000 - U-0000007F (same as ASCII)
                            $ascii .= $var{$c};
                            break;

                        case (($ord_var_c & 0xE0) == 0xC0):
                            // characters U-00000080 - U-000007FF, mask 110XXXXX
                            // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                            if ($c+1 >= $strlen_var) {
                                $c += 1;
                                $ascii .= '?';
                                break;
                            }
                            
                            $char = pack('C*', $ord_var_c, ord($var{$c + 1}));
                            $c += 1;
                            $utf16 = $this->utf82utf16($char);
                            $ascii .= sprintf('\u%04s', bin2hex($utf16));
                            break;

                        case (($ord_var_c & 0xF0) == 0xE0):
                            if ($c+2 >= $strlen_var) {
                                $c += 2;
                                $ascii .= '?';
                                break;
                            }
                            // characters U-00000800 - U-0000FFFF, mask 1110XXXX
                            // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                            $char = pack('C*', $ord_var_c,
                                         @ord($var{$c + 1}),
                                         @ord($var{$c + 2}));
                            $c += 2;
                            $utf16 = $this->utf82utf16($char);
                            $ascii .= sprintf('\u%04s', bin2hex($utf16));
                            break;

                        case (($ord_var_c & 0xF8) == 0xF0):
                            if ($c+3 >= $strlen_var) {
                                $c += 3;
                                $ascii .= '?';
                                break;
                            }
                            // characters U-00010000 - U-001FFFFF, mask 11110XXX
                            // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                            $char = pack('C*', $ord_var_c,
                                         ord($var{$c + 1}),
                                         ord($var{$c + 2}),
                                         ord($var{$c + 3}));
                            $c += 3;
                            $utf16 = $this->utf82utf16($char);
                            $ascii .= sprintf('\u%04s', bin2hex($utf16));
                            break;

                        case (($ord_var_c & 0xFC) == 0xF8):
                            // characters U-00200000 - U-03FFFFFF, mask 111110XX
                            // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                            if ($c+4 >= $strlen_var) {
                                $c += 4;
                                $ascii .= '?';
                                break;
                            }
                            $char = pack('C*', $ord_var_c,
                                         ord($var{$c + 1}),
                                         ord($var{$c + 2}),
                                         ord($var{$c + 3}),
                                         ord($var{$c + 4}));
                            $c += 4;
                            $utf16 = $this->utf82utf16($char);
                            $ascii .= sprintf('\u%04s', bin2hex($utf16));
                            break;

                        case (($ord_var_c & 0xFE) == 0xFC):
                        if ($c+5 >= $strlen_var) {
                                $c += 5;
                                $ascii .= '?';
                                break;
                            }
                            // characters U-04000000 - U-7FFFFFFF, mask 1111110X
                            // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                            $char = pack('C*', $ord_var_c,
                                         ord($var{$c + 1}),
                                         ord($var{$c + 2}),
                                         ord($var{$c + 3}),
                                         ord($var{$c + 4}),
                                         ord($var{$c + 5}));
                            $c += 5;
                            $utf16 = $this->utf82utf16($char);
                            $ascii .= sprintf('\u%04s', bin2hex($utf16));
                            break;
                    }
                }
                return  '"'.$ascii.'"';

            case 'array':
               /*
                * As per JSON spec if any array key is not an integer
                * we must treat the the whole array as an object. We
                * also try to catch a sparsely populated associative
                * array with numeric keys here because some JS engines
                * will create an array with empty indexes up to
                * max_index which can cause memory issues and because
                * the keys, which may be relevant, will be remapped
                * otherwise.
                *
                * As per the ECMA and JSON specification an object may
                * have any string as a property. Unfortunately due to
                * a hole in the ECMA specification if the key is a
                * ECMA reserved word or starts with a digit the
                * parameter is only accessible using ECMAScript's
                * bracket notation.
                */

                // treat as a JSON object
                if (is_array($var) && count($var) && (array_keys($var) !== range(0, sizeof($var) - 1))) {
                    $properties = array_map(array($this, 'name_value'),
                                            array_keys($var),
                                            array_values($var));

                    foreach($properties as $property) {
                        if(Services_JSON::isError($property)) {
                            return $property;
                        }
                    }

                    return '{' . join(',', $properties) . '}';
                }

                // treat it like a regular array
                $elements = array_map(array($this, '_encode'), $var);

                foreach($elements as $element) {
                    if(Services_JSON::isError($element)) {
                        return $element;
                    }
                }

                return '[' . join(',', $elements) . ']';

            case 'object':
            
                // support toJSON methods.
                if (($this->use & SERVICES_JSON_USE_TO_JSON) && method_exists($var, 'toJSON')) {
                    // this may end up allowing unlimited recursion
                    // so we check the return value to make sure it's not got the same method.
                    $recode = $var->toJSON();
                    
                    if (method_exists($recode, 'toJSON')) {
                        
                        return ($this->use & SERVICES_JSON_SUPPRESS_ERRORS)
                        ? 'null'
                        : new Services_JSON_Error(class_name($var).
                            " toJSON returned an object with a toJSON method.");
                            
                    }
                    
                    return $this->_encode( $recode );
                } 
                
                $vars = get_object_vars($var);
                
                $properties = array_map(array($this, 'name_value'),
                                        array_keys($vars),
                                        array_values($vars));

                foreach($properties as $property) {
                    if(Services_JSON::isError($property)) {
                        return $property;
                    }
                }

                return '{' . join(',', $properties) . '}';

            default:
                return ($this->use & SERVICES_JSON_SUPPRESS_ERRORS)
                    ? 'null'
                    : new Services_JSON_Error(gettype($var)." can not be encoded as JSON string");
        }
    }

   /**
    * array-walking function for use in generating JSON-formatted name-value pairs
    *
    * @param    string  $name   name of key to use
    * @param    mixed   $value  reference to an array element to be encoded
    *
    * @return   string  JSON-formatted name-value pair, like '"name":value'
    * @access   private
    */
    function name_value($name, $value)
    {
        $encoded_value = $this->_encode($value);

        if(Services_JSON::isError($encoded_value)) {
            return $encoded_value;
        }

        return $this->_encode(strval($name)) . ':' . $encoded_value;
    }

   /**
    * reduce a string by removing leading and trailing comments and whitespace
    *
    * @param    $str    string      string value to strip of comments and whitespace
    *
    * @return   string  string value stripped of comments and whitespace
    * @access   private
    */
    function reduce_string($str)
    {
        $str = preg_replace(array(

                // eliminate single line comments in '// ...' form
                '#^\s*//(.+)$#m',

                // eliminate multi-line comments in '/* ... */' form, at start of string
                '#^\s*/\*(.+)\*/#Us',

                // eliminate multi-line comments in '/* ... */' form, at end of string
                '#/\*(.+)\*/\s*$#Us'

            ), '', $str);

        // eliminate extraneous space
        return trim($str);
    }

   /**
    * decodes a JSON string into appropriate variable
    *
    * @param    string  $str    JSON-formatted string
    *
    * @return   mixed   number, boolean, string, array, or object
    *                   corresponding to given JSON input string.
    *                   See argument 1 to Services_JSON() above for object-output behavior.
    *                   Note that decode() always returns strings
    *                   in ASCII or UTF-8 format!
    * @access   public
    */
    function decode($str)
    {
        $str = $this->reduce_string($str);

        switch (strtolower($str)) {
            case 'true':
                return true;

            case 'false':
                return false;

            case 'null':
                return null;

            default:
                $m = array();

                if (is_numeric($str)) {
                    // Lookie-loo, it's a number

                    // This would work on its own, but I'm trying to be
                    // good about returning integers where appropriate:
                    // return (float)$str;

                    // Return float or int, as appropriate
                    return ((float)$str == (integer)$str)
                        ? (integer)$str
                        : (float)$str;

                } elseif (preg_match('/^("|\').*(\1)$/s', $str, $m) && $m[1] == $m[2]) {
                    // STRINGS RETURNED IN UTF-8 FORMAT
                    $delim = $this->substr8($str, 0, 1);
                    $chrs = $this->substr8($str, 1, -1);
                    $utf8 = '';
                    $strlen_chrs = $this->strlen8($chrs);

                    for ($c = 0; $c < $strlen_chrs; ++$c) {

                        $substr_chrs_c_2 = $this->substr8($chrs, $c, 2);
                        $ord_chrs_c = ord($chrs{$c});

                        switch (true) {
                            case $substr_chrs_c_2 == '\b':
                                $utf8 .= chr(0x08);
                                ++$c;
                                break;
                            case $substr_chrs_c_2 == '\t':
                                $utf8 .= chr(0x09);
                                ++$c;
                                break;
                            case $substr_chrs_c_2 == '\n':
                                $utf8 .= chr(0x0A);
                                ++$c;
                                break;
                            case $substr_chrs_c_2 == '\f':
                                $utf8 .= chr(0x0C);
                                ++$c;
                                break;
                            case $substr_chrs_c_2 == '\r':
                                $utf8 .= chr(0x0D);
                                ++$c;
                                break;

                            case $substr_chrs_c_2 == '\\"':
                            case $substr_chrs_c_2 == '\\\'':
                            case $substr_chrs_c_2 == '\\\\':
                            case $substr_chrs_c_2 == '\\/':
                                if (($delim == '"' && $substr_chrs_c_2 != '\\\'') ||
                                   ($delim == "'" && $substr_chrs_c_2 != '\\"')) {
                                    $utf8 .= $chrs{++$c};
                                }
                                break;

                            case preg_match('/\\\u[0-9A-F]{4}/i', $this->substr8($chrs, $c, 6)):
                                // single, escaped unicode character
                                $utf16 = chr(hexdec($this->substr8($chrs, ($c + 2), 2)))
                                       . chr(hexdec($this->substr8($chrs, ($c + 4), 2)));
                                $utf8 .= $this->utf162utf8($utf16);
                                $c += 5;
                                break;

                            case ($ord_chrs_c >= 0x20) && ($ord_chrs_c <= 0x7F):
                                $utf8 .= $chrs{$c};
                                break;

                            case ($ord_chrs_c & 0xE0) == 0xC0:
                                // characters U-00000080 - U-000007FF, mask 110XXXXX
                                //see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                                $utf8 .= $this->substr8($chrs, $c, 2);
                                ++$c;
                                break;

                            case ($ord_chrs_c & 0xF0) == 0xE0:
                                // characters U-00000800 - U-0000FFFF, mask 1110XXXX
                                // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                                $utf8 .= $this->substr8($chrs, $c, 3);
                                $c += 2;
                                break;

                            case ($ord_chrs_c & 0xF8) == 0xF0:
                                // characters U-00010000 - U-001FFFFF, mask 11110XXX
                                // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                                $utf8 .= $this->substr8($chrs, $c, 4);
                                $c += 3;
                                break;

                            case ($ord_chrs_c & 0xFC) == 0xF8:
                                // characters U-00200000 - U-03FFFFFF, mask 111110XX
                                // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                                $utf8 .= $this->substr8($chrs, $c, 5);
                                $c += 4;
                                break;

                            case ($ord_chrs_c & 0xFE) == 0xFC:
                                // characters U-04000000 - U-7FFFFFFF, mask 1111110X
                                // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                                $utf8 .= $this->substr8($chrs, $c, 6);
                                $c += 5;
                                break;

                        }

                    }

                    return $utf8;

                } elseif (preg_match('/^\[.*\]$/s', $str) || preg_match('/^\{.*\}$/s', $str)) {
                    // array, or object notation

                    if ($str{0} == '[') {
                        $stk = array(SERVICES_JSON_IN_ARR);
                        $arr = array();
                    } else {
                        if ($this->use & SERVICES_JSON_LOOSE_TYPE) {
                            $stk = array(SERVICES_JSON_IN_OBJ);
                            $obj = array();
                        } else {
                            $stk = array(SERVICES_JSON_IN_OBJ);
                            $obj = new stdClass();
                        }
                    }

                    array_push($stk, array('what'  => SERVICES_JSON_SLICE,
                                           'where' => 0,
                                           'delim' => false));

                    $chrs = $this->substr8($str, 1, -1);
                    $chrs = $this->reduce_string($chrs);

                    if ($chrs == '') {
                        if (reset($stk) == SERVICES_JSON_IN_ARR) {
                            return $arr;

                        } else {
                            return $obj;

                        }
                    }

                    //print("\nparsing {$chrs}\n");

                    $strlen_chrs = $this->strlen8($chrs);

                    for ($c = 0; $c <= $strlen_chrs; ++$c) {

                        $top = end($stk);
                        $substr_chrs_c_2 = $this->substr8($chrs, $c, 2);

                        if (($c == $strlen_chrs) || (($chrs{$c} == ',') && ($top['what'] == SERVICES_JSON_SLICE))) {
                            // found a comma that is not inside a string, array, etc.,
                            // OR we've reached the end of the character list
                            $slice = $this->substr8($chrs, $top['where'], ($c - $top['where']));
                            array_push($stk, array('what' => SERVICES_JSON_SLICE, 'where' => ($c + 1), 'delim' => false));
                            //print("Found split at {$c}: ".$this->substr8($chrs, $top['where'], (1 + $c - $top['where']))."\n");

                            if (reset($stk) == SERVICES_JSON_IN_ARR) {
                                // we are in an array, so just push an element onto the stack
                                array_push($arr, $this->decode($slice));

                            } elseif (reset($stk) == SERVICES_JSON_IN_OBJ) {
                                // we are in an object, so figure
                                // out the property name and set an
                                // element in an associative array,
                                // for now
                                $parts = array();
                                
                               if (preg_match('/^\s*(["\'].*[^\\\]["\'])\s*:/Uis', $slice, $parts)) {
                                   // "name":value pair
                                    $key = $this->decode($parts[1]);
                                    $val = $this->decode(trim(substr($slice, strlen($parts[0])), ", \t\n\r\0\x0B"));
                                    if ($this->use & SERVICES_JSON_LOOSE_TYPE) {
                                        $obj[$key] = $val;
                                    } else {
                                        $obj->$key = $val;
                                    }
                                } elseif (preg_match('/^\s*(\w+)\s*:/Uis', $slice, $parts)) {
                                    // name:value pair, where name is unquoted
                                    $key = $parts[1];
                                    $val = $this->decode(trim(substr($slice, strlen($parts[0])), ", \t\n\r\0\x0B"));

                                    if ($this->use & SERVICES_JSON_LOOSE_TYPE) {
                                        $obj[$key] = $val;
                                    } else {
                                        $obj->$key = $val;
                                    }
                                }

                            }

                        } elseif ((($chrs{$c} == '"') || ($chrs{$c} == "'")) && ($top['what'] != SERVICES_JSON_IN_STR)) {
                            // found a quote, and we are not inside a string
                            array_push($stk, array('what' => SERVICES_JSON_IN_STR, 'where' => $c, 'delim' => $chrs{$c}));
                            //print("Found start of string at {$c}\n");

                        } elseif (($chrs{$c} == $top['delim']) &&
                                 ($top['what'] == SERVICES_JSON_IN_STR) &&
                                 (($this->strlen8($this->substr8($chrs, 0, $c)) - $this->strlen8(rtrim($this->substr8($chrs, 0, $c), '\\'))) % 2 != 1)) {
                            // found a quote, we're in a string, and it's not escaped
                            // we know that it's not escaped becase there is _not_ an
                            // odd number of backslashes at the end of the string so far
                            array_pop($stk);
                            //print("Found end of string at {$c}: ".$this->substr8($chrs, $top['where'], (1 + 1 + $c - $top['where']))."\n");

                        } elseif (($chrs{$c} == '[') &&
                                 in_array($top['what'], array(SERVICES_JSON_SLICE, SERVICES_JSON_IN_ARR, SERVICES_JSON_IN_OBJ))) {
                            // found a left-bracket, and we are in an array, object, or slice
                            array_push($stk, array('what' => SERVICES_JSON_IN_ARR, 'where' => $c, 'delim' => false));
                            //print("Found start of array at {$c}\n");

                        } elseif (($chrs{$c} == ']') && ($top['what'] == SERVICES_JSON_IN_ARR)) {
                            // found a right-bracket, and we're in an array
                            array_pop($stk);
                            //print("Found end of array at {$c}: ".$this->substr8($chrs, $top['where'], (1 + $c - $top['where']))."\n");

                        } elseif (($chrs{$c} == '{') &&
                                 in_array($top['what'], array(SERVICES_JSON_SLICE, SERVICES_JSON_IN_ARR, SERVICES_JSON_IN_OBJ))) {
                            // found a left-brace, and we are in an array, object, or slice
                            array_push($stk, array('what' => SERVICES_JSON_IN_OBJ, 'where' => $c, 'delim' => false));
                            //print("Found start of object at {$c}\n");

                        } elseif (($chrs{$c} == '}') && ($top['what'] == SERVICES_JSON_IN_OBJ)) {
                            // found a right-brace, and we're in an object
                            array_pop($stk);
                            //print("Found end of object at {$c}: ".$this->substr8($chrs, $top['where'], (1 + $c - $top['where']))."\n");

                        } elseif (($substr_chrs_c_2 == '/*') &&
                                 in_array($top['what'], array(SERVICES_JSON_SLICE, SERVICES_JSON_IN_ARR, SERVICES_JSON_IN_OBJ))) {
                            // found a comment start, and we are in an array, object, or slice
                            array_push($stk, array('what' => SERVICES_JSON_IN_CMT, 'where' => $c, 'delim' => false));
                            $c++;
                            //print("Found start of comment at {$c}\n");

                        } elseif (($substr_chrs_c_2 == '*/') && ($top['what'] == SERVICES_JSON_IN_CMT)) {
                            // found a comment end, and we're in one now
                            array_pop($stk);
                            $c++;

                            for ($i = $top['where']; $i <= $c; ++$i)
                                $chrs = substr_replace($chrs, ' ', $i, 1);

                            //print("Found end of comment at {$c}: ".$this->substr8($chrs, $top['where'], (1 + $c - $top['where']))."\n");

                        }

                    }

                    if (reset($stk) == SERVICES_JSON_IN_ARR) {
                        return $arr;

                    } elseif (reset($stk) == SERVICES_JSON_IN_OBJ) {
                        return $obj;

                    }

                }
        }
    }

    /**
     * @todo Ultimately, this should just call PEAR::isError()
     */
    function isError($data, $code = null)
    {
//        if (class_exists('pear',false)) {
//            return PEAR::isError($data, $code);
//        } else
        if (is_object($data) && (get_class($data) == 'services_json_error' ||
                                 is_subclass_of($data, 'services_json_error'))) {
            return true;
        }

        return false;
    }
    
    /**
    * Calculates length of string in bytes
    * @param string 
    * @return integer length
    */
    function strlen8( $str ) 
    {
        if ( $this->_mb_strlen ) {
            return mb_strlen( $str, "8bit" );
        }
        return strlen( $str );
    }
    
    /**
    * Returns part of a string, interpreting $start and $length as number of bytes.
    * @param string 
    * @param integer start 
    * @param integer length 
    * @return integer length
    */
    function substr8( $string, $start, $length=false ) 
    {
        if ( $length === false ) {
            $length = $this->strlen8( $string ) - $start;
        }
        if ( $this->_mb_substr ) {
            return mb_substr( $string, $start, $length, "8bit" );
        }
        return substr( $string, $start, $length );
    }

}

//if (class_exists('PEAR_Error',false)) {
//
//    class Services_JSON_Error extends PEAR_Error
//    {
//        function Services_JSON_Error($message = 'unknown error', $code = null,
//                                     $mode = null, $options = null, $userinfo = null)
//        {
//            parent::PEAR_Error($message, $code, $mode, $options, $userinfo);
//        }
//    }
//
//} else {

    /**
     * @todo Ultimately, this class shall be descended from PEAR_Error
     */
    class Services_JSON_Error
    {
        function Services_JSON_Error($message = 'unknown error', $code = null,
                                     $mode = null, $options = null, $userinfo = null)
        {

        }
    }
    
//}
/**
 * OpenSDK工具类
 *
 * 主要做一些通用方法的封装和兼容性工作
 *
 * 依赖：
 * PHP 5 >= 5.1.2, PECL hash >= 1.1 (no need now)
 *
 * @ignore
 * @author icehu@vip.qq.com
 *
 */

class OpenSDK_Util
{

    /**
     * fix json_encode
     *
     * @see json_encode
     * @param mix $value
     * @return string
     */
    public static function json_encode($value)
    {
        if(function_exists('json_encode'))
        {
            return json_encode($value);
        }
        $jsonObj = new Services_JSON();
        return( $json->encode($value) );
    }
    /**
     * json_decode
     *
     * @see json_decode
     * @param string $json
     * @param bool $assoc
     * @return array|object
     */
    public static function json_decode($json , $assoc=null)
    {
        if(function_exists('json_decode'))
        {
            return json_decode($json, $assoc);
        }
        $jsonObj = new Services_JSON();
        $use = 0;
        if($assoc)
        {
            //SERVICES_JSON_LOOSE_TYPE    返回关联数组
            $use = 0x10;
        }
        $jsonObj = new Services_JSON($use);
        return $jsonObj->decode($json);
    }

    /**
     * rfc3986 encode
     * why not encode ~
     *
     * @param string|mix $input
     * @return string
     */
    public static function urlencode_rfc3986($input)
    {
        if(is_array($input))
        {
            return array_map( array( __CLASS__ , 'urlencode_rfc3986') , $input);
        }
        else if(is_scalar($input))
        {
            return str_replace('%7E', '~', rawurlencode($input));
        }
        else
        {
            return '';
        }
    }

    /**
     * fix hash_hmac
     *
     * @see hash_hmac
     * @param string $algo
     * @param string $data
     * @param string $key
     * @param bool $raw_output
     */
    public static function hash_hmac( $algo , $data , $key , $raw_output = false )
    {
        if(function_exists('hash_hmac'))
        {
            return hash_hmac($algo, $data, $key, $raw_output);
        }

        $algo = strtolower($algo);
        if($algo == 'sha1')
        {
            $pack = 'H40';
        }
        elseif($algo == 'md5')
        {
            $pach = 'H32';
        }
        else
        {
            return '';
        }
        $size = 64;
        $opad = str_repeat(chr(0x5C), $size);
        $ipad = str_repeat(chr(0x36), $size);

        if (strlen($key) > $size) {
            $key = str_pad(pack($pack, $algo($key)), $size, chr(0x00));
        } else {
            $key = str_pad($key, $size, chr(0x00));
        }

        for ($i = 0; $i < strlen($key) - 1; $i++) {
            $opad[$i] = $opad[$i] ^ $key[$i];
            $ipad[$i] = $ipad[$i] ^ $key[$i];
        }

        $output = $algo($opad.pack($pack, $algo($ipad.$data)));

        return ($raw_output) ? pack($pack, $output) : $output;
    }
}
/**
 * OAuth协议接口
 *
 * 依赖：
 * PHP 5 >= 5.1.2, PECL hash >= 1.1 (no need now)
 *
 * @ignore
 * @author icehu@vip.qq.com
 *
 */

class OpenSDK_OAuth_Client
{
    /**
     * 签名的url标签
     * @var string
     */
    public $oauth_signature_key = 'oauth_signature';

    /**
     * app secret
     * @var string
     */
    private $_app_secret = '';

    /**
     * token secret
     * @var string
     */
    private $_token_secret = '';

    /**
     * 上一次请求返回的Httpcode
     * @var number
     */
    private $_httpcode = null;

    /**
     * 是否debug
     * @var bool
     */
    private $_debug = false;

    public function  __construct( $appsecret='' , $debug=false)
    {
        $this->_app_secret = $appsecret;
        $this->_debug = $debug;
    }
    /**
     * 设置App secret
     * @param string $appsecret
     */
    public function setAppSecret($appsecret)
    {
        $this->_app_secret = $appsecret;
    }

    /**
     * 设置token secret
     * @param string $tokensecret
     */
    public function setTokenSecret($tokensecret)
    {
        $this->_token_secret = $tokensecret;
    }

    /**
     * 组装参数签名并请求接口
     *
     * @param string $url
     * @param array $params
     * @param string $method
     * @param false|array $multi false:普通post array: array ( '{fieldname}' =>array('type'=>'mine','name'=>'filename','data'=>'filedata') ) 文件上传
     * @return string
     */
    public function request( $url, $method, $params, $multi = false )
    {
        $oauth_signature = $this->sign($url, $method, $params);
        $params[$this->oauth_signature_key] = $oauth_signature;
        return $this->http($url, $params, $method, $multi);
    }

    /**
     * OAuth 协议的签名
     *
     * @param string $url
     * @param string $method
     * @param array $params
     * @return string
     */
    private function sign( $url , $method, $params )
    {
        uksort($params, 'strcmp');
        $pairs = array();
        foreach($params as $key => $value)
        {
            $key = OpenSDK_Util::urlencode_rfc3986($key);
            if(is_array($value))
            {
                // If two or more parameters share the same name, they are sorted by their value
                // Ref: Spec: 9.1.1 (1)
                natsort($value);
                foreach($value as $duplicate_value)
                {
                    $pairs[] = $key . '=' . OpenSDK_Util::urlencode_rfc3986($duplicate_value);
                }
            }
            else
            {
                $pairs[] = $key . '=' . OpenSDK_Util::urlencode_rfc3986($value);
            }
        }

        $sign_parts = OpenSDK_Util::urlencode_rfc3986(implode('&', $pairs));

        $base_string = implode('&', array( strtoupper($method) , OpenSDK_Util::urlencode_rfc3986($url) , $sign_parts ));

        $key_parts = array(OpenSDK_Util::urlencode_rfc3986($this->_app_secret), OpenSDK_Util::urlencode_rfc3986($this->_token_secret));

        $key = implode('&', $key_parts);
        $sign = base64_encode(OpenSDK_Util::hash_hmac('sha1', $base_string, $key, true));
        if($this->_debug)
        {
            echo 'base_string: ' , $base_string , "\n";
            echo 'sign key: ', $key , "\n";
            echo 'sign: ' , $sign , "\n";
        }
        return $sign;
    }

    /**
     * Http请求接口
     *
     * @param string $url
     * @param array $params
     * @param string $method 支持 GET / POST / DELETE
     * @param false|array $multi false:普通post array: array ( 'fieldname'=>array('type'=>'mine','name'=>'filename','data'=>'filedata') ) 文件上传
     * @return string
     */
    private function http( $url , $params , $method='GET' , $multi=false )
    {
        $method = strtoupper($method);
        $postdata = '';
        $urls = @parse_url($url);
        $httpurl = $urlpath = $urls['path'] . ($urls['query'] ? '?' . $urls['query'] : '');
        if( !$multi )
        {
            $parts = array();
            foreach ($params as $key => $val)
            {
                $parts[] = urlencode($key) . '=' . urlencode($val);
            }
            if ($parts)
            {
                $postdata = implode('&', $parts);
                $httpurl = $httpurl . (strpos($httpurl, '?') ? '&' : '?') . $postdata;
            }
            else
            {
            }
        }

        $host = $urls['host'];
        $port = $urls['port'] ? $urls['port'] : 80;
        $version = '1.1';
        if($urls['scheme'] === 'https')
        {
            $port = 443;
        }
        $headers = array();
        if($method == 'GET')
        {
            $headers[] = "GET $httpurl HTTP/$version";
        }
        else if($method == 'DELETE')
        {
            $headers[] = "DELETE $httpurl HTTP/$version";
        }
        else
        {
            $headers[] = "POST $urlpath HTTP/$version";
        }
        $headers[] = 'Host: ' . $host;
        $headers[] = 'User-Agent: OpenSDK-OAuth';
        $headers[] = 'Connection: Close';

        if($method == 'POST')
        {
            if($multi)
            {
                $boundary = uniqid('------------------');
                $MPboundary = '--' . $boundary;
                $endMPboundary = $MPboundary . '--';
                $multipartbody = '';
                $headers[]= 'Content-Type: multipart/form-data; boundary=' . $boundary;
                foreach($params as $key => $val)
                {
                    $multipartbody .= $MPboundary . "\r\n";
                    $multipartbody .= 'Content-Disposition: form-data; name="' . $key . "\"\r\n\r\n";
                    $multipartbody .= $val . "\r\n";
                }
                foreach($multi as $key => $data)
                {
                    $multipartbody .= $MPboundary . "\r\n";
                    $multipartbody .= 'Content-Disposition: form-data; name="' . $key . '"; filename="' . $data['name'] . '"' . "\r\n";
                    $multipartbody .= 'Content-Type: ' . $data['type'] . "\r\n\r\n";
                    $multipartbody .= $data['data'] . "\r\n";
                }
                $multipartbody .= $endMPboundary . "\r\n";
                $postdata = $multipartbody;
            }
            else
            {
                $headers[]= 'Content-Type: application/x-www-form-urlencoded';
            }
        }
        $ret = '';
        $fp = fsockopen($host, $port, $errno, $errstr, 5);

        if(! $fp)
        {
            $error = 'Open Socket Error';
            return '';
        }
        else
        {
            if( $method != 'GET' && $postdata )
            {
                $headers[] = 'Content-Length: ' . strlen($postdata);
            }
            $this->fwrite($fp, implode("\r\n", $headers));
            $this->fwrite($fp, "\r\n\r\n");
            if( $method != 'GET' && $postdata )
            {
                $this->fwrite($fp, $postdata);
            }
            //skip headers
            while(! feof($fp))
            {
                $ret .= fgets($fp, 1024);
            }
            if($this->_debug)
            {
                echo $ret;
            }
            fclose($fp);
            $pos = strpos($ret, "\r\n\r\n");
            if($pos)
            {
                $rt = trim(substr($ret , $pos+1));
                $responseHead = trim(substr($ret, 0 , $pos));
                $responseHeads = explode("\r\n", $responseHead);
                $httpcode = explode(' ', $responseHeads[0]);
                $this->_httpcode = $httpcode[1];
                if(strpos( substr($ret , 0 , $pos), 'Transfer-Encoding: chunked'))
                {
                    $response = explode("\r\n", $rt);
                    $t = array_slice($response, 1, - 1);

                    return implode('', $t);
                }
                return $rt;
            }
            return '';
        }
    }

    /**
     * 返回上一次请求的httpCode
     * @return number
     */
    public function getHttpCode()
    {
        return $this->_httpcode;
    }

    private function fwrite($handle,$data)
    {
        fwrite($handle, $data);
        if($this->_debug)
        {
            echo $data;
        }
    }
}
/**
 * OAuth1.0 SDK Interface
 *
 * 提供给具体接口子类使用的一些公共方法
 *
 * @author icehu@vip.qq.com
 */

class OpenSDK_OAuth_Interface
{

    /**
     * app key
     * @var string
     */
    protected static $_appkey = '';
    /**
     * app secret
     * @var string
     */
    protected static $_appsecret = '';

    /**
     * OAuth 版本
     * @var string
     */
    protected static $version = '1.0';

    const RETURN_JSON = 'json';
    const RETURN_XML = 'xml';
    /**
     * 初始化
     * @param string $appkey
     * @param string $appsecret
     */
    public static function init($appkey,$appsecret)
    {
        self::setAppkey($appkey, $appsecret);
    }
    /**
     * 设置APP Key 和 APP Secret
     * @param string $appkey
     * @param string $appsecret
     */
    protected static function setAppkey($appkey,$appsecret)
    {
        self::$_appkey = $appkey;
        self::$_appsecret = $appsecret;
    }

    protected static $timestampFunc = null;

    /**
     * 获得本机时间戳的方法
     * 如果服务器时钟存在误差，在这里调整
     *
     * @return number
     */
    public static function getTimestamp()
    {
        if(null !== self::$timestampFunc && is_callable(self::$timestampFunc))
        {
            return call_user_func(self::$timestampFunc);
        }
        return time();
    }

    /**
     * 设置获取时间戳的方法
     *
     * @param function $func
     */
    public static function timestamp_set_save_handler( $func )
    {
        self::$timestampFunc = $func;
    }

    protected static $getParamFunc = null;

    public static function getParam( $key )
    {
        if(null !== self::$getParamFunc && is_callable(self::$getParamFunc))
        {
            return call_user_func(self::$getParamFunc, $key);
        }
        $session=ml_controller::getSession();
        return $session->getVal($key);
    }

    /**
     *
     * 设置Session数据的存取方法
     * 类似于session_set_save_handler来重写Session的存取方法
     * 当你的token存储到跟用户相关的数据库中时非常有用
     * $get方法 接受1个参数 $key
     * $set方法 接受2个参数 $key $val
     *
     * @param function $get
     * @param function $set
     */
    public static function param_set_save_handler( $get, $set)
    {
        self::$getParamFunc = $get;
        self::$setParamFunc = $set;
    }

    protected static $setParamFunc = null;

    public static function setParam( $key , $val=null)
    {
        $session=ml_controller::getSession();
        if(null !== self::$setParamFunc && is_callable(self::$setParamFunc))
        {
            return call_user_func(self::$setParamFunc, $key, $val);
        }
        if( null === $val)
        {
            $session-> unregister($key);
            return ;
        }
        $session->setVal($key, $val);
        $session->save();
    }

}
/**
 * Tencent 微博 SDK
 *
 * 依赖：
 * 1、PECL json >= 1.2.0    no need now
 * 2、PHP >= 5.2.0 because json_decode no need now
 * 3、$_SESSION
 * 4、PECL hash >= 1.1 no need now
 *
 * only need PHP >= 5.0
 *
 * 如何使用：
 * 1、将OpenSDK文件夹放入include_path
 * 2、require_once 'OpenSDK/Tencent/Weibo.php';
 * 3、OpenSDK_Tencent_Weibo::init($appkey,$appsecret);
 * 4、OpenSDK_Tencent_Weibo::getRequestToken($callback); 获得request token
 * 5、OpenSDK_Tencent_Weibo::getAuthorizeURL($token); 获得跳转授权URL
 * 6、OpenSDK_Tencent_Weibo::getAccessToken($oauth_verifier) 获得access token
 * 7、OpenSDK_Tencent_Weibo::call();调用API接口
 *
 * 建议：
 * 1、PHP5.2 以下版本，可以使用Pear库中的 Service_JSON 来兼容json_decode
 * 2、使用 session_set_save_handler 来重写SESSION。调用API接口前需要主动session_start
 * 3、OpenSDK的文件和类名的命名规则符合Pear 和 Zend 规则
 *    如果你的代码也符合这样的标准 可以方便的加入到__autoload规则中
 *
 * @author icehu@vip.qq.com
 */

class OpenSDK_Tencent_Weibo extends OpenSDK_OAuth_Interface
{

    private static $accessTokenURL = 'http://open.t.qq.com/cgi-bin/access_token';

    private static $authorizeURL = 'http://open.t.qq.com/cgi-bin/authorize';

    private static $requestTokenURL = 'http://open.t.qq.com/cgi-bin/request_token';

    /**
     * OAuth 对象
     * @var OpenSDK_OAuth_Client
     */
    protected static $oauth = null;
    /**
     * OAuth 版本
     * @var string
     */
    protected static $version = '1.0';
    /**
     * 存储oauth_token的session key
     */
    const OAUTH_TOKEN = 'access_token_key';
    /**
     * 存储oauth_token_secret的session key
     */
    const OAUTH_TOKEN_SECRET = 'token_secret';
    /**
     * 存储access_token的session key
     */
    const ACCESS_TOKEN = 'access_token';

    /**
     * 存储oauth_name的Session key
     */
    const OAUTH_NAME = '3rd_id';

    /**
     * 获取requestToken
     *
     * 返回的数组包括：
     * oauth_token：返回的request_token
     * oauth_token_secret：返回的request_secret
     * oauth_callback_confirmed：回调确认
     *
     * @param string $callback 回调地址
     * @return array
     */
    public static function getRequestToken($callback='null')
    {
        self::getOAuth()->setTokenSecret('');
        $response = self::request( self::$requestTokenURL, 'GET' , array(
                'oauth_callback' => $callback,
        ));
        parse_str($response , $rt);
        if($rt['oauth_token'] && $rt['oauth_token_secret'])
        {
            self::getOAuth()->setTokenSecret($rt['oauth_token_secret']);
            self::setParam(self::OAUTH_TOKEN, $rt['oauth_token']);
            self::setParam(self::OAUTH_TOKEN_SECRET, $rt['oauth_token_secret']);
            return $rt;
        }
        else
        {
            return false;
        }
    }

    /**
     *
     * 获得授权URL
     *
     * @param string|array $token
     * @param bool $mini 是否mini窗口
     * @return string
     */
    public static function getAuthorizeURL($token , $mini=false)
    {
        if(is_array($token))
        {
            $token = $token['oauth_token'];
        }
        return self::$authorizeURL . '?oauth_token=' . $token . ($mini ? '&mini=1' : '');
    }

    /**
     * 获得Access Token
     * @param string $oauth_verifier
     * @return array
     */
    public static function getAccessToken( $oauth_verifier = false )
    {
        $response = self::request( self::$accessTokenURL, 'GET' , array(
                'oauth_token' => self::getParam(self::OAUTH_TOKEN),
                'oauth_verifier' => $oauth_verifier,
        ));
        parse_str($response,$rt);
        if( $rt['oauth_token'] && $rt['oauth_token_secret'] )
        {
            self::getOAuth()->setTokenSecret($rt['oauth_token_secret']);
            self::setParam(self::ACCESS_TOKEN, $rt['oauth_token']);
            self::setParam(self::OAUTH_TOKEN_SECRET, $rt['oauth_token_secret']);
            self::setParam(self::OAUTH_NAME, $rt['name']);
        }
        return $rt;
    }

    /**
     * 统一调用接口的方法
     * 照着官网的参数往里填就行了
     * 需要调用哪个就填哪个，如果方法调用得频繁，可以封装更方便的方法。
     *
     * 如果上传文件 $method = 'POST';
     * $multi 是一个二维数组
     *
     * array(
     *    '{fieldname}' => array(        //第一个文件
     *        'type' => 'mine 类型',
     *        'name' => 'filename',
     *        'data' => 'filedata 字节流',
     *    ),
     *    ...如果接受多个文件，可以再加
     * )
     *
     * @param string $command 官方说明中去掉 http://open.t.qq.com/api/ 后面剩余的部分
     * @param array $params 官方说明中接受的参数列表，一个关联数组
     * @param string $method 官方说明中的 method GET/POST
     * @param false|array $multi 是否上传文件
     * @param bool $decode 是否对返回的字符串解码成数组
     * @param OpenSDK_Tencent_Weibo::RETURN_JSON|OpenSDK_Tencent_Weibo::RETURN_XML $format 调用格式
     */
    public static function call($command , $params=array() , $method = 'GET' , $multi=false ,$decode=true , $format=self::RETURN_JSON)
    {
        if($format == self::RETURN_XML)
            ;
        else
            $format == self::RETURN_JSON;
        $params['format'] = $format;
        //去掉空数据
        foreach($params as $key => $val)
        {
            if(strlen($val) == 0)
            {
                unset($params[$key]);
            }
        }
        $params['oauth_token'] = self::getParam(self::ACCESS_TOKEN);
        $response = self::request( 'http://open.t.qq.com/api/'.ltrim($command,'/') , $method, $params, $multi);
        if($decode)
        {
            if($format == self::RETURN_JSON)
            {
                return OpenSDK_Util::json_decode($response, true);
            }
            else
            {
                //parse xml2array later
                return $response;
            }
        }
        else
        {
            return $response;
        }
    }

    /**
     * 获得OAuth 对象
     * @return OpenSDK_OAuth_Client
     */
    protected static function getOAuth()
    {
        if( null === self::$oauth )
        {
            self::$oauth = new OpenSDK_OAuth_Client(self::$_appsecret);
            $secret = self::getParam(self::OAUTH_TOKEN_SECRET);
            if($secret)
            {
                self::$oauth->setTokenSecret($secret);
            }
        }
        return self::$oauth;
    }

    /**
     *
     * OAuth协议请求接口
     *
     * @param string $url
     * @param string $method
     * @param array $params
     * @param array $multi
     * @return string
     * @ignore
     */
    protected static function request($url , $method , $params , $multi=false)
    {
        if(!self::$_appkey || !self::$_appsecret)
        {
            exit('app key or app secret not init');
        }
        $params['oauth_nonce'] = md5( mt_rand(1, 100000) . microtime(true) );
        $params['oauth_consumer_key'] = self::$_appkey;
        $params['oauth_signature_method'] = 'HMAC-SHA1';
        $params['oauth_version'] = self::$version;
        $params['oauth_timestamp'] = self::getTimestamp();
        return self::getOAuth()->request($url, $method, $params, $multi);
    }
}
