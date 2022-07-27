<?php
/**
 * TODO merge with core oc23
 */
class ModelExtensionModuleSmshareCoreNomod extends Model {


    /**
     *
     */
    public function get_my_checkbox($order_info, $css_class_left = "col-sm-2", $css_class_right = "col-sm-10") {

        $this->load->language('extension/module/smshare_core_safe');
        $data['smshare_entry_notify_by_sms_label'] = $this->language->get('smshare_entry_notify_by_sms_label');

        $data['css_class_left']  = $css_class_left;
        $data['css_class_right'] = $css_class_right;
        $data['extension_identifier'] = "smshare core extension";

        $data['firstname'] = $order_info['firstname'];
        $data['lastname']  = $order_info['lastname'];
        $data['telephone']  = $order_info['telephone'];


        return $this->load->view('extension/module/smshare/fragments/pws__notify_by_sms__checkbox', $data);
    }


    /**
     *
     */
    public function build_request_params_to_be_injected_in_the_url_of_the_order_status_update_ajax_request(){

        /*
         *  + '&notify_by_sms=' + ($('input[name=\'notify_by_sms\']').prop('checked') ? 1 : 0) + '&notify=' +
         */
        $params = "'&pws_notify_by_sms=' + ($('input[name=\'pws_notify_by_sms\']').prop('checked') ? 1 : 0)" ;

        return $params;
    }

}