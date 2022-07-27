<?php
class ControllerApiProduct extends Controller
{
    public function getCategoryProducts(){
        $this->load->language('api/cart');
        $this->load->model('api/product');
        $this->load->model('tool/image');
        $json = array();
        $json['products'] = array();
        $filter_data = array();
        
        if(!empty($this->request->post['filter_category_id'])){
            $filter_data['filter_category_id'] = $this->request->post['filter_category_id'];
        }

        if(!empty($this->request->post['customer_group_id'])){
            $filter_data['customer_group_id'] = $this->request->post['customer_group_id'];
        }else{
            $filter_data['customer_group_id'] = 1;
        }
     
        $results = $this->model_api_product->getProducts($filter_data); 
        
        foreach ($results as $result) {
            //print_r($result);exit;    
            if ($result['image']) {
                $image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
            }
            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                $price = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
            } else {
                $price = false;
            }
            
            if ((float) $result['special']) {
                $special = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
            } else {
                $special = '';
            }
            if ($this->config->get('config_tax')) {
                $tax = (float) $result['special'] ? $result['special'] : $result['price'];
            } else {
                $tax = false;
            }
            if ($this->config->get('config_review_status')) {
                $rating = (int) $result['rating'];
            } else {
                $rating = false;
            }
            $data['products'][] = array(
                'product_id' => $result['product_id'],
                'thumb' => $image,
                'name' => $result['name'],
                'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                'price' => $price,
                'special' => $special,
                'discounts'=>$result['discounts'],
                'tax' => $tax,
                'quantity' => $result['quantity'],
                'minimum' => $result['minimum'] > 0 ? $result['minimum'] : 1,
                'rating' => $result['rating'],
                'shipping'=> $result['shipping'],
                'tax_class_id'=>$result['tax_class_id'],
                'model'=>$result['model'],
                'shipping'=>$result['shipping'],                
                'href' => $this->url->link('product/product', 'product_id=' . $result['product_id']),
            );
        }
        $json['status'] = 'success';
        $json['data'] = $data['products'];
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getProduct()
    {
        $this->load->language('api/cart');
        $this->load->model('api/product');
        $this->load->model('tool/image');
        $json = array();       
        $filter_data = array();
        
        if(!empty($this->request->post['filter_product_id'])){
            $product_id  = $this->request->post['filter_product_id'];
        }        
        if(!empty($this->request->post['customer_group_id'])){
            $filter_data['customer_group_id'] = $this->request->post['customer_group_id'];
        }else{
            $filter_data['customer_group_id'] = 1;
        }
      
        
        $results = $this->model_api_product->getProduct($product_id,$filter_data);
        
        $json['status'] = 'success';
        $json['data']   = $results;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getCategories()
    {       
        $this->load->model('catalog/category');        
        $json = array();             

        $results = $this->model_catalog_category->getCategories();    
        //print_r($results);exit;
        $cate = array();
        foreach($results as $key=>$singleItem){
            if(($singleItem['category_id']!=67) && ($singleItem['category_id']!=68)){
                array_push($cate,$singleItem);
            }
        }  
        $json['status'] = 'success';
        $json['data']   = $cate;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getSpecialProducts(){

        $this->load->language('api/cart');
        $this->load->model('api/product');
        $this->load->model('tool/image');
        $json = array();       
        $filter_data = array();
        
        if(!empty($this->request->post['filter_category_id'])){
            $filter_data['filter_category_id'] = $this->request->post['filter_category_id'];
        }else{
            $filter_data['filter_category_id'] = 68;
        }

        if(!empty($this->request->post['customer_group_id'])){
            $filter_data['customer_group_id'] = $this->request->post['customer_group_id'];
        }else{
            $filter_data['customer_group_id'] = 1;
        }
        $special= array();
        $specialresults = $this->model_api_product->getProducts($filter_data); 
       
        foreach ($specialresults as $result) {
            if ($result['image']) {
                $image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
            }
            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                $price = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
            } else {
                $price = false;
            }
            
            if ((float) $result['special']) {
                $special = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
            } else {
                $special = '';
            }
            if ($this->config->get('config_tax')) {
                $tax = (float) $result['special'] ? $result['special'] : $result['price'];
            } else {
                $tax = false;
            }
            if ($this->config->get('config_review_status')) {
                $rating = (int) $result['rating'];
            } else {
                $rating = false;
            }
            $specialproducts[] = array(
                'product_id' => $result['product_id'],
                'thumb' => $image,
                'name' => $result['name'],
                'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                'price' => $price,
                'special' => $special,
                'tax' => $tax,
                'quantity' => $result['quantity'],
                'minimum' => $result['minimum'] > 0 ? $result['minimum'] : 1,
                'rating' => $result['rating'],
                'shipping'=> $result['shipping'],
                'tax_class_id'=>$result['tax_class_id'],
                'model'=>$result['model'],  
                'href' => $this->url->link('product/product', 'product_id=' . $result['product_id']),
            );
        }
        $json['data'][0]['title']    =   'Special Products';
        $json['data'][0]['products']    =    $specialproducts;

        $filter_data = array();
        
        if(!empty($this->request->post['filter_category_id'])){
            $filter_data['filter_category_id'] = $this->request->post['filter_category_id'];
        }else{
            $filter_data['filter_category_id'] = 67;
        }

        if(!empty($this->request->post['customer_group_id'])){
            $filter_data['customer_group_id'] = $this->request->post['customer_group_id'];
        }else{
            $filter_data['customer_group_id'] = 1;
        }
     
        $bestsellersresults = $this->model_api_product->getProducts($filter_data); 
       
        foreach ($bestsellersresults as $result) {
            //print_r($result);exit;
            if ($result['image']) {
                $image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
            }
            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                $price = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
            } else {
                $price = false;
            }
            
            if ((float) $result['special']) {
                $special = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
            } else {
                $special = '';
            }
            if ($this->config->get('config_tax')) {
                $tax = (float) $result['special'] ? $result['special'] : $result['price'];
            } else {
                $tax = false;
            }
            if ($this->config->get('config_review_status')) {
                $rating = (int) $result['rating'];
            } else {
                $rating = false;
            }
            $bestsellers['products'][] = array(
                'product_id' => $result['product_id'],
                'thumb' => $image,
                'name' => $result['name'],
                'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                'price' => $price,
                'special' => $special,
                'discounts'=>$result['discounts'],
                'tax' => $tax,
                'quantity' => $result['quantity'],
                'minimum' => $result['minimum'] > 0 ? $result['minimum'] : 1,
                'rating' => $result['rating'],
                'shipping'=> $result['shipping'],               
                'tax_class_id'=>$result['tax_class_id'],
                'model'=>$result['model'],              
                'href' => $this->url->link('product/product', 'product_id=' . $result['product_id']),
            );
        }
       
        $json['status']                 =   'Success';
        $json['data'][1]['title']    =   'BestSellers';
        $json['data'][1]['products']    =    $bestsellers['products'];
     
        $json['status'] =   'Success';

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getBestSellerProducts(){

        $this->load->language('api/cart');
        $this->load->model('api/product');
        $this->load->model('tool/image');
        $json = array();
        $json['products'] = array();
        $filter_data = array();
        
        if(!empty($this->request->post['filter_category_id'])){
            $filter_data['filter_category_id'] = $this->request->post['filter_category_id'];
        }else{
            $filter_data['filter_category_id'] = 67;
        }

        if(!empty($this->request->post['customer_group_id'])){
            $filter_data['customer_group_id'] = $this->request->post['customer_group_id'];
        }else{
            $filter_data['customer_group_id'] = 1;
        }
     
        $results = $this->model_api_product->getProducts($filter_data); 
       
        foreach ($results as $result) {
            if ($result['image']) {
                $image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
            }
            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
            } else {
                $price = false;
            }
            
            if ((float) $result['special']) {
                $special = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
            } else {
                $special = '';
            }
            if ($this->config->get('config_tax')) {
                $tax = (float) $result['special'] ? $result['special'] : $result['price'];
            } else {
                $tax = false;
            }
            if ($this->config->get('config_review_status')) {
                $rating = (int) $result['rating'];
            } else {
                $rating = false;
            }
            $data['products'][] = array(
                'product_id' => $result['product_id'],
                'thumb' => $image,
                'name' => $result['name'],
                'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                'price' => $price,
                'special' => $special,
                'tax' => $tax,
                'quantity' => $result['quantity'],
                'minimum' => $result['minimum'] > 0 ? $result['minimum'] : 1,
                'rating' => $result['rating'],
                'shipping'=> $result['shipping'],
                'shipping'=> $result['shipping'],               
                'tax_class_id'=>$result['tax_class_id'],
                'model'=>$result['model'],     
                'href' => $this->url->link('product/product', 'product_id=' . $result['product_id']),
            );
        }
       
        $json['status'] =   'Success';
        $json['data']   =   $data['products'];
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function cartProducts(){
        $this->load->language('api/cart');
        $this->load->model('api/product');
        $this->load->model('tool/image');
        $json = array();
        $filter_data = array();
        
        if(!empty($this->request->post['filter_cart_products'])){
            $filter_data['filter_cart_products'] = $this->request->post['filter_cart_products'];
        }

        if(!empty($this->request->post['customer_group_id'])){
            $filter_data['customer_group_id'] = $this->request->post['customer_group_id'];
        }else{
            $filter_data['customer_group_id'] = 1;
        }
     
        $results = $this->model_api_product->getCartProducts($filter_data); 
        
        foreach ($results as $result) {
            //print_r($result);exit;    
            if ($result['image']) {
                $image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
            }
            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                $price = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
            } else {
                $price = false;
            }
            
            if ((float) $result['special']) {
                $special = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
            } else {
                $special = '';
            }
            if ($this->config->get('config_tax')) {
                $tax = (float) $result['special'] ? $result['special'] : $result['price'];
            } else {
                $tax = false;
            }
            if ($this->config->get('config_review_status')) {
                $rating = (int) $result['rating'];
            } else {
                $rating = false;
            }
            $data['products'][] = array(
                'product_id' => $result['product_id'],
                'thumb' => $image,
                'name' => $result['name'],
                'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                'price' => $price,
                'special' => $special,
                'discounts'=>$result['discounts'],
                'tax' => $tax,
                'quantity' => $result['quantity'],
                'minimum' => $result['minimum'] > 0 ? $result['minimum'] : 1,
                'rating' => $result['rating'],
                'shipping'=> $result['shipping'],
                'href' => $this->url->link('product/product', 'product_id=' . $result['product_id']),
            );
        }
        $json['status'] = 'success';
        $json['data'] = $data['products'];
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}