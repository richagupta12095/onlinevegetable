<?php
class ControllerExtensionModuleCiBlogCategory extends Controller {
	public function index() {
		static $module = 0;
		$this->load->language('extension/module/ciblog_category');

		$data['text_title'] = $this->language->get('text_title');

		if (isset($this->request->get['ciblogpath'])) {
			$parts = explode('_', (string)$this->request->get['ciblogpath']);
		} else {
			$parts = array();
		}

		if (isset($parts[0])) {
			$data['ciblog_category_id'] = $parts[0];
		} else {
			$data['ciblog_category_id'] = 0;
		}

		if (isset($parts[1])) {
			$data['child_id'] = $parts[1];
		} else {
			$data['child_id'] = 0;
		}

		$this->load->model('extension/ciblog/cicategory');

		$this->load->model('extension/ciblog/ciblogpost');

		$data['categories'] = array();

		$categories = $this->model_extension_ciblog_cicategory->getCiCategories(0);

		foreach ($categories as $ciblog_category) {
			$children_data = array();

			if ($ciblog_category['ciblog_category_id'] == $data['ciblog_category_id']) {
				$children = $this->model_extension_ciblog_cicategory->getCiCategories($ciblog_category['ciblog_category_id']);

				foreach($children as $child) {
					$filter_data = array('filter_ciblog_category_id' => $child['ciblog_category_id'], 'filter_sub_ciblog_category' => true);

					$children_data[] = array(
						'ciblog_category_id' => $child['ciblog_category_id'],
						'name' => $child['name'] . ($this->config->get('ciblog_store_post_count') ? ' (' . $this->model_extension_ciblog_ciblogpost->getTotalCiBlogPosts($filter_data) . ')' : ''),
						'href' => $this->url->link('extension/ciblog/cicategory', 'ciblogpath=' . $ciblog_category['ciblog_category_id'] . '_' . $child['ciblog_category_id'])
					);
				}
			}

			$filter_data = array(
				'filter_ciblog_category_id'  => $ciblog_category['ciblog_category_id'],
				'filter_sub_ciblog_category' => true
			);

			$data['categories'][] = array(
				'ciblog_category_id' => $ciblog_category['ciblog_category_id'],
				'name'        => $ciblog_category['name'] . ($this->config->get('ciblog_store_post_count') ? ' (' . $this->model_extension_ciblog_ciblogpost->getTotalCiBlogPosts($filter_data) . ')' : ''),
				'children'    => $children_data,
				'href'        => $this->url->link('extension/ciblog/cicategory', 'ciblogpath=' . $ciblog_category['ciblog_category_id'])
			);
		}
		if($data['categories']) {
			$data['module'] = $module++;
			return $this->ciblog->view('extension/module/ciblog_category', $data);
		}
	}
}