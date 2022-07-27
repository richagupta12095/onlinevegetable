<?php
// Version
define('VERSION', '3.0.2.0');
date_default_timezone_set('Asia/Kolkata');

if (is_file('config.php')) {
	require_once('config.php');
}

// Install
if (!defined('DIR_APPLICATION')) {
	header('Location: install/index.php');
	exit;
}
/*
$url_array = array('checkout/shipping_method/save',
					'checkout/shipping_address/save',
					'checkout/shipping_method',
					'checkout/shipping_address',
					'checkout/checkout/country&country_id=99',
					'checkout/payment_address/save',
					'checkout/payment_address',
				);
if(stripos($_SERVER['QUERY_STRING'],"=") && ($_SERVER['QUERY_STRING'] != '_route_=checkout')){
	$url_parts = explode("=", $_SERVER['QUERY_STRING']);
	
	if(count($url_parts) > 1){
		array_shift($url_parts);
		if(!empty($url_parts[0]) && !in_array($url_parts[0],$url_array)){ echo $_SERVER['QUERY_STRING'];exit;
			$mysqli = new mysqli(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,DB_DATABASE);	
			if (!$mysqli -> connect_errno) {
				$sql = "SELECT * FROM oc_seo_url WHERE REPLACE(query,'&amp;','&') = '".$url_parts[0]."' AND store_id = '0' AND language_id = '1' LIMIT 0,1";
				
				if ($result = $mysqli -> query($sql)) {
					
				  	if($result->num_rows > 0){
						
				  		$row = $result -> fetch_array();
				  		if(!empty($row['keyword'])){	echo "aaa";exit;						
				  			header("Location: https://iavocado.in/".$row['keyword'], true, 301); 	
				  		}
				  	}
				}
			}	
		}
			
	}	
}
*/

// VirtualQMOD
require_once('./vqmod/vqmod.php');
VQMod::bootup();

// VQMODDED Startup
require_once(VQMod::modCheck(DIR_SYSTEM . 'startup.php'));

start('catalog');