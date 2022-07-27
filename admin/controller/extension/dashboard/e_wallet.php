<?php
class ControllerExtensionDashboardewallet extends Controller {
	public function index() {
		$this->load->language('dashboard/e_wallet');

		$data['heading_title1'] = $this->language->get('heading_title1');
		$data['heading_title2'] = $this->language->get('heading_title2');
		$data['heading_title3'] = $this->language->get('heading_title3');
		$data['heading_title4'] = $this->language->get('heading_title4');

		$data['text_view'] = $this->language->get('text_view');

		$data['user_token'] = $this->session->data['user_token'];

		$req=$this->db->query("SELECT status FROM ".DB_PREFIX."cod_request ")->rows;
		$pendding=0;
		$approve=0;
		$reject=0;
		foreach ($req as $key => $value) {
			if($value['status']==1){
				$approve++;
			}else if($value['status']==2){
				$reject++;
			}else{
				$pendding++;
			}
		}
		$data['approve']="Approve : ".$approve;
		$data['reject']="Reject : ".$reject;
		$data['pendding']="Pendding : ".$pendding;

		$data['balance'] = round($this->db->query("SELECT SUM(price) AS balance FROM ".DB_PREFIX."e_wallet_transaction")->row['balance'], 2);
		$data['totaltrasaction'] = $this->db->query("SELECT price FROM ".DB_PREFIX."e_wallet_transaction")->num_rows;		  
		$data['e_wallet'] = $this->url->link('e_wallet/e_wallet', 'user_token=' . $this->session->data['user_token'], true);
		$data['customer'] = $this->url->link('e_wallet/e_wallet/customers', 'user_token=' . $this->session->data['user_token'], true);
		$data['add'] = $this->url->link('e_wallet/e_wallet/add', 'user_token=' . $this->session->data['user_token'], true);
		$data['money'] = $this->url->link('e_wallet/e_wallet/add_request', 'user_token=' . $this->session->data['user_token'], true);

		return $this->load->view('extension/dashboard/e_wallet', $data);
	}
}