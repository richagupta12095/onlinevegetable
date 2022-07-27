<?php
class ControllerExtensionModuleCiBlogRelatedToProduct extends Controller {
	public function index($setting) {
		//  this module is only works at product page
		if($this->request->get['route']!='product/product' || empty($this->request->get['product_id'])) {
			return;
		}
		static $module = 0;
		$this->load->language('extension/module/ciblog_relatedtoproduct');
		$this->load->language('extension/ciblog/ciblog_common');
		$this->load->model('extension/ciblog/ciblogpost');
		$this->load->model('extension/ciblog/ciauthor');
		$this->load->model('tool/image');

		$this->document->addStyle('catalog/view/theme/default/stylesheet/ciblog.css');
		$this->document->addScript('catalog/view/javascript/ciblog/ciblog.js');


		$data['product_id'] = $this->request->get['product_id'];

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


		if(empty($setting['limit'])) {
			$setting['limit'] = 5;
		}

		if(empty($setting['width'])) {
			$setting['width'] = 438;
		}
		if(empty($setting['height'])) {
			$setting['height'] = 292;
		}


		$url = '';

		$data['blogposts'] = array();

		$results = $this->model_extension_ciblog_ciblogpost->getCiBlogRelatedToProduct($this->request->get['product_id']);

		if ($results) {
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
					'image_thumb_width'      => '100%', //$setting['width']
					'image_thumb_height'      =>  $setting['height'].'px',
					'date_added'      => date($date_format, strtotime($result['date_added'])),
					'search_date_added'      => $this->url->link('extension/ciblog/cisearch', 'date='. date('Y-m-d', strtotime($result['date_added'])) . $url, true),
					'href'        => $this->url->link('extension/ciblog/ciblogpost', 'ciblog_post_id=' . $result['ciblog_post_id'] . $url, true)
				);
			}

			$data['few_width'] = '67';
			$data['few_height'] = '45';

			$data['view'] = 'more';
			if(isset($setting['position']) && in_array($setting['position'], array('column_left','column_right'))) {
				$data['view'] = 'few';
			}
			if($data['blogposts']) {
				$data['module'] = $module++;
				return $this->ciblog->view('extension/module/ciblog_relatedtoproduct', $data);
			}
		}
	}
}