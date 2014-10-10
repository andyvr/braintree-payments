<h2><?php echo $text_credit_card; ?></h2>

<span id="braintree_mesaages" class="payment-errors error"></span>

<form id="payment-form" method="post" action="">
  <div id="braintree"></div>
  <div class="buttons">
    <div class="right">
        <input type="submit" value="<?php echo $button_confirm; ?>" id="button_confirm" class="button" />
    </div>
  </div>
</form>

<script type="text/javascript" src="https://js.braintreegateway.com/v2/braintree.js"></script>
<script type="text/javascript">
  function wait_for_braintree_to_load() {
    if (window.braintree && window.braintree.setup)
        braintree_setup();
    else
        setTimeout(function() { wait_for_braintree_to_load() }, 50);
  }
  function complete_braintree_payment(nonce) {
      $('#payment-form').find('#button_confirm').prop('disabled', true);
      $.ajax({
          url: 'index.php?route=payment/simple_braintree_payments/send',
          type: 'post',
          data: 'payment_method_nonce=' + nonce,
          dataType: 'json',
          complete: function() {
              $('.attention').remove();
          },				
          success: function(json) {
              if (json['error']) {
                  $('#braintree_mesaages').text(json['error']);
                  $('#payment-form').find('#button_confirm').prop('disabled', false);
              }
              if (json['success']) {
                  location = json['success'];
              }
          }
        });
  }
  wait_for_braintree_to_load();

  function braintree_setup() {
    braintree.setup("<?php echo $clientToken; ?>", 'dropin', {
      container: 'braintree',
      paymentMethodNonceReceived: function (event, nonce) {
        //complete payment transaction
        complete_braintree_payment(nonce);
        $('#payment-form').before('<div class="attention"><img src="<?php echo $this->config->get('config_ssl'); ?>catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
      }
    });
  }
    
  jQuery(function($) {
    $('#payment-form').submit(function(e) {
      $('#braintree_mesaages').text('');
    });
  });
</script>