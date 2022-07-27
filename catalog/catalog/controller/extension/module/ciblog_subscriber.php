<?php
class ControllerExtensionModuleCiBlogSubscriber extends Controller {
	public function index() {
		static $module = 0;

		$this->load->language('extension/module/ciblog_subscriber');

		$this->document->addScript('extension/module/ciblog_subscriber');

		$data['text_title'] = $this->language->get('text_title');

		$data['entry_email'] = $this->language->get('entry_email');

		$data['button_subscribe'] = $this->language->get('button_subscribe');
		$data['button_unsubscribe'] = $this->language->get('button_unsubscribe');

		$data['email'] = '';//$this->customer->getEmail();
		$data['module'] = $module++;
		return $this->ciblog->view('extension/module/ciblog_subscriber', $data);
	}
}