<?php
class ModelExtensionTotalApiSubTotal extends Model {
	public function getTotal($total,$order_data) {
		$products 	= 	$order_data['products'];
		$this->load->language('extension/total/sub_total');

		$this->load->model('extension/total/api/general');
		$sub_total = $this->model_extension_total_api_general->getSubTotal($products);
		
		/*
		if (!empty($this->session->data['vouchers'])) {
			foreach ($this->session->data['vouchers'] as $voucher) {
				$sub_total += $voucher['amount'];
			}
		}
		*/
		$total['totals'][] = array(
			'code'       => 'sub_total',
			'title'      => $this->language->get('text_sub_total'),
			'value'      => $sub_total,
			'sort_order' => $this->config->get('sub_total_sort_order')
		);

		$total['total'] += $sub_total;
	}
}
