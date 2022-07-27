<?php
class ControllerExtensionModuleSmshareEventHandler extends Controller {


    /**
     *
     */
    public function customer_add_after_event_handler(&$route, &$args, &$output) {

        if($this->config->get('smshare_core_cfg_log')) $this->log->write("[smshare] Into `customer_add` event handler. args: " . print_r($args, true));

        $this->load->model('extension/module/smshare_core');
        $this->model_extension_module_smshare_core->notify_about_registration($args[0]);

    }


    /**
     *
     */
    public function forgotten_password_after_event_handler(&$route, &$args, &$output) {
        if($this->config->get('smshare_core_cfg_log')) $this->log->write("[smshare] Into `forgotten_password` event handler. args: " . print_r($args, true));

        $sms_extension = $this->config->get('lbt_cfg_sms_extension');
        if ($sms_extension != 'core') {
            if($this->config->get('smshare_core_cfg_log')) $this->log->write("[smshare_core] Do not send `forgotten_password` SMS because another SMS extension is selected for doing that: " . $sms_extension . ". Aborting!");
            return ;
        }


        $this->load->model('extension/module/smshare_core');
        $this->model_extension_module_smshare_core->notify_about_password_reset_link($args[0], $args[1]);

    }

}