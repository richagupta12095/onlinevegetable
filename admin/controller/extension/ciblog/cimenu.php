<?php
class ControllerExtensionCiBlogCiMenu extends Controller {

	public function __construct($registry) {
		parent :: __construct($registry);
		$this->ciblog->buildTable();
	}

	public function index() {

		$this->document->addStyle('view/stylesheet/ciblog/ciblog.css');

		$this->load->language('extension/ciblog/cimenu');

		$data['text_cidashboard'] = $this->language->get('text_cidashboard');
		$data['text_ciauthor'] = $this->language->get('text_ciauthor');
		$data['text_cicategory'] = $this->language->get('text_cicategory');
		$data['text_ciblogpost'] = $this->language->get('text_ciblogpost');
		$data['text_cicomment'] = $this->language->get('text_cicomment');
		$data['text_cisubscriber'] = $this->language->get('text_cisubscriber');
		$data['text_cisetting'] = $this->language->get('text_cisetting');

		$data['cidashboard'] = $this->url->link('extension/ciblog/cidashboard', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], $this->ciblog->ocssl);
		$data['ciauthor'] = $this->url->link('extension/ciblog/ciauthor', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], $this->ciblog->ocssl);
		$data['cicategory'] = $this->url->link('extension/ciblog/cicategory', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], $this->ciblog->ocssl);
		$data['ciblogpost'] = $this->url->link('extension/ciblog/ciblogpost', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], $this->ciblog->ocssl);
		$data['cicomment'] = $this->url->link('extension/ciblog/cicomment', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], $this->ciblog->ocssl);
		$data['cisubscriber'] = $this->url->link('extension/ciblog/cisubscriber', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], $this->ciblog->ocssl);
		$data['cisetting'] = $this->url->link('extension/ciblog/cisetting', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], $this->ciblog->ocssl);


		return $this->ciblog->view('extension/ciblog/cimenu', $data);
	}

	public function getTexEditorFiles($data) {
		return $this->ciblog->view('extension/ciblog/citexteditor', $data);
	}

	public function getLeftMenu() {
		$ciblog = array();

		$this->load->language('extension/ciblog/cimenu');

		if ($this->user->hasPermission('access', 'extension/ciblog/cidashboard')) {
			$this->load->language('extension/ciblog/ciblog_menu');
			$ciblog[] = array(
				'name'	   => $this->language->get('text_cidashboard'),
				'href'     => $this->url->link('extension/ciblog/cidashboard', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], $this->ciblog->ocssl),
				'children' => array()
			);
		}
		if ($this->user->hasPermission('access', 'extension/ciblog/ciauthor')) {
			$this->load->language('extension/ciblog/ciblog_menu');
			$ciblog[] = array(
				'name'	   => $this->language->get('text_ciauthor'),
				'href'     => $this->url->link('extension/ciblog/ciauthor', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], $this->ciblog->ocssl),
				'children' => array()
			);
		}
		if ($this->user->hasPermission('access', 'extension/ciblog/cicategory')) {
			$this->load->language('extension/ciblog/ciblog_menu');
			$ciblog[] = array(
				'name'	   => $this->language->get('text_cicategory'),
				'href'     => $this->url->link('extension/ciblog/cicategory', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], $this->ciblog->ocssl),
				'children' => array()
			);
		}
		if ($this->user->hasPermission('access', 'extension/ciblog/ciblogpost')) {
			$this->load->language('extension/ciblog/ciblog_menu');
			$ciblog[] = array(
				'name'	   => $this->language->get('text_ciblogpost'),
				'href'     => $this->url->link('extension/ciblog/ciblogpost', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], $this->ciblog->ocssl),
				'children' => array()
			);
		}
		if ($this->user->hasPermission('access', 'extension/ciblog/cicomment')) {
			$this->load->language('extension/ciblog/ciblog_menu');
			$ciblog[] = array(
				'name'	   => $this->language->get('text_cicomment'),
				'href'     => $this->url->link('extension/ciblog/cicomment', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], $this->ciblog->ocssl),
				'children' => array()
			);
		}
		if ($this->user->hasPermission('access', 'extension/ciblog/cisubscriber')) {
			$this->load->language('extension/ciblog/ciblog_menu');
			$ciblog[] = array(
				'name'	   => $this->language->get('text_cisubscriber'),
				'href'     => $this->url->link('extension/ciblog/cisubscriber', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], $this->ciblog->ocssl),
				'children' => array()
			);
		}
		if ($this->user->hasPermission('access', 'extension/ciblog/cisetting')) {
			$this->load->language('extension/ciblog/ciblog_menu');
			$ciblog[] = array(
				'name'	   => $this->language->get('text_cisetting'),
				'href'     => $this->url->link('extension/ciblog/cisetting', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], $this->ciblog->ocssl),
				'children' => array()
			);
		}

		return $ciblog;
	}
}