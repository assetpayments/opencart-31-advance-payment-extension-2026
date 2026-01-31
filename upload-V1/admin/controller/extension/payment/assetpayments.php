<?php
class ControllerExtensionPaymentAssetPayments extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/assetpayments');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_assetpayments', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['merchant'])) {
			$data['error_merchant'] = $this->error['merchant'];
		} else {
			$data['error_merchant'] = '';
		}

		if (isset($this->error['signature'])) {
			$data['error_signature'] = $this->error['signature'];
		} else {
			$data['error_signature'] = '';
		}

		if (isset($this->error['type'])) {
			$data['error_type'] = $this->error['type'];
		} else {
			$data['error_type'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/assetpayments', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/assetpayments', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

		if (isset($this->request->post['payment_assetpayments_merchant'])) {
			$data['payment_assetpayments_merchant'] = $this->request->post['payment_assetpayments_merchant'];
		} else {
			$data['payment_assetpayments_merchant'] = $this->config->get('payment_assetpayments_merchant');
		}

		if (isset($this->request->post['payment_assetpayments_signature'])) {
			$data['payment_assetpayments_signature'] = $this->request->post['payment_assetpayments_signature'];
		} else {
			$data['payment_assetpayments_signature'] = $this->config->get('payment_assetpayments_signature');
		}

		if (isset($this->request->post['payment_assetpayments_type'])) {
			$data['payment_assetpayments_type'] = $this->request->post['payment_assetpayments_type'];
		} else {
			$data['payment_assetpayments_type'] = $this->config->get('payment_assetpayments_type');
		}

		if (isset($this->request->post['payment_assetpayments_total'])) {
			$data['payment_assetpayments_total'] = $this->request->post['payment_assetpayments_total'];
		} else {
			$data['payment_assetpayments_total'] = $this->config->get('payment_assetpayments_total');
		}

		if (isset($this->request->post['payment_assetpayments_order_status_id'])) {
			$data['payment_assetpayments_order_status_id'] = $this->request->post['payment_assetpayments_order_status_id'];
		} else {
			$data['payment_assetpayments_order_status_id'] = $this->config->get('payment_assetpayments_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_assetpayments_geo_zone_id'])) {
			$data['payment_assetpayments_geo_zone_id'] = $this->request->post['payment_assetpayments_geo_zone_id'];
		} else {
			$data['payment_assetpayments_geo_zone_id'] = $this->config->get('payment_assetpayments_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_assetpayments_status'])) {
			$data['payment_assetpayments_status'] = $this->request->post['payment_assetpayments_status'];
		} else {
			$data['payment_assetpayments_status'] = $this->config->get('payment_assetpayments_status');
		}

		if (isset($this->request->post['payment_assetpayments_sort_order'])) {
			$data['payment_assetpayments_sort_order'] = $this->request->post['payment_assetpayments_sort_order'];
		} else {
			$data['payment_assetpayments_sort_order'] = $this->config->get('payment_assetpayments_sort_order');
		}

		if (isset($this->request->post['payment_assetpayments_processingId'])) {
		    $data['payment_assetpayments_processingId'] = $this->request->post['payment_assetpayments_processingId'];
		} else {
		    $data['payment_assetpayments_processingId'] = $this->config->get('payment_assetpayments_processingId');
		}

		if (isset($this->request->post['payment_assetpayments_skip_checkout'])) {
		    $data['payment_assetpayments_skip_checkout'] = $this->request->post['payment_assetpayments_skip_checkout'];
		} else {
		    $data['payment_assetpayments_skip_checkout'] = $this->config->get('payment_assetpayments_skip_checkout');
		}

		if (isset($this->request->post['payment_assetpayments_advance'])) {
		    $data['payment_assetpayments_advance'] = $this->request->post['payment_assetpayments_advance'];
		} else {
		    $data['payment_assetpayments_advance'] = $this->config->get('payment_assetpayments_advance');
		}

		if (isset($this->request->post['payment_assetpayments_advance_product_title'])) {
		    $data['payment_assetpayments_advance_product_title'] = $this->request->post['payment_assetpayments_advance_product_title'];
		} else {
		    $data['payment_assetpayments_advance_product_title'] = $this->config->get('payment_assetpayments_advance_product_title');
		}

		if (isset($this->request->post['payment_assetpayments_title'])) {
		    $data['payment_assetpayments_title'] = $this->request->post['payment_assetpayments_title'];
		} else {
		    $data['payment_assetpayments_title'] = $this->config->get('payment_assetpayments_title');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/assetpayments', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/assetpayments')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payment_assetpayments_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}

		if (!$this->request->post['payment_assetpayments_signature']) {
			$this->error['signature'] = $this->language->get('error_signature');
		}

		return !$this->error;
	}
}
