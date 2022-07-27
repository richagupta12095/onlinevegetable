<?php
class ModelExtensionModuleSmshareCore extends Model {


    /**
     *
     */
    public function notify_about_checkout($args) {


        $extension_status = $this->config->get('smshare_core_cfg_extension_status');
        if(! $extension_status){
            if($this->config->get('smshare_core_cfg_log')) $this->log->write("[smshare_core] Extension status is disabled, no SMS on checkout. Aborting!");
            return;
        }

        if(! $this->config->get('smshare_core_cfg_notify_customer_by_sms_on_checkout') && ! $this->config->get('smshare_core_cfg_notify_admin_by_sms_on_checkout') ){
            if($this->config->get('smshare_core_cfg_log')) $this->log->write("[smshare_core] neither customer, nor admin notification is enabled for checkout. Aborting!");
            return;
        }


        $order_info      = $this->formatTotal($args['order_info']);
        $order_status_id = $args['order_status_id'];


        /*
         * honor the Do-not-send statuses
         */
        $donotsend_sms_on_these_checkout_statuses = $this->config->get('smshare_core_cfg_donotsend_sms_on_these_checkout_statuses');
//        if($this->config->get('smshare_core_cfg_log')) $this->log->write("[smshare_core] donotsend_sms_on_these_checkout_statuses: " . print_r($donotsend_sms_on_these_checkout_statuses, true));
        if(! empty($donotsend_sms_on_these_checkout_statuses)){
//            if($this->config->get('smshare_core_cfg_log')) $this->log->write("[smshare_core] donotsent statuses not empty and orderstatus_id: " . $order_status_id);
            foreach ($donotsend_sms_on_these_checkout_statuses as $donotsend_sms_on_these_checkout_status){

//                if($this->config->get('smshare_core_cfg_log')) $this->log->write("[smshare_core] checking against: " . $donotsend_sms_on_these_checkout_status);
                if($order_status_id == $donotsend_sms_on_these_checkout_status){
                    if($this->config->get('smshare_core_cfg_log')) $this->log->write("[smshare_core] Order status does not allow sending SMS on checkout. Aborting!");
                    return;
                }
            }
        }


        /*
         *
         */
        $this->load->library('smshare_core/smshare_core_dao');
        $order_status = $this->smshare_core_dao->get_order_status_name($order_info['language_id'], $order_status_id);


        /*
         *
         */
        // Load the language for any mails that might be required to be sent out
        $language = new Language($order_info['language_code']);
        $language->load($order_info['language_code']);
        //$language->load('mail/order_add');


        /*
         * country_code
         */
        //$country_code = $this->smshare_core_dao->get_country_iso_code_2($data['address_id']);
        $country_code = isset($order_info['shipping_iso_code_2']) ? $order_info['shipping_iso_code_2'] : $order_info['payment_iso_code_2'];


        /*
         * I suppose `config_store_id` is being initialized by opencart earlier for each request.
         * Backport here also: 16416
         */
        $store_id = $this->config->get('config_store_id');

        /*
         *
         */
        $this->load->model('setting/setting');
        $config_current_store = $this->model_setting_setting->getSetting('smshare_core', $store_id);
        $config_default_store = $this->model_setting_setting->getSetting('smshare_core', 0);


        /*
         *
         */
        if($this->config->get('smshare_core_cfg_notify_customer_by_sms_on_checkout')) {

            /*
             *
             */
            $language_code = $order_info['language_code'];

            /*
             *
             */
            $templates = $this->config->get('smshare_core_cfg_on_checkout_customer_tmpls');
            $template  = $templates[$language_code];

            //hook_303b8 : let our special vqmod to use templates from order status templates. Custom hack done for info@dev-here.com
            //~

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


            /*
             *
             */
            $this->load->library('smshare_core/smshare_core_facade');
            $this->smshare_core_facade->send_sms_or_not(array(
                'to'                   => $order_info['telephone']             ,
                'body'                 => $sms_body                            ,
                'country_code'         => $country_code                        ,
                'store_id'             => $this->config->get('config_store_id'),
                "config_current_store" => $config_current_store                ,
                "config_default_store" => $config_default_store                ,
            ));

        }


        if($this->config->get('smshare_core_cfg_notify_admin_by_sms_on_checkout') || $this->config->get('smshare_core_cfg_on_checkout_additional_numbers')) {

            //No translation for admin template
            $template = $this->config->get('smshare_core_cfg_on_checkout_admin_tmpl');

            $this->load->library('smshare_core/smshare_core_tag');
            $sms_body = $this->smshare_core_tag->merge(array(
                "order_info"   => $order_info,
                "order_status" => $order_status,
                "comment"      => "",
                "language"     => $language,
                "template"     => $template
            ));


            $this->load->library('smshare_core/smshare_core_facade');

            if($this->config->get('smshare_core_cfg_notify_admin_by_sms_on_checkout')){
                $this->smshare_core_facade->send_sms_or_not(array(
                    'to'                   => $this->config->get('config_telephone'),
                    'body'                 => $sms_body                             ,
                    'country_code'         => $country_code                         ,
                    'store_id'             => $this->config->get('config_store_id') ,
                    "config_current_store" => $config_current_store                 ,
                    "config_default_store" => $config_default_store                 ,
                ));
            }

            if($this->config->get('smshare_core_cfg_on_checkout_additional_numbers')){

                $additionalNumbers = explode(",", $this->config->get('smshare_core_cfg_on_checkout_additional_numbers'));

                foreach ($additionalNumbers as $additionalNumber) {
                    $this->smshare_core_facade->send_sms_or_not(array(
                        'to'                   => $additionalNumber                     ,
                        'body'                 => $sms_body                             ,
                        'country_code'         => $country_code                         ,
                        'store_id'             => $this->config->get('config_store_id') ,
                        "config_current_store" => $config_current_store                 ,
                        "config_default_store" => $config_default_store                 ,
                    ));
                }

            }
        }

    }


    /**
     *
     */
    private function get_observer($order_status_id){
        /*
         * fast exit
         */
        $smshare_core_cfg_os_observers = $this->config->get('smshare_core_cfg_os_observers');
        if(empty($smshare_core_cfg_os_observers)){
            if($this->config->get('smshare_core_cfg_log')) $this->log->write("[pws] No observers, no sms. Aborting!");
            return null;
        }

        foreach ($smshare_core_cfg_os_observers as $smshare_core_cfg_os_observer){
            if($smshare_core_cfg_os_observer['order_status'] === $order_status_id){
                return $smshare_core_cfg_os_observer;
            }
        }

        return null;
    }


    /**
     *
     */
    public function notify_about_order_status_update($args) {

        /*
         * fast exit
         */
        $extension_status = $this->config->get('smshare_core_cfg_extension_status');
        if(! $extension_status){
            if($this->config->get('smshare_core_cfg_log')) $this->log->write("[smshare_core] Extension status is disabled, no SMS on status update. Aborting!");
            return;
        }

        /*
         * fast exit
         */
        $new_status_id = $args['order_status_id'];
        $observer = $this->get_observer($new_status_id);
        if(! $observer){
            if($this->config->get('smshare_core_cfg_log')) $this->log->write("[pws] No observer found for order status with id: " . $new_status_id . " Aborting!");
            return;
        }

        /*
         *
         */
        $comment       = $args['comment'];
        $tracking_info = $args['powertrack_info'];
        $order_info    = $this->formatTotal($args['order_info']);


        /*
         *
         */
        $this->load->library('smshare_core/smshare_core_dao');
        $order_status = $this->smshare_core_dao->get_order_status_name($order_info['language_id'], $new_status_id);


        /*
         *
         */
        // Load the language for any mails that might be required to be sent out
        $language = new Language($order_info['language_code']);
        $language->load($order_info['language_code']);
        //$language->load('mail/order_add');



        /*
         * country_code
         */
        //$country_code = $this->smshare_core_dao->get_country_iso_code_2($data['address_id']);
        $country_code = isset($order_info['shipping_iso_code_2']) ? $order_info['shipping_iso_code_2'] : $order_info['payment_iso_code_2'];


        /*
         * I suppose `config_store_id` is being initialized by opencart earlier for each request.
         * Backport here also: 16416
         */
        $store_id = $this->config->get('config_store_id');

        /*
         *
         */
        $this->load->model('setting/setting');
        $config_current_store = $this->model_setting_setting->getSetting('smshare_core', $store_id);
        $config_default_store = $this->model_setting_setting->getSetting('smshare_core', 0);


        /*
         *
         */
        $language_code = $order_info['language_code'];

        /*
         *
         */
        $template = $observer['template'][$language_code];


        /*
         *
         */
        $this->load->library('smshare_core/smshare_core_tag');
        $sms_body = $this->smshare_core_tag->merge(array(
            "order_info"   => $order_info,
            "order_status" => $order_status,
            "comment"      => $comment,
            "language"     => $language,
            "template"     => $template
        ));



        //powertrack variables
        //hook_4588a


        $this->load->library('smshare_core/smshare_core_facade');
        $this->smshare_core_facade->send_sms_or_not(array(
            'to'                   => $order_info['telephone']             ,
            'body'                 => $sms_body                            ,
            'country_code'         => $country_code                        ,
            'store_id'             => $this->config->get('config_store_id'),
            "config_current_store" => $config_current_store                ,
            "config_default_store" => $config_default_store                ,
        ));
    }


    public function notify_about_password_reset_link($telephone, $code) {

        $extension_status = $this->config->get('smshare_core_cfg_extension_status');
        if(! $extension_status){
            if($this->config->get('smshare_core_cfg_log')) $this->log->write("[pws] Extension disabled. Do not send SMS to reset password. Aborting!");
            return;
        }

        // borrowed from catalog/controller/mail/forgotten.php
        $reset_link = str_replace('&amp;', '&', $this->url->link('account/reset', 'code=' . $code, true));

        $find = array(
            '{reset_link}'  ,
        );

        $replace = array(
            'reset_link'   => $reset_link,
        );



        /*
        * I suppose `config_store_id` is being initialized by opencart earlier for each request.
        * backport here also: 16416
        */
        $store_id = $this->config->get('config_store_id');

        /*
         *
         */
        $this->load->model('setting/setting');
        $config_current_store = $this->model_setting_setting->getSetting('smshare_core', $store_id);
        $config_default_store = $this->model_setting_setting->getSetting('smshare_core', 0);


        /*
         * country_code
         */
        //$country_code = $this->component_smshare_dao->get_country_iso_code_2($data['address_id']);
        $country_code = "";  //it looks like it is impossible to get address at this time. FFE is a must.



        /*
         * Template by selected language
         */
        $this->load->model('localisation/language');
        //$row = $this->model_localisation_language->getLanguage($post_data['language_id']);
        $row = $this->model_localisation_language->getLanguage($this->config->get('config_language_id'));
        $language_code = $row['code'];

        /*
         *
         */
        $sms_templates = $this->config->get('lbt_cfg_forgotten_password_by_sms_tmpls');
        $message       = $sms_templates[$language_code];
        $sms_body      = str_replace($find, $replace, $message);

        $this->load->library('smshare_core/smshare_core_facade');
        $this->smshare_core_facade->send_sms_or_not(array(
            'to'                   => $telephone                           ,
            'body'                 => $sms_body                            ,
            'country_code'         => $country_code                        ,
            'store_id'             => $this->config->get('config_store_id'),
            "config_current_store" => $config_current_store                ,
            "config_default_store" => $config_default_store                ,
        ));


    }

    /**
     *
     */
    public function notify_about_registration($data) {

        $extension_status = $this->config->get('smshare_core_cfg_extension_status');
        if(! $extension_status){
            if($this->config->get('smshare_core_cfg_log')) $this->log->write("[pws] Extension disabled. Do not send SMS at registration. Aborting!");
            return;
        }

        /*
         * fast exit, social login may lack the telephone info
         */
        if (! $data['telephone']) {
            if($this->config->get('smshare_core_cfg_log')) $this->log->write("[pws] Telephone is blank. Aborting!");
            return;
        }

        //$this->load->component('smshare/dao');
        //$this->load->component('smshare/core');
        //$this->load->component('smshare/router');

        $find = array(
            '{firstname}'  ,
            '{lastname}'   ,
            '{phonenumber}',
            '{email}'      ,
            '{password}'   ,
            '{store_url}'  ,
        );

        $replace = array(
            'firstname'   => $data['firstname'],
            'lastname'    => $data['lastname'] ,
            'phonenumber' => $data['telephone'],
            'email'       => $data['email']    ,
            'password'    => $data['password'] ,
            'store_url'   => HTTPS_SERVER      ,
        );


        /*
         * I suppose `config_store_id` is being initialized by opencart earlier for each request.
         * backport here also: 16416
         */
        $store_id = $this->config->get('config_store_id');

        /*
         *
         */
        $this->load->model('setting/setting');
        $config_current_store = $this->model_setting_setting->getSetting('smshare_core', $store_id);
        $config_default_store = $this->model_setting_setting->getSetting('smshare_core', 0);


        /*
         * country_code
         */
        //$country_code = $this->component_smshare_dao->get_country_iso_code_2($data['address_id']);
        $country_code = "";  //it looks like it is impossible to get address at this time. FFE is a must.


        /*
         * Send SMS to customer
         */
        if($this->config->get('smshare_core_cfg_notify_customer_by_sms_on_registration')) {

            /*
             * template by preferred language setting.
             */
            $this->load->model('localisation/language');
            //$row = $this->model_localisation_language->getLanguage($post_data['language_id']);
            $row = $this->model_localisation_language->getLanguage($this->config->get('config_language_id'));
            $language_code = $row['code'];

            /*
             *
             */
            $sms_templates = $this->config->get('smshare_core_cfg_on_registration_customer_tmpls');
            $message       = $sms_templates[$language_code];
            $sms_body      = str_replace($find, $replace, $message);

            $this->load->library('smshare_core/smshare_core_facade');
            $this->smshare_core_facade->send_sms_or_not(array(
                'to'                   => $data['telephone']                   ,
                'body'                 => $sms_body                            ,
                'country_code'         => $country_code                        ,
                'store_id'             => $this->config->get('config_store_id'),
                "config_current_store" => $config_current_store                ,
                "config_default_store" => $config_default_store                ,
            ));
        }

        /*
         * Send sms to store owner on registration
         */
        if ($this->config->get('smshare_core_cfg_notify_admin_by_sms_on_registration')){

            //No translation for admin template
            $sms_template = $this->config->get('smshare_core_cfg_on_registration_admin_tmpl');
            $sms_body = str_replace($find, $replace, $sms_template);

            $this->load->library('smshare_core/smshare_core_facade');
            $this->smshare_core_facade->send_sms_or_not(array(
                'to'                   => $this->config->get('config_telephone'),
                'body'                 => $sms_body                             ,
                'country_code'         => $country_code                         ,
                'store_id'             => $this->config->get('config_store_id') ,
                "config_current_store" => $config_current_store                 ,
                "config_default_store" => $config_default_store                 ,
            ));
        }
    }




    public function formatTotal($order_info){
        $order_info['total'] = $this->currency->format($order_info['total'], $this->config->get('config_currency'));
        return $order_info;
    }



    public function get_language_code($lang_id){

        $this->load->model('localisation/language');
        $row = $this->model_localisation_language->getLanguage($lang_id);
        if($row) {
            return $row['code'];
        }

        //why would we come here?
        $lang = $this->config->get('config_language');
        if ($this->config->get('smshare_core_cfg_log')) $this->log->write("[smshare] preferred language is default one: " . $lang);
        return $lang;
    }




}