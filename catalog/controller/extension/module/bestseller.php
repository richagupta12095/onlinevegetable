<?php

class ControllerExtensionModuleBestSeller extends Controller {

	public function index($setting) {

		$this->load->language('extension/module/bestseller');



		$this->load->model('catalog/product');



		$this->load->model('tool/image');



		$this->document->addStyle('catalog/view/javascript/jquery/swiper/css/owl.carousel.css');

		$this->document->addStyle('catalog/view/javascript/jquery/swiper/css/owl.theme.css');

		$this->document->addScript('catalog/view/javascript/jquery/swiper/js/owl.carousel.min.js');



		$data['products'] = array();

		$filter_data = array(

			'filter_category_id'=> 67		

		);

		//$results = $this->model_catalog_product->getBestSellerProducts($setting['limit']);
		//By Ankur Mittal
		$results = $this->model_catalog_product->getProducts($filter_data);

		$kcartitem=$this->cart->getProducts();

		if ($results) {

			foreach ($results as $result) {

				if ($result['image']) {

					$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);

				} else {

					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);

				}



				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {

					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

				} else {

					$price = false;

				}



				if ((float)$result['special']) {

					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

				} else {

					$special = false;

				}



				if ($this->config->get('config_tax')) {

					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);

				} else {

					$tax = false;

				}



				if ($this->config->get('config_review_status')) {

					$rating = $result['rating'];

				} else {

					$rating = false;

				}



				/* insp Images Start */



				$insp_data['insp_images'] = array();

				$insp_results = $this->model_catalog_product->getProductImages($result['product_id']);



				



				foreach ($insp_results as $insp_result) {

					$insp_data['insp_images'][] = array('popup' => $this->model_tool_image->resize($insp_result['image'],$setting['width'], $setting['height']));

				}
				$discount = 0 ;
				if ((float)$result['special']) { 
					
					$discount = (($result['price'] - $result['special']) / $result['price']) * 100; 
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

				} else {

					$special = false;

				}


				
				$cqty='';
				$background='';
				$button='';
				foreach ($kcartitem as $key => $value) {
					if($value['product_id']== $result['product_id']){
						$background='yes';
						$cqty=$value['quantity'];
						$button='yes';
					}
				}
				


				/* End */


				if($result['product_id'] != 75){
					$data['products'][] = array(

						'product_id'  => $result['product_id'],
	
						'thumb'       => $image,
	
						'name'        => $result['name'],
	
						'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
	
						'price'       => $price,
	
						'special'     => $special,
	
						'tax'         => $tax,
						'discount'=>$discount,
	
						'rating'      => $rating,
						'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,


						'quantity' => $result['quantity'],
						'background'=>$background,
						'button'=>$button,
						'cqty'=>$cqty,

							// Add images Data 
	
						'insp_images' => $insp_data['insp_images'],
	
						//End
	
						'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
	
					);
	
				}
				
			}


			return $this->load->view('extension/module/bestseller', $data);

		}

	}

}

