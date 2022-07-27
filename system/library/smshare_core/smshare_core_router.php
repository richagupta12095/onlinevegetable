<?php
namespace smshare_core;
class smshare_core_router extends smshare_core_000{


    public function select_route_and_send_sms($args) {

        $config_default_store = $args['config_default_store'];
        $config_current_store = $args['config_current_store'];


        /*
         * Elect sending mode:
         */
        if(isset($args['sending_mode']) && $args['sending_mode']){
            $smshare_core_cfg_sender_profile = $args['sending_mode'];

        }else if(isset($config_default_store['smshare_core_cfg_sender_profile'])){
            $smshare_core_cfg_sender_profile = $config_default_store['smshare_core_cfg_sender_profile'];

        }else if(isset($config_current_store['smshare_core_cfg_sender_profile'])) {
            $smshare_core_cfg_sender_profile = $config_current_store['smshare_core_cfg_sender_profile'];
        }


        if(empty($smshare_core_cfg_sender_profile)){
            if ($this->config->get('smshare_core_cfg_log')) $this->log->write('[smshare] No sending mode registered. Aborting!');
            return;
        }




        /*
         * Send
         */
        if($smshare_core_cfg_sender_profile === 'profile_android') {
            $this->load->library('smshare_core/smshare_core_android');
            $result = $this->registry->get('smshare_core_android')->send_using_android($args, $config_default_store, $config_current_store);

        }else {
            $this->load->library('smshare_core/smshare_core_gateway');
            $result = $this->registry->get('smshare_core_gateway')->send_using_gateway($args, $config_default_store, $config_current_store);
        }


        return $result;

    }


}