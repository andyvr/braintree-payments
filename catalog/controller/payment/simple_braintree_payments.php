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

		$braintree_mod_name = $this->config->get('simple_braintree_mod_name');
		
		if (!empty($braintree_mod_name)) {
			$this->data['text_credit_card'] = $braintree_mod_name;
		} else {
			$this->data['text_credit_card'] = $this->language->get('text_credit_card');
		}
		
		$this->data['braintree_mod_msg'] = $this->config->get('simple_braintree_mod_msg');
		
		$braintree_mod_msg_class = $this->config->get('simple_braintree_mod_msg_class');
		
		if (!empty($braintree_mod_msg_class)) {
			$this->data['braintree_mod_msg_class'] = $braintree_mod_msg_class;
		} else {
			$this->data['braintree_mod_msg_class'] = 'success';
		}
		
		$this->data['text_wait'] = $this->language->get('text_wait');
		$this->data['text_test_msg'] = $this->language->get('text_test_msg');

		$this->data['entry_cc_owner'] = $this->language->get('entry_cc_owner');
		$this->data['entry_cc_number'] = $this->language->get('entry_cc_number');
		$this->data['entry_cc_expire_date'] = $this->language->get('entry_cc_expire_date');
		$this->data['entry_cc_cvv2'] = $this->language->get('entry_cc_cvv2');

		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');
		
		$this->data['simple_braintree_payments_public_key'] = $this->config->get('simple_braintree_payments_public_key');
		$this->data['simple_braintree_payments_cse'] = $this->config->get('simple_braintree_payments_cse');
		$this->data['simple_braintree_payments_mode'] = $this->config->get('simple_braintree_payments_mode');

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
		$this->load->model('account/customer');

		// Set order id string
		$order_id = $this->session->data['order_id'];
		$order_prefix = $this->config->get('simple_braintree_payments_order_prefix');
		$order_id_full = $order_prefix . $order_id;
		
		// Load order info array
		$order_info = $this->model_checkout_order->getOrder($order_id);
		
		// Set customer id that is attached to the order, note 0 is guest
		// TODO set a generic catchall customer in braintree called guest guest?
		$customer_id = $order_info['customer_id'];
		$customer_prefix = $this->config->get('simple_braintree_payments_customer_prefix');
		$customer_id_full = $customer_prefix . $customer_id;
		
		// Load customer info array
		$order_customer = $this->model_account_customer->getCustomer($customer_id);
		
		$amount = $this->currency->format($order_info['total'], $order_info['currency_code'], 1.00000, false);
		
		// Load Braintree Library
		require_once('./vendor/braintree/braintree_php/lib/Braintree.php');
		Braintree_Configuration::environment($this->config->get('simple_braintree_payments_mode'));
		Braintree_Configuration::merchantId($this->config->get('simple_braintree_payments_merchant'));
		Braintree_Configuration::publicKey($this->config->get('simple_braintree_payments_public_key'));
		Braintree_Configuration::privateKey($this->config->get('simple_braintree_payments_private_key'));
		
		// Payment nonce received from the client js side
		$client_post = $_POST["payment_method_nonce"];
		
		// create object to use as json
		$json = array();
		$result = null;
		try {
		
			/* test with this number
			 * 4009348888881881
			 */
		
			// Build base transaction array
			// OK
			$result = array(
				'amount' => $amount,
				'paymentMethodNonce' => $client_post,
				'merchantAccountId' => $this->prepstr($this->config->get('simple_braintree_payments_accnt_id')),
				'creditCard' => [
					'cardholderName' => strtoupper($this->prepstr($order_info['payment_firstname'], true, 175) . ' ' . $this->prepstr($order_info['payment_lastname'], true, 175))
				],
				'billing' => [
					'firstName' => $this->prepstr($order_info['payment_firstname'], true),
					'firstName' => $this->prepstr($order_info['payment_firstname'], true),
					'lastName' => $this->prepstr($order_info['payment_lastname'], true),
					'company' => $this->prepstr($order_info['payment_company'], true),
					'streetAddress' => $this->prepstr($order_info['payment_address_1'], true),
					'extendedAddress' => $this->prepstr($order_info['payment_address_2'], true),
					'postalCode' => $this->prepstr($order_info['payment_postcode'])
				],
				'shipping' => [
					'firstName' => $this->prepstr($order_info['shipping_firstname'], true),
					'lastName' => $this->prepstr($order_info['shipping_lastname'], true),
					'company' => $this->prepstr($order_info['shipping_company'], true),
					'streetAddress' => $this->prepstr($order_info['shipping_address_1'], true),
					'extendedAddress' => $this->prepstr($order_info['shipping_address_2'], true),
					'postalCode' => $this->prepstr($order_info['shipping_postcode'])
				],
				'options' => [
					'submitForSettlement' => $this->config->get('simple_braintree_payments_auth_mode'),
					'addBillingAddressToPaymentMethod' => true
				],
				'orderId' => $order_id_full
			);

/*
			// Nulled customer arrays
			// OK
			$customer_base = $customer_add = array('customer' => NULL);
			
			if ($customer_id > 0) {
				// Base customer data
				// OK
				$customer_base = array(
					'customer' => [
						'firstName' => $this->prepstr($order_customer['firstname'], true),
						'lastName' => $this->prepstr($order_customer['lastname'], true),
						'phone' => $this->prepstr($order_customer['telephone'], true),
						'fax' => $this->prepstr($order_customer['fax'], true),
						'email' => $this->prepstr($order_customer['email'], true)
					]
				);
				
				// Extra customer identify
				// OK
				$customer_add = array(
					'customer' => [
						'id' => $customer_id_full
					],
					'options' => [
						'storeInVaultOnSuccess' => true
					]
				);
			
				try {
					// check for braintree customer exists in vault
					// OK
					//$customer_exists = Braintree_Customer::find($customer_id_full);
					
					$this->log->write('UPDATING CUSTOMER ID # ' . $customer_id_full);
					
					// update existing customer
					// OK
					//Braintree_Customer::update($customer_exists->id, array_merge($customer_add['customer'], $customer_base['customer']));
					
					//$result = array_merge($result, $customer_base);

				} catch (Exception $e) {
				
					$this->log->write('CREATING CUSTOMER ID # ' . $customer_id_full . ' THEN MERGING ARRAYS INTO SALE');

					// create a new customer before order is sent
					// FIXME not saving payment and has no token yet
					//Braintree_Customer::create(array_merge(array('id' => $customer_id_full), $customer_base['customer']));
					
					//$result = array_merge($result, $customer_base, $customer_add);
				}
			}
*/



			// run the transaction with optional merged extras
			// FIXME to assimilate customer to each order
			// FIXME make customer better linked in from order view
			// FIXME customer vault only shows 1 order
			// TODO make all settings multistore based
			// TODO pull transaction string/token/id back into order
			// TODO Make a button in OC order area to settle transaction
			// TODO better logging and history attachment to order including who hit settle button
			// TODO make order status clause and passback for when authorize goes to settled...possible?
			// TODO when editing a customer, update the vault
			// TODO when editing an order where total changes, update braintree and vice versa, possible?
			// OFF $this->log->write('running transaction');

			$result = Braintree_Transaction::sale($result);

		} catch (Exception $e) {
			$json['phperror'] = $e->getMessage();
		}
		$json['details'] = $result;
		
		if ($result->success) {
			$this->model_checkout_order->confirm($order_id, $this->config->get('simple_braintree_payments_order_status_id'));
			$this->model_checkout_order->update($order_id, $this->config->get('simple_braintree_payments_order_status_id'), $result, false);			
			$json['success'] = $this->url->link('checkout/success', '', 'SSL');
		} else {
			$json['error'] = $result->_attributes['message'];
		}
		
		$this->response->setOutput(json_encode($json));
	}
	
	// create strings if exist, else NULL
	// TODO make html special chars decoded else &amp; shows in braintree
	private function prepstr($object, $trim = false, $chars = 255) {
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
