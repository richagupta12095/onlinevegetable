<?php
namespace smshare_core;
class smshare_core_facade extends smshare_core_000{


    public function send_sms_or_not($args){

        //$this->load->component('smshare/rewrite');
        //$this->load->component('smshare/dao');
        //$this->load->component('smshare/router');
        $this->load->library('smshare_core/smshare_core_insight');
        $this->load->library('smshare_core/smshare_core_router');


        /*
         * args
         */
        $phonenumber  = $args['to'];
        $sms_body	  = $args['body'];
        $country_code = $args['country_code'];
        $sending_mode = null;
        if(isset($args['sending_mode']) && !empty($args['sending_mode'])){
            $sending_mode = $args['sending_mode'];
        }
        $store_id	  = 0;  //when done here, do it here also: 14df6
        //if(isset($args['store_id'])){
        //    $store_id = $args['store_id'];
        //}

        //$this->load->model('setting/setting');
        //$my_config = $this->model_setting_setting->getSetting('smshare', $store_id);
        //$config_00 = $this->model_setting_setting->getSetting('smshare', 0);
        //$this->log->write("[DEBUG] my_config: " . print_r($my_config, true));

        $config_default_store = $args['config_default_store'];
        $config_current_store = $args['config_current_store'];


        /*
         * number insight
         */

        $my_args = array(
            "to" => $phonenumber,
            "cc" => $country_code,
        );
        $insight_formating = $this->registry->get('smshare_core_insight')->format_or_not_number_using_insight($my_args, $config_current_store, $config_default_store);
        if($insight_formating['status'] == 'error') {
            return $insight_formating;
        }
        $phonenumber = $insight_formating['result'];
        if ($this->config->get('smshare_core_cfg_log')) $this->log->write("[smshare] Formatted number: $phonenumber");


        /*
         * Number filter
         */
        //$this->load->component('smshare/filter');
        //if(! $this->component_smshare_filter->pass_filters($phonenumber, $store_id)){
        //    return array(
        //        "status" => "error",
        //        "message"=> "phone number does not pass filters",
        //    );
        //}

        /*
         * number rewrite
         */
        //$this->load->helper('smshare_phonenumber_filter');
        //$sms_to = rewritePhoneNumber($phonenumber, $this, $store_id);

        $sms_to = $phonenumber;


        /*
         * Send SMS
         */
        $this->registry->get('smshare_core_router')->select_route_and_send_sms(array(
            'to'           => $sms_to       ,
            'body'         => $sms_body     ,
            'sending_mode' => $sending_mode ,
            'store_id'     => $store_id     ,
            "config_default_store" => $config_default_store,
            "config_current_store" => $config_current_store,
        ));


    }


}