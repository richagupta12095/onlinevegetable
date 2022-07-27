<?php
class ModelExtensionTotalApiGeneral extends Model {
	
	public function getSubTotal($products) {
		$total = 0;

		foreach ($products as $product) {
			$total += $product['total'];
		}

		return $total;
	}

	public function getTaxes($products) {
		$tax_data = array();

		foreach ($products as $product) {
			if ($product['tax_class_id']) {
				$tax_rates = $this->tax->getRates($product['price'], $product['tax_class_id']);

				foreach ($tax_rates as $tax_rate) {
					if (!isset($tax_data[$tax_rate['tax_rate_id']])) {
						$tax_data[$tax_rate['tax_rate_id']] = ($tax_rate['amount'] * $product['quantity']);
					} else {
						$tax_data[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $product['quantity']);
					}
				}
			}
		}

		return $tax_data;
	}

	public function getTotal($products) {
		$total = 0;

		foreach ($products as $product) {
			$total += $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'];
		}

		return $total;
	}

	public function hasShipping($products) {
		foreach ($products as $product) {
			if (isset($product['shipping'])) {
				return true;
			}
		}

		return false;
	}
}