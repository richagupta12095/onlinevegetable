<?php
class ControllerExtensionCiBlogCiBlogPost extends Controller {
	private $error = array();

	public function __construct($registry) {
		parent :: __construct($registry);
		$this->load->language('extension/ciblog/ciblogpost');
		$this->load->language('extension/ciblog/cicomment');
		$this->load->language('extension/ciblog/ciblog_common');
		$this->load->model('extension/ciblog/cicategory');
		$this->load->model('extension/ciblog/ciauthor');
		$this->load->model('extension/ciblog/ciblogpost');
		$this->load->model('extension/ciblog/cicomment');
		$this->load->model('tool/image');
	}

	public function index() {
		$this->document->addStyle('catalog/view/theme/default/stylesheet/ciblog.css');
		$this->document->addScript('catalog/view/javascript/ciblog/ciblog.js');
		$this->document->addScript('catalog/view/javascript/ciblog/rating/bootstrap-rating-input.js');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_blog'),
			'href' => $this->url->link('extension/ciblog/ciblog')
		);

		if (isset($this->request->get['ciblogpath'])) {
			$ciblogpath = '';

			$parts = explode('_', (string)$this->request->get['ciblogpath']);

			$ciblog_category_id = (int)array_pop($parts);

			foreach ($parts as $ciblogpath_id) {
				if (!$ciblogpath) {
					$ciblogpath = $ciblogpath_id;
				} else {
					$ciblogpath .= '_' . $ciblogpath_id;
				}

				$category_info = $this->model_extension_ciblog_cicategory->getCiCategory($ciblogpath_id);

				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('extension/ciblog/cicategory', 'ciblogpath=' . $ciblogpath)
					);
				}
			}

			// Set the last category breadcrumb
			$category_info = $this->model_extension_ciblog_cicategory->getCiCategory($ciblog_category_id);

			if ($category_info) {
				$url = '';

				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}

				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}

				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}

				$data['breadcrumbs'][] = array(
					'text' => $category_info['name'],
					'href' => $this->url->link('extension/ciblog/cicategory', 'ciblogpath=' . $this->request->get['ciblogpath'] . $url)
				);
			}
		}


		if (isset($this->request->get['ciblog_author_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_brand'),
				'href' => $this->url->link('extension/ciblog/ciauthor')
			);

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$ciblog_author_info = $this->model_extension_ciblog_ciauthor->getCiAuthor($this->request->get['ciblog_author_id']);

			if ($ciblog_author_info) {
				$data['breadcrumbs'][] = array(
					'text' => $ciblog_author_info['name'],
					'href' => $this->url->link('extension/ciblog/ciauthor/info', 'ciblog_author_id=' . $this->request->get['ciblog_author_id'] . $url)
				);
			}
		}

		if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
			$url = '';

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['ciblog_category_id'])) {
				$url .= '&ciblog_category_id=' . $this->request->get['ciblog_category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_search'),
				'href' => $this->url->link('extension/ciblog/cisearch', $url)
			);
		}

		if (isset($this->request->get['ciblog_post_id'])) {
			$ciblog_post_id = (int)$this->request->get['ciblog_post_id'];
		} else {
			$ciblog_post_id = 0;
		}


		$blogpost_info = $this->model_extension_ciblog_ciblogpost->getCiBlogPost($ciblog_post_id);

		if ($blogpost_info) {

			$data['can_like'] = (($this->config->get('ciblog_store_can_like')=='LOGGED' && $this->customer->isLogged()) || ($this->config->get('ciblog_store_can_like')=='BOTH') );

			$image_thumb_width = (int)$this->config->get('ciblog_store_blog_image_thumb_width');
			$image_thumb_height = (int)$this->config->get('ciblog_store_blog_image_thumb_height');
			if(!$image_thumb_width) {
				$image_thumb_width = 1024;
			}
			if(!$image_thumb_height) {
				$image_thumb_height = 683;
			}


			$image_popup_width = (int)$this->config->get('ciblog_store_blog_image_popup_width');
			$image_popup_height = (int)$this->config->get('ciblog_store_blog_image_popup_height');
			if(!$image_popup_width) {
				$image_popup_width = 1024;
			}
			if(!$image_popup_height) {
				$image_popup_height = 683;
			}

			$image_additional_width = (int)$this->config->get('ciblog_store_blog_image_additional_width');
			$image_additional_height = (int)$this->config->get('ciblog_store_blog_image_additional_height');
			if(!$image_additional_width) {
				$image_additional_width = 424;
			}
			if(!$image_additional_height) {
				$image_additional_height = 283;
			}

			$data['blog_image_show_thumb'] = (int)$this->config->get('ciblog_store_blog_image_show_thumb');
			$data['blogpage_show_date_publish'] = (int)$this->config->get('ciblog_store_blogpage_show_date_publish');
			$data['blogpage_show_total_view'] = (int)$this->config->get('ciblog_store_blogpage_show_total_view');
			$data['blogpage_show_author'] = (int)$this->config->get('ciblog_store_blogpage_show_author');
			$data['blogpage_like_show_total'] = (int)$this->config->get('ciblog_store_blogpage_like_show_total');
			$data['blogpage_like_allow'] = (int)$this->config->get('ciblog_store_blogpage_like_allow');
			$data['blogpage_show_social_share'] = (int)$this->config->get('ciblog_store_blogpage_show_social_share');
			$data['blogpage_comment_allow'] = (int)$this->config->get('ciblog_store_blogpage_comment_allow');
			$data['blogpage_comment_allow_guest'] = (int)$this->config->get('ciblog_store_blogpage_comment_allow_guest');
			$data['blogpage_comment_show_total'] = (int)$this->config->get('ciblog_store_blogpage_comment_show_total');
			$data['blogpage_comment_snippet'] = (int)$this->config->get('ciblog_store_blogpage_comment_snippet');
			$data['blogpage_comment_show'] = (int)$this->config->get('ciblog_store_blogpage_comment_show');
			$data['blogpage_comment_limit'] = (int)$this->config->get('ciblog_store_blogpage_comment_limit');
			$data['blogpage_comment_approve'] = $this->config->get('ciblog_store_blogpage_comment_approve');
			$data['blogpage_comment_alert'] = (int)$this->config->get('ciblog_store_blogpage_comment_alert');
			$date_format = 'F m, Y';//$this->language->get('date_format_short');


			$url = '';

			if (isset($this->request->get['ciblogpath'])) {
				$url .= '&ciblogpath=' . $this->request->get['ciblogpath'];
			}

			if (isset($this->request->get['ciblog_author_id'])) {
				$url .= '&ciblog_author_id=' . $this->request->get['ciblog_author_id'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['ciblog_category_id'])) {
				$url .= '&ciblog_category_id=' . $this->request->get['ciblog_category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $blogpost_info['name'],
				'href' => $this->url->link('extension/ciblog/ciblogpost', $url . '&ciblog_post_id=' . $this->request->get['ciblog_post_id'])
			);

			$this->document->setTitle($blogpost_info['meta_title']);
			$this->document->setDescription($blogpost_info['meta_description']);
			$this->document->setKeywords($blogpost_info['meta_keyword']);
			$this->document->addLink($this->url->link('extension/ciblog/ciblogpost', 'ciblog_post_id=' . $this->request->get['ciblog_post_id']), 'canonical');

			// $this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
			// $this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
			// $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
			// $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
			// $this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

			$data['heading_title'] = $blogpost_info['name'];

			$data['text_write'] = $this->language->get('text_write');
			$data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true));

			$data['text_tags'] = $this->language->get('text_tags');
			$data['text_related'] = $this->language->get('text_related');
			$data['text_related_products'] = $this->language->get('text_related_products');
			$data['text_postby'] = $this->language->get('text_postby');
			$data['text_on'] = $this->language->get('text_on');
			$data['text_rating'] = $this->language->get('text_rating');

			$data['text_loading'] = $this->language->get('text_loading');
			$data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true));

			$data['text_note'] = $this->language->get('text_note');

			$data['entry_author'] = $this->language->get('entry_author');
			$data['entry_email'] = $this->language->get('entry_email');
			$data['entry_text'] = $this->language->get('entry_text');
			$data['entry_rating'] = $this->language->get('entry_rating');


			$data['button_continue'] = $this->language->get('button_continue');
			$data['button_read_more'] = $this->language->get('button_read_more');

			$data['ciblog_post_id'] = $blogpost_info['ciblog_post_id'];

			$data['date_added'] = date($date_format, strtotime($blogpost_info['date_added']));

			$data['viewed'] = (int)$blogpost_info['viewed'];
			$data['heart'] = (int)$blogpost_info['heart'];

			$data['add_video_url'] = (int)$blogpost_info['add_video_url'];
			$data['video_url'] = $this->ciblog->getVideoURLEmbedURL($blogpost_info['video_url']);
			$data['image_thumb_width'] = '100%';// $image_thumb_width;
			$data['image_thumb_height'] = $image_thumb_height.'px';


			// heart static
			$data['isMyHeart'] = false;
			$isHearted = $this->model_extension_ciblog_ciblogpost->isHearted($blogpost_info['ciblog_post_id']);
			if($isHearted) {
				$data['isMyHeart'] = true;
			}

			$data['comments'] = (int)$blogpost_info['comments'];

			$ciauthor_info = $this->model_extension_ciblog_ciauthor->getCiAuthor($blogpost_info['ciblog_author_id']);

			$data['author'] = array();
			$data['author']['name'] = '';
			$data['author']['href'] = '';
			if($ciauthor_info) {
				$data['author']['name'] = $ciauthor_info['name'];
				$data['author']['href'] = $this->url->link('extension/ciblog/ciauthor/info', 'ciblog_author_id=' . $ciauthor_info['ciblog_author_id'], true);
			}

			$data['share'] = $this->url->link('extension/ciblog/ciblogpost', 'ciblog_post_id=' . (int)$this->request->get['ciblog_post_id']);
			$data['description'] = html_entity_decode($blogpost_info['description'], ENT_QUOTES, 'UTF-8');

			if ($blogpost_info['image']) {
				$data['popup'] = $this->model_tool_image->resize($blogpost_info['image'],$image_popup_width, $image_popup_height);
			} else {
				$data['popup'] = '';
			}

			if ($blogpost_info['image']) {
				$data['thumb'] = $this->model_tool_image->resize($blogpost_info['image'], $image_thumb_width, $image_thumb_height);
			} else {
				$data['thumb'] = '';
			}

			$data['images'] = array();

			$results = $this->model_extension_ciblog_ciblogpost->getCiBlogPostImages($this->request->get['ciblog_post_id']);

			foreach ($results as $result) {
				$data['images'][] = array(
					'popup' => $this->model_tool_image->resize($result['image'],$image_popup_width, $image_popup_height),
					'thumb' => $this->model_tool_image->resize($result['image'],$image_additional_width, $image_additional_height)
				);
			}



			$data['allow_comment'] = $data['blogpage_comment_allow'] || $blogpost_info['allow_comment'];

			$data['comment_guest'] = ($data['blogpage_comment_allow_guest'] || $this->customer->isLogged());


			if ($this->customer->isLogged()) {
				$data['customer_name'] = $this->customer->getFirstName() . '&nbsp;' . $this->customer->getLastName();
				$data['customer_email'] = $this->customer->getEmail();
			} else {
				$data['customer_name'] = '';
				$data['customer_email'] = '';
			}

			/*rich snippets starts*/
			$data['rich'] = '';
			$cicomment_total = 0;
			$results = array();
			if($data['blogpage_comment_snippet']) {
				$filter_data = array(
	                'ciblog_post_id' => $this->request->get['ciblog_post_id'],
	                'sort' => 'r.date_added',
	                'order' => 'DESC',
	            );
	            $cicomment_total = $this->model_extension_ciblog_cicomment->getTotalCiCommentsByCiBlogPostId($filter_data);
				$results = $this->model_extension_ciblog_cicomment->getCiCommentsByCiBlogPostId($filter_data);
			}
			if($cicomment_total > 0) {
				$rich_snippets = array();
				$rich_snippets["@context"] = "http://schema.org";
				$rich_snippets["@type"] = "Blog";
				$rich_snippets["description"] = str_replace (array('"'), array('&#34;'), html_entity_decode($blogpost_info['description'], ENT_QUOTES, 'UTF-8'));
				$rich_snippets["name"] = $blogpost_info['name'];
				$rich_snippets["image"] = "";
				if(file_exists(DIR_IMAGE . $blogpost_info['image'])) {
					$rich_snippets["image"] = $this->getFrontUrl('image/') . $blogpost_info['image'];
				}
				$rich_snippets["url"] = $this->url->link('extension/ciblog/ciblogpost','ciblog_post_id='. $blogpost_info['ciblog_post_id'], true);
				$rich_snippets["aggregateRating"] = array();
				$rich_snippets["aggregateRating"]["@type"] = "AggregateRating";
				$rich_snippets["aggregateRating"]["ratingValue"] = round($this->model_extension_ciblog_cicomment->getAvgRatingOfCiBlogPosts($blogpost_info['ciblog_post_id']),2);
				$rich_snippets["aggregateRating"]["reviewCount"] = $cicomment_total;
				if($results) {
					$rich_snippets["review"] = array();

					foreach($results as $key => $result) {
						$rich_snippets["review"][$key] = array();
						$rich_snippets["review"][$key]["@type"] = "Review";
						$rich_snippets["review"][$key]["author"] = $result['author'];
						$rich_snippets["review"][$key]["datePublished"] = $result['date_added'];
						$rich_snippets["review"][$key]["reviewBody"] = nl2br($result['text']);
						$rich_snippets["review"][$key]["reviewRating"] = array();
						$rich_snippets["review"][$key]["reviewRating"]["@type"] = "Rating";
						$rich_snippets["review"][$key]["reviewRating"]["bestRating"] = "5";
						$rich_snippets["review"][$key]["reviewRating"]["ratingValue"] = (int)$result['rating'];
						$rich_snippets["review"][$key]["reviewRating"]["worstRating"] = "1";
					}
				}
				/*
				JSON_PRETTY_PRINT
				JSON_UNESCAPED_SLASHES
				*/
				$data['rich'] = json_encode($rich_snippets, JSON_UNESCAPED_SLASHES) ;
			}
			/*rich snippets ends*/

			$data['rating'] = false;
			if((int)$this->config->get('ciblog_store_rating_show')) {
				$data['rating'] = (int)$blogpost_info['rating'];
			}

			// Captcha
			if ($this->config->get($this->config->get('config_captcha') . '_status') && $this->config->get('ciblog_store_blogpage_comment_captcha')) {
				if (VERSION <= '2.2.0.0') {
					$data['captcha'] = $this->load->controller('captcha/' . $this->config->get('config_captcha'));
				} else {
					$data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'));
				}
			} else {
				$data['captcha'] = '';
			}

			$data['share'] = $this->url->link('extension/ciblog/ciblogpost', 'ciblog_post_id=' . (int)$this->request->get['ciblog_post_id']);


			$related_image_listing_width = (int)$this->config->get('ciblog_store_blogrelated_image_listing_width');
			$related_image_listing_height = (int)$this->config->get('ciblog_store_blogrelated_image_listing_height');
			if(!$related_image_listing_width) {
				$related_image_listing_width = 438;
			}
			if(!$related_image_listing_height) {
				$related_image_listing_height = 292;
			}

			$data['blogrelated_row'] = (int)$this->config->get('ciblog_store_blogrelated_row');
			$data['blogrelated_show_title'] = (int)$this->config->get('ciblog_store_blogrelated_show_title');
			$data['blogrelated_show_description'] = (int)$this->config->get('ciblog_store_blogrelated_show_description');
			$data['blogrelated_image_show_listing'] = (int)$this->config->get('ciblog_store_blogrelated_image_show_listing');
			$data['blogrelated_show_date_publish'] = (int)$this->config->get('ciblog_store_blogrelated_show_date_publish');
			$data['blogrelated_show_total_view'] = (int)$this->config->get('ciblog_store_blogrelated_show_total_view');
			$data['blogrelated_show_author'] = (int)$this->config->get('ciblog_store_blogrelated_show_author');
			$data['blogrelated_like_show_total'] = (int)$this->config->get('ciblog_store_blogrelated_like_show_total');
			$data['blogrelated_comment_show_total'] = (int)$this->config->get('ciblog_store_blogrelated_comment_show_total');


			$description_length = (int)$this->config->get('ciblog_store_blogrelated_description_length') ? (int)$this->config->get('ciblog_store_blogrelated_description_length') : 200;
			$date_format = $this->language->get('date_format_short');

			$data['blogposts'] = array();
			$results = $this->model_extension_ciblog_ciblogpost->getCiBlogPostRelated($this->request->get['ciblog_post_id']);

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $related_image_listing_width, $related_image_listing_height);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $related_image_listing_width, $related_image_listing_height);
				}

				$rating = false;
				if((int)$this->config->get('ciblog_store_rating_show')) {
					$rating = (int)$result['rating'];
				}

				$author_info = $this->model_extension_ciblog_ciauthor->getCiAuthor($result['ciblog_author_id']);
				$result['author'] = array();
				$result['author']['name'] = '';
				$result['author']['href'] = '';
				if ($author_info) {
					$result['author']['name'] = htmlentities($author_info['name'], ENT_QUOTES, 'UTF-8');
					$result['author']['href'] = $this->url->link('extension/ciblog/ciauthor/info', 'ciblog_author_id='. $author_info['ciblog_author_id'] . $url, true);
				}

				// heart static
				$isMyHeart = false;
				$isHearted = $this->model_extension_ciblog_ciblogpost->isHearted($result['ciblog_post_id']);
				if($isHearted) {
					$isMyHeart = true;
				}

				$description = strip_tags(html_entity_decode($result['small_description'], ENT_QUOTES, 'UTF-8'));
				if(strlen($description) > $description_length) {
					$description = utf8_substr($description, 0, $description_length) . '..';
				}

				$data['blogposts'][] = array(
					'ciblog_post_id'  => $result['ciblog_post_id'],
					'thumb'       => $image,
					'name'        => htmlentities($result['name'], ENT_QUOTES, 'UTF-8'),
					'image_title'        => $result['image_title'],
					'image_alt'        => $result['image_alt'],
					'author'        => $result['author'],
					'description' => $description,
					'rating'      => $rating,
					'viewed'      => $result['viewed'],
					'heart'      => $result['heart'],
					'isMyHeart'      => $isMyHeart,
					'comments'      => $result['comments'],
					'add_video_url'      => (int)$result['add_video_url'],
					'video_url'      => $this->ciblog->getVideoURLEmbedURL($result['video_url']),
					'image_thumb_width'      => '100%', //$related_image_listing_width
					'image_thumb_height'      =>  $related_image_listing_height.'px',
					'date_added'      => date($date_format, strtotime($result['date_added'])),
					'search_date_added'      => $this->url->link('extension/ciblog/cisearch', 'date='. date('Y-m-d', strtotime($result['date_added'])) . $url, true),
					'href'        => $this->url->link('extension/ciblog/ciblogpost', 'ciblog_post_id=' . $result['ciblog_post_id'] . $url, true)
				);
			}

			$data['tags'] = array();

			if ($blogpost_info['tag']) {
				$tags = explode(',', $blogpost_info['tag']);

				foreach ($tags as $tag) {
					$data['tags'][] = array(
						'tag'  => trim($tag),
						'href' => $this->url->link('extension/ciblog/cisearch', 'tag=' . trim($tag))
					);
				}
			}

			// related products
			$data['text_tax'] = $this->language->get('text_tax');
			$data['button_cart'] = $this->language->get('button_cart');
			$data['button_wishlist'] = $this->language->get('button_wishlist');
			$data['button_compare'] = $this->language->get('button_compare');

			$data['products'] = array();
			$results = $this->model_extension_ciblog_ciblogpost->getProductRelatedToCiBlog($this->request->get['ciblog_post_id']);

			if(VERSION <= '2.3.0.2') {
				$product_related_width = $this->config->get($this->config->get('config_theme') . '_image_related_width');
				$product_related_height = $this->config->get($this->config->get('config_theme') . '_image_related_height');
				$product_description_length = $this->config->get($this->config->get('config_theme') . '_product_description_length');
			} else {
				$product_related_width = $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_width');
				$product_related_height = $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_height');
				$product_description_length = $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length');
			}

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $product_related_width, $product_related_height);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $product_related_width, $product_related_height);
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
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}

				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => htmlentities($result['name'], ENT_QUOTES, 'UTF-8'),
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $product_description_length ) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $rating,
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}

			$this->model_extension_ciblog_ciblogpost->updateViewed($this->request->get['ciblog_post_id']);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->ciblog->view('extension/ciblog/ciblogpost', $data));
		} else {
			$url = '';

			if (isset($this->request->get['ciblogpath'])) {
				$url .= '&ciblogpath=' . $this->request->get['ciblogpath'];
			}

			if (isset($this->request->get['ciblog_author_id'])) {
				$url .= '&ciblog_author_id=' . $this->request->get['ciblog_author_id'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['ciblog_category_id'])) {
				$url .= '&ciblog_category_id=' . $this->request->get['ciblog_category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('extension/ciblog/ciblogpost', $url . '&ciblog_post_id=' . $ciblog_post_id)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->ciblog->view('error/not_found', $data));
		}
	}

	private function getFrontUrl($url='') {
		if ($this->request->server['HTTPS']) {
			return HTTP_SERVER . $url;
		} else {
			return HTTPS_SERVER . $url;
		}
	}

	public function cicomment() {
		$data['text_no_comments'] = $this->language->get('text_no_comments');

		if(isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'r.date_added';
		}
		if(isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = 5;
		if((int)$this->config->get('ciblog_store_blogpage_comment_limit')) {
			$limit = (int)$this->config->get('ciblog_store_blogpage_comment_limit');
		}

		$data['cicomments'] = array();

		$filter_data = array(
			'ciblog_post_id' => $this->request->get['ciblog_post_id'],
			'sort' => $sort,
			'order' => $order,
			'start' => ($page - 1) * $limit,
			'limit' => $limit,
		);

		$cicomment_total = $this->model_extension_ciblog_cicomment->getTotalCiCommentsByCiBlogPostId($filter_data);

		$results = $this->model_extension_ciblog_cicomment->getCiCommentsByCiBlogPostId($filter_data);

		foreach ($results as $result) {
			$data['cicomments'][] = array(
				'author'     => $result['author'],
				'text'       => nl2br($result['text']),
				'rating'     => (int)$result['rating'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$pagination = new Pagination();
		$pagination->total = $cicomment_total;
		$pagination->page = $page;
		$pagination->limit = $limit;

		$pagination->url = $this->url->link('extension/ciblog/ciblogpost/cicomment', 'ciblog_post_id=' . $this->request->get['ciblog_post_id'] . '&page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($cicomment_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($cicomment_total - $limit)) ? $cicomment_total : ((($page - 1) * $limit) + $limit), $cicomment_total, ceil($cicomment_total / $limit));
		$view = $this->ciblog->view('extension/ciblog/cicomment', $data);
		$this->response->setOutput($view);
	}

	public function write() {

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if ((utf8_strlen($this->request->post['cibc_author']) < 3) || (utf8_strlen($this->request->post['cibc_author']) > 25)) {
				$json['error']['cibc_author'] = $this->language->get('error_author');
			}

			if ((utf8_strlen($this->request->post['cibc_text']) < 25) || (utf8_strlen($this->request->post['cibc_text']) > 10000)) {
				$json['error']['cibc_text'] = $this->language->get('error_text');
			}

			if ((utf8_strlen($this->request->post['cibc_email']) > 96) || !filter_var($this->request->post['cibc_email'], FILTER_VALIDATE_EMAIL)) {
				$json['error']['cibc_email'] = $this->language->get('error_email');
			}

			if(isset($this->request->post['cibc_rating'])) {
				if ($this->request->post['cibc_rating'] <= 0 || $this->request->post['cibc_rating'] > 5) {
					$json['error']['cibc_rating'] = $this->language->get('error_rating');
				}
			}
			if(!isset($this->request->post['cibc_rating'])) {
				$json['error']['cibc_rating'] = $this->language->get('error_rating');
			}

			// Captcha
			if ($this->config->get($this->config->get('config_captcha') . '_status') && $this->config->get('ciblog_store_blogpage_comment_captcha')) {
				if (VERSION <= '2.2.0.0') {
					$captcha = $this->load->controller('captcha/' . $this->config->get('config_captcha') . '/validate');
				} else {
					$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');
				}

				if ($captcha) {
					$json['error']['captcha'] = $captcha;

					if (VERSION <= '2.2.0.0') {
						$json['captcha'] = $this->load->controller('captcha/' . $this->config->get('config_captcha'), $json['error']);
					} else {
						$json['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'), $json['error']);
					}

					$json['error']['warning'] = $captcha;
				}
			}

			if (!isset($json['error'])) {
				$ciblog_comment_id = $this->model_extension_ciblog_cicomment->addCiComment($this->request->get['ciblog_post_id'], $this->request->post);

				$json['success']['msg'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function heartgiven() {
		$json = array();

		if (empty($this->request->post['blogid'])) {
			$json['error'] = $this->language->get('error_undefined');
		}

		$can_like = (($this->config->get('ciblog_store_can_like')=='LOGGED' && $this->customer->isLogged()) || ($this->config->get('ciblog_store_can_like')=='BOTH') );

		if (!$can_like) {
			$json['error'] = $this->language->get('error_can_like');
		}

		$ciblog_post_id = $this->request->post['blogid'];

		if(!$json) {
			//first check if customer is logged or not. Then check if already hearted or not
			$results = $this->model_extension_ciblog_ciblogpost->isHearted($ciblog_post_id);
			if(count($results) < 1) {
				$this->model_extension_ciblog_ciblogpost->updateHeart($ciblog_post_id);
			}

			$hearts = $this->model_extension_ciblog_ciblogpost->getCiBlogPostHearts($ciblog_post_id);
			$json['heart'] = $hearts;
			$json['success'] = $this->language->get('text_success_heart');

		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

}
