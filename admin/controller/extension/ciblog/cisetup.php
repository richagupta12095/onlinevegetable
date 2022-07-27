<?php
class ControllerExtensionCiBlogCiSetup extends Controller {

	public function index() {
		$this->load->model('extension/ciblog/cicommon');
		$this->model_extension_ciblog_cicommon->buildTable();
	}
}