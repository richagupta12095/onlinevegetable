<?php
class ControllerExtensionCiBlogCiDashboard extends Controller {

	public function __construct($registry) {
		parent :: __construct($registry);
		$this->ciblog->buildTable();
	}

	public function index() {
		$this->load->language('extension/ciblog/cidashboard');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/cidashboard', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/ciblog/cidashboard', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], true)
		);

		$data['token'] = $this->session->data[$this->ciblog->octoken];
		$data['var_octoken'] = $this->ciblog->octoken;
		// Total Blogs
		$data['text_total_blogs'] = $this->language->get('text_total_blogs');
		$this->load->model('extension/ciblog/ciblogpost');

		$ciblog_total = $this->model_extension_ciblog_ciblogpost->getTotalCiBlogPosts();

		if ($ciblog_total > 1000000000000) {
			$data['ciblog_total'] = round($ciblog_total / 1000000000000, 1) . 'T';
		} elseif ($ciblog_total > 1000000000) {
			$data['ciblog_total'] = round($ciblog_total / 1000000000, 1) . 'B';
		} elseif ($ciblog_total > 1000000) {
			$data['ciblog_total'] = round($ciblog_total / 1000000, 1) . 'M';
		} elseif ($ciblog_total > 1000) {
			$data['ciblog_total'] = round($ciblog_total / 1000, 1) . 'K';
		} else {
			$data['ciblog_total'] = $ciblog_total;
		}

		$data['ciblog'] = $this->url->link('extension/ciblog/ciblogpost', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], true);

		// Total Categories
		$data['text_total_categories'] = $this->language->get('text_total_categories');

		$this->load->model('extension/ciblog/cicategory');

		$cicategory_total = $this->model_extension_ciblog_cicategory->getTotalCiCategories();

		if ($cicategory_total > 1000000000000) {
			$data['cicategory_total'] = round($cicategory_total / 1000000000000, 1) . 'T';
		} elseif ($cicategory_total > 1000000000) {
			$data['cicategory_total'] = round($cicategory_total / 1000000000, 1) . 'B';
		} elseif ($cicategory_total > 1000000) {
			$data['cicategory_total'] = round($cicategory_total / 1000000, 1) . 'M';
		} elseif ($cicategory_total > 1000) {
			$data['cicategory_total'] = round($cicategory_total / 1000, 1) . 'K';
		} else {
			$data['cicategory_total'] = $cicategory_total;
		}

		$data['cicategory'] = $this->url->link('extension/ciblog/cicategory', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], true);

		// Total Comments
		$data['text_total_comments'] = $this->language->get('text_total_comments');

		$this->load->model('extension/ciblog/cicomment');

		$cicomment_total = $this->model_extension_ciblog_cicomment->getTotalCiComments();

		if ($cicomment_total > 1000000000000) {
			$data['cicomment_total'] = round($cicomment_total / 1000000000000, 1) . 'T';
		} elseif ($cicomment_total > 1000000000) {
			$data['cicomment_total'] = round($cicomment_total / 1000000000, 1) . 'B';
		} elseif ($cicomment_total > 1000000) {
			$data['cicomment_total'] = round($cicomment_total / 1000000, 1) . 'M';
		} elseif ($cicomment_total > 1000) {
			$data['cicomment_total'] = round($cicomment_total / 1000, 1) . 'K';
		} else {
			$data['cicomment_total'] = $cicomment_total;
		}

		$data['cicomment'] = $this->url->link('extension/ciblog/cicomment', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], true);

		// Total Subscribers
		$data['text_total_subscribers'] = $this->language->get('text_total_subscribers');

		$this->load->model('extension/ciblog/cisubscriber');

		$cisubscriber_total = $this->model_extension_ciblog_cisubscriber->getTotalCiSubscribers();

		if ($cisubscriber_total > 1000000000000) {
			$data['cisubscriber_total'] = round($cisubscriber_total / 1000000000000, 1) . 'T';
		} elseif ($cisubscriber_total > 1000000000) {
			$data['cisubscriber_total'] = round($cisubscriber_total / 1000000000, 1) . 'B';
		} elseif ($cisubscriber_total > 1000000) {
			$data['cisubscriber_total'] = round($cisubscriber_total / 1000000, 1) . 'M';
		} elseif ($cisubscriber_total > 1000) {
			$data['cisubscriber_total'] = round($cisubscriber_total / 1000, 1) . 'K';
		} else {
			$data['cisubscriber_total'] = $cisubscriber_total;
		}

		$data['cisubscriber'] = $this->url->link('extension/ciblog/cisubscriber', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken], true);

		// Recent Top 5 Comment
		$data['text_recent_comments'] = $this->language->get('text_recent_comments');
		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['column_number'] = $this->language->get('column_number');
		$data['column_blog'] = $this->language->get('column_blog');
		$data['column_author'] = $this->language->get('column_author');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_view'] = $this->language->get('button_view');

		$this->load->model('extension/ciblog/cicomment');

		$filter_recent_comments = array(
			'sort' => 'r.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => 5
		);
		$cicomments = $this->model_extension_ciblog_cicomment->getCiComments($filter_recent_comments);

		$data['cicomments'] = array();

		$date_format = $this->language->get('date_format_short');

		foreach ($cicomments as $cicomment) {
			$data['cicomments'][] = array(
				'ciblog_comment_id' => $cicomment['ciblog_comment_id'],
				'ciblog_post' => $cicomment['ciblog_post'],
				'ciblog_post_href' => $this->url->link('extension/ciblog/ciblogpost/edit', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken].'&ciblog_post_id='.$cicomment['ciblog_post_id'], true),
				'author' => $cicomment['author'],
				'date_added' => $cicomment['date_added'] != '0000-00-00 00:00:00' ? date($date_format, strtotime($cicomment['date_added'])) : '',
				'status' => $cicomment['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'view' => $this->url->link('extension/ciblog/cicomment/edit', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken].'&ciblog_comment_id='.$cicomment['ciblog_comment_id'], true)
			);
		}

		// Recent Top 5 Blog
		$data['text_recent_blogs'] = $this->language->get('text_recent_blogs');
		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['column_number'] = $this->language->get('column_number');
		$data['column_blog'] = $this->language->get('column_blog');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_view'] = $this->language->get('button_view');

		$this->load->model('extension/ciblog/ciblogpost');

		$filter_recent_ciblogposts = array(
			'sort' => 'p.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => 5
		);
		$ciblogposts = $this->model_extension_ciblog_ciblogpost->getCiBlogPosts($filter_recent_ciblogposts);

		$data['ciblogposts'] = array();

		$date_format = $this->language->get('date_format_short');

		foreach ($ciblogposts as $cipost) {

			$data['ciblogposts'][] = array(
				'ciblog_post_id' => $cipost['ciblog_post_id'],
				'name' => $cipost['name'],
				'date_added' => $cipost['date_added'] != '0000-00-00 00:00:00' ? date($date_format, strtotime($cipost['date_added'])) : '',
				'status' => $cipost['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'view' => $this->url->link('extension/ciblog/ciblogpost/edit', $this->ciblog->octoken.'=' . $this->session->data[$this->ciblog->octoken].'&ciblog_post_id='.$cipost['ciblog_post_id'], true)
			);
		}


		$data['cimenu'] = $this->load->controller('extension/ciblog/cimenu');
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');


		$this->response->setOutput($this->ciblog->view('extension/ciblog/cidashboard', $data));
	}
}