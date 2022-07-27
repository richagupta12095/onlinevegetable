<?php

require_once DIR_SYSTEM . 'library/kbgoogleshopping/src/Google/Client.php';
require_once(DIR_SYSTEM . 'library/kbgoogleshopping/GSSingleAction.php');
require_once(DIR_SYSTEM . 'library/kbgoogleshopping/GSFeedSchedule.php');
require_once(DIR_SYSTEM . 'library/kbgoogleshopping/GSFeed.php');

class ControllerExtensionModuleGoogleShopping extends Controller
{

    private $error = array();
    private $session_token_key = 'token';
    private $session_token = '';
    private $module_path = '';

    public function __construct($registry)
    {
        parent::__construct($registry);

        if (VERSION >= 2.0 && VERSION <= 2.2) {
            $this->session_token_key = 'token';
            $this->session_token = $this->session->data['token'];

            /* BreadCrumb Path */
            $this->extension_path = 'extension/module';

            /* Main Module Path */
            $this->module_path = 'module';
        } else if (VERSION < 3.0) {
            $this->session_token_key = 'token';
            $this->session_token = $this->session->data['token'];

            $this->extension_path = 'extension/extension';
            $this->module_path = 'extension/module';
        } else {
            $this->session_token_key = 'user_token';
            $this->session_token = $this->session->data['user_token'];

            $this->extension_path = 'marketplace/extension';
            $this->module_path = 'extension/module';
        }
    }

    public function install()
    {
        $this->load->model('setting/googleshopping');
        $this->model_setting_googleshopping->install();
    }

    public function uninstall()
    {
        $this->load->model('setting/googleshopping');
        $this->model_setting_googleshopping->uninstall();
    }

    public function index()
    {
        $this->load->language($this->module_path . '/googleshopping');
        $this->document->setTitle($this->language->get('heading_title_main'));

        $this->load->model('setting/setting');
        $this->load->model('setting/googleshopping');

        if (isset($this->request->get['store_id'])) {
            $store_id = $this->request->get['store_id'];
        } else {
            $store_id = 0;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateConnectionSettings()) {
            $this->request->post['google_connection_settings'] = $this->request->post['googleshopping'];
            unset($this->request->post['googleshopping']);
            $this->model_setting_googleshopping->editSetting('google_connection_settings', $this->request->post, $store_id);
            $this->session->data['success'] = $this->language->get('googleshopping_text_success');
            $this->response->redirect($this->url->link($this->module_path . '/googleshopping/connect', $this->session_token_key . '=' . $this->session_token, true));
            die();
        }

        /* Post request for saving data in data base */
        $data['heading_title'] = $this->language->get('heading_title');
        $data['heading_title_main'] = $this->language->get('heading_title_main');


        //Right menu cookies check
        if (isset($this->request->cookie['rightMenu'])) {
            $data['rightMenu'] = true;
        } else {
            $data['rightMenu'] = false;
        }

        //Bread crumbs
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', $this->session_token_key . '=' . $this->session_token, true),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link($this->extension_path, $this->session_token_key . '=' . $this->session_token, true),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_main'),
            'href' => $this->url->link($this->module_path . '/googleshopping', $this->session_token_key . '=' . $this->session_token, true),
            'separator' => ' :: '
        );

        $account_error = "";
        $account_connection = "";

        $settings = $this->model_setting_googleshopping->getSetting('google_connection_settings', $store_id);
        if (!empty($settings)) {
            $gs_config = $settings['google_connection_settings']['connection'];
            $settings_token = $this->model_setting_googleshopping->getSetting('gs_token_info', $store_id);
            $settings_refresh_token = $this->model_setting_googleshopping->getSetting('gs_refresh_token', $store_id);
            $gs_config['token'] = $this->session_token;
            $gs_config['refresh_token'] = $settings_refresh_token['gs_refresh_token'];
            $gs_config['token_info'] = json_encode($settings_token['gs_token_info']);
            $gs_config['store_id'] = $store_id;
            if ($gs_config['refresh_token'] != "") {
                try {
                    $productList = array();
                    $obj = new GSSingleAction($gs_config);
                    $obj->getProductList($productList);
                    $account_connection = $this->language->get('account_connection');
                } catch (Exception $e) {
                    $account_error = $this->language->get('account_connection_error') . "<br/>" . $e->getMessage();
                }
            } else {
                $account_error = $this->language->get('refresh_token_error');
            }
        }
        $data['account_error'] = $account_error;
        $data['account_connection'] = $account_connection;


        $data['text_edit'] = $this->language->get('text_edit');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_save_and_stay'] = $this->language->get('button_save_and_stay');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_add_module'] = $this->language->get('button_add_module');
        $data['button_remove'] = $this->language->get('button_remove');
        $data['tab_module'] = $this->language->get('tab_module');
        $data['redirect_url'] = HTTPS_CATALOG . 'index.php?route=googleshopping/cron/refresh&secure_key=axbto4ynms';
        $data['configure_url_text'] = $this->language->get('configure_url_text');
        $data['USER_MANUAL_LINK'] = str_replace("{USER_MANUAL_LINK}", "http://www.knowband.com/blog/user-manual/opencart-google-shopping/", $this->language->get('USER_MANUAL_LINK'));

        // Connection Settings tab & info
        $data['text_app_name'] = $this->language->get('text_app_name');
        $data['text_googleshopping_client_id'] = $this->language->get('text_googleshopping_client_id');
        $data['text_googleshopping_merchant_id'] = $this->language->get('text_googleshopping_merchant_id');
        $data['text_googleshopping_client_secret'] = $this->language->get('text_googleshopping_client_secret');
        $data['text_edit_connection'] = $this->language->get('text_edit_connection');

        $data['action'] = $this->url->link($this->module_path . '/googleshopping', $this->session_token_key . '=' . $this->session_token, true);
        $data['route'] = $this->url->link($this->module_path . '/googleshopping', $this->session_token_key . '=' . $this->session_token, true);
        $data['cancel'] = $this->url->link($this->extension_path, $this->session_token_key . '=' . $this->session_token, true);
        $data['token'] = $this->session_token;

        if ($this->model_setting_googleshopping->getSetting('google_connection_settings', $store_id)) {
            $settings = $this->model_setting_googleshopping->getSetting('google_connection_settings', $store_id);
            $data['googleshopping'] = $settings['google_connection_settings'];
        } else {
            $data['googleshopping'] = $this->request->post;
        }
        if (isset($this->request->post['googleshopping']['connection']['app_name'])) {
            $data['googleshopping']['connection']['app_name'] = $this->request->post['googleshopping']['connection']['app_name'];
        }
        if (isset($this->request->post['googleshopping']['connection']['client_id'])) {
            $data['googleshopping']['connection']['client_id'] = $this->request->post['googleshopping']['connection']['client_id'];
        }
        if (isset($this->request->post['googleshopping']['connection']['client_secret'])) {
            $data['googleshopping']['connection']['client_secret'] = $this->request->post['googleshopping']['connection']['client_secret'];
        }
        if (isset($this->request->post['googleshopping']['connection']['merchant_id'])) {
            $data['googleshopping']['connection']['merchant_id'] = $this->request->post['googleshopping']['connection']['merchant_id'];
        }

        if (isset($this->error['googleshopping_app_name'])) {
            $data['error_googleshopping_app_name'] = $this->error['googleshopping_app_name'];
        } else {
            $data['error_googleshopping_app_name'] = '';
        }
        if (isset($this->error['googleshopping_client_id'])) {
            $data['error_googleshopping_client_id'] = $this->error['googleshopping_client_id'];
        } else {
            $data['error_googleshopping_client_id'] = '';
        }
        if (isset($this->error['googleshopping_client_secret'])) {
            $data['error_googleshopping_client_secret'] = $this->error['googleshopping_client_secret'];
        } else {
            $data['error_googleshopping_client_secret'] = '';
        }

        if (isset($this->error['googleshopping_merchant_id'])) {
            $data['error_googleshopping_merchant_id'] = $this->error['googleshopping_merchant_id'];
        } else {
            $data['error_googleshopping_merchant_id'] = '';
        }


        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }
        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else {
            $data['error'] = '';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $data['googleshopping_common'] = $this->load->controller($this->module_path . '/googleshopping/common');
        if (!is_writable(DIR_IMAGE . 'googleshopping/feeds/')) {
            $data['error_warning'] = $this->language->get('error_feed_upload');
            if (VERSION >= '2.2.0.0') {
                $this->response->setOutput($this->load->view($this->module_path . '/kbgoogleshopping/googleshopping_permission_issue', $data));
            } else {
                $this->response->setOutput($this->load->view($this->module_path . '/kbgoogleshopping/googleshopping_permission_issue.tpl', $data));
            }
        } else {
            if (VERSION >= '2.2.0.0') {
                $this->response->setOutput($this->load->view($this->module_path . '/kbgoogleshopping/googleshopping', $data));
            } else {
                $this->response->setOutput($this->load->view($this->module_path . '/kbgoogleshopping/googleshopping.tpl', $data));
            }
        }
    }

    public function common($data = array())
    {
        $this->load->language($this->module_path . '/googleshopping');

        $data['text_gs'] = $this->language->get('text_gs');
        $data['text_cs'] = $this->language->get('text_cs');
        $data['text_pm'] = $this->language->get('text_pm');
        $data['text_fm'] = $this->language->get('text_fm');
        $data['text_st'] = $this->language->get('text_st');
        $data['text_ste'] = $this->language->get('text_ste');
        $data['text_pl'] = $this->language->get('text_pl');
        $data['text_os'] = $this->language->get('text_os');
        $data['text_ol'] = $this->language->get('text_ol');
        $data['text_sy'] = $this->language->get('text_sy');
        $data['text_al'] = $this->language->get('text_al');
        $data['text_support'] = $this->language->get('text_support');

        //links
        $data['connection_settings'] = $this->url->link($this->module_path . '/googleshopping', $this->session_token_key . '=' . $this->session_token, true);
        $data['general_settings'] = $this->url->link($this->module_path . '/googleshopping/generalSettings', $this->session_token_key . '=' . $this->session_token, true);
        $data['profile_management'] = $this->url->link($this->module_path . '/googleshopping/profileManagement', $this->session_token_key . '=' . $this->session_token, true);
        $data['feed_management'] = $this->url->link($this->module_path . '/googleshopping/feedManagement', $this->session_token_key . '=' . $this->session_token, true);
        $data['product_listing'] = $this->url->link($this->module_path . '/googleshopping/productListing', $this->session_token_key . '=' . $this->session_token, true);
        $data['synchronization'] = $this->url->link($this->module_path . '/googleshopping/synchronization', $this->session_token_key . '=' . $this->session_token, true);
        $data['audit_log'] = $this->url->link($this->module_path . '/googleshopping/auditLog', $this->session_token_key . '=' . $this->session_token, true);
        $data['support'] = $this->url->link($this->module_path . '/googleshopping/support', $this->session_token_key . '=' . $this->session_token, true);
        if (VERSION >= '2.2.0.0') {
            return $this->load->view($this->module_path . '/kbgoogleshopping/googleshopping_common', $data);
        } else {
            return $this->load->view($this->module_path . '/kbgoogleshopping/googleshopping_common.tpl', $data);
        }
    }

    public function connect()
    {
        $this->load->language($this->module_path . '/googleshopping');

        $data['heading_title'] = $this->language->get('heading_title');
        $data['heading_title_main'] = $this->language->get('heading_title_main');


        //Right menu cookies check
        if (isset($this->request->cookie['rightMenu'])) {
            $data['rightMenu'] = true;
        } else {
            $data['rightMenu'] = false;
        }

        //Bread crumbs
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', $this->session_token_key . '=' . $this->session_token, true),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link($this->extension_path, $this->session_token_key . '=' . $this->session_token, true),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_main'),
            'href' => $this->url->link($this->module_path . '/googleshopping', $this->session_token_key . '=' . $this->session_token, true),
            'separator' => ' :: '
        );

        $data['text_edit'] = $this->language->get('text_edit');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['cancel'] = $this->url->link($this->module_path . '/googleshopping', $this->session_token_key . '=' . $this->session_token, true);
        $data['configure_google_text'] = $this->language->get('configure_google_text');
        $data['authenticate_account_text'] = $this->language->get('authenticate_account_text');
        $data['catalog_url'] = HTTPS_CATALOG;
        $data['currentURL'] = base64_encode($this->url->link($this->module_path . '/googleshopping', $this->session_token_key . '=' . $this->session_token, true));

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }
        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else {
            $data['error'] = '';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        if (VERSION >= '2.2.0.0') {
            $this->response->setOutput($this->load->view($this->module_path . '/kbgoogleshopping/googleshopping_connection_settings', $data));
        } else {
            $this->response->setOutput($this->load->view($this->module_path . '/kbgoogleshopping/googleshopping_connection_settings.tpl', $data));
        }
    }

    public function generalSettings()
    {
        $this->load->language($this->module_path . '/googleshopping');

        $this->document->setTitle($this->language->get('heading_title_main'));

        $this->load->model('setting/setting');
        $this->load->model('setting/googleshopping');

        if (isset($this->request->get['store_id'])) {
            $store_id = $this->request->get['store_id'];
        } else {
            $store_id = 0;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateGeneralSettings()) {

            $this->request->post['google_general_settings'] = $this->request->post['googleshopping'];
            unset($this->request->post['googleshopping']);
            
            if(isset($this->request->post['google_general_settings']['general']['enable']) && $this->request->post['google_general_settings']['general']['enable'] == 1) {
                $enable_status['module_googleshopping_status'] = '1';
            } else {
                $enable_status['module_googleshopping_status'] = '0';
            }
            
            $this->model_setting_setting->editSetting('module_googleshopping', $enable_status, $store_id);
            
            $this->session->data['success'] = $this->language->get('googleshopping_text_success');
            $this->model_setting_googleshopping->editSetting('google_general_settings', $this->request->post, $store_id);
            if (!isset($this->request->post['save'])) {
                $this->response->redirect($this->url->link($this->module_path . '/googleshopping/generalSettings', $this->session_token_key . '=' . $this->session_token, true));
                die();
            } else if (!isset($this->session_token)) {
                $this->response->redirect($this->url->link($this->module_path . '/googleshopping/generalSettings', $this->session_token_key . '=' . $this->session_token, true));
                die();
            }
        }
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', $this->session_token_key . '=' . $this->session_token, true),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link($this->extension_path, $this->session_token_key . '=' . $this->session_token, true),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_main'),
            'href' => $this->url->link($this->module_path . '/googleshopping', $this->session_token_key . '=' . $this->session_token, true),
            'separator' => ' :: '
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_general'),
            'href' => $this->url->link($this->module_path . '/googleshopping/generalSettings', $this->session_token_key . '=' . $this->session_token, true),
            'separator' => ' :: '
        );
        //links

        $data['general_settings'] = $this->url->link($this->module_path . '/googleshopping/generalSettings', $this->session_token_key . '=' . $this->session_token, true);
        $data['action'] = $this->url->link($this->module_path . '/googleshopping/generalSettings', $this->session_token_key . '=' . $this->session_token, true);
        $data['route'] = $this->url->link($this->module_path . '/googleshopping/generalSettings', $this->session_token_key . '=' . $this->session_token, true);
        $data['cancel'] = $this->url->link($this->module_path . '/googleshopping', $this->session_token_key . '=' . $this->session_token, true);

        $data['token'] = $this->session_token;

        $data['heading_title'] = $this->language->get('heading_title');
        $data['heading_title_main'] = $this->language->get('heading_title_main');


        // General Settings tab & info
        $data['text_general_enable'] = $this->language->get('text_general_enable');
        $data['text_general_image'] = $this->language->get('text_general_image');
        $data['text_home_default'] = $this->language->get('text_home_default');
        $data['text_large_default'] = $this->language->get('text_large_default');
        $data['text_out_of_stock'] = $this->language->get('text_out_of_stock');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_googleshopping_product_price'] = $this->language->get('text_googleshopping_product_price');
        $data['text_no_upc'] = $this->language->get('text_no_upc');
        $data['text_sync_type'] = $this->language->get('text_sync_type');
        $data['text_api'] = $this->language->get('text_api');
        $data['text_feed'] = $this->language->get('text_feed');
        $data['text_googleshopping_utm_campaign'] = $this->language->get('text_googleshopping_utm_campaign');
        $data['text_googleshopping_utm_source'] = $this->language->get('text_googleshopping_utm_source');
        $data['text_googleshopping_utm_medium'] = $this->language->get('text_googleshopping_utm_medium');
        $data['text_googleshopping_def_lang'] = $this->language->get('text_googleshopping_def_lang');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        //Tooltips
        $data['text_edit_general'] = $this->language->get('text_edit_general');


        //buttons
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        if ($this->model_setting_googleshopping->getSetting('google_general_settings', $store_id)) {
            $settings = $this->model_setting_googleshopping->getSetting('google_general_settings', $store_id);
            $data['googleshopping'] = $settings['google_general_settings'];
            $data['selected_language'] = $settings['google_general_settings']['general']['def_lang'];
        } else {
            $data['googleshopping'] = $this->request->post;
        }
        if (isset($this->request->post['googleshopping']['general']['enable'])) {
            $data['googleshopping']['general']['enable'] = $this->request->post['googleshopping']['general']['enable'];
        }
        if (isset($this->request->post['googleshopping']['general']['image'])) {
            $data['googleshopping']['general']['image'] = $this->request->post['googleshopping']['general']['image'];
        }
        if (isset($this->request->post['googleshopping']['general']['out_of_stock'])) {
            $data['googleshopping']['general']['out_of_stock'] = $this->request->post['googleshopping']['general']['out_of_stock'];
        }
        if (isset($this->request->post['googleshopping']['general']['product_price'])) {
            $data['googleshopping']['general']['product_price'] = $this->request->post['googleshopping']['general']['product_price'];
        }
        if (isset($this->request->post['googleshopping']['general']['no_upc'])) {
            $data['googleshopping']['general']['no_upc'] = $this->request->post['googleshopping']['general']['no_upc'];
        }
        if (isset($this->request->post['googleshopping']['general']['sync_type'])) {
            $data['googleshopping']['general']['sync_type'] = $this->request->post['googleshopping']['general']['sync_type'];
        }
        if (isset($this->request->post['googleshopping']['general']['utm_campaign'])) {
            $data['googleshopping']['general']['utm_campaign'] = $this->request->post['googleshopping']['general']['utm_campaign'];
        }
        if (isset($this->request->post['googleshopping']['general']['utm_source'])) {
            $data['googleshopping']['general']['utm_source'] = $this->request->post['googleshopping']['general']['utm_source'];
        }
        if (isset($this->request->post['googleshopping']['general']['utm_medium'])) {
            $data['googleshopping']['general']['utm_medium'] = $this->request->post['googleshopping']['general']['utm_medium'];
        }
        if (isset($this->request->post['googleshopping']['general']['def_lang'])) {
            $data['googleshopping']['general']['def_lang'] = $this->request->post['googleshopping']['general']['def_lang'];
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }
        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else {
            $data['error'] = '';
        }
        if (isset($this->error['product_price'])) {
            $data['error_googleshopping_product_price'] = $this->error['product_price'];
        } else {
            $data['error_googleshopping_product_price'] = '';
        }
        $this->load->model('localisation/language');
        $data['languages'] = $this->model_localisation_language->getLanguages();
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $active_tab['active_tab'] = 2;
        $data['googleshopping_common'] = $this->load->controller($this->module_path . '/googleshopping/common', $active_tab);

        if (VERSION >= '2.2.0.0') {
            $this->response->setOutput($this->load->view($this->module_path . '/kbgoogleshopping/googleshopping_general_settings', $data));
        } else {
            $this->response->setOutput($this->load->view($this->module_path . '/kbgoogleshopping/googleshopping_general_settings.tpl', $data));
        }
    }

    public function profileManagement()
    {
        if (isset($this->request->get['filter_profile_name'])) {
            $filter_profile_name = $this->request->get['filter_profile_name'];
        } else {
            $filter_profile_name = null;
        }
        if (isset($this->request->get['filter_profile_id'])) {
            $filter_profile_id = $this->request->get['filter_profile_id'];
        } else {
            $filter_profile_id = null;
        }
        if (isset($this->request->get['filter_profile_status'])) {
            $filter_profile_status = $this->request->get['filter_profile_status'];
        } else {
            $filter_profile_status = '';
        }
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $this->load->language($this->module_path . '/googleshopping');
        $this->document->setTitle($this->language->get('heading_title_main'));

        $this->load->model('setting/setting');
        $this->load->model('setting/googleshopping');

        if (isset($this->request->get['store_id'])) {
            $store_id = $this->request->get['store_id'];
        } else {
            $store_id = 0;
        }
        
        if (isset($this->request->get['action']) && $this->request->get['action'] == 'delete') {
            if ($this->model_setting_googleshopping->deleteProfile($this->request->get['id_gs_profiles'])) {
                $this->session->data['success'] = $this->language->get('text_profile_delete_success');
            } else {
                $this->session->data['error'] = $this->language->get('text_profile_delete_failure');
            }
        }
        
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'id_gs_profiles';
        }
        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array) $this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }
        
        $url = '';
        if (isset($this->request->get['filter_profile_name'])) {
            $url .= '&filter_profile_name=' . urlencode(html_entity_decode($this->request->get['filter_profile_name'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }
        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }
        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }
        
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', $this->session_token_key . '=' . $this->session_token . $url, true),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link($this->extension_path, $this->session_token_key . '=' . $this->session_token . $url, true),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_main'),
            'href' => $this->url->link($this->module_path . '/googleshopping', $this->session_token_key . '=' . $this->session_token . $url, true),
            'separator' => ' :: '
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_profile'),
            'href' => $this->url->link($this->module_path . '/googleshopping/profileManagement', $this->session_token_key . '=' . $this->session_token . $url, true),
            'separator' => ' :: '
        );

        $filter_data = array(
            'filter_profile_name' => $filter_profile_name,
            'filter_profile_id' => $filter_profile_id,
            'filter_profile_status' => $filter_profile_status,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );
        $url = '';
        if (isset($this->request->get['filter_profile_name'])) {
            $url .= '&filter_profile_name=' . urlencode(html_entity_decode($this->request->get['filter_profile_name'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['filter_profile_id'])) {
            $url .= '&filter_profile_id=' . urlencode(html_entity_decode($this->request->get['filter_profile_id'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['filter_profile_status'])) {
            $url .= '&filter_profile_status=' . urlencode(html_entity_decode($this->request->get['filter_profile_status'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }
        
        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }
        $data['sort_id_gs_profiles'] = $this->url->link($this->module_path . '/googleshopping/profileManagement', $this->session_token_key . '=' . $this->session_token . '&sort=id_gs_profiles' . $url, true);
        $data['sort_profile_title'] = $this->url->link($this->module_path . '/googleshopping/profileManagement', $this->session_token_key . '=' . $this->session_token . '&sort=profile_title' . $url, true);

        $data['sort_shipping_origin_country'] = $this->url->link($this->module_path . '/googleshopping/profileManagement', $this->session_token_key . '=' . $this->session_token . '&sort=shipping_origin_country' . $url, true);
        $data['sort_shipping_template_title'] = $this->url->link($this->module_path . '/googleshopping/profileManagement', $this->session_token_key . '=' . $this->session_token . '&sort=st.shipping_template_title' . $url, true);
        $data['sort_active'] = $this->url->link($this->module_path . '/googleshopping/profileManagement', $this->session_token_key . '=' . $this->session_token . '&sort=f.id_gs_feed' . $url, true);
        $data['sort_date_added'] = $this->url->link($this->module_path . '/googleshopping/profileManagement', $this->session_token_key . '=' . $this->session_token . '&sort=date_add' . $url, true);
        $data['sort_date_updated'] = $this->url->link($this->module_path . '/googleshopping/profileManagement', $this->session_token_key . '=' . $this->session_token . '&sort=date_upd' . $url, true);
        
        $url = '';
        if (isset($this->request->get['filter_profile_name'])) {
            $url .= '&filter_profile_name=' . urlencode(html_entity_decode($this->request->get['filter_profile_name'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['filter_profile_id'])) {
            $url .= '&filter_profile_id=' . urlencode(html_entity_decode($this->request->get['filter_profile_id'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['filter_profile_status'])) {
            $url .= '&filter_profile_status=' . urlencode(html_entity_decode($this->request->get['filter_profile_status'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }
        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }
        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $profile_total = $this->model_setting_googleshopping->getProfileTotal($filter_data);
        $profile_result = $this->model_setting_googleshopping->getProfileDetails($filter_data);
        $data['profiles'] = array();
        foreach ($profile_result as $result) {
            if ($result['id_gs_feed'] == NULL) {
                $status = $this->language->get('text_feed_not_created');
            } else {
                $status = $this->language->get('text_enabled');
            }
            $data['profiles'][] = array(
                'id_gs_profiles' => $result['id_gs_profiles'],
                'profile_title' => $result['profile_title'],
                'id_gs_feed' => $result['id_gs_feed'],
                'feed_generated' => $result['feed_generated'],
                'download_link' => $this->url->link($this->module_path . '/googleshopping/downloadFeed', $this->session_token_key . '=' . $this->session_token . "&id_gs_profiles=" . $result['id_gs_profiles'] . $url, true),
                'active' => $status,
                'date_add' => $result['date_add'],
                'date_upd' => $result['date_upd'],
                'status' => $result['active'],
                'edit' => $this->url->link($this->module_path . '/googleshopping/addProfile', $this->session_token_key . '=' . $this->session_token . "&id_gs_profiles=" . $result['id_gs_profiles'] . $url, true),
                'delete' => $this->url->link($this->module_path . '/googleshopping/profileManagement', $this->session_token_key . '=' . $this->session_token . "&action=delete&id_gs_profiles=" . $result['id_gs_profiles'] . $url, true),
                'enable' => $this->url->link($this->module_path . '/googleshopping/enableProfile', $this->session_token_key . '=' . $this->session_token . "&id_gs_profiles=" . $result['id_gs_profiles'] . $url, true),
                'disable' => $this->url->link($this->module_path . '/googleshopping/disableProfile', $this->session_token_key . '=' . $this->session_token . "&id_gs_profiles=" . $result['id_gs_profiles'] . $url, true)
            );
        }
        $pagination = new Pagination();
        $pagination->total = $profile_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link($this->module_path . '/googleshopping/profileManagement', $this->session_token_key . '=' . $this->session_token . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();
        $data['results'] = sprintf($this->language->get('text_pagination'), ($profile_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($profile_total - $this->config->get('config_limit_admin'))) ? $profile_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $profile_total, ceil($profile_total / $this->config->get('config_limit_admin')));
        //links
        $data['general_settings'] = $this->url->link($this->module_path . '/googleshopping/generalSettings', $this->session_token_key . '=' . $this->session_token, true);
        $data['action'] = $this->url->link($this->module_path . '/googleshopping', $this->session_token_key . '=' . $this->session_token, true);
        $data['route'] = $this->url->link($this->module_path . '/googleshopping', $this->session_token_key . '=' . $this->session_token, true);
        $data['cancel'] = $this->url->link($this->module_path . '/googleshopping', $this->session_token_key . '=' . $this->session_token, true);
        $data['add'] = $this->url->link($this->module_path . '/googleshopping/addProfile', $this->session_token_key . '=' . $this->session_token . $url, true);
        $data['delete'] = $this->url->link($this->module_path . '/googleshopping/deleteProfiles', $this->session_token_key . '=' . $this->session_token . $url, true);
        $data['token'] = $this->session_token;
        $data['token_key'] = $this->session_token_key;
        $data['module_path'] = $this->module_path;

        $data['heading_title'] = $this->language->get('heading_title');
        $data['heading_title_main'] = $this->language->get('heading_title_main');
        $data['text_edit_profile'] = $this->language->get('text_edit_profile');

        // Filter info
        $data['text_filter_profile_name'] = $this->language->get('text_filter_profile_name');
        $data['text_filter_profile_id'] = $this->language->get('text_filter_profile_id');
        $data['text_id_positive'] = $this->language->get('text_id_positive');
        $data['column_profile_id'] = $this->language->get('column_profile_id');
        $data['column_profile_title'] = $this->language->get('column_profile_title');
        $data['column_feed_download'] = $this->language->get('column_feed_download');
        $data['column_profile_status'] = $this->language->get('column_profile_status');
        $data['text_select_profile_status'] = $this->language->get('text_select_profile_status');
        $data['column_profile_added'] = $this->language->get('column_profile_added');
        $data['column_profile_updated'] = $this->language->get('column_profile_updated');
        $data['column_action'] = $this->language->get('column_action');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        //Tooltips
        //buttons
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_enable'] = $this->language->get('button_enable');
        $data['button_disable'] = $this->language->get('button_disable');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_reset'] = $this->language->get('button_reset');


        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }
        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else {
            $data['error'] = '';
        }

        $settings = $this->model_setting_googleshopping->getSetting('google_general_settings', $store_id);
        $data['sync_type'] = $settings['google_general_settings']['general']['sync_type'];

        $data['sort'] = $sort;
        $data['order'] = strtolower($order);
        $data['filter_profile_name'] = $filter_profile_name;
        $data['filter_profile_id'] = $filter_profile_id;
        $data['filter_profile_status'] = $filter_profile_status;
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $active_tab['active_tab'] = 3;
        $data['googleshopping_common'] = $this->load->controller($this->module_path . '/googleshopping/common', $active_tab);

        if (VERSION >= '2.2.0.0') {
            $this->response->setOutput($this->load->view($this->module_path . '/kbgoogleshopping/googleshopping_profile', $data));
        } else {
            $this->response->setOutput($this->load->view($this->module_path . '/kbgoogleshopping/googleshopping_profile.tpl', $data));
        }
    }

    public function feedManagement()
    {
        $this->load->model('setting/setting');
        $this->load->model('setting/googleshopping');
        $this->load->language($this->module_path . '/googleshopping');
        
        if (isset($this->request->get['filter_feed_name'])) {
            $filter_feed_name = $this->request->get['filter_feed_name'];
        } else {
            $filter_feed_name = null;
        }
        if (isset($this->request->get['filter_profile_title'])) {
            $filter_profile_title = $this->request->get['filter_profile_title'];
        } else {
            $filter_profile_title = null;
        }
        if (isset($this->request->get['filter_feed_status'])) {
            $filter_feed_status = $this->request->get['filter_feed_status'];
        } else {
            $filter_feed_status = '';
        }
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }
        
        $this->document->setTitle($this->language->get('heading_title_main'));

        if (isset($this->request->get['store_id'])) {
            $store_id = $this->request->get['store_id'];
        } else {
            $store_id = 0;
        }
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'id_gs_feed';
        }
        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if(isset($this->request->get['action']) && $this->request->get['action'] == 'delete') {
            $delete_feed_response = $this->model_setting_googleshopping->deleteFeed($this->request->get['id_gs_feed'], $store_id);
            if($delete_feed_response) {
                $this->session->data['success'] = $this->language->get('text_feed_delete_success');
            } else {
                $this->error['warning'] = $this->language->get('text_feed_delete_error');
            }
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array) $this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }
        $url = '';
        if (isset($this->request->get['filter_profile_name'])) {
            $url .= '&filter_profile_name=' . urlencode(html_entity_decode($this->request->get['filter_profile_name'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }
        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }
        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', $this->session_token_key . '=' . $this->session_token . $url, true),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link($this->extension_path, $this->session_token_key . '=' . $this->session_token . $url, true),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_main'),
            'href' => $this->url->link($this->module_path . '/googleshopping', $this->session_token_key . '=' . $this->session_token . $url, true),
            'separator' => ' :: '
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_feed'),
            'href' => $this->url->link($this->module_path . '/googleshopping/feedManagement', $this->session_token_key . '=' . $this->session_token . $url, true),
            'separator' => ' :: '
        );

        $filter_data = array(
            'filter_feed_name' => $filter_feed_name,
            'filter_profile_title' => $filter_profile_title,
            'filter_feed_status' => $filter_feed_status,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );
        $url = '';
        if (isset($this->request->get['filter_feed_name'])) {
            $url .= '&filter_feed_name=' . urlencode(html_entity_decode($this->request->get['filter_feed_name'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['filter_profile_title'])) {
            $url .= '&filter_profile_title=' . urlencode(html_entity_decode($this->request->get['filter_profile_title'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['filter_feed_status'])) {
            $url .= '&filter_feed_status=' . urlencode(html_entity_decode($this->request->get['filter_feed_status'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }
        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }
        $data['sort_id_gs_feed'] = $this->url->link($this->module_path . '/googleshopping/feedManagement', $this->session_token_key . '=' . $this->session_token . '&sort=f.id_gs_feed' . $url, true);
        $data['sort_feed_label'] = $this->url->link($this->module_path . '/googleshopping/feedManagement', $this->session_token_key . '=' . $this->session_token . '&sort=f.feed_label' . $url, true);
        $data['sort_profile_title'] = $this->url->link($this->module_path . '/googleshopping/feedManagement', $this->session_token_key . '=' . $this->session_token . '&sort=p.profile_title' . $url, true);
        $data['sort_active'] = $this->url->link($this->module_path . '/googleshopping/feedManagement', $this->session_token_key . '=' . $this->session_token . '&sort=f.active' . $url, true);
        $data['sort_date_added'] = $this->url->link($this->module_path . '/googleshopping/feedManagement', $this->session_token_key . '=' . $this->session_token . '&sort=f.date_add' . $url, true);
        $data['sort_date_updated'] = $this->url->link($this->module_path . '/googleshopping/feedManagement', $this->session_token_key . '=' . $this->session_token . '&sort=f.date_upd' . $url, true);
        $feed_total = $this->model_setting_googleshopping->getFeedTotal($filter_data);
        $feed_result = $this->model_setting_googleshopping->getFeedDetails($filter_data);
        $settings = $this->model_setting_googleshopping->getSetting('google_general_settings', $store_id);
        $data['sync_type'] = $settings['google_general_settings']['general']['sync_type'];
        $data['warning_feed_type'] = $this->language->get('text_warning_feed_type');
        $data['text_warning_feed_type_info'] = $this->language->get('text_warning_feed_type_info');
        
        $data['feeds'] = array();
        foreach ($feed_result as $result) {
            if ($result['active'] == 1) {
                $status = $this->language->get('text_enabled');
            } else {
                $status = $this->language->get('text_disabled');
            }
            $data['feeds'][] = array(
                'id_gs_feed' => $result['id_gs_feed'],
                'feed_title' => $result['feed_label'],
                'profile_title' => $result['profile_title'],
                'active' => $status,
                'status' => $result['active'],
                'date_add' => $result['date_add'],
                'date_upd' => $result['date_upd'],
                'edit' => $this->url->link($this->module_path . '/googleshopping/addFeed', $this->session_token_key . '=' . $this->session_token . "&id_gs_feed=" . $result['id_gs_feed'] . $url, true),
                'enable' => $this->url->link($this->module_path . '/googleshopping/enableFeed', $this->session_token_key . '=' . $this->session_token . "&id_gs_feed=" . $result['id_gs_feed'] . $url, true),
                'disable' => $this->url->link($this->module_path . '/googleshopping/disableFeed', $this->session_token_key . '=' . $this->session_token . "&id_gs_feed=" . $result['id_gs_feed'] . $url, true),
                'delete' => $this->url->link($this->module_path . '/googleshopping/feedManagement', $this->session_token_key . '=' . $this->session_token . "&action=delete&id_gs_feed=" . $result['id_gs_feed'] . $url, true)
            );
        }
        $pagination = new Pagination();
        $pagination->total = $feed_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link($this->module_path . '/googleshopping/feedManagement', $this->session_token_key . '=' . $this->session_token . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();
        $data['results'] = sprintf($this->language->get('text_pagination'), ($feed_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($feed_total - $this->config->get('config_limit_admin'))) ? $feed_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $feed_total, ceil($feed_total / $this->config->get('config_limit_admin')));
        //links
        $data['general_settings'] = $this->url->link($this->module_path . '/googleshopping/generalSettings', $this->session_token_key . '=' . $this->session_token, true);
        $data['action'] = $this->url->link($this->module_path . '/googleshopping', $this->session_token_key . '=' . $this->session_token, true);
        $data['route'] = $this->url->link($this->module_path . '/googleshopping', $this->session_token_key . '=' . $this->session_token, true);
        $data['cancel'] = $this->url->link($this->module_path . '/googleshopping', $this->session_token_key . '=' . $this->session_token, true);
        $data['add'] = $this->url->link($this->module_path . '/googleshopping/addFeed', $this->session_token_key . '=' . $this->session_token . $url, true);
        $data['delete'] = $this->url->link($this->module_path . '/googleshopping/deleteFeed', $this->session_token_key . '=' . $this->session_token . $url, true);
        $data['token'] = $this->session_token;
        $data['token_key'] = $this->session_token_key;
        $data['module_path'] = $this->module_path;

        $data['heading_title'] = $this->language->get('heading_title');
        $data['heading_title_main'] = $this->language->get('heading_title_main');
        $data['text_edit_feed'] = $this->language->get('text_edit_feed');
        $data['button_enable'] = $this->language->get('button_enable');
        $data['button_disable'] = $this->language->get('button_disable');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        // Filter info
        $data['text_filter_feed_name'] = $this->language->get('text_filter_feed_name');
        $data['text_filter_profile_title'] = $this->language->get('text_filter_profile_title');
        $data['column_feed_status'] = $this->language->get('column_feed_status');
        $data['text_filter_profile_name'] = $this->language->get('text_filter_profile_name');
        $data['text_select_profile_status'] = $this->language->get('text_select_profile_status');
        $data['column_feed_id'] = $this->language->get('column_feed_id');
        $data['column_profile_title'] = $this->language->get('column_profile_title');
        $data['column_feed_title'] = $this->language->get('column_feed_title');
        $data['column_feed_download'] = $this->language->get('column_feed_download');
        $data['column_profile_status'] = $this->language->get('column_profile_status');
        $data['column_profile_added'] = $this->language->get('column_profile_added');
        $data['column_profile_updated'] = $this->language->get('column_profile_updated');
        $data['column_action'] = $this->language->get('column_action');
        $data['text_no_results'] = $this->language->get('text_no_results');

        //Tooltips
        //buttons
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_reset'] = $this->language->get('button_reset');


        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }
        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else {
            $data['error'] = '';
        }

        $data['sort'] = $sort;
        $data['order'] = strtolower($order);
        $data['filter_feed_name'] = $filter_feed_name;
        $data['filter_profile_title'] = $filter_profile_title;
        $data['filter_feed_status'] = $filter_feed_status;
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $active_tab['active_tab'] = 4;
        $data['googleshopping_common'] = $this->load->controller($this->module_path . '/googleshopping/common', $active_tab);

        if (VERSION >= '2.2.0.0') {
            $this->response->setOutput($this->load->view($this->module_path . '/kbgoogleshopping/googleshopping_feed', $data));
        } else {
            $this->response->setOutput($this->load->view($this->module_path . '/kbgoogleshopping/googleshopping_feed.tpl', $data));
        }
    }

    public function productListing()
    {
        $this->load->language($this->module_path . '/googleshopping');

        $this->document->setTitle($this->language->get('heading_title_main'));

        $this->load->model('setting/setting');
        $this->load->model('setting/googleshopping');
        
        if(isset($this->request->get['action']) && $this->request->get['action'] != "") {
            if($this->request->get['action'] == "disable") {
                $this->model_setting_googleshopping->disableProduct($this->request->get["id_gs_products_list"]);
                $this->session->data['success'] = $this->language->get('disable_product_success');
            }
            
            if($this->request->get['action'] == "enable") {
                $this->model_setting_googleshopping->enableProduct($this->request->get["id_gs_products_list"]);
                $this->session->data['success'] = $this->language->get('enable_product_success');
            }
        }
        
        if(isset($this->request->get['bulk_action']) && $this->request->get['bulk_action'] != "") {
            if(isset($this->request->post['selected']) && count($this->request->post['selected']) > 0) {
                if($this->request->get['bulk_action'] == "disable") {
                    if (isset($this->request->post['selected'])) {
                        foreach($this->request->post['selected'] as $selected) {
                            $this->model_setting_googleshopping->disableProduct($selected);
                        }
                    }
                    $this->session->data['success'] = $this->language->get('disable_products_success');
                }

                if($this->request->get['bulk_action'] == "enable") {
                    if (isset($this->request->post['selected'])) {
                        foreach($this->request->post['selected'] as $selected) {
                            $this->model_setting_googleshopping->enableProduct($selected);
                        }
                    }
                    $this->session->data['success'] = $this->language->get('enable_products_success');
                }
            } else {
                $this->session->data['error'] = $this->language->get('select_product_error');
            }
        }
        

        if (isset($this->request->get['store_id'])) {
            $store_id = $this->request->get['store_id'];
        } else {
            $store_id = 0;
        }
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }

        if (isset($this->request->get['filter_model'])) {
            $filter_model = $this->request->get['filter_model'];
        } else {
            $filter_model = null;
        }

        if (isset($this->request->get['filter_listing_status'])) {
            $filter_listing_status = $this->request->get['filter_listing_status'];
        } else {
            $filter_listing_status = null;
        }

        if (isset($this->request->get['filter_listed_on'])) {
            $filter_listed_on = $this->request->get['filter_listed_on'];
        } else {
            $filter_listed_on = null;
        }

        if (isset($this->request->get['filter_listing_id'])) {
            $filter_listing_id = $this->request->get['filter_listing_id'];
        } else {
            $filter_listing_id = null;
        }
        
        if (isset($this->request->get['filter_profile_title'])) {
            $filter_profile_title = $this->request->get['filter_profile_title'];
        } else {
            $filter_profile_title = null;
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'id_gs_products_list';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_listing_status'])) {
            $url .= '&filter_listing_status=' . $this->request->get['filter_listing_status'];
        }

        if (isset($this->request->get['filter_quantity'])) {
            $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }

        if (isset($this->request->get['filter_listing_id'])) {
            $url .= '&filter_listing_id=' . $this->request->get['filter_listing_id'];
        }
        
        if (isset($this->request->get['filter_profile_title'])) {
            $url .= '&filter_profile_title=' . $this->request->get['filter_profile_title'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', $this->session_token_key . '=' . $this->session_token . $url, true),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link($this->extension_path, $this->session_token_key . '=' . $this->session_token . $url, true),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_main'),
            'href' => $this->url->link($this->module_path . '/googleshopping', $this->session_token_key . '=' . $this->session_token . $url, true),
            'separator' => ' :: '
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_product_listing'),
            'href' => $this->url->link($this->module_path . '/googleshopping/productListing', $this->session_token_key . '=' . $this->session_token . $url, true),
            'separator' => ' :: '
        );

        $filter_data = array(
            'filter_name' => $filter_name,
            'filter_model' => $filter_model,
            'filter_listing_status' => $filter_listing_status,
            'filter_listed_on' => $filter_listed_on,
            'filter_listing_id' => $filter_listing_id,
            'filter_profile_title' => $filter_profile_title,
            'filter_status' => $filter_status,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );
        $data['filter_data'] = $filter_data;

        $products_total = $this->model_setting_googleshopping->getTotalProducts($filter_data);
        $results = $this->model_setting_googleshopping->getProducts($filter_data);
        $data['googleshopping_products'] = array();
        $this->load->model('tool/image');
        
        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_listing_status'])) {
            $url .= '&filter_listing_status=' . $this->request->get['filter_listing_status'];
        }

        if (isset($this->request->get['filter_quantity'])) {
            $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }

        if (isset($this->request->get['filter_listing_id'])) {
            $url .= '&filter_listing_id=' . $this->request->get['filter_listing_id'];
        }
        
        if (isset($this->request->get['filter_profile_title'])) {
            $url .= '&filter_profile_title=' . $this->request->get['filter_profile_title'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }
        
        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $listing_status_array = array(
            'Listed' => $this->language->get('text_listed'),
            'Pending' => $this->language->get('text_pending'),
            'Inactive' => $this->language->get('text_inactive')
        );

        foreach ($results as $result) {
            if (is_file(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], 40, 40);
            } else {
                $image = $this->model_tool_image->resize('no_image.png', 40, 40);
            }
            $color = '';
            $size = '';
            if ($result['id_product_attribute'] > 0) {
                
            }
            $data['googleshopping_products'][] = array(
                'listing_id' => $result['listing_id'],
                'id_gs_products_list' => $result['id_gs_products_list'],
                'image' => $image,
                'name' => $result['name'],
                'profile_title' => $result['profile_title'],
                'model' => $result['reference'],
                'quantity' => $result['quantity'],
                'listing_status' => $listing_status_array[$result['listing_status']],
                'renew_status' => $result['renew_flag'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
                'renew_flag' => $result['renew_flag'],
                'delete_flag' => $result['delete_flag'],
                'listed_on' => $result['date_listed'],
                'message' => $result['listing_error'],
                'option' => $color . "<br>" . $size,
                'enable' => $this->url->link($this->module_path . '/googleshopping/productListing', $this->session_token_key . '=' . $this->session_token . '&action=enable&id_gs_products_list=' . $result['id_gs_products_list'] . $url, true),
                'disable' => $this->url->link($this->module_path . '/googleshopping/productListing', $this->session_token_key . '=' . $this->session_token . '&action=disable&id_gs_products_list=' . $result['id_gs_products_list'] . $url, true),
                'delete' => $this->url->link($this->module_path . '/googleshopping/deleteProduct', $this->session_token_key . '=' . $this->session_token . '&action_type=delete&id_gs_products_list=' . $result['id_gs_products_list'] . $url, true),
                'relist' => $this->url->link($this->module_path . '/googleshopping/productListing', $this->session_token_key . '=' . $this->session_token . '&action_type=relist&id_gs_products_list=' . $result['id_gs_products_list'] . $url, true),
                'renew' => $this->url->link($this->module_path . '/googleshopping/renewListing', $this->session_token_key . '=' . $this->session_token . '&action_type=renew&id_gs_products_list=' . $result['id_gs_products_list'] . $url, true),
                'halt' => $this->url->link($this->module_path . '/googleshopping/productListing', $this->session_token_key . '=' . $this->session_token . '&action_type=halt&id_gs_products_list=' . $result['id_gs_products_list'] . $url, true)
            );
        }
        
        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_listing_status'])) {
            $url .= '&filter_listing_status=' . $this->request->get['filter_listing_status'];
        }

        if (isset($this->request->get['filter_quantity'])) {
            $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }

        if (isset($this->request->get['filter_listing_id'])) {
            $url .= '&filter_listing_id=' . $this->request->get['filter_listing_id'];
        }
        
        if (isset($this->request->get['filter_profile_title'])) {
            $url .= '&filter_profile_title=' . $this->request->get['filter_profile_title'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }
        
        if (isset($this->request->get['sort'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $products_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link($this->module_path . '/googleshopping/productListing', $this->session_token_key . '=' . $this->session_token . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();
        $data['results'] = sprintf($this->language->get('text_pagination'), ($products_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($products_total - $this->config->get('config_limit_admin'))) ? $products_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $products_total, ceil($products_total / $this->config->get('config_limit_admin')));
        //links
        $data['general_settings'] = $this->url->link($this->module_path . '/googleshopping/generalSettings', $this->session_token_key . '=' . $this->session_token, true);
        $data['action'] = $this->url->link($this->module_path . '/googleshopping/shippingTemplates', $this->session_token_key . '=' . $this->session_token, true);
        $data['route'] = $this->url->link($this->module_path . '/googleshopping/shippingTemplates', $this->session_token_key . '=' . $this->session_token, true);
        $data['cancel'] = $this->url->link($this->module_path . '/googleshopping', $this->session_token_key . '=' . $this->session_token, true);
        $data['delete'] = $this->url->link($this->module_path . '/googleshopping/deleteShippingTemplates', $this->session_token_key . '=' . $this->session_token . $url, true);
        $data['enable_action'] = $this->url->link($this->module_path . '/googleshopping/productListing', $this->session_token_key . '=' . $this->session_token . "&bulk_action=enable" . $url, true);
        $data['disable_action'] = $this->url->link($this->module_path . '/googleshopping/productListing', $this->session_token_key . '=' . $this->session_token . "&bulk_action=disable" . $url, true);

        $data['token'] = $this->session_token;
        $data['token_key'] = $this->session_token_key;
        $data['module_path'] = $this->module_path;
        
        $data['local_sync_link'] = HTTPS_CATALOG . 'index.php?route=googleshopping/cron/syncLocal';
        $data['feed_sync_link'] = HTTPS_CATALOG . 'index.php?route=googleshopping/cron/syncFeedsListing';
        $data['sync_product_status_link'] = HTTPS_CATALOG . 'index.php?route=googleshopping/cron/syncProductStatus';
        $data['text_local_sync'] = $this->language->get('text_local_sync');
        $data['text_feed_sync'] = $this->language->get('text_feed_sync');
        $data['text_product_status_sync'] = $this->language->get('text_product_status_sync');
        
        $data['heading_title'] = $this->language->get('heading_title');
        $data['heading_title_main'] = $this->language->get('heading_title_main');
        $data['text_edit_shipping'] = $this->language->get('text_edit_shipping');

        // Filter info
        $data['text_product_listing'] = $this->language->get('text_product_listing');
        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_missing'] = $this->language->get('text_missing');

        $data['column_image'] = $this->language->get('column_image');
        $data['column_name'] = $this->language->get('column_name');
        $data['column_model'] = $this->language->get('column_model');
        $data['column_price'] = $this->language->get('column_price');
        $data['column_quantity'] = $this->language->get('column_quantity');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_action'] = $this->language->get('column_action');
        $data['column_listing_status'] = $this->language->get('column_listing_status');
        $data['column_relisting_status'] = $this->language->get('column_relisting_status');
        $data['column_listing_id'] = $this->language->get('column_listing_id');
        $data['column_listed_on'] = $this->language->get('column_listed_on');
        $data['column_variation'] = $this->language->get('column_variation');
        $data['column_profile_title'] = $this->language->get('column_profile_title');

        
        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_model'] = $this->language->get('entry_model');
        $data['entry_price'] = $this->language->get('entry_price');
        $data['entry_quantity'] = $this->language->get('entry_quantity');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_error_message'] = $this->language->get('entry_error_message');
        $data['entry_no_error'] = $this->language->get('entry_no_error');

        $data['button_error'] = $this->language->get('button_error');
        $data['button_renew'] = $this->language->get('button_renew');
        $data['button_halt'] = $this->language->get('button_halt');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_view'] = $this->language->get('button_view');
        $data['button_relist'] = $this->language->get('button_relist');
        $data['button_reset'] = $this->language->get('button_reset');
        $data['button_disable_product'] = $this->language->get('button_disable_product');
        $data['button_enable_product'] = $this->language->get('button_enable_product');
        $data['button_disable_products'] = $this->language->get('button_disable_products');
        $data['button_enable_products'] = $this->language->get('button_enable_products');
        
        //Tooltips
        //buttons
        $data['button_save'] = $this->language->get('button_save');
        $data['button_save_and_stay'] = $this->language->get('button_save_and_stay');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_add_module'] = $this->language->get('button_add_module');
        $data['button_remove'] = $this->language->get('button_remove');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['button_filter'] = $this->language->get('button_filter');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->session->data['error'])) {
            $data['error_warning'] = $this->session->data['error'];
            unset($this->session->data['error']);
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array) $this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total=' . $this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }
        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }
        
        $data['filter_name'] = $filter_name;
        $data['filter_model'] = $filter_model;
        $data['filter_listing_status'] = $filter_listing_status;
        $data['filter_listed_on'] = $filter_listed_on;
        $data['filter_status'] = $filter_status;
        $data['filter_listing_id'] = $filter_listing_id;
        $data['filter_profile_title'] = $filter_profile_title;
        
        $data['listing_statuses'] = array(
            'Pending' => $this->language->get('text_pending'),
            'Listed' => $this->language->get('text_listed'),
            'Inactive' => $this->language->get('text_inactive')
        );
        
        $profile_result = $this->model_setting_googleshopping->getProfileDetails(array("sort" => "profile_title", "order" => "ASC"));
        $data['profile_result'] = $profile_result;
        
        $data['sort'] = $sort;
        $data['order'] = strtolower($order);

        $data['sort_name'] = $this->url->link($this->module_path . '/googleshopping/productListing', $this->session_token_key . '=' . $this->session_token . '&sort=pd.name' . $url, true);
        $data['sort_model'] = $this->url->link($this->module_path . '/googleshopping/productListing', $this->session_token_key . '=' . $this->session_token . '&sort=p.model' . $url, true);
        $data['sort_listing_status'] = $this->url->link($this->module_path . '/googleshopping/productListing', $this->session_token_key . '=' . $this->session_token . '&sort=gpl.listing_status' . $url, true);
        $data['sort_profile_title'] = $this->url->link($this->module_path . '/googleshopping/productListing', $this->session_token_key . '=' . $this->session_token . '&sort=profile_title' . $url, true);
        $data['sort_listing_id'] = $this->url->link($this->module_path . '/googleshopping/productListing', $this->session_token_key . '=' . $this->session_token . '&sort=gpl.listing_id' . $url, true);
        $data['sort_renew_status'] = $this->url->link($this->module_path . '/googleshopping/productListing', $this->session_token_key . '=' . $this->session_token . '&sort=gpl.renew_flag' . $url, true);
        $data['sort_listed_on'] = $this->url->link($this->module_path . '/googleshopping/productListing', $this->session_token_key . '=' . $this->session_token . '&sort=gpl.date_listed' . $url, true);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $active_tab['active_tab'] = 5;
        $data['googleshopping_common'] = $this->load->controller($this->module_path . '/googleshopping/common', $active_tab);

        if (VERSION >= '2.2.0.0') {
            $this->response->setOutput($this->load->view($this->module_path . '/kbgoogleshopping/googleshopping_product_listing', $data));
        } else {
            $this->response->setOutput($this->load->view($this->module_path . '/kbgoogleshopping/googleshopping_product_listing.tpl', $data));
        }
    }

    public function auditLog()
    {
        if (isset($this->request->get['filter_id_etsy_audit_log'])) {
            $filter_id_etsy_audit_log = $this->request->get['filter_id_etsy_audit_log'];
        } else {
            $filter_id_etsy_audit_log = null;
        }
        if (isset($this->request->get['filter_log_entry'])) {
            $filter_log_entry = $this->request->get['filter_log_entry'];
        } else {
            $filter_log_entry = null;
        }
        if (isset($this->request->get['filter_log_class_method'])) {
            $filter_log_class_method = $this->request->get['filter_log_class_method'];
        } else {
            $filter_log_class_method = null;
        }
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }
        $this->load->language($this->module_path . '/googleshopping');

        $this->document->setTitle($this->language->get('heading_title_main'));

        $this->load->model('setting/setting');
        $this->load->model('setting/googleshopping');

        if (isset($this->request->get['store_id'])) {
            $store_id = $this->request->get['store_id'];
        } else {
            $store_id = 0;
        }
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'id_gs_audit_log';
        }
        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array) $this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }
        $url = '';
        if (isset($this->request->get['filter_id_etsy_audit_log'])) {
            $url .= '&filter_id_etsy_audit_log=' . urlencode(html_entity_decode($this->request->get['filter_id_etsy_audit_log'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_log_entry'])) {
            $url .= '&filter_log_entry=' . urlencode(html_entity_decode($this->request->get['filter_log_entry'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['filter_log_class_method'])) {
            $url .= '&filter_log_class_method=' . urlencode(html_entity_decode($this->request->get['filter_log_class_method'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }
        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }
        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', $this->session_token_key . '=' . $this->session_token . $url, true),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link($this->extension_path, $this->session_token_key . '=' . $this->session_token . $url, true),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_main'),
            'href' => $this->url->link($this->module_path . '/googleshopping', $this->session_token_key . '=' . $this->session_token . $url, true),
            'separator' => ' :: '
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_audit'),
            'href' => $this->url->link($this->module_path . '/googleshopping/auditLog', $this->session_token_key . '=' . $this->session_token . $url, true),
            'separator' => ' :: '
        );

        $filter_data = array(
            'filter_id_etsy_audit_log' => $filter_id_etsy_audit_log,
            'filter_log_entry' => $filter_log_entry,
            'filter_log_class_method' => $filter_log_class_method,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );
        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }
        $data['sort_id_etsy_audit_log'] = $this->url->link($this->module_path . '/googleshopping/auditLog', $this->session_token_key . '=' . $this->session_token . '&sort=id_etsy_audit_log' . $url, true);
        $data['sort_log_entry'] = $this->url->link($this->module_path . '/googleshopping/auditLog', $this->session_token_key . '=' . $this->session_token . '&sort=log_entry' . $url, true);
        $data['sort_log_user'] = $this->url->link($this->module_path . '/googleshopping/auditLog', $this->session_token_key . '=' . $this->session_token . '&sort=log_user' . $url, true);
        $data['sort_log_class_method'] = $this->url->link($this->module_path . '/googleshopping/auditLog', $this->session_token_key . '=' . $this->session_token . '&sort=log_class_method' . $url, true);
        $data['sort_log_time'] = $this->url->link($this->module_path . '/googleshopping/auditLog', $this->session_token_key . '=' . $this->session_token . '&sort=log_time' . $url, true);
        $audit_total = $this->model_setting_googleshopping->getAuditLogTotal($filter_data);
        $audit_details = $this->model_setting_googleshopping->getAuditLog($filter_data);
        $data['googleshopping'] = array();
        foreach ($audit_details as $result) {
            $data['audit_log'][] = array(
                'id_gs_audit_log' => $result['id_gs_audit_log'],
                'log_entry' => $result['log_entry'],
                'log_user' => $result['log_user'],
                'log_class_method' => $result['log_class_method'],
                'log_time' => $result['log_time'],
            );
        }
        $pagination = new Pagination();
        $pagination->total = $audit_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link($this->module_path . '/googleshopping/auditLog', $this->session_token_key . '=' . $this->session_token . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();
        $data['results'] = sprintf($this->language->get('text_pagination'), ($audit_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($audit_total - $this->config->get('config_limit_admin'))) ? $audit_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $audit_total, ceil($audit_total / $this->config->get('config_limit_admin')));
        //links
        $data['general_settings'] = $this->url->link($this->module_path . '/googleshopping/generalSettings', $this->session_token_key . '=' . $this->session_token, true);
        $data['cancel'] = $this->url->link($this->module_path . '/googleshopping', $this->session_token_key . '=' . $this->session_token, true);
        $data['token'] = $this->session_token;

        $data['heading_title'] = $this->language->get('heading_title');
        $data['heading_title_main'] = $this->language->get('heading_title_main');
        $data['text_edit_audit'] = $this->language->get('text_edit_audit');

        // Filter info
        $data['text_filter_log_entry'] = $this->language->get('text_filter_log_entry');
        $data['text_filter_log_class_method'] = $this->language->get('text_filter_log_class_method');
        $data['text_filter_min_proc_days'] = $this->language->get('text_filter_min_proc_days');
        $data['text_filter_max_proc_days'] = $this->language->get('text_filter_max_proc_days');
        $data['column_log_id'] = $this->language->get('column_log_id');
        $data['column_log_description'] = $this->language->get('column_log_description');
        $data['column_action_user'] = $this->language->get('column_action_user');
        $data['column_action_called'] = $this->language->get('column_action_called');
        $data['column_time_action'] = $this->language->get('column_time_action');
        $data['column_action'] = $this->language->get('column_action');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['button_add'] = $this->language->get('button_add');
        $data['button_reset'] = $this->language->get('button_reset');

        //Tooltips
        //buttons
        $data['button_save'] = $this->language->get('button_save');
        $data['button_save_and_stay'] = $this->language->get('button_save_and_stay');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_add_module'] = $this->language->get('button_add_module');
        $data['button_remove'] = $this->language->get('button_remove');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['button_filter'] = $this->language->get('button_filter');


        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }
        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else {
            $data['error'] = '';
        }
        if (isset($this->error['etsy_api_key'])) {
            $data['error_etsy_api_key'] = $this->error['etsy_api_key'];
        } else {
            $data['error_etsy_api_key'] = '';
        }
        $data['sort'] = $sort;
        $data['filter_log_entry'] = $filter_log_entry;
        $data['filter_log_class_method'] = $filter_log_class_method;
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        if (VERSION >= '2.2.0.0') {
            $this->response->setOutput($this->load->view($this->module_path . '/kbgoogleshopping/googleshopping_audit', $data));
        } else {
            $this->response->setOutput($this->load->view($this->module_path . '/kbgoogleshopping/googleshopping_audit.tpl', $data));
        }
    }

    public function addProfile()
    {
        $this->load->language($this->module_path . '/googleshopping');

        $this->document->setTitle($this->language->get('heading_title_main'));

        $this->load->model('setting/setting');
        $this->load->model('setting/googleshopping');
        $this->load->model('catalog/category');
        $this->load->model('catalog/option');
        $this->load->model('localisation/currency');

        if (isset($this->request->get['store_id'])) {
            $store_id = $this->request->get['store_id'];
        } else {
            $store_id = 0;
        }
        if (isset($this->request->get['id_gs_profiles'])) {
            $profile_details = $this->model_setting_googleshopping->getProfileDetailsById($this->request->get['id_gs_profiles']);
            $data['currencys'] = $this->model_setting_googleshopping->ajaxProcessGetCurrency($profile_details[0]['id_country']);
            
            $data['languages'] = $this->model_setting_googleshopping->ajaxProcessGetLanguages($profile_details[0]['id_country']);
            $data['googleshopping'] = array();
            $data['googleshopping'] = array(
                'profile' => array(
                    'profile_title' => $profile_details[0]['profile_title'],
                    'customize_title' => $profile_details[0]['customize_product_title'],
                    'custom_label_0' => $profile_details[0]['custom_label_0'],
                    'custom_label_1' => $profile_details[0]['custom_label_1'],
                    'custom_label_2' => $profile_details[0]['custom_label_2'],
                    'custom_label_3' => $profile_details[0]['custom_label_3'],
                    'custom_label_4' => $profile_details[0]['custom_label_4'],
                    'id_google_profiles' => $profile_details[0]['id_gs_profiles'],
                )
            );
            $categories = explode(",", $profile_details[0]['store_category']);
            $data['product_categories'] = array();
            foreach ($categories as $category_id) {
                $category_info = $this->model_catalog_category->getCategory($category_id);
                if ($category_info) {
                    $data['product_categories'][] = array(
                        'category_id' => $category_info['category_id'],
                        'name' => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
                    );
                }
            }
            $data['google_select_countries'] = $profile_details[0]['id_country'];
            $data['selected_gtin'] = $profile_details[0]['gtin'];
            $data['google_category_id'] = $profile_details[0]['gs_category_code'];
            $data['id_gs_profiles'] = $profile_details[0]['id_gs_profiles'];
            $data['google_catgeory_text'] = $profile_details[0]['gs_category_text'];
            $data['selected_material'] = $profile_details[0]['material'];
            $data['selected_pattern'] = $profile_details[0]['pattern'];
            $data['selected_gender'] = $profile_details[0]['gender'];
            $data['selected_age_group'] = $profile_details[0]['age_group'];
            $data['selected_content'] = $profile_details[0]['adult'];
            $data['selected_color'] = $profile_details[0]['color'];
            $data['selected_size'] = $profile_details[0]['size'];
            $data['selected_size_type'] = $profile_details[0]['size_type'];
            $data['selected_system'] = $profile_details[0]['size_system'];
            $data['selected_shipping'] = explode(",", $profile_details[0]['shipping']);
            $data['selected_language'] = $profile_details[0]['id_lang'];
            $data['selected_oc_language'] = $profile_details[0]['oc_lang_id'];
            $data['selected_currency'] = $profile_details[0]['id_currency'];
        } else {
            $data['google_select_countries'] = '';
            $data['selected_gtin'] = '';
            $data['google_category_id'] = '';
            $data['id_gs_profiles'] = '';
            $data['google_catgeory_text'] = '';
            $data['selected_material'] = '';
            $data['selected_pattern'] = '';
            $data['selected_gender'] = '';
            $data['selected_age_group'] = '';
            $data['selected_content'] = '';
            $data['selected_color'] = '';
            $data['selected_size'] = '';
            $data['selected_size_type'] = '';
            $data['selected_system'] = '';
            $data['selected_shipping'] = array();
            $data['selected_language'] = '';
            $data['selected_oc_language'] = '';
            $data['selected_currency'] = '';
            $categories = array();
            $data['product_categories'] = array();
            foreach ($categories as $category_id) {
                $category_info = $this->model_catalog_category->getCategory($category_id);

                if ($category_info) {
                    $data['product_categories'][] = array(
                        'category_id' => $category_info['category_id'],
                        'name' => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
                    );
                }
            }
        }
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            if (isset($this->request->post['googleshopping']['profile']['id_google_profiles']) && $this->request->post['googleshopping']['profile']['id_google_profiles'] != '') {
                $this->model_setting_googleshopping->editProfile($this->request->post);
                $this->session->data['success'] = $this->language->get('googleshopping_profile_update_success');
            } else {
                $this->session->data['success'] = $this->language->get('googleshopping_profile_add_success');
                $this->model_setting_googleshopping->addProfile($this->request->post);
            }
            $this->response->redirect($this->url->link($this->module_path . '/googleshopping/profileManagement', $this->session_token_key . '=' . $this->session_token, true));
            die();
        }
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', $this->session_token_key . '=' . $this->session_token, true),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link($this->extension_path, $this->session_token_key . '=' . $this->session_token, true),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_main'),
            'href' => $this->url->link($this->module_path . '/googleshopping', $this->session_token_key . '=' . $this->session_token, true),
            'separator' => ' :: '
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_profile'),
            'href' => $this->url->link($this->module_path . '/googleshopping/profileManagement', $this->session_token_key . '=' . $this->session_token, true),
            'separator' => ' :: '
        );
        if (isset($this->request->get['id_ebay_profiles'])) {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title_profile_edit'),
                'href' => $this->url->link($this->module_path . '/googleshopping/addProfile', $this->session_token_key . '=' . $this->session_token, true),
                'separator' => ' :: '
            );
        } else {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title_profile_add'),
                'href' => $this->url->link($this->module_path . '/googleshopping/addProfile', $this->session_token_key . '=' . $this->session_token, true),
                'separator' => ' :: '
            );
        }
        $url = '';
        if (isset($this->request->get['id_gs_profiles'])) {
            $url = '&id_gs_profiles=' . $this->request->get['id_gs_profiles'];
        }
        //links
        $data['general_settings'] = $this->url->link($this->module_path . '/googleshopping/addProfile', $this->session_token_key . '=' . $this->session_token . $url, true);
        $data['action'] = $this->url->link($this->module_path . '/googleshopping/addProfile', $this->session_token_key . '=' . $this->session_token . $url, true);
        $data['route'] = $this->url->link($this->module_path . '/googleshopping/addProfile', $this->session_token_key . '=' . $this->session_token . $url, true);
        $data['cancel'] = $this->url->link($this->module_path . '/googleshopping/profileManagement', $this->session_token_key . '=' . $this->session_token . $url, true);
        $data['token'] = $this->session_token;

        $data['heading_title'] = $this->language->get('heading_title');
        $data['heading_title_main'] = $this->language->get('heading_title_main');
        $data['add_currencies_in_oc'] = $this->language->get('add_currencies_in_oc');

        // General Settings tab & info
        $data['save_general'] = $this->language->get('save_general');
        $data['text_data_tab'] = $this->language->get('text_data_tab');
        $data['text_specifics'] = $this->language->get('text_specifics');
        $data['text_profile_title'] = $this->language->get('text_profile_title');
        $data['text_ebay_category'] = $this->language->get('text_ebay_category');
        $data['text_store_category'] = $this->language->get('text_store_category');
        $data['text_shipping_template'] = $this->language->get('text_shipping_template');
        $data['text_walmart_currency'] = $this->language->get('text_walmart_currency');
        $data['text_who_made'] = $this->language->get('text_who_made');
        $data['text_when_made'] = $this->language->get('text_when_made');
        $data['text_recipient'] = $this->language->get('text_recipient');
        $data['text_occasion'] = $this->language->get('text_occasion');
        $data['text_general_store_name'] = $this->language->get('text_general_store_name');
        $data['text_general_walmart_quantity'] = $this->language->get('text_general_walmart_quantity');
        $data['text_general_guestenable'] = $this->language->get('text_general_guestenable');
        $data['text_walmart_api_detail'] = $this->language->get('text_walmart_api_detail');
        $data['text_walmart_api_detail_title'] = $this->language->get('text_walmart_api_detail_title');
        $data['text_walmart_store'] = $this->language->get('text_walmart_store');
        $data['text_walmart_merchant_id'] = $this->language->get('text_walmart_merchant_id');
        $data['text_walmart_market_place_id'] = $this->language->get('text_walmart_market_place_id');
        $data['text_walmart_consumer_id'] = $this->language->get('text_walmart_consumer_id');
        $data['text_walmart_private_key'] = $this->language->get('text_walmart_private_key');
        $data['text_walmart_consumer_channel'] = $this->language->get('text_walmart_consumer_channel');
        $data['text_product_listing'] = $this->language->get('text_product_listing');
        $data['text_product_update'] = $this->language->get('text_product_update');
        $data['text_product_id'] = $this->language->get('text_product_id');
        $data['text_product_name'] = $this->language->get('text_product_name');
        $data['text_product_sku'] = $this->language->get('text_product_sku');
        $data['text_product_price'] = $this->language->get('text_product_price');
        $data['text_product_quantity'] = $this->language->get('text_product_quantity');
        $data['text_status'] = $this->language->get('text_status');
        $data['text_action'] = $this->language->get('text_action');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_select_attr'] = $this->language->get('text_select_attr');
        $data['text_ebay_sites'] = $this->language->get('text_ebay_sites');
        $data['text_ebay_category_select'] = $this->language->get('text_ebay_category_select');
        $data['text_duration'] = $this->language->get('text_duration');
        $data['text_product_condition'] = $this->language->get('text_product_condition');
        $data['text_product_quantity'] = $this->language->get('text_product_quantity');
        $data['text_product_dispatch_time'] = $this->language->get('text_product_dispatch_time');
        $data['text_profile_return'] = $this->language->get('text_profile_return');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_return_time'] = $this->language->get('text_return_time');
        $data['text_return_type'] = $this->language->get('text_return_type');
        $data['text_return_shipping'] = $this->language->get('text_return_shipping');
        $data['text_googleshopping_country'] = $this->language->get('text_googleshopping_country');
        $data['text_googleshopping_language'] = $this->language->get('text_googleshopping_language');
        $data['text_googleshopping_language_select'] = $this->language->get('text_googleshopping_language_select');
        
        $data['text_opencartshopping_language'] = $this->language->get('text_opencartshopping_language');
        $data['text_opencartshopping_language_select'] = $this->language->get('text_opencartshopping_language_select');
        
        $data['text_googleshopping_currency'] = $this->language->get('text_googleshopping_currency');
        $data['text_googleshopping_currency_select'] = $this->language->get('text_googleshopping_currency_select');
        $data['text_gtin'] = $this->language->get('text_gtin');
        $data['text_customize_title'] = $this->language->get('text_customize_title');
        $data['text_googleshopping_category'] = $this->language->get('text_googleshopping_category');
        $data['text_material'] = $this->language->get('text_material');
        $data['text_pattern'] = $this->language->get('text_pattern');
        $data['text_gender'] = $this->language->get('text_gender');
        $data['text_age_group'] = $this->language->get('text_age_group');
        $data['text_adult_content'] = $this->language->get('text_adult_content');
        $data['text_color'] = $this->language->get('text_color');
        $data['text_size'] = $this->language->get('text_size');
        $data['text_size_type'] = $this->language->get('text_size_type');
        $data['text_size_system'] = $this->language->get('text_size_system');
        $data['text_shipping'] = $this->language->get('text_shipping');
        $data['text_google_adsense'] = $this->language->get('text_google_adsense');
        $data['text_custom_label_0'] = $this->language->get('text_custom_label_0');
        $data['text_custom_label_1'] = $this->language->get('text_custom_label_1');
        $data['text_custom_label_2'] = $this->language->get('text_custom_label_2');
        $data['text_custom_label_3'] = $this->language->get('text_custom_label_3');
        $data['text_custom_label_4'] = $this->language->get('text_custom_label_4');
        $data['identifier_field'] = $this->language->get('identifier_field');

        $data['required'] = $this->language->get('required');
        $data['remote'] = $this->language->get('remote');
        $data['email'] = $this->language->get('email');
        $data['url'] = $this->language->get('url');
        $data['date'] = $this->language->get('date');
        $data['dateISO'] = $this->language->get('dateISO');
        $data['number'] = $this->language->get('number');
        $data['digits'] = $this->language->get('digits');
        $data['creditcard'] = $this->language->get('creditcard');
        $data['equalTo'] = $this->language->get('equalTo');
        $data['maxlength'] = $this->language->get('maxlength');
        $data['minlength'] = $this->language->get('minlength');
        $data['rangelength'] = $this->language->get('rangelength');
        $data['range'] = $this->language->get('range');
        $data['max'] = $this->language->get('max');
        $data['min'] = $this->language->get('min');
        $data['ocmultiselect'] = $this->language->get('ocmultiselect');

        $data['text_change'] = $this->language->get('text_change');
        $data['mandatory'] = $this->language->get('mandatory');
        $data['price'] = $this->language->get('price');
        $data['decimalNotRequired'] = $this->language->get('decimalNotRequired');
        $data['email'] = $this->language->get('email');
        $data['passwd'] = $this->language->get('passwd');
        $data['notRequiredPasswd'] = $this->language->get('notRequiredPasswd');
        $data['mobile'] = $this->language->get('mobile');
        $data['addressLine1'] = $this->language->get('addressLine1');
        $data['addressLine2'] = $this->language->get('addressLine2');
        $data['digit'] = $this->language->get('digit');
        $data['notRequiredDigit'] = $this->language->get('notRequiredDigit');
        $data['firstname'] = $this->language->get('firstname');
        $data['lastname'] = $this->language->get('lastname');
        $data['middlename'] = $this->language->get('middlename');
        $data['requiredMin2Max60NoSpecial'] = $this->language->get('requiredMin2Max60NoSpecial');
        $data['requiredip'] = $this->language->get('requiredip');
        $data['optionalip'] = $this->language->get('optionalip');
        $data['requiredimage'] = $this->language->get('requiredimage');
        $data['optionalimage'] = $this->language->get('optionalimage');
        $data['requiredcharonly'] = $this->language->get('requiredcharonly');
        $data['optionalcharonly'] = $this->language->get('optionalcharonly');
        $data['barcode'] = $this->language->get('barcode');
        $data['ean'] = $this->language->get('ean');
        $data['upc'] = $this->language->get('upc');
        $data['size'] = $this->language->get('size');
        $data['requiredurl'] = $this->language->get('requiredurl');
        $data['optionalurl'] = $this->language->get('optionalurl');
        $data['carrier'] = $this->language->get('carrier');
        $data['brand'] = $this->language->get('brand');
        $data['optionalcompany'] = $this->language->get('optionalcompany');
        $data['requiredcompany'] = $this->language->get('requiredcompany');
        $data['sku'] = $this->language->get('sku');
        $data['requiredmmddyy'] = $this->language->get('requiredmmddyy');
        $data['optionalmmddyy'] = $this->language->get('optionalmmddyy');
        $data['requiredddmmyy'] = $this->language->get('requiredddmmyy');
        $data['optionalddmmyy'] = $this->language->get('optionalddmmyy');
        $data['optionalpercentage'] = $this->language->get('optionalpercentage');
        $data['requiredpercentage'] = $this->language->get('requiredpercentage');
        $data['checktags'] = $this->language->get('checktags');
        $data['checkhtmltags'] = $this->language->get('checkhtmltags');
        $data['requireddocs'] = $this->language->get('requireddocs');
        $data['optionaldocs'] = $this->language->get('optionaldocs');
        $data['requiredcolor'] = $this->language->get('requiredcolor');
        $data['optionalcolor'] = $this->language->get('optionalcolor');

        //Tooltips
        if (isset($this->request->get['id_gs_profiles'])) {
            $data['text_edit_profile_add'] = $this->language->get('text_edit_profile_edit');
        } else {
            $data['text_edit_profile_add'] = $this->language->get('text_edit_profile_add');
        }

        //buttons
        $data['button_save'] = $this->language->get('button_save');
        $data['button_save_and_stay'] = $this->language->get('button_save_and_stay');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_add_module'] = $this->language->get('button_add_module');
        $data['button_remove'] = $this->language->get('button_remove');
        $data['token_key'] = $this->session_token_key;
        $data['module_path'] = $this->module_path;
    
        $this->load->model('localisation/currency');
        $oc_currencies = $this->model_localisation_currency->getCurrencies();
        $oc_currencies_array = array();
        foreach($oc_currencies  as $oc_currency) {
            $oc_currencies_array[$oc_currency["code"]] = $oc_currency["code"];
        }
        $data['oc_currencies'] = $oc_currencies_array;
        
        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }
        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else {
            $data['error'] = '';
        }
        if (isset($this->error['googleshopping_profile_title'])) {
            $data['error_googleshopping_profile_title'] = $this->error['googleshopping_profile_title'];
        } else {
            $data['error_googleshopping_profile_title'] = '';
        }
        if (isset($this->error['googleshopping_language'])) {
            $data['error_googleshopping_language'] = $this->error['googleshopping_language'];
        } else {
            $data['error_googleshopping_language'] = '';
        }
        if (isset($this->error['googleshopping_currency'])) {
            $data['error_googleshopping_currency'] = $this->error['googleshopping_currency'];
        } else {
            $data['error_googleshopping_currency'] = '';
        }
        if (isset($this->error['googleshopping_category'])) {
            $data['error_googleshopping_category'] = $this->error['googleshopping_category'];
        } else {
            $data['error_googleshopping_category'] = '';
        }
        if (isset($this->error['googleshopping_shipping'])) {
            $data['error_googleshopping_shipping'] = $this->error['googleshopping_shipping'];
        } else {
            $data['error_googleshopping_shipping'] = '';
        }
        if (isset($this->error['google_store_category'])) {
            $data['error_google_store_category'] = $this->error['google_store_category'];
        } else {
            $data['error_google_store_category'] = '';
        }
        $googleshopping_gtin = array(
            '0' => array(
                'id_gtin' => 'UPC',
                'name' => 'UPC'
            ),
            '1' => array(
                'id_gtin' => 'MPN',
                'name' => 'MPN'
            ),
            '2' => array(
                'id_gtin' => 'ISBN',
                'name' => 'ISBN'
            ),
            '3' => array(
                'id_gtin' => 'EAN',
                'name' => 'EAN'
            ),
            '4' => array(
                'id_gtin' => 'JAN',
                'name' => 'JAN'
            )
        );
        $material = array(
            '0' => array(
                'id' => '',
                'name' => $this->language->get('text_select')
            ),
            '1' => array(
                'id' => 'Height',
                'name' => 'Height'
            ),
            '2' => array(
                'id' => 'Width',
                'name' => 'Width'
            ),
            '3' => array(
                'id' => 'Depth',
                'name' => 'Depth'
            ),
            '4' => array(
                'id' => 'Weight',
                'name' => 'Weight'
            ),
            '5' => array(
                'id' => 'Compositions',
                'name' => 'Compositions'
            ),
            '6' => array(
                'id' => 'Styles',
                'name' => 'Styles'
            ),
            '7' => array(
                'id' => 'Properties',
                'name' => 'Properties'
            ),
        );
        $gender = array(
            '0' => array(
                'id' => '',
                'name' => $this->language->get('text_select')
            ),
            '1' => array(
                'id' => 'Male',
                'name' => 'Male'
            ),
            '2' => array(
                'id' => 'Female',
                'name' => 'Female'
            ),
            '3' => array(
                'id' => 'Unisex',
                'name' => 'Unisex'
            ),
        );
        $age_group = array(
            '0' => array(
                'id' => '',
                'name' => $this->language->get('text_select')
            ),
            '1' => array(
                'id' => 'Newborn',
                'name' => 'Newborn'
            ),
            '2' => array(
                'id' => 'Infant',
                'name' => 'Infant'
            ),
            '3' => array(
                'id' => 'Toddler',
                'name' => 'Toddler'
            ),
            '4' => array(
                'id' => 'Kids',
                'name' => 'Kids'
            ),
            '5' => array(
                'id' => 'Adult',
                'name' => 'Adult'
            ),
        );
        $adult_content = array(
            '0' => array(
                'id' => '0',
                'name' => $this->language->get('text_no')
            ),
            '1' => array(
                'id' => '1',
                'name' => $this->language->get('text_yes')
            ),
        );

        $size_type = array(
            '0' => array(
                'id' => '',
                'name' => $this->language->get('text_select')
            ),
            '1' => array(
                'id' => 'Regular',
                'name' => 'Regular'
            ),
            '2' => array(
                'id' => 'Petite',
                'name' => 'Petite'
            ),
            '3' => array(
                'id' => 'Plus',
                'name' => 'Plus'
            ),
            '4' => array(
                'id' => 'Big and Tall',
                'name' => 'Big and Tall'
            ),
            '5' => array(
                'id' => 'Maternity',
                'name' => 'Maternity'
            ),
        );
        $size_system = array(
            '0' => array(
                'id' => '',
                'name' => $this->language->get('text_select')
            ),
            '1' => array(
                'id' => 'AU',
                'name' => 'AU'
            ),
            '2' => array(
                'id' => 'BR',
                'name' => 'BR'
            ),
            '3' => array(
                'id' => 'CN',
                'name' => 'CN'
            ),
            '4' => array(
                'id' => 'DE',
                'name' => 'DE'
            ),
            '5' => array(
                'id' => 'EU',
                'name' => 'EU'
            ),
            '6' => array(
                'id' => 'FR',
                'name' => 'FR'
            ),
            '7' => array(
                'id' => 'IT',
                'name' => 'IT'
            ),
            '8' => array(
                'id' => 'JP',
                'name' => 'JP'
            ),
            '9' => array(
                'id' => 'MEX',
                'name' => 'MEX'
            ),
            '10' => array(
                'id' => 'UK',
                'name' => 'UK'
            ),
            '11' => array(
                'id' => 'GB',
                'name' => 'GB'
            ),
        );

        $data['shippings'] = array();
        
        if (VERSION < 2.3) {
            $files = glob(DIR_APPLICATION . 'controller/shipping/*.php');
        } else {
            $files = glob(DIR_APPLICATION . 'controller/extension/shipping/*.php');
        }

        if ($files) {
            foreach ($files as $file) {
                $extension = basename($file, '.php');
                if (VERSION < 2.3) {
                    $this->load->language('shipping/' . $extension);
                } else {
                    $this->load->language('extension/shipping/' . $extension);
                }
                if (VERSION > 2.3) {
                    if ($this->config->get('shipping_'.$extension . '_status')) {
                        $data['shippings'][] = array(
                            'name' => $this->language->get('heading_title'),
                            'status' => $this->config->get($extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                            'code' => $extension,
                        );
                    }
                } else {
                    if ($this->config->get($extension . '_status')) {
                        $data['shippings'][] = array(
                            'name' => $this->language->get('heading_title'),
                            'status' => $this->config->get($extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                            'code' => $extension,
                        );
                    }
                }
            }
        }
        if (isset($this->request->post['googleshopping']['profile']['country'])) {
            
            $data['google_select_countries'] = $this->request->post['googleshopping']['profile']['country'];
            $data['currencys'] = $this->model_setting_googleshopping->ajaxProcessGetCurrency($this->request->post['googleshopping']['profile']['country']);
            $data['languages'] = $this->model_setting_googleshopping->ajaxProcessGetLanguages($this->request->post['googleshopping']['profile']['country']);
        }
        
        $this->load->model('localisation/language');
        $oc_currencies = $this->model_localisation_language->getLanguages(array("sort_by" => "name", "order" => "ASC"));
        $data['opencart_languages'] = $oc_currencies;
        
        if (isset($this->request->post['googleshopping']['profile']['language'])) {
            $data['selected_language'] = $this->request->post['googleshopping']['profile']['language'];
        }
        
        if (isset($this->request->post['googleshopping']['profile']['opencart_language'])) {
            $data['selected_oc_language'] = $this->request->post['googleshopping']['profile']['opencart_language'];
        }
        
        if (isset($this->request->post['googleshopping']['profile']['currency'])) {
            $data['selected_currency'] = $this->request->post['googleshopping']['profile']['currency'];
        }
        if (isset($this->request->post['googleshopping']['profile']['profile_title'])) {
            $data['googleshopping']['profile']['profile_title'] = $this->request->post['googleshopping']['profile']['profile_title'];
        }
        if (isset($this->request->post['googleshopping']['profile']['gtin'])) {
            $data['selected_gtin'] = $this->request->post['googleshopping']['profile']['gtin'];
        }
        if (isset($this->request->post['googleshopping']['profile']['material'])) {
            $data['selected_material'] = $this->request->post['googleshopping']['profile']['material'];
        }
        if (isset($this->request->post['googleshopping']['profile']['pattern'])) {
            $data['selected_pattern'] = $this->request->post['googleshopping']['profile']['pattern'];
        }
        if (isset($this->request->post['googleshopping']['profile']['gender'])) {
            $data['selected_gender'] = $this->request->post['googleshopping']['profile']['gender'];
        }
        if (isset($this->request->post['googleshopping']['profile']['age_group'])) {
            $data['selected_age_group'] = $this->request->post['googleshopping']['profile']['age_group'];
        }
        if (isset($this->request->post['googleshopping']['profile']['adult_content'])) {
            $data['selected_content'] = $this->request->post['googleshopping']['profile']['adult_content'];
        }
        if (isset($this->request->post['googleshopping']['profile']['color'])) {
            $data['selected_color'] = $this->request->post['googleshopping']['profile']['color'];
        }
        if (isset($this->request->post['googleshopping']['profile']['size'])) {
            $data['selected_size'] = $this->request->post['googleshopping']['profile']['size'];
        }
        if (isset($this->request->post['googleshopping']['profile']['size_type'])) {
            $data['selected_size_type'] = $this->request->post['googleshopping']['profile']['size_type'];
        }
        if (isset($this->request->post['googleshopping']['profile']['size_system'])) {
            $data['selected_system'] = $this->request->post['googleshopping']['profile']['size_system'];
        }
        if (isset($this->request->post['googleshopping']['profile']['custom_label_0'])) {
            $data['googleshopping']['profile']['custom_label_0'] = $this->request->post['googleshopping']['profile']['custom_label_0'];
        }
        if (isset($this->request->post['googleshopping']['profile']['custom_label_1'])) {
            $data['googleshopping']['profile']['custom_label_1'] = $this->request->post['googleshopping']['profile']['custom_label_1'];
        }
        if (isset($this->request->post['googleshopping']['profile']['custom_label_2'])) {
            $data['googleshopping']['profile']['custom_label_2'] = $this->request->post['googleshopping']['profile']['custom_label_2'];
        }
        if (isset($this->request->post['googleshopping']['profile']['custom_label_3'])) {
            $data['googleshopping']['profile']['custom_label_3'] = $this->request->post['googleshopping']['profile']['custom_label_3'];
        }
        if (isset($this->request->post['googleshopping']['profile']['custom_label_4'])) {
            $data['googleshopping']['profile']['custom_label_4'] = $this->request->post['googleshopping']['profile']['custom_label_4'];
        }
        $options[] = array("id" => "", "name" => $this->language->get('select_option'));

        $productOption = $this->model_catalog_option->getOptions();
        if (!empty($productOption)) {
            foreach ($productOption as $option) {
                $options[] = array("id" => $option["option_id"], "name" => $option["name"]);
            }
        }

        $data['googleshopping']['profile']['shipping'] = array();
        $enabled_countries = $this->model_setting_googleshopping->getEnabledCountries();
        $data['google_categories'] = $this->model_setting_googleshopping->getGoogleCategories();
        $data['enabled_countries'] = $enabled_countries;
        $data['googleshopping_gtin'] = $googleshopping_gtin;
        $data['material'] = $material;
        $data['gender'] = $gender;
        $data['age_group'] = $age_group;
        $data['adult_content'] = $adult_content;
        $data['options'] = $options;
        $data['size_type'] = $size_type;
        $data['size_system'] = $size_system;
        $data['image_loader'] = HTTPS_SERVER . 'view/image/loader.gif';
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $active_tab['active_tab'] = 3;
        $data['googleshopping_common'] = $this->load->controller($this->module_path . '/googleshopping/common', $active_tab);

        if (VERSION >= '2.2.0.0') {
            $this->response->setOutput($this->load->view($this->module_path . '/kbgoogleshopping/googleshopping_profile_add', $data));
        } else {
            $this->response->setOutput($this->load->view($this->module_path . '/kbgoogleshopping/googleshopping_profile_add.tpl', $data));
        }
    }

    public function addFeed()
    {
        $this->load->language($this->module_path . '/googleshopping');
        $this->document->setTitle($this->language->get('heading_title_main'));

        $this->load->model('setting/setting');
        $this->load->model('setting/googleshopping');
        $this->load->model('catalog/category');
        $this->load->model('localisation/currency');
        if (isset($this->request->get['store_id'])) {
            $store_id = $this->request->get['store_id'];
        } else {
            $store_id = 0;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $id_gs_feed = "";
            if(!empty($this->request->post['googleshopping']['feed']['id_gs_feed'])) {
                $id_gs_feed = $this->request->post['googleshopping']['feed']['id_gs_feed'];
            }
            if (!$this->model_setting_googleshopping->checkFeed($this->request->post['googleshopping']['feed']['profile'], $id_gs_feed)) {
                $this->session->data['error'] = $this->language->get('googleshopping_text_error_mapped');
            } else {
                if (isset($this->request->post['googleshopping']['feed']['id_gs_feed']) && $this->request->post['googleshopping']['feed']['id_gs_feed'] != '') {
                    $this->model_setting_googleshopping->editFeed($this->request->post);
                    $this->session->data['success'] = $this->language->get('googleshopping_feed_update_success');
                } else {
                    $this->session->data['success'] = $this->language->get('googleshopping_feed_add_success');
                    $this->model_setting_googleshopping->addFeed($this->request->post);
                }
                $this->response->redirect($this->url->link($this->module_path . '/googleshopping/feedManagement', $this->session_token_key . '=' . $this->session_token, true));
                die();
            }
        }
        
        if (isset($this->request->get['id_gs_feed'])) {
            $feed_details = $this->model_setting_googleshopping->getFeedDetails(array(), $this->request->get['id_gs_feed']);
            $data['googleshopping'] = array();
            $data['googleshopping'] = array(
                'feed' => array(
                    'feed_label' => $feed_details[0]['feed_label'],
                )
            );
            $data['google_select_profile'] = $feed_details[0]['id_gs_profiles'];
            $data['google_selected_schedule'] = $feed_details[0]['upload_type'];
            $data['selected_weekday'] = $feed_details[0]['weekday'];
            $data['selected_month'] = $feed_details[0]['day_of_month'];
            $data['selected_hour'] = $feed_details[0]['upload_hour'];
            $data['id_gs_feed'] = $feed_details[0]['id_gs_feed'];
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $data['google_select_profile'] = $this->request->post['googleshopping']['feed']['profile'];
            $data['googleshopping']['feed']['feed_label'] = $this->request->post['googleshopping']['feed']['feed_label'];
            $data['google_selected_schedule'] = $this->request->post['googleshopping']['feed']['schedule'];
            $data['selected_weekday'] = $this->request->post['googleshopping']['feed']['weekday'];
            $data['selected_month'] = $this->request->post['googleshopping']['feed']['day_of_month'];
            $data['selected_hour'] = $this->request->post['googleshopping']['feed']['hour'];
            $data['id_gs_feed'] = $this->request->post['googleshopping']['feed']['id_gs_feed'];
        } else {
            $data['googleshopping']['feed']['feed_label'] = '';
            $data['google_select_profile'] = '';
            $data['google_selected_schedule'] = '';
            $data['selected_weekday'] = '';
            $data['selected_month'] = '';
            $data['selected_hour'] = '';
            $data['id_gs_feed'] = '';
        }
        
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', $this->session_token_key . '=' . $this->session_token, true),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link($this->extension_path, $this->session_token_key . '=' . $this->session_token, true),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_main'),
            'href' => $this->url->link($this->module_path . '/googleshopping', $this->session_token_key . '=' . $this->session_token, true),
            'separator' => ' :: '
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_feed_management'),
            'href' => $this->url->link($this->module_path . '/googleshopping/feedManagement', $this->session_token_key . '=' . $this->session_token, true),
            'separator' => ' :: '
        );
        if (isset($this->request->get['id_gs_feed'])) {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title_feed_edit'),
                'href' => $this->url->link($this->module_path . '/googleshopping/addFeed', $this->session_token_key . '=' . $this->session_token, true),
                'separator' => ' :: '
            );
        } else {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title_feed_add'),
                'href' => $this->url->link($this->module_path . '/googleshopping/addFeed', $this->session_token_key . '=' . $this->session_token, true),
                'separator' => ' :: '
            );
        }
        //links
        $data['general_settings'] = $this->url->link($this->module_path . '/googleshopping/addFeed', $this->session_token_key . '=' . $this->session_token, true);
        if (isset($this->request->get['id_gs_feed'])) {
            $data['action'] = $this->url->link($this->module_path . '/googleshopping/addFeed', $this->session_token_key . '=' . $this->session_token . "&id_gs_feed=" . $this->request->get['id_gs_feed'], true);
        } else {
            $data['action'] = $this->url->link($this->module_path . '/googleshopping/addFeed', $this->session_token_key . '=' . $this->session_token, true);
        }
        
        $data['route'] = $this->url->link($this->module_path . '/googleshopping/addFeed', $this->session_token_key . '=' . $this->session_token, true);
        $data['cancel'] = $this->url->link($this->module_path . '/googleshopping/feedManagement', $this->session_token_key . '=' . $this->session_token, true);
        $data['token'] = $this->session_token;

        $data['heading_title'] = $this->language->get('heading_title');
        $data['heading_title_main'] = $this->language->get('heading_title_main');

        // General Settings tab & info
        $data['save_general'] = $this->language->get('save_general');
        $data['text_data_tab'] = $this->language->get('text_data_tab');
        $data['text_specifics'] = $this->language->get('text_specifics');
        $data['text_profile_title'] = $this->language->get('text_profile_title');
        $data['text_ebay_category'] = $this->language->get('text_ebay_category');
        $data['text_store_category'] = $this->language->get('text_store_category');
        $data['text_shipping_template'] = $this->language->get('text_shipping_template');
        $data['text_walmart_currency'] = $this->language->get('text_walmart_currency');
        $data['text_who_made'] = $this->language->get('text_who_made');
        $data['text_when_made'] = $this->language->get('text_when_made');
        $data['text_recipient'] = $this->language->get('text_recipient');
        $data['text_occasion'] = $this->language->get('text_occasion');
        $data['text_general_store_name'] = $this->language->get('text_general_store_name');
        $data['text_general_walmart_quantity'] = $this->language->get('text_general_walmart_quantity');
        $data['text_general_guestenable'] = $this->language->get('text_general_guestenable');
        $data['text_walmart_api_detail'] = $this->language->get('text_walmart_api_detail');
        $data['text_walmart_api_detail_title'] = $this->language->get('text_walmart_api_detail_title');
        $data['text_walmart_store'] = $this->language->get('text_walmart_store');
        $data['text_walmart_merchant_id'] = $this->language->get('text_walmart_merchant_id');
        $data['text_walmart_market_place_id'] = $this->language->get('text_walmart_market_place_id');
        $data['text_walmart_consumer_id'] = $this->language->get('text_walmart_consumer_id');
        $data['text_walmart_private_key'] = $this->language->get('text_walmart_private_key');
        $data['text_walmart_consumer_channel'] = $this->language->get('text_walmart_consumer_channel');
        $data['text_product_listing'] = $this->language->get('text_product_listing');
        $data['text_product_update'] = $this->language->get('text_product_update');
        $data['text_product_id'] = $this->language->get('text_product_id');
        $data['text_product_name'] = $this->language->get('text_product_name');
        $data['text_product_sku'] = $this->language->get('text_product_sku');
        $data['text_product_price'] = $this->language->get('text_product_price');
        $data['text_product_quantity'] = $this->language->get('text_product_quantity');
        $data['text_status'] = $this->language->get('text_status');
        $data['text_action'] = $this->language->get('text_action');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_select_attr'] = $this->language->get('text_select_attr');
        $data['text_ebay_sites'] = $this->language->get('text_ebay_sites');
        $data['text_ebay_category_select'] = $this->language->get('text_ebay_category_select');
        $data['text_duration'] = $this->language->get('text_duration');
        $data['text_product_condition'] = $this->language->get('text_product_condition');
        $data['text_product_quantity'] = $this->language->get('text_product_quantity');
        $data['text_product_dispatch_time'] = $this->language->get('text_product_dispatch_time');
        $data['text_profile_return'] = $this->language->get('text_profile_return');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_return_time'] = $this->language->get('text_return_time');
        $data['text_return_type'] = $this->language->get('text_return_type');
        $data['text_return_shipping'] = $this->language->get('text_return_shipping');
        $data['text_googleshopping_profile'] = $this->language->get('text_googleshopping_profile');
        $data['text_googleshopping_language'] = $this->language->get('text_googleshopping_language');
        $data['text_googleshopping_language_select'] = $this->language->get('text_googleshopping_language_select');
        $data['text_googleshopping_currency'] = $this->language->get('text_googleshopping_currency');
        $data['text_googleshopping_currency_select'] = $this->language->get('text_googleshopping_currency_select');
        $data['text_gtin'] = $this->language->get('text_gtin');
        $data['text_customize_title'] = $this->language->get('text_customize_title');
        $data['text_googleshopping_category'] = $this->language->get('text_googleshopping_category');
        $data['text_material'] = $this->language->get('text_material');
        $data['text_pattern'] = $this->language->get('text_pattern');
        $data['text_gender'] = $this->language->get('text_gender');
        $data['text_age_group'] = $this->language->get('text_age_group');
        $data['text_adult_content'] = $this->language->get('text_adult_content');
        $data['text_color'] = $this->language->get('text_color');
        $data['text_size'] = $this->language->get('text_size');
        $data['text_size_type'] = $this->language->get('text_size_type');
        $data['text_size_system'] = $this->language->get('text_size_system');
        $data['text_shipping'] = $this->language->get('text_shipping');
        $data['text_google_adsense'] = $this->language->get('text_google_adsense');
        $data['text_custom_label_0'] = $this->language->get('text_custom_label_0');
        $data['text_custom_label_1'] = $this->language->get('text_custom_label_1');
        $data['text_custom_label_2'] = $this->language->get('text_custom_label_2');
        $data['text_custom_label_3'] = $this->language->get('text_custom_label_3');
        $data['text_custom_label_4'] = $this->language->get('text_custom_label_4');

        $data['text_feed_label'] = $this->language->get('text_feed_label');
        $data['text_googleshopping_schedule'] = $this->language->get('text_googleshopping_schedule');
        $data['text_weekday'] = $this->language->get('text_weekday');
        $data['text_day_of_month'] = $this->language->get('text_day_of_month');
        $data['text_hour'] = $this->language->get('text_hour');

        $data['required'] = $this->language->get('required');
        $data['remote'] = $this->language->get('remote');
        $data['email'] = $this->language->get('email');
        $data['url'] = $this->language->get('url');
        $data['date'] = $this->language->get('date');
        $data['dateISO'] = $this->language->get('dateISO');
        $data['number'] = $this->language->get('number');
        $data['digits'] = $this->language->get('digits');
        $data['creditcard'] = $this->language->get('creditcard');
        $data['equalTo'] = $this->language->get('equalTo');
        $data['maxlength'] = $this->language->get('maxlength');
        $data['minlength'] = $this->language->get('minlength');
        $data['rangelength'] = $this->language->get('rangelength');
        $data['range'] = $this->language->get('range');
        $data['max'] = $this->language->get('max');
        $data['min'] = $this->language->get('min');


        $data['mandatory'] = $this->language->get('mandatory');
        $data['price'] = $this->language->get('price');
        $data['decimalNotRequired'] = $this->language->get('decimalNotRequired');
        $data['email'] = $this->language->get('email');
        $data['passwd'] = $this->language->get('passwd');
        $data['notRequiredPasswd'] = $this->language->get('notRequiredPasswd');
        $data['mobile'] = $this->language->get('mobile');
        $data['addressLine1'] = $this->language->get('addressLine1');
        $data['addressLine2'] = $this->language->get('addressLine2');
        $data['digit'] = $this->language->get('digit');
        $data['notRequiredDigit'] = $this->language->get('notRequiredDigit');
        $data['firstname'] = $this->language->get('firstname');
        $data['lastname'] = $this->language->get('lastname');
        $data['middlename'] = $this->language->get('middlename');
        $data['requiredMin2Max60NoSpecial'] = $this->language->get('requiredMin2Max60NoSpecial');
        $data['requiredip'] = $this->language->get('requiredip');
        $data['optionalip'] = $this->language->get('optionalip');
        $data['requiredimage'] = $this->language->get('requiredimage');
        $data['optionalimage'] = $this->language->get('optionalimage');
        $data['requiredcharonly'] = $this->language->get('requiredcharonly');
        $data['optionalcharonly'] = $this->language->get('optionalcharonly');
        $data['barcode'] = $this->language->get('barcode');
        $data['ean'] = $this->language->get('ean');
        $data['upc'] = $this->language->get('upc');
        $data['size'] = $this->language->get('size');
        $data['requiredurl'] = $this->language->get('requiredurl');
        $data['optionalurl'] = $this->language->get('optionalurl');
        $data['carrier'] = $this->language->get('carrier');
        $data['brand'] = $this->language->get('brand');
        $data['optionalcompany'] = $this->language->get('optionalcompany');
        $data['requiredcompany'] = $this->language->get('requiredcompany');
        $data['sku'] = $this->language->get('sku');
        $data['requiredmmddyy'] = $this->language->get('requiredmmddyy');
        $data['optionalmmddyy'] = $this->language->get('optionalmmddyy');
        $data['requiredddmmyy'] = $this->language->get('requiredddmmyy');
        $data['optionalddmmyy'] = $this->language->get('optionalddmmyy');
        $data['optionalpercentage'] = $this->language->get('optionalpercentage');
        $data['requiredpercentage'] = $this->language->get('requiredpercentage');
        $data['checktags'] = $this->language->get('checktags');
        $data['checkhtmltags'] = $this->language->get('checkhtmltags');
        $data['requireddocs'] = $this->language->get('requireddocs');
        $data['optionaldocs'] = $this->language->get('optionaldocs');
        $data['requiredcolor'] = $this->language->get('requiredcolor');
        $data['optionalcolor'] = $this->language->get('optionalcolor');

        //Tooltips
        if (isset($this->request->get['id_gs_feed'])) {
            $data['text_edit_feed_add'] = $this->language->get('text_edit_feed_edit');
        } else {
            $data['text_edit_feed_add'] = $this->language->get('text_edit_feed_add');
        }

        //buttons
        $data['button_save'] = $this->language->get('button_save');
        $data['button_save_and_stay'] = $this->language->get('button_save_and_stay');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_add_module'] = $this->language->get('button_add_module');
        $data['button_remove'] = $this->language->get('button_remove');
        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }
        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else {
            $data['error'] = '';
        }
        if (isset($this->error['googleshopping_feed_label'])) {
            $data['error_googleshopping_feed_label'] = $this->error['googleshopping_feed_label'];
        } else {
            $data['error_googleshopping_feed_label'] = '';
        }
        if (isset($this->error['walmart_store_category'])) {
            $data['error_walmart_store_category'] = $this->error['walmart_store_category'];
        } else {
            $data['error_walmart_store_category'] = '';
        }
        if (isset($this->error['ebay_product_quantity'])) {
            $data['error_ebay_product_quantity'] = $this->error['ebay_product_quantity'];
        } else {
            $data['error_ebay_product_quantity'] = '';
        }
        if (isset($this->error['ebay_product_dispatch_time'])) {
            $data['error_ebay_product_dispatch_time'] = $this->error['ebay_product_dispatch_time'];
        } else {
            $data['error_ebay_product_dispatch_time'] = '';
        }
        $schedules = array(
            '1' => array(
                'id' => 'daily',
                'name' => $this->language->get('text_daily')
            ),
            '2' => array(
                'id' => 'weekly',
                'name' => $this->language->get('text_weekly')
            ),
            '3' => array(
                'id' => 'monthly',
                'name' => $this->language->get('text_monthly')
            ),
        );
        $weekdays = array(
            '1' => array(
                'id' => 'sunday',
                'name' => $this->language->get('text_sunday')
            ),
            '2' => array(
                'id' => 'monday',
                'name' => $this->language->get('text_monday')
            ),
            '3' => array(
                'id' => 'tuesday',
                'name' => $this->language->get('text_tuesday')
            ),
            '4' => array(
                'id' => 'wednesday',
                'name' => $this->language->get('text_wednesday')
            ),
            '5' => array(
                'id' => 'thursday',
                'name' => $this->language->get('text_thursday')
            ),
            '6' => array(
                'id' => 'friday',
                'name' => $this->language->get('text_friday')
            ),
            '7' => array(
                'id' => 'saturday',
                'name' => $this->language->get('text_saturday')
            ),
        );
        $day_of_month = array(
            '1' => array(
                'id' => '1',
                'name' => '1'
            ),
            '2' => array(
                'id' => '2',
                'name' => '2'
            ),
            '3' => array(
                'id' => '3',
                'name' => '3'
            ),
            '4' => array(
                'id' => '4',
                'name' => '4'
            ),
            '5' => array(
                'id' => '5',
                'name' => '5'
            ),
            '6' => array(
                'id' => '6',
                'name' => '6'
            ),
            '7' => array(
                'id' => '7',
                'name' => '7'
            ),
            '8' => array(
                'id' => '8',
                'name' => '8'
            ),
            '9' => array(
                'id' => '9',
                'name' => '9'
            ),
            '10' => array(
                'id' => '10',
                'name' => '10'
            ),
            '11' => array(
                'id' => '11',
                'name' => '11'
            ),
            '12' => array(
                'id' => '12',
                'name' => '12'
            ),
            '13' => array(
                'id' => '13',
                'name' => '13'
            ),
            '14' => array(
                'id' => '14',
                'name' => '14'
            ),
            '15' => array(
                'id' => '15',
                'name' => '15'
            ),
            '16' => array(
                'id' => '16',
                'name' => '16'
            ),
            '17' => array(
                'id' => '17',
                'name' => '17'
            ),
            '18' => array(
                'id' => '18',
                'name' => '18'
            ),
            '19' => array(
                'id' => '19',
                'name' => '19'
            ),
            '20' => array(
                'id' => '20',
                'name' => '20'
            ),
            '21' => array(
                'id' => '21',
                'name' => '21'
            ),
            '22' => array(
                'id' => '22',
                'name' => '22'
            ),
            '23' => array(
                'id' => '23',
                'name' => '23'
            ),
            '24' => array(
                'id' => '24',
                'name' => '24'
            ),
            '25' => array(
                'id' => '25',
                'name' => '25'
            ),
            '26' => array(
                'id' => '26',
                'name' => '26'
            ),
            '27' => array(
                'id' => '27',
                'name' => '27'
            ),
            '28' => array(
                'id' => '28',
                'name' => '28'
            ),
            '29' => array(
                'id' => '29',
                'name' => '29'
            ),
            '30' => array(
                'id' => '30',
                'name' => '30'
            ),
            '31' => array(
                'id' => '31',
                'name' => '31'
            ),
        );
        $hours = array(
            '0' => array(
                'id' => '0',
                'name' => '0'
            ),
            '1' => array(
                'id' => '1',
                'name' => '1'
            ),
            '2' => array(
                'id' => '2',
                'name' => '2'
            ),
            '3' => array(
                'id' => '3',
                'name' => '3'
            ),
            '4' => array(
                'id' => '4',
                'name' => '4'
            ),
            '5' => array(
                'id' => '5',
                'name' => '5'
            ),
            '6' => array(
                'id' => '6',
                'name' => '6'
            ),
            '7' => array(
                'id' => '7',
                'name' => '7'
            ),
            '8' => array(
                'id' => '8',
                'name' => '8'
            ),
            '9' => array(
                'id' => '9',
                'name' => '9'
            ),
            '10' => array(
                'id' => '10',
                'name' => '10'
            ),
            '11' => array(
                'id' => '11',
                'name' => '11'
            ),
            '12' => array(
                'id' => '12',
                'name' => '12'
            ),
            '13' => array(
                'id' => '13',
                'name' => '13'
            ),
            '14' => array(
                'id' => '14',
                'name' => '14'
            ),
            '15' => array(
                'id' => '15',
                'name' => '15'
            ),
            '16' => array(
                'id' => '16',
                'name' => '16'
            ),
            '17' => array(
                'id' => '17',
                'name' => '17'
            ),
            '18' => array(
                'id' => '18',
                'name' => '18'
            ),
            '19' => array(
                'id' => '19',
                'name' => '19'
            ),
            '20' => array(
                'id' => '20',
                'name' => '20'
            ),
            '21' => array(
                'id' => '21',
                'name' => '21'
            ),
            '22' => array(
                'id' => '22',
                'name' => '22'
            ),
            '23' => array(
                'id' => '23',
                'name' => '23'
            )
        );
        $profile_result = $this->model_setting_googleshopping->getProfileDetails($filter_data = array());
        $data['profile_details'] = $profile_result;
        $data['schedules'] = $schedules;
        $data['weekdays'] = $weekdays;
        $data['day_of_month'] = $day_of_month;
        $data['hours'] = $hours;
        $data['image_loader'] = HTTPS_SERVER . 'view/image/loader.gif';
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $active_tab['active_tab'] = 4;
        $data['googleshopping_common'] = $this->load->controller($this->module_path . '/googleshopping/common', $active_tab);

        if (VERSION >= '2.2.0.0') {
            $this->response->setOutput($this->load->view($this->module_path . '/kbgoogleshopping/googleshopping_feed_add', $data));
        } else {
            $this->response->setOutput($this->load->view($this->module_path . '/kbgoogleshopping/googleshopping_feed_add.tpl', $data));
        }
    }

    private function validate()
    {
        $this->load->model('setting/googleshopping');
        $this->error = array();
        if (!$this->user->hasPermission('modify', $this->module_path . '/googleshopping')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        if (isset($this->request->post['etsy']['general'])) {
            if (!$this->request->post['etsy']['general']['api_key']) {
                $this->error['etsy_api_key'] = $this->language->get('error_etsy_api_key');
            }
            if (!$this->request->post['etsy']['general']['api_secret']) {
                $this->error['etsy_api_secret'] = $this->language->get('error_etsy_api_secret');
            }
            if (!$this->request->post['etsy']['general']['api_host']) {
                $this->error['etsy_api_host'] = $this->language->get('error_etsy_api_host');
            }
        }
        if (isset($this->request->post['googleshopping']['profile'])) {
            if (!$this->request->post['googleshopping']['profile']['profile_title']) {
                $this->error['googleshopping_profile_title'] = $this->language->get('error_googleshopping_profile_title');
            } else {
                if (isset($this->request->post['googleshopping']['profile']['id_google_profiles']) && $this->request->post['googleshopping']['profile']['id_google_profiles'] != '') {
                    $status = $this->model_setting_googleshopping->checkProfileTitle($this->request->post['googleshopping']['profile']['profile_title'], $this->request->post['googleshopping']['profile']['id_google_profiles']);
                } else {
                    $status = $this->model_setting_googleshopping->checkProfileTitle($this->request->post['googleshopping']['profile']['profile_title']);
                }
                if (!empty($status)) {
                    $this->error['googleshopping_profile_title'] = $this->language->get('error_googleshopping_profile_title_already');
                }
            }
            if (!$this->request->post['googleshopping']['profile']['googleshopping_category']) {
                $this->error['googleshopping_category'] = $this->language->get('error_googleshopping_category');
            }
            if ($this->request->post['googleshopping']['profile']['language'] == '0') {
                $this->error['googleshopping_language'] = $this->language->get('error_googleshopping_language');
            }
            if (!isset($this->request->post['product_category'])) {
                
            } else {
                if (isset($this->request->post['googleshopping']['profile']['id_google_profiles']) && $this->request->post['googleshopping']['profile']['id_google_profiles'] != '') {
                    $status = $this->model_setting_googleshopping->checkProfileCategory($this->request->post['product_category'], $this->request->post['googleshopping']['profile']['country'], $this->request->post['googleshopping']['profile']['id_google_profiles']);
                } else {
                    $status = $this->model_setting_googleshopping->checkProfileCategory($this->request->post['product_category'], $this->request->post['googleshopping']['profile']['country']);
                }
                if (!empty($status)) {
                    $this->error['google_store_category'] = $this->language->get('error_category_already_mapped');
                }
            }
            if (!$this->request->post['googleshopping']['profile']['currency']) {
                $this->error['googleshopping_currency'] = $this->language->get('error_googleshopping_currency');
            }
            if (empty($this->request->post['googleshopping']['profile']['shipping'])) {
                $this->error['googleshopping_shipping'] = $this->language->get('error_googleshopping_shipping');
            }
        }
        if (isset($this->request->post['googleshopping']['feed'])) {
            if (!$this->request->post['googleshopping']['feed']['feed_label']) {
                $this->error['googleshopping_feed_label'] = $this->language->get('error_googleshopping_feed_label');
            }
        }
        return !$this->error;
    }

    private function validateGeneralSettings()
    {
        if (!$this->user->hasPermission('modify', $this->module_path . '/googleshopping')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        if (isset($this->request->post['googleshopping']['general'])) {
            if (!empty($this->request->post['googleshopping']['general']['product_price'])) {
                if (!is_numeric($this->request->post['googleshopping']['general']['product_price'])) {
                    $this->error['product_price'] = $this->language->get('error_googleshopping_product_price_digit');
                } elseif ($this->request->post['googleshopping']['general']['product_price'] < 0) {
                    $this->error['product_price'] = $this->language->get('error_googleshopping_product_price_negative');
                }
            }
        }
        return !$this->error;
    }

    private function validateConnectionSettings()
    {
        if (!$this->user->hasPermission('modify', $this->module_path . '/googleshopping')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        if (isset($this->request->post['googleshopping']['connection'])) {
            if (empty($this->request->post['googleshopping']['connection']['app_name'])) {
                $this->error['googleshopping_app_name'] = $this->language->get('error_googleshopping_app_name');
            }
            if (empty($this->request->post['googleshopping']['connection']['client_id'])) {
                $this->error['googleshopping_client_id'] = $this->language->get('error_googleshopping_client_id');
            }
            if (empty($this->request->post['googleshopping']['connection']['client_secret'])) {
                $this->error['googleshopping_client_secret'] = $this->language->get('error_googleshopping_client_secret');
            }
            if (empty($this->request->post['googleshopping']['connection']['merchant_id'])) {
                $this->error['googleshopping_merchant_id'] = $this->language->get('error_googleshopping_merchant_id');
            }
        }
        return !$this->error;
    }

    public function renewListing()
    {
        $this->load->model('setting/googleshopping');
        $id = $this->request->get['id_gs_products_list'];
        if ($id) {
            $this->model_setting_googleshopping->renewListing($id);
            echo 'success';
        }
    }

    public function deleteProduct()
    {
        $this->load->model('setting/googleshopping');
        $id = $this->request->get['id_gs_products_list'];
        if ($id) {
            $this->model_setting_googleshopping->deleteProduct($id);
            echo 'success';
        }
    }

    public function downloadFeed()
    {
        $this->load->model('setting/googleshopping');
        $id = $this->request->get['id_gs_profiles'];
        if ($id) {
            $this->model_setting_googleshopping->downloadFeed($id);
            echo 'success';
        }
    }

    public function synchronization()
    {
        $this->load->language($this->module_path . '/googleshopping');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', $this->session_token_key . '=' . $this->session_token, true),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link($this->extension_path, $this->session_token_key . '=' . $this->session_token, true),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_main'),
            'href' => $this->url->link($this->module_path . '/googleshopping', $this->session_token_key . '=' . $this->session_token, true),
            'separator' => ' :: '
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_synchronization'),
            'href' => $this->url->link($this->module_path . '/googleshopping/synchronization', $this->session_token_key . '=' . $this->session_token, true),
            'separator' => ' :: '
        );
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }
        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else {
            $data['error'] = '';
        }
        $this->load->model('setting/googleshopping');
        if (isset($this->request->get['store_id'])) {
            $store_id = $this->request->get['store_id'];
        } else {
            $store_id = 0;
        }
        $base_url = HTTPS_CATALOG;
        $settings = $this->model_setting_googleshopping->getSetting('google_general_settings', $store_id);
        if ($settings['google_general_settings']['general']['sync_type'] == 'feed') {
            $data['feed_sync_link'] = $base_url . 'index.php?route=googleshopping/cron/syncFeedsListing';
            $data['text_feed_sync_button'] = $this->language->get('text_feed_sync_button');
            $data['text_feed_sync_default'] = $this->language->get('text_feed_sync_default');
        } else {
            $data['feed_sync_link'] = $base_url . 'index.php?route=googleshopping/cron/syncProductsListing';
            $data['text_feed_sync_button'] = $this->language->get('text_product_sync_button');
            $data['text_feed_sync_default'] = $this->language->get('text_product_sync_default');
        }
        $data['general_enable'] = $settings['google_general_settings']['general']['enable'];
        $data['text_module_not_enabled'] = $this->language->get('text_module_not_enabled');
        $data['text_module_no_profile'] = $this->language->get('text_module_no_profile');
        $data['text_module_no_feed'] = $this->language->get('text_module_no_feed');

        $data['profile_count'] = $this->model_setting_googleshopping->profileCount();
        $data['feed_count'] = $this->model_setting_googleshopping->feedCount();
        $data['product_status_sync_link'] = $base_url . 'index.php?route=googleshopping/cron/syncProductStatus';
        $data['base_url'] = HTTPS_CATALOG . 'index.php?route=googleshopping/cron/jobs&action=';
        $data['heading_title'] = $this->language->get('heading_title');
        $data['heading_title_synchronization'] = $this->language->get('heading_title_synchronization');
        $data['text_country_syncronization'] = $this->language->get('text_country_syncronization');
        $data['text_shipping_template_syncronization'] = $this->language->get('text_shipping_template_syncronization');
        $data['text_product_syncronization'] = $this->language->get('text_product_syncronization');
        $data['text_variation_syncronization'] = $this->language->get('text_variation_syncronization');
        $data['text_order_syncronization'] = $this->language->get('text_order_syncronization');
        $data['text_status_syncronization'] = $this->language->get('text_status_syncronization');
        $data['text_language_syncronization'] = $this->language->get('text_language_syncronization');
        $data['text_sync_now'] = $this->language->get('text_sync_now');
        $data['text_cron_config'] = $this->language->get('text_cron_config');
        $data['text_cron_config_help'] = $this->language->get('text_cron_config_help');
        $data['text_cron_via_cp'] = $this->language->get('text_cron_via_cp');
        $data['text_cron_via_ssh'] = $this->language->get('text_cron_via_ssh');

        $data['text_feed_sync'] = $this->language->get('text_feed_sync');
        $data['text_Synchronization_sync'] = $this->language->get('text_Synchronization_sync');

        $data['text_product_status'] = $this->language->get('text_product_status');
        $data['text_product_status_sync_default'] = $this->language->get('text_product_status_sync_default');
        $data['text_product_status_sync_button'] = $this->language->get('text_product_status_sync_button');
        $data['text_success'] = $this->language->get('text_success');
        $data['token'] = $this->session_token;


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $active_tab['active_tab'] = 6;
        $data['googleshopping_common'] = $this->load->controller($this->module_path . '/googleshopping/common', $active_tab);

        if (VERSION >= '2.2.0.0') {
            $this->response->setOutput($this->load->view($this->module_path . '/kbgoogleshopping/googleshopping_synchronization', $data));
        } else {
            $this->response->setOutput($this->load->view($this->module_path . '/kbgoogleshopping/googleshopping_synchronization.tpl', $data));
        }
    }

    private function checkPositiveNumber($value)
    {
        $filter_options = array(
            'options' => array('min_range' => 0)
        );
        if (filter_var($value, FILTER_VALIDATE_INT, $filter_options) !== FALSE && $value > 0) {
            return true;
        }
    }

    private function validatePriceFormat($value)
    {
        $filter_options = array(
            'options' => array('min_range' => 0)
        );
        if (filter_var($value, FILTER_VALIDATE_FLOAT, $filter_options) !== FALSE && $value > 0) {
            return true;
        }
    }

    public function auditLogEntry($auditLogEntry = '', $auditMethodName = '', $auditLogUser = '')
    {
        $auditLogTime = date("Y-m-d H:i:s");

        $auditLogUser = 'cronJob';

        if (!empty($auditLogEntry) && !empty($auditLogUser) && !empty($auditMethodName) && !empty($auditLogTime)) {
            $auditLogSQL = "INSERT INTO " . DB_PREFIX . "etsy_audit_log VALUES (NULL, '" . $this->db->escape($auditLogEntry) . "', '" . $this->db->escape($auditLogUser) . "', '" . $this->db->escape($auditMethodName) . "', '" . $this->db->escape($auditLogTime) . "');";
            if ($this->db->query($auditLogSQL)) {
                return true;
            }
            return false;
        } else {
            return false;
        }
    }

    public function deleteFeed()
    {
        $this->load->model('setting/googleshopping');
        $this->load->language($this->module_path . '/googleshopping');
        if (isset($this->request->post['selected'])) {
            $flag = false;
            foreach ($this->request->post['selected'] as $id_gs_feed) {
                if ($this->model_setting_googleshopping->checkFeedMapping($id_gs_feed)) {
                    $flag = true;
                }
            }
            if ($flag == true) {
                $this->session->data['error'] = $this->language->get('text_feed_delete_allowed');
            } else {
                foreach ($this->request->post['selected'] as $id_gs_feed) {
                    $this->model_setting_googleshopping->deleteFeed($id_gs_feed);
                }
                $this->session->data['success'] = $this->language->get('text_feed_delete_success');
            }
        }
        $this->response->redirect($this->url->link($this->module_path . '/googleshopping/feedManagement', $this->session_token_key . '=' . $this->session_token . $url, true));
        die();
    }

    public function deleteProfiles()
    {
        $this->load->model('setting/googleshopping');
        $this->load->language($this->module_path . '/googleshopping');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $id_gs_profiles) {
                if ($this->model_setting_googleshopping->deleteProfile($id_gs_profiles)) {
                    $this->session->data['success'] = $this->language->get('text_profile_delete_success');
                } else {
                    $this->session->data['error'] = $this->language->get('text_profile_delete_failure');
                }
            }
        }
        $url = '';
        if (isset($this->request->get['filter_profile_name'])) {
            $url .= '&filter_profile_name=' . urlencode(html_entity_decode($this->request->get['filter_profile_name'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }
        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }
        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $this->response->redirect($this->url->link($this->module_path . '/googleshopping/profileManagement', $this->session_token_key . '=' . $this->session_token . $url, true));
        die();
    }

    public function enableFeed()
    {
        $id = $this->request->get['id_gs_feed'];
        $this->load->model('setting/googleshopping');
        $this->model_setting_googleshopping->enableFeed($id);
        $url = '';
        if (isset($this->request->get['filter_profile_name'])) {
            $url .= '&filter_profile_name=' . urlencode(html_entity_decode($this->request->get['filter_profile_name'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }
        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }
        $url .= '&order=ASC';

        $this->response->redirect($this->url->link($this->module_path . '/googleshopping/feedManagement', $this->session_token_key . '=' . $this->session_token . $url, true));
        die();
    }

    public function disableFeed()
    {
        $id = $this->request->get['id_gs_feed'];
        $this->load->model('setting/googleshopping');
        $this->model_setting_googleshopping->disableFeed($id);
        $url = '';
        if (isset($this->request->get['filter_profile_name'])) {
            $url .= '&filter_profile_name=' . urlencode(html_entity_decode($this->request->get['filter_profile_name'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }
        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }
        $url .= '&order=ASC';

        $this->response->redirect($this->url->link($this->module_path . '/googleshopping/feedManagement', $this->session_token_key . '=' . $this->session_token . $url, true));
        die();
    }

    public function disableProfile()
    {
        $id = $this->request->get['id_gs_profiles'];
        $this->load->model('setting/googleshopping');
        $this->model_setting_googleshopping->disableProfile($id);
        $url = '';
        if (isset($this->request->get['filter_profile_name'])) {
            $url .= '&filter_profile_name=' . urlencode(html_entity_decode($this->request->get['filter_profile_name'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }
        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }
        $url .= '&order=ASC';

        $this->response->redirect($this->url->link($this->module_path . '/googleshopping/profileManagement', $this->session_token_key . '=' . $this->session_token . $url, true));
        die();
    }

    public function enableProfile()
    {
        $id = $this->request->get['id_gs_profiles'];
        $this->load->model('setting/googleshopping');
        $this->model_setting_googleshopping->enableProfile($id);
        $url = '';
        if (isset($this->request->get['filter_profile_name'])) {
            $url .= '&filter_profile_name=' . urlencode(html_entity_decode($this->request->get['filter_profile_name'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }
        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }
        $url .= '&order=ASC';

        $this->response->redirect($this->url->link($this->module_path . '/googleshopping/profileManagement', $this->session_token_key . '=' . $this->session_token . $url, true));
        die();
    }

    public function support()
    {
        $this->load->language($this->module_path . '/googleshopping');
        $this->load->model('setting/setting');
        $this->load->model('setting/googleshopping');

        $this->document->setTitle($this->language->get('heading_title_main'));
        $data['heading_title'] = $this->language->get('heading_title');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', $this->session_token_key . '=' . $this->session_token, true),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link($this->extension_path, $this->session_token_key . '=' . $this->session_token, true),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_main'),
            'href' => $this->url->link($this->module_path . '/googleshopping', $this->session_token_key . '=' . $this->session_token, true),
            'separator' => ' :: '
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_support'),
            'href' => $this->url->link($this->module_path . '/googleshopping/support', $this->session_token_key . '=' . $this->session_token, true),
            'separator' => ' :: '
        );


        $data['token'] = $this->session_token;
        $data['text_support'] = $this->language->get('heading_title_support');
        $data['text_support_other'] = $this->language->get('text_support_other');
        $data['text_support_marketplace'] = $this->language->get('text_support_marketplace');
        $data['text_support_marketplace_descp'] = $this->language->get('text_support_marketplace_descp');
        $data['text_support_etsy'] = $this->language->get('text_support_etsy');
        $data['text_support_etsy_descp'] = $this->language->get('text_support_etsy_descp');
        $data['text_support_ebay'] = $this->language->get('text_support_ebay');
        $data['text_support_ebay_descp'] = $this->language->get('text_support_ebay_descp');
        $data['text_support_mab'] = $this->language->get('text_support_mab');
        $data['text_support_mab_descp'] = $this->language->get('text_support_mab_descp');
        $data['text_support_view_more'] = $this->language->get('text_support_view_more');
        $data['text_support_ticket1'] = $this->language->get('text_support_ticket1');
        $data['text_support_ticket2'] = $this->language->get('text_support_ticket2');
        $data['text_support_ticket3'] = $this->language->get('text_support_ticket3');
        $data['text_click_here'] = $this->language->get('text_click_here');
        $data['text_user_manual'] = $this->language->get('text_user_manual');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $active_tab['active_tab'] = 8;
        $data['googleshopping_common'] = $this->load->controller($this->module_path . '/googleshopping/common', $active_tab);

        if (VERSION >= '2.2.0.0') {
            $this->response->setOutput($this->load->view($this->module_path . '/kbgoogleshopping/googleshopping_support', $data));
        } else {
            $this->response->setOutput($this->load->view($this->module_path . '/kbgoogleshopping/googleshopping_support.tpl', $data));
        }
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', $this->module_path . '/googleshopping')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        return !$this->error;
    }

    public function ajaxProcessGetLanguages()
    {
        $this->load->language($this->module_path . '/googleshopping');
        $country_id = $this->request->get['country_id'];
        $this->load->model('setting/googleshopping');
        $data = $this->model_setting_googleshopping->ajaxProcessGetLanguages($country_id);
        echo json_encode($data);
        die();
    }

    public function ajaxProcessGetCurrency()
    {
        $this->load->language($this->module_path . '/googleshopping');
        $country_id = $this->request->get['country_id'];
        $this->load->model('setting/googleshopping');
        $data = $this->model_setting_googleshopping->ajaxProcessGetCurrency($country_id);
        echo json_encode($data);
        die();
    }

    public function ajaxProcessGetSubcategories()
    {
        $this->load->language($this->module_path . '/googleshopping');
        $category_id = $this->request->get['category_id'];
        $this->load->model('setting/googleshopping');
        $data = $this->model_setting_googleshopping->getGoogleSubcategories($category_id);
        echo json_encode($data);
        die;
    }

}

?>