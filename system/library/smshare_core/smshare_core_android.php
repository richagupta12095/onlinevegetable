<?php
namespace smshare_core;

class smshare_core_android extends smshare_core_000 {

    /**
     *
     */
    public function send_using_android($args, $current_config, $default_config){

        /*
         *
         */
        $smshare_core_cfg_smartphone_access_token = "";

        if(isset($current_config['smshare_core_cfg_smartphone_access_token'])) {
            $smshare_core_cfg_smartphone_access_token = $current_config['smshare_core_cfg_smartphone_access_token'];
        }else if(isset($default_config['smshare_core_cfg_smartphone_access_token'])){
            $smshare_core_cfg_smartphone_access_token =$default_config['smshare_core_cfg_smartphone_access_token'];
        }

        if(! $smshare_core_cfg_smartphone_access_token) {
            $this->log->write('[smshare] no smshare_core_cfg_smartphone_access_token. Aborting!');
            return;
        }

        /*
         *
         */

        $smshare_core_cfg_smartphone_uuid = "";

        if(isset($current_config['smshare_core_cfg_smartphone_uuid'])) {
            $smshare_core_cfg_smartphone_uuid = $current_config['smshare_core_cfg_smartphone_uuid'];
        }else if(isset($default_config['smshare_core_cfg_smartphone_uuid'])){
            $smshare_core_cfg_smartphone_uuid = $default_config['smshare_core_cfg_smartphone_uuid'];
        }

        if(! $smshare_core_cfg_smartphone_uuid) {
            $this->log->write('[smshare] no smshare_core_cfg_smartphone_uuid. Aborting!');
            return;
        }



        /*
         *
         */

        $json = $this->build_smshare_json(
            $smshare_core_cfg_smartphone_uuid,
            $args
        );


        /*
         *
         */
        $this->load->library('smshare_core/smshare_core_url');
        $api_url = $this->registry->get('smshare_core_url')->get_send_sms_url($current_config, $default_config);

        /*
         *
         */

        $this->load->library('smshare_core/smshare_core_net');
        $result = $this->registry->get('smshare_core_net')->post_json($api_url, $json);

        return $result;
    }



    /**
     *
     */
    private function build_smshare_json($gateway_uuid, $args) {

        $json_arr[] = array(
            "numbers" => $args['to'],
            "message" => html_entity_decode($args['body'], ENT_QUOTES, 'UTF-8'),
            "gateway" => $gateway_uuid,
        );

        if(isset($args['scheduleTimestamp'])) {    //smsreview
            $json_arr["scheduleTimestamp"] = $args['scheduleTimestamp'];
        }


        $json = json_encode($json_arr);

        return $json;
    }

}