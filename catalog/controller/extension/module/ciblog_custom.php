<?php
class ControllerExtensionModuleCiBlogCustom extends Controller {
	public function index($setting) {
		static $module = 0;
		$this->load->language('extension/module/ciblog_custom');
		$this->load->language('extension/ciblog/ciblog_common');
		$this->load->model('extension/ciblog/ciblogpost');
		$this->load->model('extension/ciblog/ciauthor');
		$this->load->model('extension/ciblog/cicategory');
		$this->load->model('tool/image');

		$this->document->addStyle('catalog/view/theme/default/stylesheet/ciblog.css');
		$this->document->addScript('catalog/view/javascript/ciblog/ciblog.js');

		$data['can_like'] = (($this->config->get('ciblog_store_can_like')=='LOGGED' && $this->customer->isLogged()) || ($this->config->get('ciblog_store_can_like')=='BOTH') );

		$data['blog_row'] = 2;
		$data['show_title'] = 1;
		$data['show_description'] = 1;
		$data['image_show_listing'] = 1;
		$data['show_date_publish'] = 1;
		$data['show_total_view'] = 1;
		$data['show_author'] = 1;
		$data['like_show_total'] = 1;
		$data['comment_show_total'] = 1;

		$description_length = 200;
		$date_format = $this->language->get('date_format_short');

		$data['text_title'] = $this->language->get('text_title');
		$data['text_postby'] = $this->language->get('text_postby');
		$data['text_on'] = $this->language->get('text_on');
		$data['text_rating'] = $this->language->get('text_rating');

		$data['button_read_more'] = $this->language->get('button_read_more');

		if(empty((int)$setting['limit'])) {
			$setting['limit'] = 5;
		}


		$data['blogtabs'] = array();
		$data['blogtabs_first_key'] = '';
		$data['blogposts'] = array();
		$data['blogposts']['ciblog_category'] = array();
		$data['blogposts']['ciblog_author'] = array();
		$data['blogposts']['ciblog_custom'] = array();
		$data['blogposts']['ciblog_latest'] = array();
		$data['blogposts']['ciblog_topview'] = array();

		$url = '';

		if(!empty($setting['ciblog_category'])) {
			$data['blogtabs_first_key'] = 'ciblog_category';
			$data['blogtabs'][] = array(
				'id' => 'ciblog_category',
				'name' => $this->language->get('text_ciblog_category'),
				'sort_order' => (int)$setting['ciblog_category_sort_order'],

			);

			$sort = 'p.sort_order';
			$order = 'ASC';
			$page = 1;
			$limit = $setting['limit'];

			foreach ((array)$setting['ciblog_category'] as $ciblog_category_id) {

				$filter_data = array(
					'filter_ciblog_category_id' => $ciblog_category_id,
					'sort'               => $sort,
					'order'              => $order,
					'start'              => ($page - 1) * $limit,
					'limit'              => $limit
				);

				$results = $this->model_extension_ciblog_ciblogpost->getCiBlogPosts($filter_data);

				foreach ($results as $result) {
					if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
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

					$data['blogposts']['ciblog_category'][] = array(
						'ciblog_post_id'  => $result['ciblog_post_id'],
						'linkto'  => 'ciblog_category_'.$ciblog_category_id,
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
						'image_thumb_width'      => '100%', //$setting['width']
						'image_thumb_height'      =>  $setting['height'].'px',
						'date_added'      => date($date_format, strtotime($result['date_added'])),
						'search_date_added'      => $this->url->link('extension/ciblog/cisearch', 'date='. date('Y-m-d', strtotime($result['date_added'])) . $url, true),
						'href'        => $this->url->link('extension/ciblog/ciblogpost', 'ciblog_post_id=' . $result['ciblog_post_id'] . $url, true)
					);
				}
			}

		}
		if(!empty($setting['ciblog_author'])) {
			$data['blogtabs_first_key'] = 'ciblog_author';
			$data['blogtabs'][] = array(
				'id' => 'ciblog_author',
				'name' => $this->language->get('text_ciblog_author'),
				'sort_order' => (int)$setting['ciblog_author_sort_order'],

			);

			$sort = 'p.sort_order';
			$order = 'ASC';
			$page = 1;
			$limit = $setting['limit'];

			foreach ((array)$setting['ciblog_author'] as $ciblog_author_id) {
				$filter_data = array(
					'filter_ciblog_author_id' => $ciblog_author_id,
					'sort'               => $sort,
					'order'              => $order,
					'start'              => ($page - 1) * $limit,
					'limit'              => $limit
				);

				$results = $this->model_extension_ciblog_ciblogpost->getCiBlogPosts($filter_data);

				foreach ($results as $result) {
					if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
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

					$data['blogposts']['ciblog_author'][] = array(
						'ciblog_post_id'  => $result['ciblog_post_id'],
						'linkto'  => 'ciblog_author_'.$ciblog_author_id,
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
						'image_thumb_width'      => '100%', //$setting['width']
						'image_thumb_height'      =>  $setting['height'].'px',
						'date_added'      => date($date_format, strtotime($result['date_added'])),
						'search_date_added'      => $this->url->link('extension/ciblog/cisearch', 'date='. date('Y-m-d', strtotime($result['date_added'])) . $url, true),
						'href'        => $this->url->link('extension/ciblog/ciblogpost', 'ciblog_post_id=' . $result['ciblog_post_id'] . $url, true)
					);
				}
			}
		}

		if(!empty($setting['ciblog_custom'])) {
			$data['blogtabs_first_key'] = 'ciblog_custom';
			$data['blogtabs'][] = array(
				'id' => 'ciblog_custom',
				'name' => $this->language->get('text_ciblog_custom'),
				'sort_order' => (int)$setting['ciblog_custom_sort_order'],

			);

			$sort = 'p.sort_order';
			$order = 'ASC';
			$page = 1;
			$limit = $setting['limit'];

			$ciblog_post_ids = '';
			foreach ((array)$setting['ciblog_custom'] as $ciblog_post_id) {
				$ciblog_post_ids = $ciblog_post_id.',';
			}

			$ciblog_post_ids = substr($ciblog_post_ids, 0,-1);
			if (!empty($ciblog_post_ids)) {

				$filter_data = array(
					'filter_ciblog_post_id' => $ciblog_post_ids,
					'sort'               => $sort,
					'order'              => $order,
					// 'start'              => ($page - 1) * $limit,
					// 'limit'              => $limit
				);

				$results = $this->model_extension_ciblog_ciblogpost->getCiBlogPosts($filter_data);

				foreach ($results as $result) {
					if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
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

					$data['blogposts']['ciblog_custom'][] = array(
						'ciblog_post_id'  => $result['ciblog_post_id'],
						'linkto'  => 'ciblog_post_'.$ciblog_post_id,
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
						'image_thumb_width'      => '100%', //$setting['width']
						'image_thumb_height'      =>  $setting['height'].'px',
						'date_added'      => date($date_format, strtotime($result['date_added'])),
						'search_date_added'      => $this->url->link('extension/ciblog/cisearch', 'date='. date('Y-m-d', strtotime($result['date_added'])) . $url, true),
						'href'        => $this->url->link('extension/ciblog/ciblogpost', 'ciblog_post_id=' . $result['ciblog_post_id'] . $url, true)
					);
				}
			}
		}

		if($setting['ciblog_topview']) {
			$data['blogtabs_first_key'] = 'ciblog_topview';
			$data['blogtabs'][] = array(
				'id' => 'ciblog_topview',
				'name' => $this->language->get('text_ciblog_topview'),
				'sort_order' => (int)$setting['ciblog_topview_sort_order'],
			);


			// Most view Blogs
			$sort = 'p.viewed';
			$order = 'DESC';
			$page = 1;
			$limit = $setting['limit'];

			$filter_data = array(
				'sort'  => $sort,
				'order' => $order,
				'start' => ($page - 1) * $limit,
				'limit' => $limit
			);

			$results = $this->model_extension_ciblog_ciblogpost->getCiBlogPosts($filter_data);

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
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

				$data['blogposts']['ciblog_topview'][] = array(
					'ciblog_post_id'  => $result['ciblog_post_id'],
					'linkto'  => 'ciblog_post_mostview_'.$ciblog_post_id,
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
					'image_thumb_width'      => '100%', //$setting['width']
					'image_thumb_height'      =>  $setting['height'].'px',
					'date_added'      => date($date_format, strtotime($result['date_added'])),
					'search_date_added'      => $this->url->link('extension/ciblog/cisearch', 'date='. date('Y-m-d', strtotime($result['date_added'])) . $url, true),
					'href'        => $this->url->link('extension/ciblog/ciblogpost', 'ciblog_post_id=' . $result['ciblog_post_id'] . $url, true)
				);
			}

		}
		if($setting['ciblog_latest']) {
			$data['blogtabs_first_key'] = 'ciblog_latest';
			$data['blogtabs'][] = array(
				'id' => 'ciblog_latest',
				'name' => $this->language->get('text_ciblog_latest'),
				'sort_order' => (int)$setting['ciblog_latest_sort_order'],

			);

			// Latest Blogs
			$sort = 'p.date_added';
			$order = 'DESC';
			$page = 1;
			$limit = $setting['limit'];
			$filter_data = array(
				'sort'  => $sort,
				'order' => $order,
				'start' => ($page - 1) * $limit,
				'limit' => $limit
			);

			$results = $this->model_extension_ciblog_ciblogpost->getCiBlogPosts($filter_data);

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
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

				$data['blogposts']['ciblog_latest'][] = array(
					'ciblog_post_id'  => $result['ciblog_post_id'],
					'linkto'  => 'ciblog_post_latest_'.$ciblog_post_id,
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
					'image_thumb_width'      => '100%', //$setting['width']
					'image_thumb_height'      =>  $setting['height'].'px',
					'date_added'      => date($date_format, strtotime($result['date_added'])),
					'search_date_added'      => $this->url->link('extension/ciblog/cisearch', 'date='. date('Y-m-d', strtotime($result['date_added'])) . $url, true),
					'href'        => $this->url->link('extension/ciblog/ciblogpost', 'ciblog_post_id=' . $result['ciblog_post_id'] . $url, true)
				);
			}
		}

		if (!empty($data['blogtabs_first_key'])) {

			// sort tabs as per sort order
			if (count($data['blogtabs']) > 1) {
				$sort_array = array();
				foreach ($data['blogtabs'] as $key => $row) {
				    $sort_array[$key] = $row['sort_order'];
				}
				array_multisort($sort_array, SORT_ASC, $data['blogtabs']);
			}

			$data['module'] = $module++;

			$data['few_width'] = '67';
			$data['few_height'] = '45';

			$data['view'] = 'more';
			if(isset($setting['position']) && in_array($setting['position'], array('column_left','column_right'))) {
				$data['view'] = 'few';
			}

			return $this->ciblog->view('extension/module/ciblog_custom', $data);
		}
	}
}
