<?php
class ModelExtensionCiBlogCiCommon extends Model {
	public function buildTable() {
		/*--
		-- Table structure for table `oc_ciblog_author`
		--*/
		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."ciblog_author` (
		  `ciblog_author_id` int(11) NOT NULL AUTO_INCREMENT,
		  `image` varchar(255) DEFAULT NULL,
		  `sort_order` int(3) NOT NULL,
		  `status` tinyint(4) NOT NULL,
		  `date_added` datetime NOT NULL,
		  `date_modified` datetime NOT NULL,
		  PRIMARY KEY (`ciblog_author_id`)
		) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;");

		/*--
		-- Table structure for table `oc_ciblog_author_description`
		--*/

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."ciblog_author_description` (
		  `ciblog_author_id` int(11) NOT NULL,
		  `language_id` int(11) NOT NULL,
		  `image_alt` varchar(255) NOT NULL,
		  `image_title` varchar(255) NOT NULL,
		  `description` text NOT NULL,
		  `name` varchar(64) NOT NULL,
		  `meta_title` varchar(255) NOT NULL,
		  `meta_description` varchar(255) NOT NULL,
		  `meta_keyword` varchar(255) NOT NULL,
		  PRIMARY KEY (`ciblog_author_id`,`language_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;");



		/*--
		-- Table structure for table `oc_ciblog_author_to_store`
		--*/

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."ciblog_author_to_store` (
		  `ciblog_author_id` int(11) NOT NULL,
		  `store_id` int(11) NOT NULL,
		  PRIMARY KEY (`ciblog_author_id`,`store_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;");



		/*--
		-- Table structure for table `oc_ciblog_category`
		--*/

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."ciblog_category` (
		  `ciblog_category_id` int(11) NOT NULL AUTO_INCREMENT,
		  `image` varchar(255) DEFAULT NULL,
		  `parent_id` int(11) NOT NULL DEFAULT '0',
		  `top` tinyint(1) NOT NULL,
		  `column` int(3) NOT NULL,
		  `sort_order` int(3) NOT NULL DEFAULT '0',
		  `status` tinyint(1) NOT NULL,
		  `date_added` datetime NOT NULL,
		  `date_modified` datetime NOT NULL,
		  PRIMARY KEY (`ciblog_category_id`),
		  KEY `parent_id` (`parent_id`)
		) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;");



		/*--
		-- Table structure for table `oc_ciblog_category_description`
		--*/

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."ciblog_category_description` (
		  `ciblog_category_id` int(11) NOT NULL,
		  `language_id` int(11) NOT NULL,
		  `image_alt` varchar(255) NOT NULL,
		  `image_title` varchar(255) NOT NULL,
		  `name` varchar(255) NOT NULL,
		  `description` text NOT NULL,
		  `meta_title` varchar(255) NOT NULL,
		  `meta_description` varchar(255) NOT NULL,
		  `meta_keyword` varchar(255) NOT NULL,
		  PRIMARY KEY (`ciblog_category_id`,`language_id`),
		  KEY `name` (`name`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;");



		/*--
		-- Table structure for table `oc_ciblog_category_path`
		--*/

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."ciblog_category_path` (
		  `ciblog_category_id` int(11) NOT NULL,
		  `path_id` int(11) NOT NULL,
		  `level` int(11) NOT NULL,
		  PRIMARY KEY (`ciblog_category_id`,`path_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;");



		/*--
		-- Table structure for table `oc_ciblog_category_to_layout`
		--*/

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."ciblog_category_to_layout` (
		  `ciblog_category_id` int(11) NOT NULL,
		  `store_id` int(11) NOT NULL,
		  `layout_id` int(11) NOT NULL,
		  PRIMARY KEY (`ciblog_category_id`,`store_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;");



		/*--
		-- Table structure for table `oc_ciblog_category_to_store`
		--
		*/
		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."ciblog_category_to_store` (
		  `ciblog_category_id` int(11) NOT NULL,
		  `store_id` int(11) NOT NULL,
		  PRIMARY KEY (`ciblog_category_id`,`store_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;");



		/*--
		-- Table structure for table `oc_ciblog_comment`
		--*/

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."ciblog_comment` (
		  `ciblog_comment_id` int(11) NOT NULL AUTO_INCREMENT,
		  `ciblog_post_id` int(11) NOT NULL,
		  `customer_id` int(11) NOT NULL,
		  `language_id` int(11) NOT NULL,
		  `store_id` int(11) NOT NULL,
		  `title` varchar(255) NOT NULL,
		  `author` varchar(64) NOT NULL,
		  `text` mediumtext NOT NULL,
		  `rating` int(1) NOT NULL,
		  `email` varchar(96) NOT NULL,
		  `status` tinyint(1) NOT NULL DEFAULT '0',
		  `date_added` datetime NOT NULL,
		  `date_modified` datetime NOT NULL,
		  PRIMARY KEY (`ciblog_comment_id`),
		  KEY `product_id` (`ciblog_post_id`)
		) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;");



		/*--
		-- Table structure for table `oc_ciblog_post`
		--*/

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."ciblog_post` (
		  `ciblog_post_id` int(11) NOT NULL AUTO_INCREMENT,
		  `image` varchar(255) DEFAULT NULL,
		  `ciblog_author_id` int(11) NOT NULL,
		  `add_video_url` tinyint(1) NOT NULL,
		  `video_url` varchar(255) NOT NULL,
		  `sort_order` int(11) NOT NULL DEFAULT '0',
		  `date_available` date NOT NULL,
		  `status` tinyint(1) NOT NULL DEFAULT '0',
		  `allow_comment` tinyint(1) NOT NULL,
		  `viewed` int(5) NOT NULL DEFAULT '0',
		  `date_added` datetime NOT NULL,
		  `date_modified` datetime NOT NULL,
		  PRIMARY KEY (`ciblog_post_id`)
		) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;");



		/*--
		-- Table structure for table `oc_ciblog_post_description`
		--*/

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."ciblog_post_description` (
		  `ciblog_post_id` int(11) NOT NULL,
		  `language_id` int(11) NOT NULL,
		  `image_alt` varchar(255) NOT NULL,
		  `image_title` varchar(255) NOT NULL,
		  `name` varchar(255) NOT NULL,
		  `small_description` text NOT NULL,
		  `description` text NOT NULL,
		  `tag` text NOT NULL,
		  `meta_title` varchar(255) NOT NULL,
		  `meta_description` varchar(255) NOT NULL,
		  `meta_keyword` varchar(255) NOT NULL,
		  PRIMARY KEY (`ciblog_post_id`,`language_id`),
		  KEY `name` (`name`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;");



		/*--
		-- Table structure for table `oc_ciblog_post_heart`
		--*/

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."ciblog_post_heart` (
		  `ciblog_post_heart` int(11) NOT NULL AUTO_INCREMENT,
		  `ciblog_post_id` int(11) NOT NULL,
		  `customer_id` int(11) NOT NULL,
		  `session_id` varchar(255) NOT NULL,
		  `heart` tinyint(4) NOT NULL,
		  `status` int(11) NOT NULL,
		  PRIMARY KEY (`ciblog_post_heart`)
		) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;");



		/*--
		-- Table structure for table `oc_ciblog_post_image`
		--*/

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."ciblog_post_image` (
		  `ciblog_post_image_id` int(11) NOT NULL AUTO_INCREMENT,
		  `ciblog_post_id` int(11) NOT NULL,
		  `image` varchar(255) DEFAULT NULL,
		  `sort_order` int(3) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`ciblog_post_image_id`)
		) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;");



		/*--
		-- Table structure for table `oc_ciblog_post_image_info`
		--*/

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."ciblog_post_image_info` (
		  `ciblog_post_image_id` int(11) NOT NULL AUTO_INCREMENT,
		  `ciblog_post_id` int(11) NOT NULL,
		  `language_id` int(11) NOT NULL,
		  `alt` varchar(255) NOT NULL,
		  `title` varchar(255) NOT NULL,
		  PRIMARY KEY (`ciblog_post_image_id`,`language_id`),
		  KEY `name` (`alt`)
		) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;");



		/*--
		-- Table structure for table `oc_ciblog_post_related`
		--*/

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."ciblog_post_related` (
		  `ciblog_post_id` int(11) NOT NULL,
		  `related_id` int(11) NOT NULL,
		  PRIMARY KEY (`ciblog_post_id`,`related_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;");



		/*--
		-- Table structure for table `oc_ciblog_post_related_product`
		--*/

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."ciblog_post_related_product` (
		  `ciblog_post_id` int(11) NOT NULL,
		  `related_id` int(11) NOT NULL,
		  PRIMARY KEY (`ciblog_post_id`,`related_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;");



		/*--
		-- Table structure for table `oc_ciblog_post_to_ciblog_category`
		--*/

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."ciblog_post_to_ciblog_category` (
		  `ciblog_post_id` int(11) NOT NULL,
		  `ciblog_category_id` int(11) NOT NULL,
		  PRIMARY KEY (`ciblog_post_id`,`ciblog_category_id`),
		  KEY `cibog_category_id` (`ciblog_category_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;");



		/*--
		-- Table structure for table `oc_ciblog_post_to_layout`
		--*/

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."ciblog_post_to_layout` (
		  `ciblog_post_id` int(11) NOT NULL,
		  `store_id` int(11) NOT NULL,
		  `layout_id` int(11) NOT NULL,
		  PRIMARY KEY (`ciblog_post_id`,`store_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;");



		/*--
		-- Table structure for table `oc_ciblog_post_to_store`
		--*/

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."ciblog_post_to_store` (
		  `ciblog_post_id` int(11) NOT NULL,
		  `store_id` int(11) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`ciblog_post_id`,`store_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;");



		/*--
		-- Table structure for table `oc_ciblog_subscriber`
		--*/

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."ciblog_subscriber` (
		  `ciblog_subscriber_id` int(11) NOT NULL AUTO_INCREMENT,
		  `email` varchar(128) NOT NULL,
		  `status` tinyint(1) NOT NULL DEFAULT '0',
		  `date_added` datetime NOT NULL,
		  `date_modified` datetime NOT NULL,
		  `code` varchar(25) NOT NULL,
		  `date_generated` datetime NOT NULL,
		  `expire_hours` tinyint(4) NOT NULL,
		  `verification_status` varchar(10) NOT NULL COMMENT 'EXPIRED,CONFIRMED,PENDING',
		  `action_taken` varchar(100) NOT NULL COMMENT 'DATE_ZERO,DATE_EXPIRED,CONFIRMED',
		  `action_requested` varchar(100) NOT NULL COMMENT 'SUBSCRIBE,UNSUBSCRIBE',
		  PRIMARY KEY (`ciblog_subscriber_id`)
		) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;");

	}
}