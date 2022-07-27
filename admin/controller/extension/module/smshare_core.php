<?php
class ControllerExtensionModuleSmshareCore extends Controller {
    
	private $error = array();


    /**
     *
     */
	public function install() {

	    $defaultSettings = array(
            "smshare_core_cfg_sender_profile"   => "",
            "smshare_core_cfg_store_uuid"       => "",
            "smshare_core_cfg_log"              => true,
            "smshare_core_cfg_extension_status" => true,
            "smshare_core_cfg_use_https"        => true,

            "smshare_core_cfg_smartphone_access_token" => "",
            "smshare_core_cfg_smartphone_uuid"         => "",
            "smshare_core_cfg_gateway_provider"        => "",
            "smshare_core_cfg_api_url"                 => "",
            "smshare_core_cfg_api_http_method"         => "",
            "smshare_core_cfg_api_dest_var"            => "",
            "smshare_core_cfg_api_msg_var"             => "",
            "smshare_core_cfg_api_msg_to_unicode"      => "",
            "smshare_core_cfg_os_observers"            => array(),
            "smshare_core_cfg_gw_fields"               => array(),

            "smshare_core_cfg_notify_customer_by_sms_on_registration"    => "",
            "smshare_core_cfg_on_registration_customer_tmpls"            => array(),
            "smshare_core_cfg_notify_customer_by_sms_on_checkout"        => "",
            "smshare_core_cfg_on_checkout_customer_tmpls"                => array(),
            "smshare_core_cfg_donotsend_sms_on_checkout_coupon_keywords" => "",
            "smshare_core_cfg_donotsend_sms_on_these_checkout_statuses"  => array(),

            "smshare_core_cfg_notify_admin_by_sms_on_registration" => "",
            "smshare_core_cfg_on_registration_admin_tmpl"          => "",
            "smshare_core_cfg_notify_admin_by_sms_on_checkout"     => "",
            "smshare_core_cfg_on_checkout_admin_tmpl"              => "",
            "smshare_core_cfg_on_checkout_additional_numbers"      => "",

            "smshare_core_cfg_number_insight_status"               => false,
            "smshare_core_cfg_number_insight_format"               => "",
	    );
	    
		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting('smshare_core', $defaultSettings);


		$this->load->model('setting/event');
        // Catalog events
        $this->model_setting_event->addEvent('tv_sms_customer_add', 'catalog/model/account/customer/addCustomer/after', 'extension/module/smshare_event_handler/customer_add_after_event_handler');
        $this->model_setting_event->addEvent('tv_sms_forgotten_password', 'catalog/model/extension/module/lbt_dao/edit_code/after', 'extension/module/smshare_event_handler/forgotten_password_after_event_handler');


		// 		$this->load->model('module/smshare_core');
// 		$this->model_module_smshare_core->install();
	}

    public function uninstall() {
        $this->load->model("setting/event");
        $this->model_setting_event->deleteEventByCode('tv_sms_customer_add');
        $this->model_setting_event->deleteEventByCode('tv_sms_forgotten_password');
    }


    /**
     *
     */
	public function index() {

        //sweetalert
        $this->document->addStyle('view/javascript/smshare/lib/sweetalert2/dist/sweetalert2.css');
        $this->document->addScript('view/javascript/smshare/lib/sweetalert2/dist/sweetalert2.all.min.js');

        //ladda
        $this->document->addScript('view/javascript/smshare/lib/ladda/spin.min.js');
        $this->document->addScript('view/javascript/smshare/lib/ladda/ladda.min.js');
        $this->document->addStyle('view/javascript/smshare/lib/ladda/ladda-themeless.min.css');


        $module_route = 'extension/module/smshare_core';
        $modules_route = 'marketplace/extension';
	    
		
		$this->load->language('extension/module/smshare_core');

		
		$this->document->setTitle($this->language->get('heading_title'));
		
		//Load the settings model. You can also add any other models you want to load here.
		$this->load->model('setting/setting');
		
		//Save the settings if the user has submitted the admin form (ie if someone has pressed save).
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {

            /*
             * Data cleaning.
             */
            if (isset($_POST['smshare_core_cfg_gw_fields'])) {

                foreach ($_POST['smshare_core_cfg_gw_fields'] as $index => $api_kv) {
                    if ($api_kv['key'] == "") {
                        unset($_POST['smshare_core_cfg_gw_fields'][$index]);
                    }
                }
                $this->request->post["smshare_core_cfg_gw_fields"] = $_POST['smshare_core_cfg_gw_fields'];
            }


            /*
             * quick send sms case?
             */
            if($this->request->post['submit_btn'] === 'quick_send_sms'){
                if($this->config->get('smshare_core_cfg_log')) $this->log->write("[smshare_core] quick send SMS btn clicked");
                $this->load->library('smshare_core/smshare_core_facade');
                $this->smshare_core_facade->send_sms_or_not(array(
                    'to'                   => $this->request->post['sms_to']       ,
                    'body'                 => $this->request->post['sms_body']     ,
                    'country_code'         => ""                                   ,
                    'store_id'             => $this->config->get('config_store_id'),
                    "config_current_store" => $this->request->post                ,
                    "config_default_store" => $this->request->post                ,
                ));

                //json

                $json = array();

                $json['type'] = "success";
                $json['payload'] = "✔ SMS sent";
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($json));
                return;

            }else {
                $this->model_setting_setting->editSetting('smshare_core', $this->request->post);

                $this->session->data['success'] = $this->language->get('text_success');

                if($this->request->post["submit_btn"] === 'save-and-stay'){
                    $this->response->redirect($this->url->link($module_route, 'user_token=' . $this->session->data['user_token'], 'SSL'));
                }else{
                    $this->response->redirect($this->url->link($modules_route, 'user_token=' . $this->session->data['user_token'] . '&type=module', 'SSL'));
                }
            }

		}


        $text_strings = array(
            'heading_title',
            'text_enabled',
            'text_disabled',
            'text_home',
            'text_yes',
            'text_no',
            'smshare_entry_username',
            'smshare_entry_passwd',
            'smshare_entry_notify_customer_by_sms_on_registration',
            'smshare_entry_notify_customer_by_sms_on_registration_help',
            'smshare_entry_cstmr_reg_available_vars',
            'smshare_entry_notify_customer_by_sms_on_checkout',
            'smshare_entry_notify_customer_by_sms_on_checkout_help',
            'smshare_entry_ntfy_admin_by_sms_on_reg',
            'smshare_entry_ntfy_admin_by_sms_on_reg_help',
            'smshare_entry_notify_admin_by_sms_on_checkout',
            'smshare_entry_notify_admin_by_sms_on_checkout_help',
            'smshare_entry_notify_extra_by_sms_on_checkout',
            'smshare_entry_notify_extra_by_sms_on_checkout_help',
            'button_save',
            'button_save_and_stay',
            'button_cancel'
        );
		
		foreach ($text_strings as $text) {
			$data[$text] = $this->language->get($text);
		}
		
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}


		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], 'SSL'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$data['breadcrumbs'][] = array(
	        'href'      => $this->url->link($modules_route, 'user_token=' . $this->session->data['user_token'] . '&type=module', 'SSL'),
	        'text'      => $this->language->get('text_module'),
	        'separator' => ' :: '
   		);
   		 
   		$data['breadcrumbs'][] = array(
	        'href'      => $this->url->link($module_route, 'user_token=' . $this->session->data['user_token'] . '&type=module', 'SSL'),
	        'text'      => $this->language->get('heading_title'),
	        'separator' => ' :: '
   		);
   		 
   		
   		$data['action'] = $this->url->link($module_route, 'user_token=' . $this->session->data['user_token'] . '&type=module', 'SSL');
   		 
   		$data['cancel'] = $this->url->link($modules_route, 'user_token=' . $this->session->data['user_token'] . '&type=module', 'SSL');


   		/*
         * Inject supported languages.
         */
        $this->load->model('localisation/language');

        $data['languages'] = array();

        $supportedLanguages = $this->model_localisation_language->getLanguages();

        foreach ($supportedLanguages as $result) {
            if ($result['status']) {
                $data['languages'][] = array(
                    'name'  => $result['name'],
                    'code'  => $result['code']
                );
            }
        }




        /*
         * Inject order statuses
         */
        $this->load->model('localisation/order_status');
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();




        $config_data = array(
            "smshare_core_cfg_store_uuid",
	        "smshare_core_cfg_log",
            "smshare_core_cfg_extension_status",
            "smshare_core_cfg_use_https",
            "smshare_core_cfg_smartphone_access_token",
            "smshare_core_cfg_smartphone_uuid",

            "smshare_core_cfg_sender_profile",
            "smshare_core_cfg_gateway_provider",
            "smshare_core_cfg_api_url",
            "smshare_core_cfg_api_http_method",
            "smshare_core_cfg_api_dest_var",
            "smshare_core_cfg_api_msg_var",
            "smshare_core_cfg_api_msg_to_unicode",
            "smshare_core_cfg_gw_fields"         ,

            "smshare_core_cfg_basic_http_auth_status",
            "smshare_core_cfg_basic_http_auth_username",
            "smshare_core_cfg_basic_http_auth_password",

            //
            "smshare_core_cfg_notify_customer_by_sms_on_registration",
            "smshare_core_cfg_on_registration_customer_tmpls",
            "smshare_core_cfg_notify_customer_by_sms_on_checkout",
            "smshare_core_cfg_on_checkout_customer_tmpls",
            "smshare_core_cfg_donotsend_sms_on_checkout_coupon_keywords",
            "smshare_core_cfg_donotsend_sms_on_these_checkout_statuses",

            "smshare_core_cfg_os_observers",

            //
            "smshare_core_cfg_notify_admin_by_sms_on_registration",
            "smshare_core_cfg_on_registration_admin_tmpl"         ,
            "smshare_core_cfg_notify_admin_by_sms_on_checkout"    ,
            "smshare_core_cfg_on_checkout_admin_tmpl"             ,
            "smshare_core_cfg_on_checkout_additional_numbers"     ,

            "smshare_core_cfg_number_insight_status",
            "smshare_core_cfg_number_insight_format",

		);
		
		foreach ($config_data as $conf) {
		    if (isset($this->request->post[$conf])) {
		        $data[$conf] = $this->request->post[$conf];
		    } else {
		        $data[$conf] = $this->config->get($conf);
		    }


            if($conf === 'smshare_core_cfg_api_kv'){
                $data[$conf][] = array(
                    "key" => "", "val" => ""
                );
            }


            //inject template item
		    if($conf === 'smshare_core_cfg_os_observers'){
		        $data[$conf][] = array(
                    "order_status" => "", "template" => "", "encode" => ""
                );
            }

		    if($conf === 'smshare_core_cfg_gw_fields'){
		        $data[$conf][] = array(
                    "key" => "", "val" => ""
                );
            }

		}


		//will be overridden by vqmod
        $data['show_sms_product_loop_purchase_link'] = true;


        $data['header']      = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer']      = $this->load->controller('common/footer');

        //hook_32bbb : inject any other fieldset (basic auth support)

        $data['fragment_smshare_controls'] = $this->load->view('extension/module/smshare/smshare_controls', $data);
        $data['fragment_quicktest_popup'] = $this->load->view('extension/module/smshare/smshare_quicktest_popup_vue', $data);
//        $data['fragment_quicktest_popup'] = $this->load->view('extension/module/smshare/smshare_quicktest_popup', $data);

        $data['tab_sending_methods_android'] = $this->load->view('extension/module/smshare/smshare_tab_sending_methods_android', $data);
        $data['tab_sending_methods_gateway'] = $this->load->view('extension/module/smshare/smshare_tab_sending_methods_gateway', $data);
        $data['tab_sending_methods']         = $this->load->view('extension/module/smshare/smshare_tab_sending_methods', $data);

        $data['fragment_sms_templates_customer'] = $this->load->view('extension/module/smshare/smshare_tab_sms_templates_customer_sms', $data);
        $data['fragment_sms_templates_admin']    = $this->load->view('extension/module/smshare/smshare_tab_sms_templates_admin_sms', $data);
        $data['fragment_variables_general']      = $this->load->view('extension/module/smshare/smshare_tab_sms_templates_variables_general', $data);
        $data['fragment_variables_products']     = $this->load->view('extension/module/smshare/smshare_tab_sms_templates_variables_products', $data);
        $data['tab_sms_templates']               = $this->load->view('extension/module/smshare/smshare_tab_sms_templates', $data);
        $data['tab_number_insight']              = $this->load->view('extension/module/smshare/smshare_tab_number_insight', $data);
        $data['tab_troubleshooting']             = $this->load->view('extension/module/smshare/smshare_tab_troubleshooting', $data);

        $this->response->setOutput($this->load->view('extension/module/smshare/smshare_core', $data));
	}



    /**
     *
     */
    public function sendsms() {

        $orderId = $this->request->get['order_id'];
        $template = $this->request->post['message'];

        $this->log->write('[smshare] Sending message to order_id: ' . $orderId . ' message: ' . $template);

        $this->load->model('sale/order');
        $order_info = $this->model_sale_order->getOrder($orderId);


        /*
         * country_code
         */
        //$country_code = $this->smshare_core_dao->get_country_iso_code_2($data['address_id']);
        $country_code = isset($order_info['shipping_iso_code_2']) ? $order_info['shipping_iso_code_2'] : $order_info['payment_iso_code_2'];

        /*
         *
         */
        $this->load->model('setting/setting');
        $config_current_store = $this->model_setting_setting->getSetting('smshare_core', $order_info['store_id']);
        $config_default_store = $this->model_setting_setting->getSetting('smshare_core', 0);



        $this->load->library('smshare_core/smshare_core_dao');
        $order_status = $this->smshare_core_dao->get_order_status_name($order_info['language_id'], $order_info['order_status_id']);

        $language = new Language($order_info['language_code']);
        $language->load($order_info['language_code']);


        /*
         *
         */
        $this->load->library('smshare_core/smshare_core_tag');
        $sms_body = $this->smshare_core_tag->merge(array(
            "order_info"   => $order_info,
            "order_status" => $order_status,
            "comment"      => "",
            "language"     => $language,
            "template"     => $template
        ));




        $this->load->library('smshare_core/smshare_core_facade');
        $this->smshare_core_facade->send_sms_or_not(array(
            'to'                   => $order_info['telephone']             ,
            'body'                 => $sms_body                            ,
            'country_code'         => $country_code                        ,
            'store_id'             => $this->config->get('config_store_id'),
            "config_current_store" => $config_current_store                ,
            "config_default_store" => $config_default_store                ,
        ));


        $json = array();

        $json['success'] = "✔ SMS sent";
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));

    }

}