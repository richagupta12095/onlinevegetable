<?php
namespace smshare_core;

class smshare_core_url {

    const ONE_SMS_URL       = "https://smshare.fr/api/v3/texting/bulk";
//    const ONE_SMS_URL       = "https://smshare.me/api/v3/texting/bulk";
    const FORMAT_NUMBER_URL = "https://smshare.fr/api/phone-number/format";



    public function get_send_sms_url($current_config, $default_config){
        return $this->get_url(self::ONE_SMS_URL, $current_config, $default_config);
    }


    public function get_format_number_url($current_config, $default_config){
        return $this->get_url(self::FORMAT_NUMBER_URL, $current_config, $default_config);
    }


    private function get_url($url, $current_config, $default_config){

        if(isset($current_config['smshare_core_cfg_use_https'])){
            $smshare_core_cfg_use_https = $current_config['smshare_core_cfg_use_https'];
        }else if(isset($default_config['smshare_core_cfg_use_https'])){
            $smshare_core_cfg_use_https = $default_config['smshare_core_cfg_use_https'];
        }

        if ($smshare_core_cfg_use_https) {
            return $url;
        } else {
            return str_replace("https://", "http://", $url);
        }
    }


}