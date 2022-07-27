<?php
// HTTP
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
define('HTTP_SERVER', 'http://iavocado.in/');

// HTTPS
define('HTTPS_SERVER', 'https://iavocado.in/');

// DIR
define('DIR_APPLICATION', '/var/www/html/catalog/');
define('DIR_SYSTEM', '/var/www/html/system/');
define('DIR_IMAGE', '/var/www/html/image/');
define('DIR_STORAGE', '/var/www/storage/');

define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/theme/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');
define('DIR_CACHE', DIR_STORAGE . 'cache/');
define('DIR_DOWNLOAD', DIR_STORAGE . 'download/');
define('DIR_LOGS', DIR_STORAGE . 'logs/');
define('DIR_MODIFICATION', DIR_STORAGE . 'modification/');
define('DIR_SESSION', DIR_STORAGE . 'session/');
define('DIR_UPLOAD', DIR_STORAGE . 'upload/');

// DB
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'database-db1.cepbhdfnxhf4.ap-south-1.rds.amazonaws.com');
define('DB_USERNAME', 'iavocado');
define('DB_PASSWORD', 'yK9lC8vK5aL3vH4n');
define('DB_DATABASE', 'dbiavocado');
define('DB_PORT', '3306');
define('DB_PREFIX', 'oc_');


//Constants
define('MINIMUM_CART_VALUE_SHIPPING_FREE', 1000);