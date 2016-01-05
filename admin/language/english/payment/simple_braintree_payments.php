<?php
// Heading
$_['heading_title']             = 'Braintree Payments';

// Text 
$_['text_payment']              = 'Payment';
$_['text_success']              = 'Success: You have modified Braintree Payments account details!';
$_['text_simple_braintree_payments'] = '<a href="https://www.braintreepayments.com/" target="_blank"><img src="view/image/payment/braintree-logo.png" alt="Braintree" title="Braintree" style="border: 1px solid #EEEEEE;" /></a>';
$_['text_test']                 = 'Sandbox (Testing)';
$_['text_live']                 = 'Production (Live)';
$_['text_authorize']            = 'Authorize-Only (Manual Settlements)';
$_['text_charge']               = 'Charge All Cards (Auto Settlements)';

// Entry General
$_['entry_status']              = 'Braintree Status:<br /><span class="help">Enable or disable this payment method.</span>';
$_['entry_mode']                = 'Gateway Mode:<br /><span class="help">In order to use sandbox mode you must <a href="https://sandbox.braintreegateway.com/login" target="_blank">create an account</a>. Then you can test with Visa card # 4009-3488-8888-1881</span>';
$_['entry_auth_mode']           = 'Authorization Mode:<br /><span class="help">In authorize-only mode you must manually settle (charge) transactions in Braintree. In automatic mode Braintree will charge cards to settle transactions for you.</span>';
$_['entry_order_status']        = 'Order Status:<br /><span class="help">OpenCart orders will change to this status upon successful transaction and order creation.</span>';
$_['entry_merchant']            = 'Merchant ID:<br /><span class="help">The primary merchant ID for your Braintree Gateway.</span>';
$_['entry_public']              = 'Public Key:<br /><span class="help">The public key used for API access. Found in Braintree user settings.</span>';
$_['entry_key']                 = 'Private Key:<br /><span class="help">The private key used for API access. Do not share this key! Found in Braintreeuser settings.</span>';
$_['entry_cse']                 = 'CSE Key:';
$_['entry_accnt_id']            = 'Merchant Account:<br /><span class="help">The optional merchant sub-account label to add to transactions. Found in Braintree processing settings.</span>';

// Entry Extras
$_['entry_mod_name']            = 'Payment Method Name:<br /><span class="help">Overwrites the default Braintree payment method name.</span>';
$_['entry_mod_msg']             = 'Payment Method Message:<br /><span class="help">Include an optional payment message on the form in checkout.</span>';
$_['entry_mod_msg_class']       = 'Payment Method Message Class:<br /><span class="help">Comma separated classes, used to style the payment method message. The default class is "success".</span>';
$_['entry_cust_prefix']         = 'Customer Number Prefix:<br /><span class="help">Optional identifier to add to customer id&#39;s to prevent cross platform collisions. <strong>Warning: changing this setting may cause duplicate customers.</strong></span>';
$_['entry_order_prefix']        = 'Order Number Prefix:<br /><span class="help">Optional identifier to add to order id&#39;s to prevent cross platform collisions.</span>';
$_['entry_geo_zone']            = 'Geo Zone:<br /><span class="help">Optional restriction based on shipping zones.</span>';
$_['entry_total']               = 'Total:<br /><span class="help">The checkout total the order must reach before this payment method becomes active.</span>';
$_['entry_sort_order']          = 'Sort Order:';

// Error 
$_['error_permission']          = 'Warning: You do not have permission to modify payment: Braintree Payments!';
$_['error_public']              = 'Public Key Required!';
$_['error_key']                 = 'Secret Key Required!';
$_['error_merchant']            = 'Merchant ID Required!';
$_['error_cse']                 = 'CSE Key Required!';
?>
