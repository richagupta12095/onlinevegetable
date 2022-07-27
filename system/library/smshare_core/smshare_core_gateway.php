<?php
namespace smshare_core;

class smshare_core_gateway extends smshare_core_000 {


    /**
     *
     */
    public function send_using_gateway($args, $current_config, $default_config){

        $result = array();

        /*
         *
         */
        $dest_param_name = "";

        if(isset($current_config['smshare_core_cfg_api_dest_var'])) {
            $dest_param_name = $current_config['smshare_core_cfg_api_dest_var'];
        }else if ($default_config['smshare_core_cfg_api_dest_var']){
            $dest_param_name = $default_config['smshare_core_cfg_api_dest_var'];
        }

        if(! $dest_param_name) {
            $err_msg = 'Missing `destination` parameter name';
            if($this->config->get('smshare_core_cfg_log')) $this->log->write('[smshare] ' . $err_msg . ". Aborting!");
            $result['error'] = $err_msg;
            return ;
        }

        $data = array($dest_param_name => $args['to']);


        /*
         *
         */
        $method = "";

        if(isset($current_config['smshare_core_cfg_api_http_method'])) {
            $method = $current_config['smshare_core_cfg_api_http_method'];
        }else if (isset($default_config['smshare_core_cfg_api_http_method'])){
            $method = $default_config['smshare_core_cfg_api_http_method'];
        }

        if(! $method) {
            $err_msg = 'Missing `http_method` gateway setting';
            if($this->config->get('smshare_core_cfg_log')) $this->log->write('[smshare] ' . $err_msg . ". Aborting!");
            $result['error'] = $err_msg;
            return ;
        }



        /*
         * gateway_url
         */

        $url = "";

        if(isset($current_config['smshare_core_cfg_api_url'])) {
            $url = $current_config['smshare_core_cfg_api_url'];
        } else if(isset($default_config['smshare_core_cfg_api_url'])) {
            $url = $default_config['smshare_core_cfg_api_url'];
        }

        if(! $url) {
            $err_msg = 'Missing gateway `URL` setting';
            if($this->config->get('smshare_core_cfg_log')) $this->log->write('[smshare] ' . $err_msg . ". Aborting!");
            $result['error'] = $err_msg;
            return ;
        }



        /*
         * SMS Body
         */

        $sms_body = $args['body'];

        $msg_to_unicode = "";

        if(isset($current_config['smshare_core_cfg_api_msg_to_unicode'])){
            $msg_to_unicode = $current_config['smshare_core_cfg_api_msg_to_unicode'];
        }else if(isset($default_config['smshare_core_cfg_api_msg_to_unicode'])){
            $msg_to_unicode = $default_config['smshare_core_cfg_api_msg_to_unicode'];
        }

        if( $msg_to_unicode === 'on'){
            $this->load->library('smshare_core/smshare_core_util');
            $sms_body = $this->registry->get('smshare_core_util')->utf8ToUnicodeCodePoints($sms_body);
        }

        //Do not encode for GET and post (application/x-www-form-urlencoded) because http_build_query encodes also. dc005
        if ($method === 'post (multipart/form-data)' || $method === 'post' /* old version of the module */ ){
            $sms_body = urlencode($sms_body);
        }

        $sms_body = html_entity_decode($sms_body);    //convert any HTML entity that may come from HTML editor into normal character.


        /*
         *
         */

        $msg_param_name = "";

        if(isset($current_config['smshare_core_cfg_api_msg_var'])) {
            $msg_param_name = $current_config['smshare_core_cfg_api_msg_var'];
        }else if(isset($default_config['smshare_core_cfg_api_msg_var'])) {
            $msg_param_name = $default_config['smshare_core_cfg_api_msg_var'];
        }

        if(! $msg_param_name) {
            $err_msg = 'Missing `message` parameter name';
            if($this->config->get('smshare_core_cfg_log')) $this->log->write('[smshare] ' . $err_msg . ". Aborting!");
            $result['error'] = $err_msg;
            return ;
        }

        $data[$msg_param_name] = $sms_body;


        /*
         * manage delay_in_utc_since_epoch
         */
        if($this->is_master_gateway($url)){
            //inject delay_in_utc_since_epoch
            if(isset($args['scheduleTimestamp'])){
                $data['scheduleTimestamp'] = $args['scheduleTimestamp'];
            }
        }

        /*
         * Inject additional fields
         */
        $api_kv_arr = array();    //defensive, bug fix gmail#14c38e947f3b158
        if(isset($current_config['smshare_core_cfg_gw_fields'])){
            $api_kv_arr = $current_config['smshare_core_cfg_gw_fields'];
        }else if(isset($default_config['smshare_core_cfg_gw_fields'])) {
            $api_kv_arr = $default_config['smshare_core_cfg_gw_fields'];
        }


        if(strncmp($method, "post", strlen("post")) === 0){  //POST method

            foreach ($api_kv_arr as $api_kv) {
                $data[$api_kv['key']] = $api_kv['val'];
            }

        }else{    //GET method
            $do_not_encode_kv = array();
            foreach ($api_kv_arr as $api_kv) {
                //28/10/2019 is still this relevant?
                //if(!isset($api_kv['encode'])) {    //No Encode. Encode must be set explicitly by the user before we encode params in http_build_query
                //    array_push($do_not_encode_kv, $api_kv['key'] . '=' . $api_kv['val']);
                //}else{
                //    $data[$api_kv['key']] = $api_kv['val'];
                //}
                $data[$api_kv['key']] = $api_kv['val'];
            }

            $request_params = http_build_query($data, '', '&');
            if(count($do_not_encode_kv) > 0){
                $request_params .= '&' . implode("&", $do_not_encode_kv);
            }

            if (strpos($url, '?') === false) {
                $connector = '?';
            }else{
                $connector = '&';
            }
            $url = $url . $connector . $request_params;
        }

        $result = $this->do_call_gateway($url, $method, $data);
        return $result;
    }



    /**
     *
     */
    function do_call_gateway($url, $method, $data){

        $result = array();

        //XXX remove me
        //$result['http_code'] = "200";
        //$result['http_body'] = "ok" ;
        //return $result;

        // curl options:
        $options = array(
            CURLOPT_RETURNTRANSFER    => true
            //,CURLOPT_FOLLOWLOCATION => true
        );


        /**
         * most gateways seem to use multipart/form-data.
         * Here below we use http_build_query to encode using application/x-www-form-urlencoded so we must avoid double encoding of message body in net.php @dc005
         *
         * application/x-www-form-urlencoded VS multipart/form-data
         * https://gist.github.com/joyrexus/524c7e811e4abf9afe56
         */
        if (strpos($url, '/api/v3/texting') !== false) {
            //inject access token in header.
            $options[CURLOPT_HTTPHEADER] = array('X-Access-Token: ' . $data['access_token']);
            unset($data['access_token']);
        }



        /*
         * HTTP BASIC AUTH support
         */
        if($this->config->get('smshare_core_cfg_basic_http_auth_status')){

            if ($this->config->get('smshare_core_cfg_gateway_provider') === 'profile_api_routee') {

                /*
                 * get access_token
                 */
                $routee_access_token = $this->get_access_token_from_routee($this);

                if(! $routee_access_token){
                    if($this->config->get('smshare_core_cfg_log')) $this->log->write("[smshare] No routee access token. Aborting!");
                    return ;
                }

                /*
                 * Set authorization bearer
                 */
                $options[CURLOPT_HTTPHEADER][] = "Authorization: Bearer " . $routee_access_token;

            }else if($this->config->get('smshare_core_cfg_gateway_provider') === 'profile_api_fluentcloud'){
                $options[CURLOPT_HTTPHEADER][] = "Authorization:" . $this->config->get('smshare_core_cfg_basic_http_auth_username');
            }else{
                $options[CURLOPT_USERPWD] = $this->config->get('smshare_core_cfg_basic_http_auth_username') . ":" . $this->config->get('smshare_core_cfg_basic_http_auth_password');
            }
        }





        /*
         * Passing a URL-encoded string will result in application/x-www-form-urlencoded
         * $options[CURLOPT_HTTPHEADER] = array('Content-Type: application/x-www-form-urlencoded');
         *
         * passing an array to CURLOPT_POSTFIELDS results in  multipart/form-data
         * http://php.net/manual/en/function.curl-setopt.php#84916
         */
        if($method === 'post (application/x-www-form-urlencoded)'){
            $data = http_build_query($data, '', '&');

        }else if($method === 'post (application/json)'){
            $data = json_encode($data);
            $options[CURLOPT_HTTPHEADER] = array('Content-Type: application/json');
        }


        if(strncmp($method, "post", strlen("post")) === 0){  //startswith
            $options[CURLOPT_POST]       = true;
            $options[CURLOPT_POSTFIELDS] = $data;
        }

        //if ($config->get('config_error_log')) $log->write("URL  is: " . $url);
        //if ($this->config->get('smshare_core_cfg_log')) $this->log->write("[PWS-DEBUG] data is: " . print_r($data, true));
        //if ($config->get('config_error_log')) $log->write("options is: " . print_r($options, true));


        /*
         * API call
         */
        $options[CURLOPT_URL] = $url;

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $output = curl_exec($ch);    //get response
        if(!$output){
            if ($this->config->get('smshare_core_cfg_log')) $this->log->write('[smshare] Curl error: ' . curl_error($ch));
        }

        $result['http_code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $result['http_body'] = $output;

        if ($this->config->get('smshare_core_cfg_log')) $this->log->write("[smshare] Gateway reply: " . print_r($result, true));

        curl_close($ch);
        return $result;
    }



    /**
     *
     */
    private function is_master_gateway($url){
        if(strpos($url, 'smshare.fr') !== false || strpos($url, 'prowebsms.com') !== false){
            return true;
        }
        return false;
    }



    /**
     *
     */
    private function get_access_token_from_routee() {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://auth.routee.net/oauth/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "grant_type=client_credentials",
            CURLOPT_HTTPHEADER => array(
                //            "authorization: Basic {base64Token}",
                "content-type: application/x-www-form-urlencoded"
            ),
        ));

        /*
         *
         */
        curl_setopt($curl, CURLOPT_USERPWD, $this->config->get('smshare_core_cfg_basic_http_auth_username') . ":" . $this->config->get('smshare_core_cfg_basic_http_auth_password'));


        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            if ($this->config->get('smshare_core_cfg_log')) $this->log->write('[smshare] Curl error: ' . $err);
            return "";
        } else {
            if ($this->config->get('smshare_core_cfg_log')) $this->log->write('[smshare] routee response: ' . $response);

            $json = json_decode($response, true);

            return $json['access_token'];
        }

    }
}