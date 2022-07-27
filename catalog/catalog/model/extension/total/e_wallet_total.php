<?php

class ModelExtensiontotalewallettotal extends Model {

	public function getTotal($total){
		 
		if (isset($this->session->data['use_e_wallet']) && $this->session->data['use_e_wallet']){

			$this->load->model('account/e_wallet');

			$wallet_balance = $this->model_account_e_wallet->getBalance();

			if((int)$wallet_balance > 0 && (float)$total['total'] > (float)$wallet_balance){

				$total['totals'][] = array(

					'code'       => 'e_wallet_total',

					'title'      => $this->config->get('total_e_wallet_total_title'),

					'value'      => -$wallet_balance,

					'sort_order' => $this->config->get('total_e_wallet_total_sort_order')

				);

				$total['total'] -= $wallet_balance;

			}

		}

	}

}