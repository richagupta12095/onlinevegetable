<?php
namespace smshare_core;

class smshare_core_tag extends smshare_core_000 {


    public function merge($args) {

        $order_info   = $args['order_info'];
        $order_status = $args['order_status'];
        $comment      = $args['comment'];
        $language     = $args['language'];
        $template     = $args['template'];


        /*
         *
         */
        $find = array(
            '{firstname}'         ,
            '{lastname}'          ,
            '{phonenumber}'       ,
            '{email}'             ,
            '{store_url}'         ,
            '{order_id}'          ,
            '{total}'             ,
            '{total_no_currency}' ,
            '{order_status}'      ,
            '{comment}'           ,
            '{date_added}'        ,
            '{shipping_company}'  ,
            '{shipping_address_1}',
            '{shipping_address_2}',
            '{shipping_postcode}' ,
            '{shipping_city}'     ,
            '{shipping_region}'   ,
            '{shipping_country}'  ,
            '{payment_company}'   ,
            '{payment_address_1}' ,
            '{payment_address_2}' ,
            '{payment_postcode}'  ,
            '{payment_city}'      ,
            '{payment_region}'    ,
            '{payment_country}'   ,
            '{payment_method}'    ,
            '{shipping_method}'   ,
        );

        $replace = array(
            'firstname'          => $order_info['firstname'],
            'lastname'           => $order_info['lastname'] ,
            'phonenumber'        => $order_info['telephone'],
            'email'              => $order_info['email']    ,
            'store_url'          => HTTPS_SERVER            ,
            'order_id'           => $order_info['order_id'] ,
            'total'              => $order_info['total']    ,
            'total_no_currency'  => preg_replace("/[^\d.,]/", "", $order_info['total']),
            'order_status'       => $order_status,
            'comment'            => $comment,
            'date_added'         => isset($order_info['date_added'])  		 ? date($language->get('date_format_short'), strtotime($order_info['date_added'])) : "",
            'shipping_company'   => (isset($order_info['shipping_company']) && $order_info['shipping_company'])  ? $order_info['shipping_company']   : "",
            'shipping_address_1' => isset($order_info['shipping_address_1']) ? $order_info['shipping_address_1'] : "",
            'shipping_address_2' => isset($order_info['shipping_address_2']) ? $order_info['shipping_address_2'] : "",
            'shipping_postcode'  => isset($order_info['shipping_postcode'])  ? $order_info['shipping_postcode']  : "",
            'shipping_city'      => isset($order_info['shipping_city'])      ? $order_info['shipping_city']      : "",
            'shipping_region'    => isset($order_info['shipping_zone'])      ? $order_info['shipping_zone']      : "",
            'shipping_country'   => isset($order_info['shipping_country'])   ? $order_info['shipping_country']   : "",
            'payment_company'    => (isset($order_info['payment_company'])  && $order_info['payment_company'])   ? $order_info['payment_company']    : "",
            'payment_address_1'  => isset($order_info['payment_address_1'])  ? $order_info['payment_address_1']  : "",
            'payment_address_2'  => isset($order_info['payment_address_2'])  ? $order_info['payment_address_2']  : "",
            'payment_postcode'   => isset($order_info['payment_postcode'])   ? $order_info['payment_postcode']   : "",
            'payment_city'       => isset($order_info['payment_city'])       ? $order_info['payment_city']       : "",
            'payment_region'     => isset($order_info['payment_zone'])       ? $order_info['payment_zone']       : "",
            'payment_country'    => isset($order_info['payment_country'])    ? $order_info['payment_country']    : "",
            'payment_method'     => isset($order_info['payment_method'])     ? $order_info['payment_method']     : "",
            'shipping_method'    => isset($order_info['shipping_method'])    ? $order_info['shipping_method']    : ""
        );


        $sms_body = str_replace($find, $replace, $template);

        //additional replacement (eg. product loop) ce034

        return $sms_body;
    }

}