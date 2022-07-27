<?php
namespace smshare_core;

class smshare_core_util {


    /**
     * http://stackoverflow.com/questions/10100617/how-to-convert-text-to-unicode-code-point-like-u0054-u0068-u0069-u0073-using-p
     * required for mobily.ws (arabic) that needs msg to be in unicode. Eg. test → %5Cu0074%5Cu0065%5Cu0073%5Cu0074
     */
    public function utf8ToUnicodeCodePoints($str) {
        if (!mb_check_encoding($str, 'UTF-8')) {
            trigger_error('$str is not encoded in UTF-8... Fatal failure here');
            return false;
        }
        $str_unicode = preg_replace_callback('/./u', array($this, "utf8ToUnicodeCodePoints_replacement_callback")/*http://stackoverflow.com/a/5530057/353985*/, $str);

        return str_replace("\n", "000D", $str_unicode);    //\n was not handled before..
    }

    /**
     * Done for users with php < 5.3 that does not support anonymous functions.
     * This function is used as a callback in the utf8ToUnicodeCodePoints function above
     */
    private function utf8ToUnicodeCodePoints_replacement_callback($m){
        $ord = ord($m[0]);

        if ($ord <= 127) {
            $r = sprintf('\u%04x', $ord);
        } else {
            $r = trim(json_encode($m[0]), '"');
        }
        return str_replace("\u", "", $r);  //remove \u because mobily.ws need unicode without \u
    }



}