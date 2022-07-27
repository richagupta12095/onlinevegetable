<?php
class ModelExtensionTotalApiShipping extends Model {
	public function getTotal($total,$order_data) {
		$products = $order_data['products'];
		$this->load->model('extension/total/api/general');		

		if ($this->model_extension_total_api_general->hasShipping($products)) {
			$total['totals'][] = array(
				'code'       => 'shipping',
				'title'      => 'Shipping',
				'value'      => $order_data['shipping_cost'],
				'sort_order' => $this->config->get('total_shipping_sort_order')
			);

			$total['total'] += $order_data['shipping_cost'];
		}
	}
}