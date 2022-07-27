<?php
/*
Total Order Discount v1.4

The Total Order Discount extension is for CMS Opencart 3.x.
It allows to give a discount based on purchase total price or quantity of products in the cart.

Copyright (c) 2018-2019 Andrii Burkatskyi aka underr

https://www.opencart.com/index.php?route=marketplace/extension/info&extension_id=33296
https://underr.space/en/notes/projects/project-001.html
https://github.com/underr-ua/ocmod3-total-order-discount
*/
class ModelExtensionTotalApiOrderDiscount extends Model {
	
	public function getTotal($total,$products) {
		$this->load->model('extension/total/api/general');		

		if ($this->config->get('total_order_discount_status') && $this->cart->hasProducts()) {
			if ($this->config->get('total_tax_status')) {
				$cart_total = $this->model_extension_total_api_general->getSubTotal($products) + array_sum($this->cart->getTaxes());
			} else {
				$cart_total = $this->model_extension_total_api_general->getSubTotal($products);
			}

			$base = $this->config->get('total_order_discount_base');
			$type = $this->config->get('total_order_discount_type');
			$point = $this->config->get('total_order_discount_point');
			$discount = $this->config->get('total_order_discount_value');

			if ('Q' == $base) {
				$base = count($products);
			} elseif ('S' == $base) {
				$base = $cart_total;
			}

			$total_discount = 0;

			if (($discount > 0 && $base >= $point) || ($discount < 0 && $base < $point)) {
				if ('P' == $type) {
					$total_discount = $cart_total * $discount / 100;
				} elseif ('F' == $type) {
					$total_discount = $discount;
				}
			}

			if (0 != $total_discount) {
				$this->load->language('extension/total/order_discount');

				$total_discount = -$total_discount;

				$total['totals'][] = array(
					'code'       => 'order_discount',
					'title'      => $this->config->get('total_order_discount_title')[$this->config->get('config_language_id')],
					'value'      => $total_discount,
					'sort_order' => $this->config->get('total_order_discount_sort_order'),
				);

				$total['total'] += $total_discount;
			}
		}
	}
}
