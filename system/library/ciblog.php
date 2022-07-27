<?php
namespace Codinginspect;

class CiBlog {

	public $octoken = 'token';
	public $ocssl = true;
	public $dir_extension = 'extension/';
	public $admin_extension_page_path = 'extension/extension';
	public function __construct($registry) {
		// do any startup work here
		$this->config = $registry->get('config');
		$this->session = $registry->get('session');
		$this->db = $registry->get('db');
		$this->registry = &$registry;

		if(VERSION < '2.2.0.0') {
			$this->ocssl = 'ssl';
			$this->dir_extension = '';
		}
		if(VERSION >= '3.0.0.0') {
			$this->octoken = 'user_token';
			$this->admin_extension_page_path = 'marketplace/extension';
		}
	}

	public function buildTable() {
		$this->registry->get('load')->controller('extension/ciblog/cisetup');
	}

	public function getLeftMenu() {
		return $this->registry->get('load')->controller('extension/ciblog/cimenu/getLeftMenu');
	}

	public function languages($languages) {

		if(VERSION >= '2.2.0.0') {
			foreach ($languages as &$language) {
				$language['flag'] = 'language/'.$language['code'].'/'.$language['code'].'.png';
			}
		} else {
			foreach ($languages as &$language) {
				$language['flag'] = 'view/image/flags/'.$language['image'].'';
			}
		}
		return $languages;

	}

	public function getTexEditorFiles(&$data) {
		$data['summernote'] = '';
		return $this->registry->get('load')->controller('extension/ciblog/cimenu/getTexEditorFiles', $data);
	}

	public function mkdir($dir) {
		if(!is_dir($dir)) {
			$oldmask = umask(0);
			mkdir($dir, 0777);
			umask($oldmask);
		}
	}

	public function mail() {
		if(VERSION >= '3.0.0.0') {
        	$mail = new \Mail($this->registry->get('config')->get('config_mail_engine'));
			$mail->parameter = $this->registry->get('config')->get('config_mail_parameter');
			$mail->smtp_hostname = $this->registry->get('config')->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->registry->get('config')->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->registry->get('config')->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->registry->get('config')->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->registry->get('config')->get('config_mail_smtp_timeout');
        } else if(VERSION >= '2.0.2.0') {
        	$mail = new \Mail();
			$mail->protocol = $this->registry->get('config')->get('config_mail_protocol');
			$mail->parameter = $this->registry->get('config')->get('config_mail_parameter');
			$mail->smtp_hostname = $this->registry->get('config')->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->registry->get('config')->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->registry->get('config')->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->registry->get('config')->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->registry->get('config')->get('config_mail_smtp_timeout');
		} else {
			$mail = new \Mail($this->registry->get('config')->get('config_mail'));
		}

		return $mail;
	}

	public function getVideoURLThumb($url) {
		$youtubeVideoId = '';
		$smallImage1 = '';
		$smallImage2 = '';
		$smallImage3 = '';
		$hdThumb = '';
		$defaultThumb = '';
		$youTubeThumb = false;
	    if(!empty($url)) {
		$parts = parse_url($url);
		if(isset($parts['query'])) {
			parse_str($parts['query'], $query);
			if(isset($query['v'])) {
				$youtubeVideoId = $query['v'];
			}
		}
		if(isset($parts['host']) && $parts['host'] == 'youtu.be') {
			$urlParts = explode('/', $parts['path']);
			$youtubeVideoId = end($urlParts);
		}
		if(isset($parts['host']) && ($parts['host'] == 'youtu.be' || $parts['host'] == 'www.youtube.com') && empty($youtubeVideoId)) {
			$urlParts = explode('/', $parts['path']);
			$youtubeVideoId = end($urlParts);
		}
	    }
		if(!empty($youtubeVideoId)) {
			$smallImage1 = 'https://img.youtube.com/vi/'. $youtubeVideoId .'/1.jpg';
			$smallImage2 = 'https://img.youtube.com/vi/'. $youtubeVideoId .'/2.jpg';
			$smallImage3 = 'https://img.youtube.com/vi/'. $youtubeVideoId .'/3.jpg';
			$hdThumb = 'https://img.youtube.com/vi/'. $youtubeVideoId .'/maxresdefault.jpg';
			$defaultThumb = 'https://img.youtube.com/vi/'. $youtubeVideoId .'/hqdefault.jpg';
			$youTubeThumb = true;
		}
		return array(
			'smallImage1' => $smallImage1,
			'smallImage2' => $smallImage2,
			'smallImage3' => $smallImage3,
			'hdThumb' => $hdThumb,
			'defaultThumb' => $defaultThumb,
			'youTubeThumb' => $youTubeThumb,
			'youtubeVideoId' => $youtubeVideoId,
		);
	}

	public function getVideoURLEmbedURL($url) {
		$videoId = '';
		$origin = 'youtube';
		$embedUrl = '';
		if(!empty($url)) {
			$parts = parse_url($url);

			if(isset($parts['query'])) {
				parse_str($parts['query'], $query);
				if(isset($query['v'])) {
					$videoId = $query['v'];

				}
			}
			if(empty($videoId) && isset($parts['host']) && ($parts['host'] == 'youtu.be' || $parts['host'] == 'www.youtube.com' )) {
				$urlParts = explode('/', $parts['path']);
				// remove any empty arrays from trailing
				if (utf8_strlen(end($parts)) == 0) {
					array_pop($parts);
				}
				$videoId = end($urlParts);
		 	}

		 	if(isset($parts['host']) && ($parts['host'] == 'youtu.be' || $parts['host'] == 'www.youtube.com' || $parts['host'] == 'youtube.com') ) {
		 		$origin = 'youtube';
		 	}
		}

		if(!empty($videoId)) {
			if($origin == 'youtube') {
				$embedUrl = 'https://www.youtube.com/embed/' . $videoId;
			}
		}

		return $embedUrl;
	}

	public function adminModelStringForCustomer() {
    	if(VERSION < '2.1.0.1') {
	    	$this->load->model('sale/customer');
	    	$return = 'model_sale_customer';
    	} else {
	    	$this->load->model('customer/customer');
	    	$return = 'model_customer_customer';
    	}
    	return $return;
    }

    protected function isAdminView($route, &$data=array()) {
    	// remove .tpl from route
		$route = str_replace(".tpl", "", $route);
		$template = true;
		if(VERSION >= '3.0.0.0') {
			if($template) {
				// we load tpl view
	    		$old_template = $this->registry->get('config')->get('template_engine');
				$this->registry->get('config')->set('template_engine', 'template');
			}

			$file = $this->registry->get('load')->view($route, $data);
			if($template) {
				$this->registry->get('config')->set('template_engine', $old_template);
			}

		} else {
			$file = $this->registry->get('load')->view($route.'.tpl', $data);
		}

		return $file;
    }

    protected function isCatalogView($route, &$data=array()) {
    	// echo $route;
    	// echo "<br>";
    	/*if (file_exists(DIR_TEMPLATE . $this->registry->get('config')->get('config_template') . '/template/account/account.tpl')) {
			$this->response->setOutput($this->load->view($this->registry->get('config')->get('config_template') . '/template/account/account.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/account.tpl', $data));

		}*/
		// remove .tpl from route
		$route = str_replace(".tpl", "", $route);


		if(VERSION < '2.2.0.0') {
			if (file_exists(DIR_TEMPLATE . $this->registry->get('config')->get('config_template') . '/template/'.$route.'.tpl')) {
				$file = $this->registry->get('load')->view($this->registry->get('config')->get('config_template').'/template/'.$route.'.tpl', $data);
			} else {
				$file = $this->registry->get('load')->view('default/template/'.$route.'.tpl', $data);
			}
		} else{
			$file = $this->registry->get('load')->view($route, $data);
		}

		return $file;
    }

    public function view($route, &$data=array()) {
    	// front end
		if (!defined('DIR_CATALOG')) {
			return $this->isCatalogView($route, $data);
		} else {
		// backend
			return $this->isAdminView($route, $data);
		}
    }

}

if(!function_exists('token')) {
	function token($length = 32) {
		// Create random token
		$string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

		$max = strlen($string) - 1;

		$token = '';

		for ($i = 0; $i < $length; $i++) {
			$token .= $string[mt_rand(0, $max)];
		}

		return $token;
	}
}