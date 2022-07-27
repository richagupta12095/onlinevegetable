<?php
class ControllerExtensionModuleCiBlogSearch extends Controller {
	public function index() {
		static $module = 0;
		$this->load->language('extension/module/ciblog_search');

		$data['text_title'] = $this->language->get('text_title');
		$data['text_search'] = $this->language->get('text_search');

		if (isset($this->request->get['ciblog_search'])) {
			$data['ciblog_search'] = $this->request->get['ciblog_search'];
		} else {
			$data['ciblog_search'] = '';
		}

		$data['search_page'] = $this->url->link('extension/ciblog/cisearch','',true);

		$data['module'] = $module++;
		return $this->ciblog->view('extension/module/ciblog_search', $data);
	}
}