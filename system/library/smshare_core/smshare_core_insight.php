<?php
namespace smshare_core;
class smshare_core_insight extends smshare_core_000 {

    /**
     *
     */
    public function format_or_not_number_using_insight($args, $config, $config0){

        $this->load->library('smshare_core/smshare_core_net');


        if(! $args['cc']){
            if ($this->config->get('smshare_core_cfg_log')) $this->log->write("[smshare] No country code to call insight API. Do not call API.");
            return array(
                'status' => 'success',
                'result' => $args['to']
            );
        }

        /*
         *
         */
        $insight_status = "";

        if(isset($config['smshare_core_cfg_number_insight_status'])){
            $insight_status = $config['smshare_core_cfg_number_insight_status'];
        }else if (isset($config0['smshare_core_cfg_number_insight_status'])) {
            $insight_status = $config0['smshare_core_cfg_number_insight_status'];
        }

        if(! $insight_status){
            if ($this->config->get('smshare_core_cfg_log')) $this->log->write("[smshare] Insight setting is disabled. Do not call API.");
            return array(
                'status' => 'success',
                'result' => $args['to']
            );
        }

        /*
         *
         */
        if(isset($config['smshare_core_cfg_number_insight_format'])){
            $insight_format = $config['smshare_core_cfg_number_insight_format'];
        }else if (isset($config0['smshare_core_cfg_number_insight_format'])) {
            $insight_format = $config0['smshare_core_cfg_number_insight_format'];
        }

        if(!$insight_format){
            return array(
                'status' => 'success',
                'result' => $args['to']
            );
        }

        /*
         *
         */
        $format_number_response = $this->registry->get('smshare_core_net')->format_number(array(
            'number'      => $args['to'],
            'countryCode' => $args['cc'],
            'format' 	  => $insight_format,
        ), $config, $config0);

        if($format_number_response['status'] === "success") {
            $phonenumber = $format_number_response['result'];
            return array(
                'status' => 'success',
                'result' => $phonenumber
            );

        }else{
            return array(
                "status" => "error",
                "message"=> "message not sent",
                "detail" => "phone number is not valid. Number insight api returned: " . $format_number_response['message'],
            );
        }


    }


}