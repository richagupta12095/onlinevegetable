<?php
class ControllerExtensionPaymentewalletpayment extends Controller {
	public function index() {
		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['continue'] = $this->url->link('checkout/success');
		return $this->load->view('extension/payment/e_wallet_payment', $data);
	}
	public function confirm() {
		if ($this->session->data['payment_method']['code'] == 'e_wallet_payment') {
			$this->load->model('account/e_wallet');
			$wallet_balance = $this->model_account_e_wallet->getBalance();
			$this->load->model('checkout/order');
			$o_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
			$amount = $o_info['total'];
			if((int)$wallet_balance > 0 && (float)$amount <= (float)$wallet_balance){
				if($o_info['currency_code'] != $this->config->get('config_currency')){
					$amount = $this->currency->convert($amount,$o_info['currency_code'],$this->config->get('config_currency'));
				}
				$data = array(
					'desc' => 'Paid for Order, Order Id is: #'.$this->session->data['order_id'],
					'amount' => -$amount,
				);
				$this->model_account_e_wallet->addtransaction($data);
				$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('payment_e_wallet_payment_order_status_id'));
			}else{
				$json['error'] = "You have Insufficient Balance in Your ".$this->config->get('e_wallet_title').'..!';
				header("Content-Type: application/json; charset=UTF-8");
				echo json_encode($json);
				die;
			}
		}else{
			$json['error'] = "Invalid Payment Gateway Method..!";
			header("Content-Type: application/json; charset=UTF-8");
			echo json_encode($json);
			die;
		}
	}
}