<?php
class ControllerExtensionPaymentAssetPaymentsV2 extends Controller {
	public function index() {
		$data['button_confirm'] = $this->language->get('button_confirm');
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		// 1. Calculate the final amount based on Advance Payment settings
		$total_full_amount = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
		$advance_payment_setting = trim($this->config->get('payment_assetpayments_v2_advance'));
		$final_amount = $total_full_amount;

		if (!empty($advance_payment_setting)) {
			if (strpos($advance_payment_setting, '%') !== false) {
				$percentage = (float)str_replace('%', '', $advance_payment_setting);
				$final_amount = round(($total_full_amount * $percentage) / 100, 2);
			} elseif (is_numeric($advance_payment_setting)) {
				$final_amount = (float)$advance_payment_setting;
			}
		}

		// 2. Prepare Products Array
		$advance_product_title = trim($this->config->get('payment_assetpayments_v2_advance_product_title'));
		$products_data = array();

		if (!empty($advance_product_title)) {
			// Option A: Field is NOT empty - Use single row logic
			$products_data[] = array(
				"ProductId"       => '12345',
				"ProductSku"      => '12345',
				"ProductName"     => $advance_product_title,
				"ProductPrice"    => $final_amount,
				"ProductItemsNum" => 1,
				"ImageUrl"        => null
			);
		} else {
			// Option B: Field is empty - Use standard cart product logic
			$order_total = 0;
			foreach ($this->cart->getProducts() as $product) {
				$products_data[] = array(
					"ProductId"       => $product['model'],
					"ProductName"     => $product['name'],
					"ProductPrice"    => $product['price'],
					"ProductItemsNum" => $product['quantity'],
					"ImageUrl"        => (isset($product['image'])) ? 'http://' . $_SERVER['HTTP_HOST'] . '/image/' . $product['image'] : '',
				);
				$order_total += $product['price'] * $product['quantity'];
			}

			// Add shipping as a product row
			$shipping_cost = $order_info['total'] - $order_total;
			$products_data[] = array(
				"ProductId"       => 'shipp',
				"ProductName"     => $order_info['shipping_method'],
				"ProductPrice"    => $shipping_cost,
				"ImageUrl"        => 'https://assetpayments.com/dist/css/images/delivery.png',
				"ProductItemsNum" => 1,
			);
		}

		// Country ISO fallback
		$country = $order_info['shipping_iso_code_3'];
		if ($country == '') {
			$country = 'UKR';
		}

		$data['action'] = 'https://assetpayments.us/checkout/pay';

		$send_data = array(
			'TemplateId'              => $this->config->get('payment_assetpayments_v2_type'),
			'MerchantInternalOrderId' => $this->session->data['order_id'],
			'StatusURL'               => $this->url->link('extension/payment/assetpayments_v2/callback', '', true),
			'ReturnURL'               => $this->url->link('checkout/success', '', true),
			'FirstName'               => $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'],
			'LastName'                => $order_info['payment_lastname'],
			'Email'                   => $order_info['email'],
			'Phone'                   => $order_info['telephone'],
			'Address'                 => $order_info['payment_address_1'] . ' ' . $order_info['payment_address_2'] . ' ' . $order_info['payment_city'] . ' ' . $order_info['payment_country'] . ' ' . $order_info['payment_postcode'],
			'CountryISO'              => $country,
			'Amount'                  => $final_amount,
			'Currency'                => $order_info['currency_code'],
			'CustomMerchantInfo'      => 'OpenCart: 3',
			'AssetPaymentsKey'        => $this->config->get('payment_assetpayments_v2_merchant'),
			'Products'                => $products_data,
			'ProcessingId'            => $this->config->get('payment_assetpayments_v2_processingId'),
			'SkipCheckout'            => ($this->config->get('payment_assetpayments_v2_skip_checkout') == 1) ? true : false
		);

		$data['xml'] = base64_encode(json_encode($send_data));
		return $this->load->view('extension/payment/assetpayments_v2', $data);
	}

	public function callback() {
		$json = json_decode(file_get_contents('php://input'), true);

		if (!$json) return;

		$key = $this->config->get('payment_assetpayments_v2_merchant');
		$secret = $this->config->get('payment_assetpayments_v2_signature');
		$transactionId = $json['Payment']['TransactionId'];
		$signature = $json['Payment']['Signature'];
		$order_id = $json['Order']['OrderId'];
		$status = $json['Payment']['StatusCode'];

		$requestSign = $key . ':' . $transactionId . ':' . strtoupper($secret);
		$sign = hash_hmac('md5', $requestSign, $secret);

		if ($sign == $signature) {
			$this->load->model('checkout/order');

			if ($status == 1) {
				// Uses the status ID selected in Admin Settings
				$target_status_id = $this->config->get('payment_assetpayments_v2_order_status_id');
				$this->model_checkout_order->addOrderHistory($order_id, $target_status_id, 'AssetPayments TransactionID: ' . $transactionId);
			} elseif ($status == 2) {
				$this->model_checkout_order->addOrderHistory($order_id, 1, 'Payment FAILED TransactionID: ' . $transactionId);
			}
		}
	}
}
