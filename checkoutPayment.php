<?PHP 
  $authChk = true;
  require('app-lib.php');
  build_header($displayName);
  lpa_log("User $displayName access to checkout payment page");
?>
<div class="row w-100">
  <?PHP build_navBlock(); ?>
  <div id="content"  class=" content col">
    <form name="frmPayment" id="frmPayment" method="post" action="paymentCompleted.php">
    <div class="sectionHeader"><h3>Checkout</h3></div>
<?PHP
      $cart = isset($_COOKIE['cart']) && $_COOKIE['cart'] != null && $_COOKIE['cart'] != '' ? json_decode($_COOKIE['cart'], true) : [];
      $toFind = implode(",", array_keys($cart));
      $itemNum = 1;
      openDB();
      $query = "SELECT * FROM lpa_users WHERE lpa_user_ID = '$authUser' LIMIT 1";
      $result = $db->query($query);
      $row = $result->fetch_assoc();
      $displayName = $row['lpa_user_firstname']." ".$row['lpa_user_lastname'];
      ?>
      <div class="col-md-12 mx-auto">
          
          <div class="form-group row">
            <label for="fldfirstName" class="col-3 col-form-label text-right">First Name:</label>
            <div class="col-6">
              <input required maxlength="50" type="text" class="form-control" id="fldfirstName" name="fldfirstName" placeholder="First Name" value="<?php echo $row['lpa_user_firstname'];?>">
            </div>
          </div>
          <div class="form-group row">
            <label for="fldlastName" class="col-3 col-form-label text-right">Last Name:</label>
            <div class="col-6">
              <input required maxlength="50" type="text" class="form-control" id="fldlastName" name="fldlastName" placeholder="Last Name" value="<?php echo $row['lpa_user_lastname'];?>">
            </div>
          </div>
          <div class="form-group row">
            <label for="fldaddress" class="col-3 col-form-label text-right">Address:</label>
            <div class="col-6">
              <input required maxlength="100" type="text" class="form-control" id="fldaddress" name="fldaddress" placeholder="Address" value="<?php echo $row['lpa_user_address'];?>">
            </div>
          </div>
          <div class="form-group row">
            <label for="fldphone" class="col-3 col-form-label text-right">Phone:</label>
            <div class="col-6">
              <input required maxlength="50" type="text" class="form-control" id="fldphone" name="fldphone" placeholder="Phone" value="<?php echo $row['lpa_user_phone'];?>">
            </div>
          </div>
          <div class="form-group row">
            <label for="fldphone" class="col-3 col-form-label text-right">Payment:</label>
            <div class="col-6">
              <select class="form-control" name="fldPayOpt" id="fldPayOpt">
                <option value="PayPal">PayPal</option>
                <option value="VISA">VISA</option>
                <option value="MasterCard">MasterCard</option>
                <option value="Direct deposit">Direct deposit</option>
              </select>
            </div>
          </div>          
          <div class="form-group row">
            <label for="fldphone" class="col-3 col-form-label text-right"><b>Total:</b></label>
            <div class="col-6 my-auto">
              $ <?php echo $_COOKIE['total'];?>
            </div>
          </div>
        </div>
      <div class="row justify-content-center pr-4">
        <button
          class="btn btn-default btn mr-3"
          type="button"
          onclick="loadURL('checkout.php')">
          Cancel
        </button>
        <button
          class="btn btn-success btn-lg"
          type="submit">
          Pay Now
        </button>
      </div>
    </form>
    </div>
</div>
<?PHP
  build_footer();
?>
