<?php
class ModelExtensionTotalApiMembershipSubscriptions extends Model {
	public function getTotal($total,$order_data) { 
		$this->load->model('membership/subscriptions');
		
		$products = $order_data['products'];
		$active_plan = $this->model_membership_subscriptions->getActivePlan($order_data['customer_id']);
		if($active_plan  && $this->config->get('mpplan_status')) {
			$this->load->language('extension/total/membership_subscriptions');
			
			
			
			$discount_total = 0;
			$this->load->model('extension/total/api/general');
			$sub_total = $this->model_extension_total_api_general->getSubTotal($products);
			
			$customer_plan = $this->model_membership_subscriptions->getCustomerPlan($order_data['customer_id'], $active_plan['mpplan_customer_id']);
			
			if($customer_plan) {			
				$discount_value = $this->model_membership_subscriptions->getCustomerGroupDiscount($customer_plan['mpplan_id'], $order_data['customer_group_id']);

				if($discount_value) {
					$plan_products = $this->model_membership_subscriptions->getMpplanProducts($customer_plan['mpplan_id']);

					foreach ($products as $product) {
						$discount = 0;

						if(in_array($product['product_id'], $plan_products)) {
							$discount += $this->tax->calculate($product['total'], $product['tax_class_id'], $this->config->get('config_tax')) / 100 * $discount_value;
						}

						$discount_total += $discount;
					}

					if ($discount_total > 0) {
						$total['totals'][] = array(
							'code'       => 'membership_subscriptions',
							'title'      => sprintf($this->language->get('text_membership_subscriptions'), $customer_plan['plan_name'], $discount_value),
							'value'      => -$discount_total,
							'sort_order' => $this->config->get('total_membership_subscriptions_sort_order')
						);

						$total['total'] -= $discount_total;
					}
				}				
			}
		}
	}
}