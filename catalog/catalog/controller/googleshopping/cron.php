<?php

require_once DIR_SYSTEM . 'library/kbgoogleshopping/src/Google/Client.php';

class ControllerGoogleshoppingCron extends Controller
{

    public function refresh()
    {
        $this->load->model('googleshopping/cron');
        $this->load->model('setting/store');
        $store_id = $this->config->get('config_store_id');

        $settings = $this->model_googleshopping_cron->getSetting('secure_key5', $store_id);
        
        $gs_demo_flag = $this->model_googleshopping_cron->getSetting('gs_demo_flag', $this->config->get('config_store_id'));
        if(!empty($gs_demo_flag) && $gs_demo_flag['gs_demo_flag'] == 1) {
            echo "Action not allowed in the demo mode.";
            die();
        }
        
        if (isset($this->request->get['secure_key']) && $this->request->get['secure_key'] == $settings['secure_key5']) {

            if (!isset($this->request->get['code']) && !isset($this->request->get['token'])) {
                die('Invalid Request');
            } else if (isset($this->request->get['token'])) {
                $this->session->data['redirect_key'] = $this->request->get['token'];
            }

            $base_url = HTTPS_SERVER;
            $get_product_cron_url = $base_url . 'index.php?route=googleshopping/cron/refresh&secure_key=' . $settings['secure_key5'];
            $settings = $this->model_googleshopping_cron->getSetting('google_connection_settings', $store_id);

            try {
                $client = new Google_Client();
                $client->setApplicationName($settings['google_connection_settings']['connection']['app_name']);
                $client->setClientId($settings['google_connection_settings']['connection']['client_id']);
                $client->setClientSecret($settings['google_connection_settings']['connection']['client_secret']);
                $client->setRedirectUri($get_product_cron_url);
                $client->setScopes('https://www.googleapis.com/auth/content');
                $client->setAccessType('offline');
                if (isset($this->request->get['code'])) {
                    $code = $this->request->get['code'];
                    $client->authenticate($code);
                    $token_info = $client->getAccessToken();
                    $token_info_obj = json_decode($token_info, true);
                    $key = 1;
                    $this->model_googleshopping_cron->editSetting('gs_token_info', $token_info_obj, $store_id, $key);
                    if (isset($token_info_obj['refresh_token'])) {
                        $refresh_token = $token_info_obj['refresh_token'];
                        $this->model_googleshopping_cron->editSetting('gs_refresh_token', $refresh_token, $store_id, $key);
                    }
                    $this->session->data['success'] = "Your account has been authenticated successfully. The system is ready to sync the products but before that, Kindly ensure the enable to the module under 'General Settings' & Create the Profile/Feed.";
                    echo '<script type="text/javascript">';
                    echo 'window.opener.document.location.href = "' . str_replace("&amp;", "&", base64_decode($this->session->data['redirect_key'])) . '";';
                    echo 'window.close();';
                    echo '</script>';
                } elseif (isset($this->request->get['connectGS'])) {
                    $this->response->redirect($client->createAuthUrl());
                } else {
                    die('Invalid Request');
                }
            } catch (Exception $e) {
                echo $e->getMessage();
                die();
            }
        } else {
            die('Invalid Request');
        }
        die();
    }

    public function syncLocal()
    {
        @ini_set('memory_limit', -1);
        @ini_set('max_execution_time', -1);
        @set_time_limit(0);

        $this->load->model('googleshopping/cron');
        $this->model_googleshopping_cron->getAllProfileProducts();
        echo "Success";
        die();
    }

    public function syncFeedsListing($queue = false, $queuetype = null)
    {
        @ini_set('memory_limit', -1);
        @ini_set('max_execution_time', -1);
        @set_time_limit(0);
        
        $this->load->model('googleshopping/cron');

        $gs_demo_flag = $this->model_googleshopping_cron->getSetting('gs_demo_flag', $this->config->get('config_store_id'));
        if(!empty($gs_demo_flag) && $gs_demo_flag['gs_demo_flag'] == 1) {
            echo "Action not allowed in the demo mode.";
            die();
        }

        if ($this->model_googleshopping_cron->getAllProfileProducts()) {
            $this->model_googleshopping_cron->createFeedListing();
        }
        echo "Success";
        die();
    }
    
    public function syncProductStatus()
    {
        @ini_set('memory_limit', -1);
        @ini_set('max_execution_time', -1);
        @set_time_limit(0);
        
        $this->load->model('googleshopping/cron');

        $gs_demo_flag = $this->model_googleshopping_cron->getSetting('gs_demo_flag', $this->config->get('config_store_id'));
        if(!empty($gs_demo_flag) && $gs_demo_flag['gs_demo_flag'] == 1) {
            echo "Action not allowed in the demo mode.";
            die();
        }

        if ($this->model_googleshopping_cron->fetchGSProduct()) {
            echo 'Success';
        }
        die;
    }
}
