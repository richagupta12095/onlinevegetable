<?php
class ControllerExtensionCiBlogCiSubscriber extends Controller {
	private $error = array();
	protected function validateSubscriber() {
		if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if(!isset($this->error['email'])) {
			$cisubscriber_info = $this->model_extension_ciblog_cisubscriber->getCiSubscriber($this->request->post['email']);
			if ($cisubscriber_info) {
				if($cisubscriber_info['verification_status']=='CONFIRMED') {
					$this->error['warning'] = $this->language->get('error_subscribed');
				} else if($cisubscriber_info['verification_status']=='PENDING') {
					$this->error['warning'] = $this->language->get('error_validation');
				} else if($cisubscriber_info['verification_status']=='EXPIRED') {
					$this->error['warning'] = $this->language->get('error_expired');
				}

				if($cisubscriber_info['status']==0 && !isset($this->error['warning'])) {
					$this->error['warning'] = $this->language->get('error_disable');
				}
				if(!in_array($cisubscriber_info['verification_status'], array('CONFIRMED','PENDING',"EXPIRED")) && $cisubscriber_info['status']==0) {
					$this->error['warning'] = $this->language->get('error_expired');
				}
			}
		}
		return !$this->error;
	}
	public function index() {
		$json = array();
		$this->load->language('extension/ciblog/cisubscriber');
		$this->load->model('extension/ciblog/cisubscriber');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateSubscriber()) {

			$this->model_extension_ciblog_cisubscriber->addCiSubscriber($this->request->post['email']);

			$cisubscriber_info = $this->model_extension_ciblog_cisubscriber->getCiSubscriber($this->request->post['email']);

			if($cisubscriber_info['verification_status']=='CONFIRMED') {
				$json['success'] = $this->language->get('text_subscribed');
			} else {
				$json['success'] = $this->language->get('text_validation');
			}

		} else {
			$json['error'] = $this->error;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function validateUnSubscriber() {
		if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error['email'] = $this->language->get('error_email');
		}
		if(!isset($this->error['email'])) {
			$cisubscriber_info = $this->model_extension_ciblog_cisubscriber->getCiSubscriber($this->request->post['email']);
			if (empty($cisubscriber_info)) {
				$this->error['warning'] = $this->language->get('error_no_record');
			}
		}
		return !$this->error;
	}

	public function unSubscribe() {
		$json = array();
		$this->load->language('extension/ciblog/cisubscriber');
		$this->load->model('extension/ciblog/cisubscriber');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateUnSubscriber()) {


			$cisubscriber_info = $this->model_extension_ciblog_cisubscriber->getCiSubscriber($this->request->post['email']);
			if($cisubscriber_info) {
				$this->model_extension_ciblog_cisubscriber->ciUnSubscribe($this->request->post['email']);
				$json['success'] = $this->language->get('text_unsubscribed');
			} else {
				$json['error']['warning'] = $this->language->get('error_no_record');
			}

		} else {
			$json['error'] = $this->error;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	// subscriber can manualy verify email using verification code.
	public function verify() {
		$this->load->language('extension/ciblog/cisubscriber_verify');

		$this->load->model('extension/ciblog/cisubscriber');

		$this->load->model('tool/image');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_verified'] = $this->language->get('text_verified');
		$data['text_title'] = $this->language->get('text_title');
		$data['text_code'] = $this->language->get('text_code');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_verify'] = $this->language->get('button_verify');
		$data['button_resend_code'] = $this->language->get('button_resend_code');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/ciblog/cisubscriber_verify', '', true)
		);

		$data['continue'] = $this->url->link('extension/ciblog/ciblog', '', true);

		$data['verified'] = false;
		if(isset($this->request->get['key'])) {

			$results = $this->model_extension_ciblog_cisubscriber->verifyCode($this->request->get['key']);
			$now = time();

			foreach ($results as $result) {
				if($result['date_generated'] == '0000-00-00 00:00:00') {
					$this->model_extension_ciblog_cisubscriber->updateCode($result['ciblog_subscriber_id'], 'DATE_ZERO', 'EXPIRED');
				} else {
					if($now > strtotime('+'. $result['expire_hours'] .' HOURS' .$result['date_generated'])) {
						$this->model_extension_ciblog_cisubscriber->updateCode($result['ciblog_subscriber_id'], 'DATE_EXPIRED', 'EXPIRED');
					} else {
						$this->model_extension_ciblog_cisubscriber->updateCode($result['ciblog_subscriber_id'], 'CONFIRMED', 'CONFIRMED');


						$this->model_extension_ciblog_cisubscriber->updateSubscriberStatus($result['ciblog_subscriber_id'], +($result['action_requested'] == 'SUBSCRIBE'));

						$data['verified'] = true;
					}
				}
			}
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->ciblog->view('extension/ciblog/cisubscriber_verify', $data));
	}

	protected function validateVerifyManual() {
		if ((utf8_strlen($this->request->get['key']) > 25) || (utf8_strlen($this->request->get['key']) < 10)) {
			$this->error['warning'] = $this->language->get('error_code');
		}

		return !$this->error;
	}

	public function verifyManual() {
		$json = array();
		$this->load->language('extension/ciblog/cisubscriber');
		$this->load->language('extension/ciblog/cisubscriber_verify');
		$this->load->model('extension/ciblog/cisubscriber');

		$noError = $this->validateVerifyManual();

		if($noError) {
			// verify and response
			$verified = false;
			$error = $this->language->get('error_code_expire');

			if(isset($this->request->get['key'])) {

				$results = $this->model_extension_ciblog_cisubscriber->verifyCode($this->request->get['key']);
				$now = time();
				foreach ($results as $result) {
					if($result['date_generated'] == '0000-00-00 00:00:00') {
						$this->model_extension_ciblog_cisubscriber->updateCode($result['ciblog_subscriber_id'], 'DATE_ZERO', 'EXPIRED');
						$error = $this->language->get('error_invalid_date');
					} else {
						if($now > strtotime('+'. $result['expire_hours'] .' HOURS' .$result['date_generated'])) {
							$this->model_extension_ciblog_cisubscriber->updateCode($result['ciblog_subscriber_id'], 'DATE_EXPIRED', 'EXPIRED');
						} else {
							$this->model_extension_ciblog_cisubscriber->updateCode($result['ciblog_subscriber_id'], 'CONFIRMED', 'CONFIRMED');
							$verified = true;
						}
					}
				}
			}
			if($verified) {
				$json['success'] = $this->language->get('text_verified');
			} else {
				$json['error']['warning'] = $error;
			}
		} else {
			$json['error'] = $this->error;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}


	protected function validateCodeResend() {
		if ((utf8_strlen($this->request->get['key']) > 96) || !filter_var($this->request->get['key'], FILTER_VALIDATE_EMAIL)) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if(!isset($this->error['email'])) {
			$cisubscriber_info = $this->model_extension_ciblog_cisubscriber->getCiSubscriber($this->request->get['key']);
			if (empty($cisubscriber_info)) {
				$this->error['warning'] = $this->language->get('error_no_record');
			}
		}

		return !$this->error;
	}

	public function codeResend() {
		$json = array();
		$this->load->language('extension/ciblog/cisubscriber');
		$this->load->model('extension/ciblog/cisubscriber');

		if ($this->validateCodeResend()) {

			$cisubscriber_info = $this->model_extension_ciblog_cisubscriber->getCiSubscriber($this->request->get['key']);
			if($cisubscriber_info) {
				$json['success'] = $this->language->get('text_confirmed');

				if($cisubscriber_info['verification_status'] != 'CONFIRMED') {
					$this->model_extension_ciblog_cisubscriber->sendVerificationCode($this->request->post['email']);
					$json['success'] = $this->language->get('text_send_code');
				}
			} else {
				$json['error']['warning'] = $this->language->get('error_no_record');
			}

		} else {
			$json['error'] = $this->error;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
