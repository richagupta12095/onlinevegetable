<?php
class ControllerSmsSend extends Controller {
	public function customer($route = '', $request = '', $response = '') {
	  $json = array();

		$customer_id = 0;

		if ($response) {
			$customer_id = $response;
		} elseif (isset($request[0]) && $request[0]) {
			$customer_id = $request[0];
		}

		$this->registry->set('sms', new Sms($this->registry));

	  if ($this->config->get('module_opc_sms_status') && $this->config->get('module_opc_sms_customer_add')) {
    	$this->sms->sendToCustomer($customer_id);
	  }

	  $json['success'] = 'Your message has been successfully sent!';

	  $this->response->addHeader('Content-Type: application/json');
	  $this->response->setOutput(json_encode($json));
	}

	public function order($route = '', $request = '', $response = '') {

	  $json = array();

	  $order_id = 0;

	  if ($response) {
	    $order_id = $response;
	  } elseif (isset($request[0]) && $request[0]) {
	    $order_id = $request[0];
	  }

	  $this->registry->set('sms', new Sms($this->registry));

	  if ($this->config->get('module_opc_sms_status') && $this->config->get('module_opc_sms_order_add')) {
	    $this->sms->sendOnOrder($order_id);
	  }

	  $json['success'] = 'Your message has been successfully sent!';

	  $this->response->addHeader('Content-Type: application/json');
	  $this->response->setOutput(json_encode($json));
	}
}
