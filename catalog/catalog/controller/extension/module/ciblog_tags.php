<?php
class ControllerExtensionModuleCiBlogTags extends Controller {
	public function index() {
		static $module = 0;
		$this->load->language('extension/module/ciblog_tags');
		$this->load->model('extension/ciblog/ciblogpost');
		$this->load->model('extension/ciblog/cicategory');

		$data['text_title'] = $this->language->get('text_title');
		$data['text_tags'] = $this->language->get('text_tags');

		if (isset($this->request->get['ciblogpath'])) {
			$parts = explode('_', (string)$this->request->get['ciblogpath']);
			$ciblog_category_id = (int)array_pop($parts);
		} else {
			$ciblog_category_id = '';
		}

		if (isset($this->request->get['ciblog_post_id'])) {
			$ciblog_post_id = $this->request->get['ciblog_post_id'];
		} else {
			$ciblog_post_id = '';
		}

		// category page
		$categor_page = ($ciblog_post_id=='' && $ciblog_category_id!='');

		// post page
		$post_page = (($ciblog_post_id!='' && $ciblog_category_id=='') || ($ciblog_post_id!='' && $ciblog_category_id!='') );

		// other page
		$other_page = ($ciblog_post_id=='' && $ciblog_category_id=='');

		$data['tags'] = array();
		$tags = array();

		if ($categor_page) {
			$cicategory_blogposts = $this->model_extension_ciblog_cicategory->getCiBlogPosts($ciblog_category_id);
			foreach ($cicategory_blogposts as $cicategory_blogpost) {
				$blogpost_info = $this->model_extension_ciblog_ciblogpost->getCiBlogPost($cicategory_blogpost['ciblog_post_id']);
				if($blogpost_info) {
					$tags = array_merge($tags, explode(',', $blogpost_info['tag']));
				}
			}
			if(count($tags) >= 20) {
				$tags = array_rand($tags, 20);
			}
		}
		if ($post_page) {
			$blogpost_info = $this->model_extension_ciblog_ciblogpost->getCiBlogPost($ciblog_post_id);
			if($blogpost_info) {
				$tags = explode(',', $blogpost_info['tag']);
			}
		}
		if ($other_page) {
			$filter_data = array(
				'sort'  => 'p.viewed',
				'order' => 'DESC',
				'start' => 0,
				'limit' => 10
			);
			$results = $this->model_extension_ciblog_ciblogpost->getCiBlogPosts($filter_data);
			foreach ($results as $result) {
				$tags = array_merge($tags, explode(',', $result['tag']));
			}
			if(count($tags) >= 20) {
				$tags = array_rand($tags, 20);
			}
		}

		foreach ($tags as $tag) {
			$data['tags'][] = array(
				'tag'  => trim($tag),
				'href' => $this->url->link('extension/ciblog/cisearch', 'tag=' . trim($tag))
			);
		}
		if($data['tags']) {
			$data['module'] = $module++;
			return $this->ciblog->view('extension/module/ciblog_tags', $data);
		}
	}
}