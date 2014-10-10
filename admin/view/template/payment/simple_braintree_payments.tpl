<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
    <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_merchant; ?></td>
          <td><input type="text" name="simple_braintree_payments_merchant" value="<?php echo $simple_braintree_payments_merchant; ?>" />
            <?php if ($error_merchant) { ?>
            <span class="error"><?php echo $error_merchant; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_public; ?></td>
          <td><input type="text" name="simple_braintree_payments_public_key" value="<?php echo $simple_braintree_payments_public_key; ?>" />
            <?php if ($error_public) { ?>
            <span class="error"><?php echo $error_public; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_key; ?></td>
          <td><input size="40" type="text" name="simple_braintree_payments_private_key" value="<?php echo $simple_braintree_payments_private_key; ?>" />
            <?php if ($error_key) { ?>
            <span class="error"><?php echo $error_key; ?></span>
            <?php } ?></td>
        </tr>
        <input type="hidden" name="simple_braintree_payments_cse" value="" />
        <!--<tr>
          <td><span class="required">*</span> <?php echo $entry_cse; ?></td>
          <td><textarea cols="43" rows="6" name="simple_braintree_payments_cse"><?php echo $simple_braintree_payments_cse; ?></textarea>
            <?php if ($error_cse) { ?>
            <span class="error"><?php echo $error_cse; ?></span>
            <?php } ?></td>
        </tr>-->
        <tr>
          <td><?php echo $entry_mode; ?></td>
          <td><select name="simple_braintree_payments_mode">
              <?php if ($simple_braintree_payments_mode == 'sandbox') { ?>
              <option value="sandbox" selected="selected"><?php echo $text_test; ?></option>
              <?php } else { ?>
              <option value="sandbox"><?php echo $text_test; ?></option>
              <?php } ?>
              <?php if ($simple_braintree_payments_mode == 'production') { ?>
              <option value="production" selected="selected"><?php echo $text_live; ?></option>
              <?php } else { ?>
              <option value="production"><?php echo $text_live; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <!--<tr>
          <td><?php echo $entry_method; ?></td>
          <td><select name="simple_braintree_payments_method">
              <option value="charge" selected="selected"><?php echo $text_charge; ?></option>
            </select></td>
        </tr>-->
        <tr>
          <td><?php echo $entry_order_status; ?></td>
          <td><select name="simple_braintree_payments_order_status_id">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $simple_braintree_payments_order_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_geo_zone; ?></td>
          <td><select name="simple_braintree_payments_geo_zone_id">
              <option value="0"><?php echo $text_all_zones; ?></option>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php if ($geo_zone['geo_zone_id'] == $simple_braintree_payments_geo_zone_id) { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="simple_braintree_payments_status">
              <?php if ($simple_braintree_payments_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
            <td><?php echo $entry_total; ?></td>
            <td><input type="text" name="simple_braintree_payments_total" value="<?php echo $simple_braintree_payments_total; ?>" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_sort_order; ?></td>
          <td><input type="text" name="simple_braintree_payments_sort_order" value="<?php echo $simple_braintree_payments_sort_order; ?>" size="1" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php echo $footer; ?>