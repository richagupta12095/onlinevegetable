<?php

require_once(DIR_SYSTEM . 'library/kbgoogleshopping/GSSingleAction.php');
require_once(DIR_SYSTEM . 'library/kbgoogleshopping/GSFeedSchedule.php');
require_once(DIR_SYSTEM . 'library/kbgoogleshopping/GSFeed.php');

class ModelGoogleshoppingCron extends Model
{

    public function getSetting($code, $store_id = 0)
    {
        $setting_data = array();
        if (VERSION > '2.0.0.0') {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int) $store_id . "' AND `code` = '" . $this->db->escape($code) . "'");
        } else {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int) $store_id . "' AND `group` = '" . $this->db->escape($code) . "'");
        }
        foreach ($query->rows as $result) {
            if (!$result['serialized']) {
                $setting_data[$result['key']] = $result['value'];
            } else {
                if (VERSION > '2.0.3.1') {
                    $setting_data[$result['key']] = json_decode($result['value'], true);
                } else {
                    $setting_data[$result['key']] = unserialize($result['value']);
                }
            }
        }
        return $setting_data;
    }

    public function editSetting($code, $data, $store_id = 0, $key = 0)
    {
        if (VERSION > '2.0.0.0') {
            $this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE store_id = '" . (int) $store_id . "' AND `code` = '" . $this->db->escape($code) . "'");
        } else {
            $this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE store_id = '" . (int) $store_id . "' AND `group` = '" . $this->db->escape($code) . "'");
        }
        if ($key == 1) {
            if (VERSION > '2.0.3.1') {
                if (!is_array($data)) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int) $store_id . "', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($code) . "', `value` = '" . $this->db->escape($data) . "'");
                } else {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int) $store_id . "', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($code) . "', `value` = '" . $this->db->escape(json_encode($data, true)) . "', serialized = '1'");
                }
            } else {
                if (!is_array($data)) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int) $store_id . "', `group` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($code) . "', `value` = '" . $this->db->escape($data) . "'");
                } else {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int) $store_id . "', `group` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($code) . "', `value` = '" . $this->db->escape(serialize($data)) . "', serialized = '1'");
                }
            }
        } else {
            if (VERSION >= '2.0') {
                foreach ($data as $key => $value) {
                    if (substr($key, 0, strlen($code)) == $code) {
                        if (!is_array($value)) {
                            $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int) $store_id . "', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
                        } else {
                            $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int) $store_id . "', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(json_encode($value, true)) . "', serialized = '1'");
                        }
                    }
                }
            } else {
                foreach ($data as $key => $value) {
                    if (substr($key, 0, strlen($code)) == $code) {
                        if (!is_array($value)) {
                            $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int) $store_id . "', `group` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
                        } else {
                            $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int) $store_id . "', `group` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(serialize($value)) . "', serialized = '1'");
                        }
                    }
                }
            }
        }
    }

    public function getAllProfileProducts()
    {
        $this->load->model('catalog/product');
        $store_id = $this->config->get('config_store_id');

        $settings = $this->getSetting('google_general_settings', $store_id);
        if ($this->updateProductStatusByProfileStatus()) {
            $productsInserted = 0;
            $productsUpdated = 0;
            $validProducts = array();
            $listProducts = array();

            $getGSProfiles = $this->db->query("SELECT * FROM " . DB_PREFIX . "gs_profiles WHERE active = '1'");

            //Check if profile details are available
            if (!empty($getGSProfiles->rows)) {
                //Iteration for accessign each profile
                foreach ($getGSProfiles->rows as $getGSProfile) {

                    $this->db->query("UPDATE " . DB_PREFIX . "gs_products_list SET delete_flag = '1' WHERE id_gs_profiles = " . (int) $getGSProfile['id_gs_profiles']);

                    $categoryIds = $this->getStoreCategoryIds($getGSProfile);
                    if (!empty($categoryIds)) {
                        foreach ($categoryIds as $value) {
                            $categoryProductsList = $this->getProductsByCategoryId($value);
                            foreach ($categoryProductsList as $categoryProduct) {
                                $listProducts[] = $categoryProduct['product_id'];
                                $product = $this->model_catalog_product->getProduct($categoryProduct['product_id']);
                                if ($product['quantity'] <= 0) {
                                    if (!$settings['google_general_settings']['general']['out_of_stock']) {
                                        continue;
                                    }
                                }

                                if ($product['status'] == 0) {
                                    continue;
                                }

                                if ($product['price'] <= $settings['google_general_settings']['general']['product_price']) {
                                    continue;
                                }

                                $gtin = $this->db->query('SELECT gtin FROM ' . DB_PREFIX . 'gs_profiles WHERE id_gs_profiles =' . (int) $getGSProfile['id_gs_profiles']);
                                if ($settings['google_general_settings']['general']['no_upc'] == 1) {
                                    if ($product[strtolower($gtin->rows[0]['gtin'])] == '') {
                                        //continue;
                                    }
                                }

                                //Check if product already exists in DB Table
                                $dataExistenceResult = $this->db->query("SELECT count(*) as count, id_gs_products_list FROM " . DB_PREFIX . "gs_products_list WHERE id_product = '" . (int) $categoryProduct['product_id'] . "' AND id_gs_profiles = '" . $getGSProfile['id_gs_profiles'] . "'");
                                if (empty($dataExistenceResult->rows) || $dataExistenceResult->rows[0]['count'] == 0) {
                                    //Insert Product into Listing DB Table
                                    $insertSQL = "INSERT INTO " . DB_PREFIX . "gs_products_list SET id_gs_products_list = '', id_gs_profiles = '" . (int) $getGSProfile['id_gs_profiles'] . "', id_product = '" . (int) $categoryProduct['product_id'] . "', reference = '" . $product[strtolower($gtin->rows[0]['gtin'])] . "', date_add = NOW()";
                                    if ($this->db->query($insertSQL)) {
                                        $validProducts[] = $this->db->getLastId();
                                        $productsInserted++;
                                    }
                                } else {
                                    //Update Product into Listing DB Table
                                    $updateSQL = "UPDATE " . DB_PREFIX . "gs_products_list SET delete_flag = '0', reference = '" . $product[strtolower($gtin->rows[0]['gtin'])] . "' WHERE id_product = '" . (int) $categoryProduct['product_id'] . "' AND id_gs_profiles = '" . (int) $getGSProfile['id_gs_profiles'] . "'";
                                    if ($this->db->query($updateSQL)) {
                                        $validProducts[] = $dataExistenceResult->row['id_gs_products_list'];
                                        $productsUpdated++;
                                    }
                                }
                            }
                        }
                    }
                    $this->db->query("DELETE FROM " . DB_PREFIX . "gs_products_list WHERE delete_flag = '1' AND id_gs_profiles = " . (int) $getGSProfile['id_gs_profiles']);
                }
            }
        }
        return true;
    }

    /** Disable Product for the Profile whose is on Disable State */
    public function updateProductStatusByProfileStatus()
    {
        $selectSQL = "SELECT * FROM " . DB_PREFIX . "gs_profiles WHERE active = '0'";
        $getGsProfiles = $this->db->query($selectSQL);

        //Check if profile details are available
        if (!empty($getGsProfiles->rows)) {
            //Iteration for accessign each profile
            foreach ($getGsProfiles->rows as $getGsProfile) {
                $updateSQL = "UPDATE " . DB_PREFIX . "gs_products_list SET listing_status = 'Inactive', delete_flag = '1', renew_flag = '0' WHERE id_gs_profiles = '" . (int) $getGsProfile['id_gs_profiles'] . "'";
                $this->db->query($updateSQL);
            }
        }
        return true;
    }

    public function getStoreCategoryIds($getGSProfile)
    {
        $categoryIds = array();
        $profileCategory = $this->db->query('SELECT * FROM ' . DB_PREFIX . 'gs_category_mapping WHERE id_gs_profiles = ' . (int) $getGSProfile['id_gs_profiles']);
        if (!empty($profileCategory->rows) && is_array($profileCategory->rows)) {
            foreach ($profileCategory->rows as $proCat) {
                $cat = explode(',', $proCat['store_category']);
                if (is_array($cat) && !empty($cat)) {
                    foreach ($cat as $innerCat) {
                        $categoryIds[] = $innerCat;
                    }
                } elseif (!is_array($cat) && !empty($cat)) {
                    $categoryIds[] = $cat;
                }
            }
        }
        return $categoryIds;
    }

    public function getProductsByCategoryId($category_id)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p INNER JOIN " . DB_PREFIX . "product_to_category p2c ON p.product_id = p2c.product_id WHERE p2c.category_id = '" . (int) $category_id . "'");
        return $query->rows;
    }

    public function createFeedListing()
    {
        $settings = $this->getSetting('google_general_settings', $this->config->get('config_store_id'));

        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "gs_products_list pl INNER JOIN " . DB_PREFIX . "gs_profiles p ON pl.id_gs_profiles = p.id_gs_profiles WHERE listing_status != 'Inactive'");
        $gs_products = $result->rows;

        $listingArray = array();
        if (!empty($gs_products)) {
            foreach ($gs_products as $gs_product) {

                $oc_lang_id = $this->getOCLangauge($gs_product['oc_lang_id']);

                $product = $this->getProduct($gs_product['id_product'], $oc_lang_id);

                /* Exclude out of stock products */
                if ($product['quantity'] <= 0) {
                    if (!$settings['google_general_settings']['general']['out_of_stock']) {
                        continue;
                    }
                }

                /* Exclude disabled products */
                if ($product['status'] == 0) {
                    continue;
                }

                if ((float) $product['special']) {
                    $product['price'] = $product['special'];
                }

                if ($product['price'] <= $settings['google_general_settings']['general']['product_price']) {
                    continue;
                }

                /* Exclude product If exclude Product is Set to Yes & GTIN is Blank */
                $gtin = $this->db->query('SELECT gtin FROM ' . DB_PREFIX . 'gs_profiles WHERE id_gs_profiles=' . (int) $gs_product['id_gs_profiles']);
                if ($settings['google_general_settings']['general']['no_upc'] == 1 && $product[strtolower($gtin->rows[0]['gtin'])] == '') {
                    continue;
                }
                $gs_product['language_id'] = $gs_product['id_lang'];
                $gs_product['oc_lang_id'] = $gs_product['oc_lang_id'];
                $item_data = $this->prepareArrayToCreateListingOnGS($gs_product, $product);
                if (!empty($item_data)) {
                    $listingArray[] = $item_data;
                }
            }
        }
        if (isset($listingArray) && count($listingArray) > 0) {
            if ($this->sendFeedToGoogle($listingArray)) {
                return true;
            }
        }
        return true;
    }

    public function getOCLangauge($language_id)
    {
        if (!empty($language_id)) {
            return $language_id;
        } else {
            return $this->config->get('config_language_id');
        }
    }

    /** Default OC getProduct was using the store front language whille we need to pick language from the selected profile */
    public function getProduct($product_id, $language_id)
    {
        $query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int) $this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int) $this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND pr.customer_group_id = '" . (int) $this->config->get('config_customer_group_id') . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int) $language_id . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int) $language_id . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int) $language_id . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int) $product_id . "' AND pd.language_id = '" . (int) $language_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int) $this->config->get('config_store_id') . "'");

        if ($query->num_rows) {
            return array(
                'product_id' => $query->row['product_id'],
                'name' => $query->row['name'],
                'description' => $query->row['description'],
                'meta_title' => $query->row['meta_title'],
                'meta_description' => $query->row['meta_description'],
                'meta_keyword' => $query->row['meta_keyword'],
                'tag' => $query->row['tag'],
                'model' => $query->row['model'],
                'sku' => $query->row['sku'],
                'upc' => $query->row['upc'],
                'ean' => $query->row['ean'],
                'jan' => $query->row['jan'],
                'isbn' => $query->row['isbn'],
                'mpn' => $query->row['mpn'],
                'location' => $query->row['location'],
                'quantity' => $query->row['quantity'],
                'stock_status' => $query->row['stock_status'],
                'image' => $query->row['image'],
                'manufacturer_id' => $query->row['manufacturer_id'],
                'manufacturer' => $query->row['manufacturer'],
                'price' => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
                'special' => $query->row['special'],
                'reward' => $query->row['reward'],
                'points' => $query->row['points'],
                'tax_class_id' => $query->row['tax_class_id'],
                'date_available' => $query->row['date_available'],
                'weight' => $query->row['weight'],
                'weight_class_id' => $query->row['weight_class_id'],
                'length' => $query->row['length'],
                'width' => $query->row['width'],
                'height' => $query->row['height'],
                'length_class_id' => $query->row['length_class_id'],
                'subtract' => $query->row['subtract'],
                'rating' => round($query->row['rating']),
                'reviews' => $query->row['reviews'] ? $query->row['reviews'] : 0,
                'minimum' => $query->row['minimum'],
                'sort_order' => $query->row['sort_order'],
                'status' => $query->row['status'],
                'date_added' => $query->row['date_added'],
                'date_modified' => $query->row['date_modified'],
                'viewed' => $query->row['viewed']
            );
        } else {
            return false;
        }
    }
    
    public function getProductOptions($product_id, $language_id) {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int) $language_id . "' ORDER BY o.sort_order");

		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = array();

			$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int) $language_id . "' ORDER BY ov.sort_order");

			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'name'                    => $product_option_value['name'],
					'image'                   => $product_option_value['image'],
					'quantity'                => $product_option_value['quantity'],
					'subtract'                => $product_option_value['subtract'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix'],
					'weight'                  => $product_option_value['weight'],
					'weight_prefix'           => $product_option_value['weight_prefix']
				);
			}

			$product_option_data[] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],
				'value'                => $product_option['value'],
				'required'             => $product_option['required']
			);
		}

		return $product_option_data;
	}

    public function prepareArrayToCreateListingOnGS($gs_product = array(), $productDetails = array())
    {
        $this->load->model('catalog/product');
        $this->load->model('catalog/manufacturer');

        $listingArray = array();
        if (isset($gs_product) && count($gs_product) > 0) {

            $profileCategory = $this->db->query('SELECT * FROM ' . DB_PREFIX . 'gs_category_mapping WHERE id_gs_profiles = ' . (int) $gs_product['id_gs_profiles']);
            $gs_category = $this->getGSCategorybyProfileANDCategory($profileCategory, $gs_product);

            if ($gs_category != 0) {
                $gs_category_mapping = $this->db->query('SELECT * FROM ' . DB_PREFIX . 'gs_category_mapping WHERE id_gs_profiles = ' . (int) $gs_product['id_gs_profiles'] . ' AND gs_category_code = ' . (int) $gs_category);

                $price = $productDetails['price'];
                
                /** Get currency conversion */
                $currency_data = $this->getCurrency($gs_product['id_currency']);
                
                /** Final price */
                $final_price = round($currency_data['value'] * $price, 2);
                $final_price = $this->tax->calculate($final_price, $productDetails['tax_class_id'], $this->config->get('config_tax'));

                $listingArray = array(
                    'product_id' => $gs_product['id_product'],
                    'id_product' => $gs_product['id_product'],
                    'language_id' => $gs_product['language_id'],
                    'oc_lang_id' => $gs_product['oc_lang_id'],
                    'reference' => $gs_product['reference'],
                    'id_gs_profiles' => $gs_product['id_gs_profiles'],
                    'id_country' => $gs_product['id_country'],
                    'id_lang' => $gs_product['id_lang'],
                    'id_currency' => $gs_product['id_currency'],
                    'customize_product_title' => $gs_product['customize_product_title'],
                    'material' => $gs_category_mapping->row['material'],
                    'pattern' => $gs_category_mapping->row['pattern'],
                    'gender' => $gs_category_mapping->row['gender'],
                    'age_group' => $gs_category_mapping->row['age_group'],
                    'adult' => $gs_category_mapping->row['adult'],
                    'color' => $gs_category_mapping->row['color'],
                    'size' => $gs_category_mapping->row['size'],
                    'size_type' => $gs_category_mapping->row['size_type'],
                    'size_system' => $gs_category_mapping->row['size_system'],
                    'shipping' => $gs_product['shipping'],
                    'custom_label_0' => $gs_product['custom_label_0'],
                    'custom_label_2' => $gs_product['custom_label_2'],
                    'custom_label_3' => $gs_product['custom_label_3'],
                    'custom_label_4' => $gs_product['custom_label_4'],
                    'gs_category' => $gs_category,
                    'price' => $final_price,
                    'product_title' => $productDetails['name'],
                    'description' => substr(html_entity_decode($productDetails["description"]), 0, 5000),
                    'manufacturer' => $productDetails['manufacturer'],
                    'image' => $productDetails['image'],
                    'weight' => $productDetails['weight'],
                    'weight_class_id' => $productDetails['weight_class_id'],
                    'quantity' => $productDetails['quantity'],
                    'upc' => $productDetails['upc'],
                    'ean' => $productDetails['ean'],
                    'model' => $productDetails['model'],
                    'jan' => $productDetails['jan'],
                    'isbn' => $productDetails['isbn'],
                    'mpn' => $productDetails['mpn'],
                    'sku' => $productDetails['sku'],
                    'gtin' => $gs_product['gtin']
                );
            }
        }
        return $listingArray;
    }

    public function getGSCategorybyProfileANDCategory($profileCategory)
    {
        $this->load->model('catalog/product');
        $gs_category = '';
        if (!empty($profileCategory->rows) && is_array($profileCategory->rows)) {
            foreach ($profileCategory->rows as $proCat) {
                $gs_category = $proCat['gs_category_code'];
            }
        }
        return $gs_category;
    }

    public function sendFeedToGoogle($listingArray = array())
    {
        $this->load->model('googleshopping/cron');

        $gs_feeds = $this->db->query('SELECT a.*, p.* FROM ' . DB_PREFIX . 'gs_feeds a INNER JOIN ' . DB_PREFIX . 'gs_profiles p ON (a.id_gs_profiles = p.id_gs_profiles) WHERE a.active = 1');
        $array_listing = array();
        foreach ($gs_feeds->rows as $gsfeeds) {
            /* Save Feed File on Directory for each feed. Create If not presnet */
            $this->gsCreateFeed($gsfeeds);
        }
        if (!empty($listingArray) && count($listingArray) > 0) {
            foreach ($listingArray as $listing) {
                if (isset($listing['id_product'])) {
                    $array_listing[$listing['id_gs_profiles']][] = $listing;
                }
            }
        }

        $gs_feeds_active = $this->db->query('SELECT * FROM ' . DB_PREFIX . 'gs_feeds where active = 1');
        $feeds = $gs_feeds_active->rows;
        $feed_id_array = array();
        foreach ($feeds as $feed) {
            $feed_id_array[] = $feed['id_gs_profiles'];
        }

        if (!empty($array_listing)) {
            foreach ($feed_id_array as $feed) {
                if(!empty($array_listing[$feed])) {
                    $listing = $array_listing[$feed];
                    $key = $feed;
                    $feedContent = '';
                    $this->feed_content = '';
                    $feed_path = DIR_IMAGE . 'googleshopping/feeds/cron_feed_' . $key . '.xml';
                    $feedContent .= $this->generateXMLFeed($listing);
                    if (!empty($feedContent)) {
                        $feed_content = '';
                        $feed_content .= '<?xml version="1.0"?>' . PHP_EOL;
                        $feed_content .= '<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">' . PHP_EOL;
                        $feed_content .= '<channel>' . PHP_EOL;
                        $feed_content .= $feedContent;
                        $feed_content .= '</channel>' . PHP_EOL;
                        $feed_content .= '</rss>' . PHP_EOL;
                        $fp = fopen($feed_path, "w");
                        fwrite($fp, $feed_content);
                        fclose($fp);
                        $this->db->query('UPDATE ' . DB_PREFIX . 'gs_profiles set feed_generated = "' . $this->db->escape($feed_path) . '" WHERE id_gs_profiles = ' . (int) $key);
                    }
                }
            }
        }
    }

    public function gsCreateFeed($gsFeed)
    {
        $feed_fetch_url = HTTPS_SERVER . 'image/googleshopping/feeds/cron_feed_' . ($gsFeed['id_gs_profiles']) . '.xml';
        $feed_fetch_path = DIR_IMAGE . 'googleshopping/feeds/cron_feed_' . ($gsFeed['id_gs_profiles']) . '.xml';
        $response = $this->createFeedSchedule($gsFeed, $feed_fetch_url);
        if (!isset($response['error'])) {
            if (isset($response['feed_id'])) {
                $this->db->query('UPDATE ' . DB_PREFIX . 'gs_feeds set feed_path = "' . $this->db->escape($feed_fetch_path) . '",feed_url="' . $this->db->escape($feed_fetch_url) . '", gs_feed_id = ' . (int) $response['feed_id'] . ',feed_error="" WHERE id_gs_feed = ' . (int) $gsFeed['id_gs_feed']);
            }
        } else {
            if (isset($response['message'])) {
                $this->db->query('UPDATE ' . DB_PREFIX . 'gs_feeds set feed_path = "' . $this->db->escape($feed_fetch_path) . '",feed_url="' . $this->db->escape($feed_fetch_url) . '", feed_error = "' . $this->db->escape($response['message']) . '" WHERE id_gs_feed = ' . (int) $gsFeed['id_gs_feed']);
            }
        }
        return true;
    }

    public function generateXMLFeed($listing)
    {
        $this->load->model('catalog/product');
        if (empty($listing)) {
            return;
        }
        foreach ($listing as $product) {
            $this->item_group_id = $this->createSingleProductXML($product);
            $this->db->query("UPDATE " . DB_PREFIX . "gs_products_list SET generated_listing_id = '" . $this->db->escape($this->item_group_id) . "' WHERE id_product = '" . (int) $product['id_product'] . "' AND id_gs_profiles = '" . (int) $product['id_gs_profiles'] . "'");
        }
        return $this->feed_content;
    }

    public function createFeedSchedule($gsFeed, $feed_fetch_url)
    {
        $store_id = $this->config->get('config_store_id');
        $settings = $this->getSetting('google_connection_settings', $store_id);
        $settings_token = $this->getSetting('gs_token_info', $store_id);
        $settings_refresh_token = $this->getSetting('gs_refresh_token', $store_id);
        $settings['google_connection_settings']['connection']['token_info'] = json_encode($settings_token['gs_token_info']);
        $settings['google_connection_settings']['connection']['refresh_token'] = $settings_refresh_token['gs_refresh_token'];
        $settings['google_connection_settings']['connection']['store_id'] = $store_id;

        $feed_schedule = new GSFeedSchedule($settings['google_connection_settings']['connection']);

        $feedStatus = false;
        if ($gsFeed['gs_feed_id'] != 0) {
            /* If false, then means feed does't exist. In that case, create the feed */
            $feedStatus = $feed_schedule->getFeedSchedule($gsFeed['gs_feed_id'], $gsFeed, $feed_fetch_url);
        }

        if ($feedStatus) {
            try {
                $response = $feed_schedule->updateFeedSchedule($gsFeed['gs_feed_id'], $gsFeed, $feed_fetch_url);
                return array('feed_id' => $gsFeed['gs_feed_id']);
            } catch (Google_Service_Exception $ex) {
                echo "Feed Create: " . $ex->getMessage() . "<br>";
                return array('error' => true, 'message' => $ex->getMessage());
            }
        } else {
            try {
                $country = $this->getCountry($gsFeed['id_country']);
                $country_iso_code = $country['iso_code'];

                $language = $this->getLanguage($gsFeed['id_lang']);
                $language_iso_code = $language['iso_code'];

                $response = $feed_schedule->insertDataFeed($gsFeed, $feed_fetch_url, $language_iso_code, $country_iso_code);
                if (!empty($response)) {
                    return array('feed_id' => $response->getId());
                } else {
                    echo "Feed Create: Feed cannot be scheduled<br/>";
                    return array('error' => true, 'message' => 'Feed cannot be scheduled');
                }
            } catch (Google_Service_Exception $ex) {
                echo "Feed Create: " . $ex->getMessage() . "<br>";
                return array('error' => true, 'message' => $ex->getMessage());
            }
        }
    }

    public function createSingleProductXML($listing)
    {
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/product');

        $store_id = $this->config->get('config_store_id');

        $productOptions = $this->getProductOptions($listing['product_id'], $listing['oc_lang_id']);
        $colorVariation = false;
        $sizeVariation = false;

        if ($listing['color'] != "") {
            foreach ($productOptions as $option) {
                if ($option['option_id'] == $listing['color']) {
                    $colorVariation = $option;
                    break;
                }
            }
        }

        if ($listing['size'] != "") {
            foreach ($productOptions as $option) {
                if ($option['option_id'] == $listing['size']) {
                    $sizeVariation = $option;
                    break;
                }
            }
        }

        $settings = $this->getSetting('google_general_settings', $store_id);
        $kb_general = $settings['google_general_settings']['general'];

        $currency = $this->getCurrency($listing['id_currency']);
        $currency_iso_code = $currency['iso_code'];

        $country = $this->getCountry($listing['id_country']);
        $country_iso_code = $country['iso_code'];

        $image_link = HTTPS_SERVER . 'image/' . $listing['image'];

        $utm_campaign = '';
        if (!empty($kb_general['utm_campaign'])) {
            $utm_campaign = $kb_general['utm_campaign'];
        }

        $utm_source = '';
        if (!empty($kb_general['utm_source'])) {
            $utm_source = $kb_general['utm_source'];
        }

        $utm_medium = '';
        if (!empty($kb_general['utm_medium'])) {
            $utm_medium = $kb_general['utm_medium'];
        }
        $str = '&';
        $str .= ($utm_campaign != '') ? 'utm_campaign=' . $utm_campaign : '';
        $str .= ($utm_source != '') ? '&utm_source=' . $utm_source : '';
        $str .= ($utm_medium != '') ? '&utm_medium=' . $utm_medium : '';
        $utm_string = urlencode($str);

        $product_url = $this->url->link('product/product', 'product_id=' . $listing['product_id'] . $utm_string);

        $has_quantity = true;
        if ($listing["adult"] == 1) {
            $listing["adult"] = 'yes';
        } else {
            $listing["adult"] = 'no';
        }

        $qty = $listing['quantity'];
        if ($qty <= 0) {
            $has_quantity = false;
        }

        $price = $listing['price'];
        $gtin_key = $listing['gtin'];

        $availibilty = 'out of stock';
        if ($has_quantity) {
            $availibilty = 'in stock';
        }

        $weight_class = $this->getWeightClass($listing["weight_class_id"]);

        $method_data = array();
        
        if (VERSION > 2.3) {
            $this->load->model('setting/extension');
            $results = $this->model_setting_extension->getExtensions('shipping');
        } else {
            $this->load->model('extension/extension');
            $results = $this->model_extension_extension->getExtensions('shipping');
        }
        
        $country_query = $this->db->query("SELECT country_id
            FROM `".DB_PREFIX."gs_countries`
            WHERE `id_gs_countries` = '".$listing['id_country']."'");

        $address_array = array(
            'country_id' => $country_query->row['country_id'],
            'zone_id' => '0'
        );
        foreach ($results as $result) {
            if ($this->config->get($result['code'] . '_status')) {
                
                if (VERSION >= 2.3) {
                    $this->load->model('extension/shipping/' . $result['code']);
                    $quote = $this->{'model_extension_shipping_' . $result['code']}->getQuote($address_array);
                } else {
                    $this->load->model('shipping/' . $result['code']);
                    $quote = $this->{'model_shipping_' . $result['code']}->getQuote($address_array);
                }
                
                if ($quote) {
                    $method_data[$result['code']] = array(
                        'title' => $quote['title'],
                        'quote' => $quote['quote'],
                        'sort_order' => $quote['sort_order'],
                        'error' => $quote['error']
                    );
                }
            }
        }
        
        $gs_shipping = explode(',', $listing['shipping']);
        $shippingDetails = array();
        $item = 0;
        if (!empty($gs_shipping)) {
            foreach ($gs_shipping as $id_carrier) {
                $cost = 0;
                $carrier_name = '';
                foreach ($method_data as $key1 => $shipping_methods) {
                    foreach ($shipping_methods['quote'] as $key => $value) {
                        if (strpos($key,$id_carrier) !== false) {
                            $cost += $value['cost'];
                            $carrier_name = $value['title'];
                        }  else if ($key1 == $id_carrier) {
                            $cost += $value['cost'];
                            $carrier_name = $value['title'];
                        }
                    }
                }
                if($carrier_name != "") {
                    $shippingDetails[$item]['price'] = round($cost * $currency['value'], 2) . ' ' . $currency_iso_code;
                    $shippingDetails[$item]['country'] = $currency_iso_code;
                    $shippingDetails[$item]['service'] = $carrier_name;
                    $item++;
                }
            }
        }


        $variations = array();
        if (!empty($colorVariation) && $sizeVariation == false) {
            $productcount = 0;
            foreach ($colorVariation['product_option_value'] as $color) {
                $variations[$productcount]["color"] = $color["name"];
                $productcount++;
            }
        } else if (!empty($sizeVariation) && $colorVariation == false) {
            $productcount = 0;
            foreach ($sizeVariation['product_option_value'] as $size) {
                $variations[$productcount]["size"] = $size["name"];
                $productcount++;
            }
        } else if (!empty($sizeVariation) && !empty($sizeVariation)) {
            /* Create Combination if Size & Color Both Exist */
            $productcount = 0;
            foreach ($colorVariation['product_option_value'] as $color) {
                foreach ($sizeVariation['product_option_value'] as $size) {
                    $variations[$productcount]["color"] = $color["name"];
                    $variations[$productcount]["size"] = $size["name"];
                    $productcount++;
                }
            }
        }

        $xml = '';

        /* Execute Feed Generation Loop One Time if No Variation */
        if (empty($variations)) {
            $variations[0] = true;
        }

        foreach ($variations as $variation) {
            $xml .= '<item>' . PHP_EOL;
            $store_name = preg_replace("/[^a-zA-Z0-9\s]/", "", $this->config->get('config_name'));
            $store_name = substr(str_replace(" ", "", strtoupper($store_name)), 0, 40);

            $google_offer_id = $store_name . "_" . $listing['id_product'];

            /* In case of variation, id will be different. Include Color & Size in the ID */
            if (is_array($variation)) {
                /* Append Color & Size If both variation are avaible for product. Else Either Size OR COlOR */
                $item_id = "";
                if (isset($variation["size"])) {
                    $item_id .= $google_offer_id . "_" . $variation["size"];
                }
                if (isset($variation["color"])) {
                    if ($item_id == "") {
                        $item_id = $google_offer_id . "_" . $variation["color"];
                    } else {
                        $item_id = $item_id . "_" . $variation["color"];
                    }
                }
                $xml .= '<g:id>' . strtoupper($item_id) . '</g:id>' . PHP_EOL;
            } else {
                $xml .= '<g:id>' . strtoupper($google_offer_id) . '</g:id>' . PHP_EOL;
            }
            $xml .= '<g:title><![CDATA[' . $listing["product_title"] . ']]></g:title>' . PHP_EOL;
            $xml .= '<g:description><![CDATA[' . $listing["description"] . ']]></g:description>' . PHP_EOL;
            $xml .= '<g:link><![CDATA[' . $product_url . ']]></g:link>' . PHP_EOL;
            $xml .= '<g:condition>new</g:condition>' . PHP_EOL;

            if ($listing[strtolower($gtin_key)] == "") {
                $xml .= '<g:identifier_exists>no</g:identifier_exists>' . PHP_EOL;
            } else {
                $xml .= '<g:gtin>' . $listing[strtolower($gtin_key)] . '</g:gtin>' . PHP_EOL;
            }
            $xml .= '<g:price>' . $price . ' ' . $currency_iso_code . '</g:price>' . PHP_EOL;
            $xml .= '<g:availability>' . $availibilty . '</g:availability>' . PHP_EOL;
            if (!empty($image_link)) {
                $xml .= '<g:image_link><![CDATA[' . $image_link . ']]></g:image_link>' . PHP_EOL;
            }
            $xml .= '<g:google_product_category>' . $listing["gs_category"] . '</g:google_product_category>' . PHP_EOL;
            $xml .= '<g:product_type><![CDATA[' . $listing["gs_category"] . ']]></g:product_type>' . PHP_EOL;

            if (isset($variation["color"]) && $variation["color"] != "") {
                $xml .= '<g:color><![CDATA[' . $variation["color"] . ']]></g:color>' . PHP_EOL;
            }

            if (isset($variation["size"])) {
                $xml .= '<g:size><![CDATA[' . $variation["size"] . ']]></g:size>' . PHP_EOL;
                if (!empty($listing['size_type'])) {
                    $xml .= '<g:size_type><![CDATA[' . $listing["size_type"] . ']]></g:size_type>' . PHP_EOL;
                }
                if (!empty($listing['size_system'])) {
                    $xml .= '<g:size_system><![CDATA[' . $listing["size_system"] . ']]></g:size_system>' . PHP_EOL;
                }
            }

            if (is_array($variation)) {
                $xml .= '<g:item_group_id><![CDATA[' . strtoupper($google_offer_id) . ']]></g:item_group_id>' . PHP_EOL;
            }

            /* If GTIN is blank, then Brand will not be accepted by Google */
            if ($listing[strtolower($gtin_key)] != "") {
                $brand_name = $listing['manufacturer'];
                if (!empty($brand_name)) {
                    $xml .= '<g:brand><![CDATA[' . $brand_name . ']]></g:brand>' . PHP_EOL;
                }
            }

            if (!empty($listing['gender'])) {
                $xml .= '<g:gender><![CDATA[' . $listing["gender"] . ']]></g:gender>' . PHP_EOL;
            }

            if (!empty($listing['age_group'])) {
                $xml .= '<g:age_group><![CDATA[' . $listing["age_group"] . ']]></g:age_group>' . PHP_EOL;
            }

            $xml .= '<g:adult><![CDATA[' . $listing["adult"] . ']]></g:adult>' . PHP_EOL;

            if (!empty($listing['custom_label_0'])) {
                $xml .= '<g:custom_label_0><![CDATA[' . $listing["custom_label_0"] . ']]></g:custom_label_0>' . PHP_EOL;
            }
            if (!empty($listing['custom_label_1'])) {
                $xml .= '<g:custom_label_1><![CDATA[' . $listing["custom_label_1"] . ']]></g:custom_label_1>' . PHP_EOL;
            }
            if (!empty($listing['custom_label_2'])) {
                $xml .= '<g:custom_label_2><![CDATA[' . $listing["custom_label_2"] . ']]></g:custom_label_2>' . PHP_EOL;
            }
            if (!empty($listing['custom_label_3'])) {
                $xml .= '<g:custom_label_3><![CDATA[' . $listing["custom_label_3"] . ']]></g:custom_label_3>' . PHP_EOL;
            }
            if (!empty($listing['custom_label_4'])) {
                $xml .= '<g:custom_label_4><![CDATA[' . $listing["custom_label_4"] . ']]></g:custom_label_4>' . PHP_EOL;
            }
            
            $method_data = array();
            if (VERSION > 2.3) {
                $this->load->model('setting/extension');
                $results = $this->model_setting_extension->getExtensions('shipping');
            } else {
                $this->load->model('extension/extension');
                $results = $this->model_extension_extension->getExtensions('shipping');
            }
            
            $country_query = $this->db->query("SELECT country_id
                FROM `".DB_PREFIX."gs_countries`
                WHERE `id_gs_countries` = '".$listing['id_country']."'");
            
            $address_array = array(
                'country_id' => $country_query->row['country_id'],
                'zone_id' => '0'
            );
            foreach ($results as $result) {
                
                $extension_status = $this->config->get($result['code'] . '_status');
                if (VERSION > 2.3) {
                    $extension_status = $this->config->get('shipping_'.$result['code'] . '_status');
                }
                
                if ($extension_status) {
                    if (VERSION >= 2.3) {
                        $this->load->model('extension/shipping/' . $result['code']);
                        $quote = $this->{'model_extension_shipping_' . $result['code']}->getQuote($address_array);
                    } else {
                        $this->load->model('shipping/' . $result['code']);
                        $quote = $this->{'model_shipping_' . $result['code']}->getQuote($address_array);
                    }

                    if ($quote) {
                        $method_data[$result['code']] = array(
                            'title' => $quote['title'],
                            'quote' => $quote['quote'],
                            'sort_order' => $quote['sort_order'],
                            'error' => $quote['error']
                        );
                    }
                }
            }
            $gs_shipping = explode(',', $listing['shipping']);
            foreach ($gs_shipping as $id_carrier) {
                $cost = 0;
                $carrier_name = '';
                foreach ($method_data as $shipping_methods) {
                    foreach ($shipping_methods['quote'] as $key => $value) {
                        if (strpos($key,$id_carrier) !== false) {
                            $cost += $value['cost'];
                            $carrier_name = $value['title'];
                        }
                    }
                }
                $xml .= '<g:shipping>' . PHP_EOL;
                $xml .= '<g:price>' . $cost . ' ' . $currency_iso_code . '</g:price>' . PHP_EOL;
                $xml .= '<g:country><![CDATA[' . $country_iso_code . ']]></g:country>' . PHP_EOL;
                $xml .= '<g:service><![CDATA[' . $carrier_name . ']]></g:service>' . PHP_EOL;
                $xml .= '</g:shipping>' . PHP_EOL;
            }
            $weight_class = $this->getWeightClass($listing["weight_class_id"]);
            $xml .= '<g:shipping_weight><![CDATA[' . $listing["weight"] . ' ' . $weight_class[0]["unit"] . ']]></g:shipping_weight>' . PHP_EOL;
            $xml .= '</item>' . PHP_EOL;
        }
        $this->feed_content .= $xml;
        return $google_offer_id;
    }

    public function getCurrency($id)
    {
        $google_currency = $this->db->query('SELECT gc.*, c.value FROM ' . DB_PREFIX . 'gs_currencies gc INNER JOIN ' . DB_PREFIX . 'currency c ON gc.iso_code = c.code WHERE id_gs_currencies = ' . (int) $id);
        return $google_currency->row;
    }

    public function getLanguage($id)
    {
        $google_language = $this->db->query('SELECT * FROM ' . DB_PREFIX . 'gs_languages WHERE id_gs_languages = ' . (int) $id);
        return $google_language->row;
    }

    public function getCountry($id)
    {
        $google_country = $this->db->query('SELECT * FROM ' . DB_PREFIX . 'gs_countries WHERE id_gs_countries = ' . (int) $id);
        return $google_country->row;
    }

    public function googleOfferId($product)
    {
        $offer_id = $product['sku'];
        if ($offer_id == "") {
            $offer_id = $product['model'];
        }
        if ($offer_id == "") {
            $offer_id = $this->generateRandomNumber();
        }
        return $offer_id;
    }

    public function getWeightClass($id)
    {
        $google_weight = $this->db->query('SELECT * FROM ' . DB_PREFIX . 'weight_class_description WHERE weight_class_id = ' . (int) $id);
        return $google_weight->rows;
    }

    public function fetchGSProduct()
    {
        $store_id = $this->config->get('config_store_id');

        $settings = $this->getSetting('google_connection_settings', $store_id);
        $gs_config = $settings['google_connection_settings']['connection'];
        $settings_token = $this->getSetting('gs_token_info', $store_id);
        $settings_refresh_token = $this->getSetting('gs_refresh_token', $store_id);
        $gs_config['refresh_token'] = $settings_refresh_token['gs_refresh_token'];
        $gs_config['token_info'] = json_encode($settings_token['gs_token_info']);
        $gs_config['store_id'] = $store_id;
        $gsActionObject = new GSSingleAction($gs_config);
        $productList = array();
        $gsActionObject->getProductList($productList);
		if(count($productList) > 0) {
			foreach ($productList as $product) {
				$this->updateProductListing($product);
			}
		}
        return true;
    }

    public function updateProductListing($data)
    {
        $google_offer_id = $data['offerId'];
        if (isset($data['itemGroupId'])) {
            $google_offer_id = $data['itemGroupId'];
        }
        $updateSQL = "UPDATE " . DB_PREFIX . "gs_products_list SET listing_id = '" . $this->db->escape($google_offer_id) . "', listing_status = 'Listed', listing_error = '', date_listed = NOW() WHERE generated_listing_id = '" . $this->db->escape($google_offer_id) . "'";
        $this->db->query($updateSQL);
    }

}
