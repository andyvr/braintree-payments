<?php 
class ControllerPaymentSimpleBraintreePayments extends Controller {
	private $error = array(); 

	public function index() {
		$this->language->load('payment/simple_braintree_payments');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('simple_braintree_payments', $this->request->post);				

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_test'] = $this->language->get('text_test');
		$this->data['text_live'] = $this->language->get('text_live');
		$this->data['text_authorize'] = $this->language->get('text_authorize');
		$this->data['text_charge'] = $this->language->get('text_charge');
		$this->data['text_no_offset'] = $this->language->get('text_no_offset');

		$this->data['entry_merchant'] = $this->language->get('entry_merchant');
		$this->data['entry_public'] = $this->language->get('entry_public');
		$this->data['entry_key'] = $this->language->get('entry_key');
		$this->data['entry_cse'] = $this->language->get('entry_cse');
		$this->data['entry_accnt_id'] = $this->language->get('entry_accnt_id');
		$this->data['entry_mode'] = $this->language->get('entry_mode');
		$this->data['entry_total'] = $this->language->get('entry_total');
		$this->data['entry_auth_mode'] = $this->language->get('entry_auth_mode');
		$this->data['entry_cust_prefix'] = $this->language->get('entry_cust_prefix');
		$this->data['entry_order_prefix'] = $this->language->get('entry_order_prefix');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_time_zone'] = $this->language->get('entry_time_zone');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_mod_name'] = $this->language->get('entry_mod_name');
		$this->data['entry_mod_msg'] = $this->language->get('entry_mod_msg');
		$this->data['entry_mod_msg_class'] = $this->language->get('entry_mod_msg_class');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['public'])) {
			$this->data['error_public'] = $this->error['public'];
		} else {
			$this->data['error_public'] = '';
		}

		if (isset($this->error['key'])) {
			$this->data['error_key'] = $this->error['key'];
		} else {
			$this->data['error_key'] = '';
		}
		if (isset($this->error['merchant'])) {
			$this->data['error_merchant'] = $this->error['merchant'];
		} else {
			$this->data['error_merchant'] = '';
		}
		if (isset($this->error['cse'])) {
			$this->data['error_cse'] = $this->error['cse'];
		} else {
			$this->data['error_cse'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'	  => $this->language->get('text_home'),
			'href'	  => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'	  => $this->language->get('text_payment'),
			'href'	  => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'text'	  => $this->language->get('heading_title'),
			'href'	  => $this->url->link('payment/simple_braintree_payments', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['action'] = HTTPS_SERVER . 'index.php?route=payment/simple_braintree_payments&token=' . $this->session->data['token'];

		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'];

		// GENERAL TAB
		if (isset($this->request->post['simple_braintree_payments_status'])) {
			$this->data['simple_braintree_payments_status'] = $this->request->post['simple_braintree_payments_status'];
		} else {
			$this->data['simple_braintree_payments_status'] = $this->config->get('simple_braintree_payments_status');
		}
		
		if (isset($this->request->post['simple_braintree_payments_mode'])) {
			$this->data['simple_braintree_payments_mode'] = $this->request->post['simple_braintree_payments_mode'];
		} else {
			$this->data['simple_braintree_payments_mode'] = $this->config->get('simple_braintree_payments_mode');
		}
		
		if (isset($this->request->post['simple_braintree_payments_auth_mode'])) {
			$this->data['simple_braintree_payments_auth_mode'] = $this->request->post['simple_braintree_payments_auth_mode'];
		} else {
			$this->data['simple_braintree_payments_auth_mode'] = $this->config->get('simple_braintree_payments_auth_mode');
		}
		
		if (isset($this->request->post['simple_braintree_payments_order_status_id'])) {
			$this->data['simple_braintree_payments_order_status_id'] = $this->request->post['simple_braintree_payments_order_status_id'];
		} else {
			$this->data['simple_braintree_payments_order_status_id'] = $this->config->get('simple_braintree_payments_order_status_id'); 
		}

		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['simple_braintree_payments_merchant'])) {
			$this->data['simple_braintree_payments_merchant'] = $this->request->post['simple_braintree_payments_merchant'];
		} else {
			$this->data['simple_braintree_payments_merchant'] = $this->config->get('simple_braintree_payments_merchant');
		}
		
		if (isset($this->request->post['simple_braintree_payments_public_key'])) {
			$this->data['simple_braintree_payments_public_key'] = $this->request->post['simple_braintree_payments_public_key'];
		} else {
			$this->data['simple_braintree_payments_public_key'] = $this->config->get('simple_braintree_payments_public_key');
		}

		if (isset($this->request->post['simple_braintree_payments_private_key'])) {
			$this->data['simple_braintree_payments_private_key'] = $this->request->post['simple_braintree_payments_private_key'];
		} else {
			$this->data['simple_braintree_payments_private_key'] = $this->config->get('simple_braintree_payments_private_key');
		}
		
		// TODO is this unused?
			if (isset($this->request->post['simple_braintree_payments_cse'])) {
				$this->data['simple_braintree_payments_cse'] = $this->request->post['simple_braintree_payments_cse'];
			} else {
				$this->data['simple_braintree_payments_cse'] = $this->config->get('simple_braintree_payments_cse');
			}

		if (isset($this->request->post['simple_braintree_payments_accnt_id'])) {
			$this->data['simple_braintree_payments_accnt_id'] = $this->request->post['simple_braintree_payments_accnt_id'];
		} else {
			$this->data['simple_braintree_payments_accnt_id'] = $this->config->get('simple_braintree_payments_accnt_id');
		}
		
		// EXTRAS TAB
		if (isset($this->request->post['simple_braintree_mod_name'])) {
			$this->data['simple_braintree_mod_name'] = $this->request->post['simple_braintree_mod_name'];
		} else {
			$this->data['simple_braintree_mod_name'] = $this->config->get('simple_braintree_mod_name');
		}
		
		if (isset($this->request->post['simple_braintree_mod_msg'])) {
			$this->data['simple_braintree_mod_msg'] = $this->request->post['simple_braintree_mod_msg'];
		} else {
			$this->data['simple_braintree_mod_msg'] = $this->config->get('simple_braintree_mod_msg');
		}

		if (isset($this->request->post['simple_braintree_mod_msg_class'])) {
			$this->data['simple_braintree_mod_msg_class'] = $this->request->post['simple_braintree_mod_msg_class'];
		} else {
			$this->data['simple_braintree_mod_msg_class'] = $this->config->get('simple_braintree_mod_msg_class');
		}

		if (isset($this->request->post['simple_braintree_payments_cust_prefix'])) {
			$this->data['simple_braintree_payments_cust_prefix'] = $this->request->post['simple_braintree_payments_cust_prefix'];
		} else {
			$this->data['simple_braintree_payments_cust_prefix'] = $this->config->get('simple_braintree_payments_cust_prefix');
		}
		
		if (isset($this->request->post['simple_braintree_payments_order_prefix'])) {
			$this->data['simple_braintree_payments_order_prefix'] = $this->request->post['simple_braintree_payments_order_prefix'];
		} else {
			$this->data['simple_braintree_payments_order_prefix'] = $this->config->get('simple_braintree_payments_order_prefix');
		}

		$this->data['time_zones'] = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
		
		if (isset($this->request->post['simple_braintree_payments_time_zone'])) {
			$this->data['simple_braintree_payments_time_zone'] = $this->request->post['simple_braintree_payments_time_zone'];
		} else {
			$this->data['simple_braintree_payments_time_zone'] = $this->config->get('simple_braintree_payments_time_zone');
		}

		if (isset($this->request->post['simple_braintree_payments_geo_zone_id'])) {
			$this->data['simple_braintree_payments_geo_zone_id'] = $this->request->post['simple_braintree_payments_geo_zone_id'];
		} else {
			$this->data['simple_braintree_payments_geo_zone_id'] = $this->config->get('simple_braintree_payments_geo_zone_id'); 
		}

		$this->load->model('localisation/geo_zone');

		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['simple_braintree_payments_total'])) {
			$this->data['simple_braintree_payments_total'] = $this->request->post['simple_braintree_payments_total'];
		} else {
			$this->data['simple_braintree_payments_total'] = $this->config->get('simple_braintree_payments_total');
		}

		if (isset($this->request->post['simple_braintree_payments_sort_order'])) {
			$this->data['simple_braintree_payments_sort_order'] = $this->request->post['simple_braintree_payments_sort_order'];
		} else {
			$this->data['simple_braintree_payments_sort_order'] = $this->config->get('simple_braintree_payments_sort_order');
		}

		$this->template = 'payment/simple_braintree_payments.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/simple_braintree_payments')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['simple_braintree_payments_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}
		
		if (!$this->request->post['simple_braintree_payments_public_key']) {
			$this->error['public'] = $this->language->get('error_public');
		}

		if (!$this->request->post['simple_braintree_payments_private_key']) {
			$this->error['key'] = $this->language->get('error_key');
		}
		
		if (!$this->request->post['simple_braintree_payments_cse']) {
			$this->error['cse'] = $this->language->get('error_cse');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>
