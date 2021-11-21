<?PHP 
  $authChk = true;
  require('app-lib.php');
  build_header($displayName);
  lpa_log("User $displayName completed a payment");
  isset($_REQUEST['fldfirstName'])? $fldfirstName = $_REQUEST['fldfirstName'] : $fldfirstName = "";
  isset($_REQUEST['fldlastName'])? $fldlastName = $_REQUEST['fldlastName'] : $fldlastName = "";
  isset($_REQUEST['fldaddress'])? $fldaddress = $_REQUEST['fldaddress'] : $fldaddress = "";
  isset($_REQUEST['fldphone'])? $fldphone = $_REQUEST['fldphone'] : $fldphone = "";
  isset($_REQUEST['fldPayOpt'])? $fldPayOpt = $_REQUEST['fldPayOpt'] : $fldPayOpt = "";
  $total = $_COOKIE['total'];
  
    openDB();
    $queryClient = "SELECT * FROM lpa_clients WHERE lpa_client_firstname = '$fldfirstName' AND  lpa_client_lastname = '$fldlastName' LIMIT 1;";
    $resultClient = $db->query($queryClient);
    $row = $resultClient->fetch_assoc();
    if(!$row){
      $queryInsertClient =
      "INSERT INTO lpa_clients (
        lpa_client_firstname,
        lpa_client_lastname,
        lpa_client_address,
        lpa_client_phone,
        lpa_client_status
       ) VALUES (
        '$fldfirstName',
        '$fldlastName',
        '$fldaddress',
        '$fldphone',
        'A'
       )
      ";
      $resultInsertClient = $db->query($queryInsertClient);
      $resultClient = $db->query($queryClient);// get client again after insert to get the ID
      $row = $resultClient->fetch_assoc();
    }
    $clientId = $row['lpa_client_ID'];
    
    $queryInvoice =
      "INSERT INTO lpa_invoices (
        lpa_inv_date,
        lpa_inv_client_ID,
        lpa_inv_client_name,
        lpa_inv_client_address,
        lpa_inv_client_phone,
        lpa_inv_payment,
        lpa_inv_amount,
        lpa_inv_status
       ) VALUES ( 
        NOW(),
        $clientId,
        CONCAT('$fldfirstName',' ','$fldlastName'),
        '$fldaddress',
        '$fldphone',
        '$fldPayOpt',
        '$total',
        'P'
       )
      ";
    $resultInvoice = $db->query($queryInvoice);


    $queryInvoice = "SELECT MAX(lpa_inv_no) as lpa_inv_no FROM lpa_invoices LIMIT 1;";
    $resultInvoice = $db->query($queryInvoice);
    $row = $resultInvoice->fetch_assoc();
    $invoiceId = $row['lpa_inv_no'];
    
    $cart = isset($_COOKIE['cart']) && $_COOKIE['cart'] != null && $_COOKIE['cart'] != '' ? json_decode($_COOKIE['cart'], true) : [];
    foreach ($cart as $idItem => $qty) {
      
      $queryStock = "SELECT * FROM lpa_stock WHERE lpa_stock_ID = $idItem;";
      $resultStock = $db->query($queryStock);
      $row = $resultStock->fetch_assoc();
      $stockName = $row['lpa_stock_name'];
      $stockPrice = $row['lpa_stock_price'];
      $itemAmount = $stockPrice * $qty;
      $queryInvoiceItem =
      "INSERT INTO lpa_invoice_items (
        lpa_invitem_inv_no,
        lpa_invitem_stock_ID,
        lpa_invitem_stock_name,
        lpa_invitem_qty,
        lpa_invitem_stock_price,
        lpa_invitem_stock_amount,
        lpa_inv_status
        ) VALUES ( 
          '$invoiceId',
          '$idItem',
          '$stockName',
          '$qty',
          '$stockPrice',
          '$itemAmount',
          'P'
          )
          ";
      $resultInvoiceItem = $db->query($queryInvoiceItem);
    }   
    
    $msg = "";
    if($db->error) {
      $error = $db->error;
      lpa_log("Error saving payment user: $displayName");
      $msg = "$displayName your payment could not be processed please try again";
      printf("Here is an error 2: %s\n", $db->error);
    } else {
      lpa_log("User $displayName payment successful");
      $msg = "$displayName your payment was successful and the order is now complete";
      setcookie('cart', null);
      setcookie('total', null);
    }
?>
<div class="row w-100">
  <?PHP build_navBlock(); ?>
  <div id="content"  class="content col d-flex">
    <div class="sectionHeader my-auto col-8 mx-auto">
      <h3>
        <?php echo $msg; ?>
      </h3>
      <div class="d-flex justify-content-center pt-3">
        <button type="button" class="btn btn-secondary"onclick="navMan('products.php')">Close</button>
      </div>
    </div>
  </div>
<?PHP
  build_footer();
?>
