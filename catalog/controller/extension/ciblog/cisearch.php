<?php
class ControllerExtensionCiBlogCiSearch extends Controller {
	public function index() {
		$this->load->language('extension/ciblog/cisearch');
		$this->load->language('extension/ciblog/ciblog_common');

		$this->load->model('extension/ciblog/cicategory');
		$this->load->model('extension/ciblog/ciauthor');
		$this->load->model('extension/ciblog/ciblogpost');
		$this->load->model('tool/image');

		$this->document->addStyle('catalog/view/theme/default/stylesheet/ciblog.css');
		$this->document->addScript('catalog/view/javascript/ciblog/ciblog.js');

		$data['can_like'] = (($this->config->get('ciblog_store_can_like')=='LOGGED' && $this->customer->isLogged()) || ($this->config->get('ciblog_store_can_like')=='BOTH') );

		$data['blog_row'] = (int)$this->config->get('ciblog_store_blogsearch_row');
		$data['show_title'] = (int)$this->config->get('ciblog_store_blogsearch_show_title');
		$data['show_description'] = (int)$this->config->get('ciblog_store_blogsearch_show_description');
		$data['image_show_listing'] = (int)$this->config->get('ciblog_store_blogsearch_image_show_listing');
		$data['show_date_publish'] = (int)$this->config->get('ciblog_store_blogsearch_show_date_publish');
		$data['show_total_view'] = (int)$this->config->get('ciblog_store_blogsearch_show_total_view');
		$data['show_author'] = (int)$this->config->get('ciblog_store_blogsearch_show_author');
		$data['like_show_total'] = (int)$this->config->get('ciblog_store_blogsearch_like_show_total');
		$data['comment_show_total'] = (int)$this->config->get('ciblog_store_blogsearch_comment_show_total');

		$description_length = (int)$this->config->get('ciblog_store_blogsearch_description_length') ? (int)$this->config->get('ciblog_store_blogsearch_description_length') : 200;
		$date_format = $this->language->get('date_format_short');


		$image_listing_width = (int)$this->config->get('ciblog_store_blogsearch_image_listing_width');
		$image_listing_height = (int)$this->config->get('ciblog_store_blogsearch_image_listing_height');
		if(!$image_listing_width) {
			$image_listing_width = 438;
		}
		if(!$image_listing_height) {
			$image_listing_height = 292;
		}

		if (isset($this->request->get['ciblog_search'])) {
			$search = $this->request->get['ciblog_search'];
		} else {
			$search = '';
		}

		if (isset($this->request->get['date'])) {
			$date = $this->request->get['date'];
		} else {
			$date = '';
		}

		if (isset($this->request->get['tag'])) {
			$tag = $this->request->get['tag'];
		} elseif (isset($this->request->get['ciblog_search'])) {
			$tag = $this->request->get['ciblog_search'];
		} else {
			$tag = '';
		}

		if (isset($this->request->get['description'])) {
			$description = $this->request->get['description'];
		} else {
			$description = '';
		}

		if (isset($this->request->get['ciblog_category_id'])) {
			$ciblog_category_id = $this->request->get['ciblog_category_id'];
		} else {
			$ciblog_category_id = 0;
		}

		if (isset($this->request->get['sub_category'])) {
			$sub_category = $this->request->get['sub_category'];
		} else {
			$sub_category = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = (int)$this->config->get('ciblog_store_blogsearch_limit') ? (int)$this->config->get('ciblog_store_blogsearch_limit') : $this->config->get($this->config->get('config_theme') . '_product_limit');
		}

		if (isset($this->request->get['ciblog_search'])) {
			$this->document->setTitle($this->language->get('heading_title') .  ' - ' . $this->request->get['ciblog_search']);
		} elseif (isset($this->request->get['tag'])) {
			$this->document->setTitle($this->language->get('heading_title') .  ' - ' . $this->language->get('heading_tag') . $this->request->get['tag']);
		} else {
			$this->document->setTitle($this->language->get('heading_title'));
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$url = '';

		if (isset($this->request->get['ciblog_search'])) {
			$url .= '&search=' . urlencode(html_entity_decode($this->request->get['ciblog_search'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['date'])) {
			$url .= '&date=' . urlencode(html_entity_decode($this->request->get['date'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['tag'])) {
			$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
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
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/ciblog/cisearch', $url)
		);

		if (isset($this->request->get['ciblog_search'])) {
			$data['heading_title'] = $this->language->get('heading_title') .  ' - ' . $this->request->get['ciblog_search'];
		} else {
			$data['heading_title'] = $this->language->get('heading_title');
		}

		$data['text_empty'] = $this->language->get('text_empty');
		$data['text_search'] = $this->language->get('text_search');
		$data['text_keyword'] = $this->language->get('text_keyword');
		$data['text_category'] = $this->language->get('text_category');
		$data['text_sub_category'] = $this->language->get('text_sub_category');
		$data['text_postby'] = $this->language->get('text_postby');
		$data['text_on'] = $this->language->get('text_on');
		$data['text_rating'] = $this->language->get('text_rating');

		$data['entry_search'] = $this->language->get('entry_search');
		$data['entry_description'] = $this->language->get('entry_description');

		$data['button_search'] = $this->language->get('button_search');
		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_read_more'] = $this->language->get('button_read_more');

		$this->load->model('extension/ciblog/cicategory');

		// 3 Level CiCategory CiSearch
		$data['categories'] = array();

		$categories_1 = $this->model_extension_ciblog_cicategory->getCiCategories(0);

		foreach ($categories_1 as $category_1) {
			$level_2_data = array();

			$categories_2 = $this->model_extension_ciblog_cicategory->getCiCategories($category_1['ciblog_category_id']);

			foreach ($categories_2 as $category_2) {
				$level_3_data = array();

				$categories_3 = $this->model_extension_ciblog_cicategory->getCiCategories($category_2['ciblog_category_id']);

				foreach ($categories_3 as $category_3) {
					$level_3_data[] = array(
						'ciblog_category_id' => $category_3['ciblog_category_id'],
						'name'        => $category_3['name'],
					);
				}

				$level_2_data[] = array(
					'ciblog_category_id' => $category_2['ciblog_category_id'],
					'name'        => $category_2['name'],
					'children'    => $level_3_data
				);
			}

			$data['categories'][] = array(
				'ciblog_category_id' => $category_1['ciblog_category_id'],
				'name'        => $category_1['name'],
				'children'    => $level_2_data
			);
		}

		$data['blogposts'] = array();

		if (isset($this->request->get['ciblog_search']) || isset($this->request->get['tag'])|| isset($this->request->get['date'])) {
			$filter_data = array(
				'filter_name'         => $search,
				'filter_date'         => $date,
				'filter_tag'          => $tag,
				'filter_description'  => $description,
				'filter_ciblog_category_id'  => $ciblog_category_id,
				'filter_sub_category' => $sub_category,
				'sort'                => $sort,
				'order'               => $order,
				'start'               => ($page - 1) * $limit,
				'limit'               => $limit
			);

			$blogpost_total = $this->model_extension_ciblog_ciblogpost->getTotalCiBlogPosts($filter_data);

			$results = $this->model_extension_ciblog_ciblogpost->getCiBlogPosts($filter_data);

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $image_listing_width, $image_listing_height);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $image_listing_width, $image_listing_height);
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
					'image_thumb_width'      => '100%', //$image_listing_width
					'image_thumb_height'      =>  $image_listing_height.'px',
					'date_added'      => date($date_format, strtotime($result['date_added'])),
					'search_date_added'      => $this->url->link('extension/ciblog/cisearch', 'date='. date('Y-m-d', strtotime($result['date_added'])) . $url, true),
					'href'        => $this->url->link('extension/ciblog/ciblogpost', 'ciblog_post_id=' . $result['ciblog_post_id'] . $url, true)
				);
			}


			$url = '';

			if (isset($this->request->get['ciblog_search'])) {
				$url .= '&search=' . urlencode(html_entity_decode($this->request->get['ciblog_search'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['date'])) {
				$url .= '&date=' . urlencode(html_entity_decode($this->request->get['date'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
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

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$pagination = new Pagination();
			$pagination->total = $blogpost_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('extension/ciblog/cisearch', $url . '&page={page}');

			$data['pagination'] = $pagination->render();

			$data['results'] = sprintf($this->language->get('text_pagination'), ($blogpost_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($blogpost_total - $limit)) ? $blogpost_total : ((($page - 1) * $limit) + $limit), $blogpost_total, ceil($blogpost_total / $limit));

			// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
			if ($page == 1) {
			    $this->document->addLink($this->url->link('extension/ciblog/cisearch', '', true), 'canonical');
			} elseif ($page == 2) {
			    $this->document->addLink($this->url->link('extension/ciblog/cisearch', '', true), 'prev');
			} else {
			    $this->document->addLink($this->url->link('extension/ciblog/cisearch', $url . '&page='. ($page - 1), true), 'prev');
			}

			if ($limit && ceil($blogpost_total / $limit) > $page) {
			    $this->document->addLink($this->url->link('extension/ciblog/cisearch', $url . '&page='. ($page + 1), true), 'next');
			}
		}

		$data['search'] = $search;
		$data['date'] = $date;
		$data['description'] = $description;
		$data['ciblog_category_id'] = $ciblog_category_id;
		$data['sub_category'] = $sub_category;

		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['limit'] = $limit;

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->ciblog->view('extension/ciblog/cisearch', $data));
	}
}
