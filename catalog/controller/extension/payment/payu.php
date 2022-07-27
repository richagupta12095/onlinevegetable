<?php
class ControllerExtensionPaymentPayu extends Controller {
	
	public function index() {
		$this->load->model('checkout/order');	
		$this->language->load('extension/payment/payu');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		if($this->config->get('payment_payu_live')){
			$data['action'] = 'https://secure.payu.in/_payment.php';
		} else {
			$data['action'] = 'https://sandboxsecure.payu.in/_payment.php';
		}

		$data['merchant'] = $this->config->get('payment_payu_merchant');             
		$data['key'] = $this->config->get('payment_payu_merchant');
		$data['salt'] = $this->config->get('payment_payu_salt');
		$data['txnid'] = $this->session->data['order_id'];
		$data['amount'] = (int)$order_info['total'];
		$data['productinfo'] = $order_info['store_name'].' products information';
		$data['firstname'] = $order_info['payment_firstname'];
		$data['Lastname'] = $order_info['payment_lastname'];
		$data['Zipcode'] = $order_info['payment_postcode'];
		$data['email'] = $order_info['email'];
		$data['phone'] = $order_info['telephone'];
		$data['address1'] = $order_info['payment_address_1'];
        $data['address2'] = $order_info['payment_address_2'];
        $data['state'] = $order_info['payment_zone'];
        $data['city']=$order_info['payment_city'];
        $data['country']=$order_info['payment_country'];
		$data['Pg'] = 'CC';
		$data['surl'] = $this->url->link('extension/payment/payu/callback');
		$data['Furl'] = $this->url->link('extension/payment/payu/callback');
		$data['curl'] = $this->url->link('extension/payment/payu/callback');
		$key          =  $this->config->get('payment_payu_merchant');
		$amount       = (int)$order_info['total'];
		$productInfo  = $data['productinfo'];
	    $firstname    = $order_info['payment_firstname'];
		$email        = $order_info['email'];
		$salt         = $this->config->get('payment_payu_salt');
		$udf5 		  = "Opencart_v_3";
		$txnid 		  = $data['txnid'];
		$Hash=hash('sha512', $key.'|'.$txnid.'|'.$amount.'|'.$productInfo.'|'.$firstname.'|'.$email.'|||||'.$udf5.'||||||'.$salt); 
		$data['user_credentials'] = $this->data['key'].':'.$this->data['email'];
		$data['udf5'] = $udf5;
		$data['Hash'] = $Hash;
		$data['service_provider'] = 'payu_paisa';
		$data['button_confirm'] = $this->language->get('button_confirm');

		return $this->load->view('extension/payment/payu', $data);
	}
	
	public function callback() {
		if (isset($this->request->post['key']) && ($this->request->post['key'] == $this->config->get('payment_payu_merchant'))) {
			$this->language->load('extension/payment/payu');
			
			$this->load->model('checkout/order');
     		$orderid = $this->request->post['txnid'];
			$order_info = $this->model_checkout_order->getOrder($orderid);
			
			$data['title'] = sprintf($this->language->get('heading_title'), $order_info['payment_method']);

			if (!isset($this->request->server['HTTPS']) || ($this->request->server['HTTPS'] != 'on')) {
				$data['base'] = HTTP_SERVER;
			} else {
				$data['base'] = HTTPS_SERVER;
			}
		
			$data['charset'] = $this->language->get('charset');
			$data['language'] = $this->language->get('code');
			$data['direction'] = $this->language->get('direction');
			$data['heading_title'] = sprintf($this->language->get('heading_title'), $order_info['payment_method']);
			$data['text_response'] = $this->language->get('text_response');

			$key          		=  	$this->request->post['key'];
			$amount      		= 	$this->request->post['amount'];
			$productInfo  		= 	$this->request->post['productinfo'];
			$firstname    		= 	$this->request->post['firstname'];
			$email        		=	$this->request->post['email'];
			$salt        		= 	$this->config->get('payment_payu_salt');
			$txnid		 		=   $this->request->post['txnid'];
			$udf5		 		=   $this->request->post['udf5'];
			$keyString 	  		=  	$key.'|'.$txnid.'|'.$amount.'|'.$productInfo.'|'.$firstname.'|'.$email.'|||||'.$udf5.'|||||';
			$keyArray 	  		= 	explode("|",$keyString);
			$reverseKeyArray 	= 	array_reverse($keyArray);
			$reverseKeyString	=	implode("|",$reverseKeyArray);
			 
			if (isset($this->request->post['status']) && $this->request->post['status'] == 'success') {
			 	$saltString     = $salt.'|'.$this->request->post['status'].'|'.$reverseKeyString;
				$sentHashString = strtolower(hash('sha512', $saltString));
			 	$responseHashString=$this->request->post['hash'];
				
				$order_id = $this->request->post['txnid'];
				$message = '';
				$message .= 'orderId: ' . $this->request->post['txnid'] . "\n";
				$message .= 'Transaction Id: ' . $this->request->post['mihpayid'] . "\n";
				foreach($this->request->post as $k => $val){
					$message .= $k.': ' . $val . "\n";
				}
				$data['text_message'] = $this->language->get('text_success');
				$data['text_message_wait'] = sprintf($this->language->get('text_success_wait'), $this->url->link('checkout/success'));

				if($sentHashString==$this->request->post['hash']){
					$this->model_checkout_order->addOrderHistory($this->request->post['txnid'], $this->config->get('payment_payu_completed_status_id'), $message, false);
					$data['continue'] = $this->url->link('checkout/success');
					$data['column_left'] = $this->load->controller('common/column_left');
		            $data['column_right'] = $this->load->controller('common/column_right');
		            $data['content_top'] = $this->load->controller('common/content_top');
		            $data['content_bottom'] = $this->load->controller('common/content_bottom');
		            $data['footer'] = $this->load->controller('common/footer');
		            $data['header'] = $this->load->controller('common/header');					
					$this->response->setOutput($this->load->view('extension/payment/payu_response', $data));														
				}			 
			} else {
    			$data['continue'] = $this->url->link('checkout/cart');
				$data['column_left'] = $this->load->controller('common/column_left');
				$data['column_right'] = $this->load->controller('common/column_right');
				$data['content_top'] = $this->load->controller('common/content_top');
				$data['content_bottom'] = $this->load->controller('common/content_bottom');
				$data['footer'] = $this->load->controller('common/footer');
				$data['header'] = $this->load->controller('common/header');

		        if(isset($this->request->post['status']) && $this->request->post['unmappedstatus'] == 'userCancelled'){
				    $data['text_message'] = $this->language->get('text_cancelled');
					$data['text_message_wait'] = sprintf($this->language->get('text_cancelled_wait'), $this->url->link('checkout/cart'));
				} else {
					$data['text_message'] = $this->language->get('text_failure');
					$data['text_message_wait'] = sprintf($this->language->get('text_failure_wait'), $this->url->link('checkout/cart'));
				}

				$this->response->setOutput($this->load->view('extension/payment/payu_response', $data));				
			}
		}
	}
	
	function generateHmacKey($data, $apiKey=null){
		$hmackey = hash_hmac('sha1',$data,$apiKey);
		return $hmackey;
	}	
}
?>