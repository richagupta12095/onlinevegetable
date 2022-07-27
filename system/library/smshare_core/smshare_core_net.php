<?php
namespace smshare_core;
class smshare_core_net extends smshare_core_000 {

    /**
     * Android (ProWebSms)
     */
    function post_json($url, $json) {

        if ($this->config->get('smshare_core_cfg_log')) $this->log->write("[pws] Data to be posted: " . print_r($json, true));
        if ($this->config->get('smshare_core_cfg_log')) $this->log->write("[pws] URL: " . $url);

        $ch = curl_init($url);
//        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  //remove me
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");    //because some customers have a Curl which comes with an outdated file to authenticate HTTPS certificates from. See http://stackoverflow.com/a/316732/353985
        $access_token = $this->config->get("smshare_core_cfg_smartphone_access_token");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "X-Access-Token: " . $access_token));

        $reply = curl_exec($ch);

        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $this->log->write("[pws] status code: " . $statusCode);
        $this->log->write("[pws] " . curl_error($ch));    //error
        $this->log->write("[pws] " . $reply);             //success


        curl_close($ch);
        return $reply;
    }


    /**
     *
     */
    public function format_number($args, $current_config, $default_config){

        $postData = json_encode($args);

        if ($this->config->get('smshare_core_cfg_log')) $this->log->write('[smshare] data to be sent: ' . print_r($postData, true));


        $this->load->library('smshare_core/smshare_core_url');
        $api_url = $this->registry->get('smshare_core_url')->get_format_number_url($current_config, $default_config);


        $ch = curl_init($api_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json'));

        $reply = curl_exec($ch);

        /*
         * logging
         */
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if($statusCode !== 200) $this->log->write("[smshare] Status code: " . $statusCode);

        $curl_error = curl_error($ch);
        if($curl_error) $this->log->write("[smshare] Curl Error: " . $curl_error);    //error

        if ($this->config->get('smshare_core_cfg_log')) $this->log->write("[smshare] Server response: " . $reply);        //success

        /*
         * decode and consume response
         */
        $data = json_decode($reply, true);

        if($data['status'] === 'error'){
            if ($this->config->get('smshare_core_cfg_log')) $this->log->write("[smshare] API formatter rejected the phone number. Error message is: " . $data['message']);
        }else{
            if ($this->config->get('smshare_core_cfg_log')) $this->log->write("[smshare] API formatter formatted the phone number from: " . $args['number'] . " to: " . $data['result']);
        }

        return $data;
    }

}