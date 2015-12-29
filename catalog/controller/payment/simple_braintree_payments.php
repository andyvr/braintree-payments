<?php
class ControllerPaymentSimpleBraintreePayments extends Controller {
	protected function index() {
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		//Load Braintree Library
		require_once('./vendor/braintree/braintree_php/lib/Braintree.php');
		Braintree_Configuration::environment($this->config->get('simple_braintree_payments_mode'));
		Braintree_Configuration::merchantId($this->config->get('simple_braintree_payments_merchant'));
		Braintree_Configuration::publicKey($this->config->get('simple_braintree_payments_public_key'));
		Braintree_Configuration::privateKey($this->config->get('simple_braintree_payments_private_key'));
		$this->data['clientToken'] = Braintree_ClientToken::generate();
		
		$this->language->load('payment/simple_braintree_payments');

		$this->data['text_credit_card'] = $this->language->get('text_credit_card');
		$this->data['text_wait'] = $this->language->get('text_wait');

		$this->data['entry_cc_owner'] = $this->language->get('entry_cc_owner');
		$this->data['entry_cc_number'] = $this->language->get('entry_cc_number');
		$this->data['entry_cc_expire_date'] = $this->language->get('entry_cc_expire_date');
		$this->data['entry_cc_cvv2'] = $this->language->get('entry_cc_cvv2');

		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');
		
		$this->data['simple_braintree_payments_public_key'] = $this->config->get('simple_braintree_payments_public_key');
		$this->data['simple_braintree_payments_cse'] = $this->config->get('simple_braintree_payments_cse');

		$this->data['months'] = array();

		for ($i = 1; $i <= 12; $i++) {
			$this->data['months'][] = array(
				'text'  => strftime('%B', mktime(0, 0, 0, $i, 1, 2000)), 
				'value' => sprintf('%02d', $i)
			);
		}

		$today = getdate();

		$this->data['year_expire'] = array();

		for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
			$this->data['year_expire'][] = array(
				'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
				'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)) 
			);
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/simple_braintree_payments.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/simple_braintree_payments.tpl';
		} else {
			$this->template = 'default/template/payment/simple_braintree_payments.tpl';
		}	

		$this->render();		
	}

	public function send() {
		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		$amount = $this->currency->format($order_info['total'], $order_info['currency_code'], 1.00000, false);
		
		//Load Braintree Library
		require_once('./vendor/braintree/braintree_php/lib/Braintree.php');
		Braintree_Configuration::environment($this->config->get('simple_braintree_payments_mode'));
		Braintree_Configuration::merchantId($this->config->get('simple_braintree_payments_merchant'));
		Braintree_Configuration::publicKey($this->config->get('simple_braintree_payments_public_key'));
		Braintree_Configuration::privateKey($this->config->get('simple_braintree_payments_private_key'));
		
		// Payment nonce received from the client js side
		$nonce = $_POST["payment_method_nonce"];
		//create object to use as json
		$json = array();
		$result = null;
		try {
			// Perform the transaction
			$result = Braintree_Transaction::sale(array(
				'amount' => $amount,
				'paymentMethodNonce' => $nonce,
				// Optional
				'orderId' => $this->session->data['order_id']
			));
		} catch (Exception $e) {
			$json['phperror'] = $e->getMessage();
		}
		$json['details'] = $result;
		
		if ($result->success) {
			$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('simple_braintree_payments_order_status_id'));
			$json['success'] = $this->url->link('checkout/success', '', 'SSL');
		} else {
			$json['error'] = $result->_attributes['message'];
		}
		
		$this->response->setOutput(json_encode($json));
	}
	private static function prepstr($object, $trim = false, $chars = 255) {
		if (!$trim && isset($object) && !empty($object)) {
			return $object;
		} elseif ($trim && is_int($chars) && isset($object) && !empty($object)) {
			return substr($object, 0, $chars);
		} else {
			return NULL;
		}
	}
}
?>
