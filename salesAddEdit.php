<?PHP
  $authChk = true;
  require('app-lib.php');
  isset($_REQUEST['invid'])? $invID = $_REQUEST['invid'] : $invID = "";
  if(!$invID) {
    isset($_POST['invid'])? $invID = $_POST['invid'] : $invID = "";
  }
  isset($_REQUEST['a'])? $action = $_REQUEST['a'] : $action = "";
  if(!$action) {
    isset($_POST['a'])? $action = $_POST['a'] : $action = "";
  }
  isset($_POST['txtSearch'])? $txtSearch = $_POST['txtSearch'] : $txtSearch = "";
  if(!$txtSearch) {
    isset($_REQUEST['txtSearch'])? $txtSearch = $_REQUEST['txtSearch'] : $txtSearch = "";
  }
  if($action == "delRec") {
    $query =
    "UPDATE lpa_invoices SET
         lpa_inv_status = 'D'
       WHERE
         lpa_inv_no = '$invID' LIMIT 1
      ";
    openDB();
    $result = $db->query($query);
    if($db->error) {
      $error = $db->error;
      lpa_log("User $displayName error deleting invoice $error");
      printf("Errormessage: %s\n", $db->error);
      exit;
    } else {
      lpa_log("User $displayName invoice deleted $txtSearch");
      header("Location: sales.php?a=recDel&txtSearch=$txtSearch");
      exit;
    }
  }
  
  lpa_log("User $displayName access to edit the sale: $invID");

  isset($_POST['txtInvID'])? $invoiceID = $_POST['txtInvID'] : $invoiceID = null;
  isset($_POST['txtDate'])? $invDate = $_POST['txtDate'] : $invDate = "";
  isset($_POST['txtClientID'])? $invClientID= $_POST['txtClientID'] : $invClientID= "";
  isset($_POST['txtClientName'])? $invClientName = $_POST['txtClientName'] : $invClientName = "";
  isset($_POST['txtClientAddress'])? $invClientAddress = $_POST['txtClientAddress'] : $invClientAddress = "";
  isset($_POST['txtInvAmount'])? $invAmount = $_POST['txtInvAmount'] : $invAmount = "";
  isset($_POST['txtStatus'])? $invStatus = $_POST['txtStatus'] : $invStatus = "";
  $mode = "insertRec";
  if($action == "updateRec") {
    $query =
      "UPDATE lpa_invoices SET
         lpa_inv_date = '$invDate',
         lpa_inv_client_ID = '$invClientID',
         lpa_inv_client_name = '$invClientName',
         lpa_inv_client_address = '$invClientAddress',
         lpa_inv_amount = '$invAmount',
         lpa_inv_status = '$invStatus'
       WHERE
		 	lpa_inv_no = '$invID' LIMIT 1
      ";
     openDB();
     $result = $db->query($query);
     if($db->error) {
        $error = $db->error;
        lpa_log("User $displayName error updating sale $error");
        printf("Here is an error: %s\n", $db->error);
       exit;
     } else {
        lpa_log("User $displayName updated sale: $invID");
         header("Location: sales.php?a=recUpdate&txtSearch=$txtSearch");
       exit;
     }
  }
  if($action == "insertRec") {
	  $dateTime = $invDate." ".date("h:i:s");
    $query =
      "INSERT INTO lpa_invoices (
        lpa_inv_date,
        lpa_inv_client_ID,
        lpa_inv_client_name,
        lpa_inv_client_address,
        lpa_inv_amount,
        lpa_inv_status
       ) VALUES (
         STR_TO_DATE('$dateTime', '%m/%d/%Y %H:%i:%s'),
         '$invClientID',
         '$invClientName',
         '$invClientAddress',
         '$invAmount',
         '$invStatus'
       )
      ";
    openDB();
    $result = $db->query($query);
    if($db->error) {
      $error = $db->error;
      lpa_log("User $displayName error updating sale $error");
      printf("Here is an error 2: %s\n", $db->error);
      exit;
    } else {
      lpa_log("User $displayName sale created");
      header("Location: sales.php?a=recInsert&txtSearch=".$invoiceID);
      exit;
    }
  }

  if($action == "Edit") {
    $query = "SELECT * FROM lpa_invoices WHERE lpa_inv_no = '$invID' LIMIT 1";
    $result = $db->query($query);
    $row_cnt = $result->num_rows;
    $row = $result->fetch_assoc();
    $invoiceID     = $row['lpa_inv_no'];
    $invDate   = $row['lpa_inv_date'];
    $invClientID   = $row['lpa_inv_client_ID'];
    $invClientName = $row['lpa_inv_client_name'];
    $invClientAddress  = $row['lpa_inv_client_address'];
    $invAmount  = $row['lpa_inv_amount'];
    $invStatus = $row['lpa_inv_status'];
    $mode = "updateRec";
  }
  
	build_header($displayName);
  $fieldSpacer = "5px";
?>
<div class="row w-100">
<?php
	build_navBlock();
?>

  <div id="content" class="col">
    <div class="PageTitle">Sales Record Management (<?PHP echo $action; ?>)</div>
    <form name="frmInvoiceRec" id="frmInvoiceRec" method="post" action="<?PHP echo $_SERVER['PHP_SELF']; ?>">
    	<?php if(isset($invoiceID)){ ?>
			<div>
			<label class="col-form-label" for="txtInvoiceID">Invoice Code</label>   
			<input required class="form-control" name="txtInvID" id="txtInvID" placeholder="Invoice ID" value="<?PHP echo $invoiceID; ?>" style="width: 100px;" >
			</div>
		<?php	 }?>  
      <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
      <label class="col-form-label" for="txtDate">Invoice Date</label>   
		<?php if($action == "Add"){ ?>  
			<input required class="form-control" type="text" readonly name="txtDate" id="txtDate" placeholder="Invoice Date" style="width: 200px;"  >
		<?php	 } else { ?>
			<input required class="form-control" type="datetime" name="txtDate" id="txtDate" placeholder="Invoice Date" value="<?PHP echo $invDate; ?>" style="width: 200px;"  >
		<?php	 }?>    
      </div>
      <div style="margin-top: <?PHP echo $fieldSpacer; ?>" >
			<label class="col-form-label" for="txtClientID">Client ID</label>   
			<div class="row p-3">
				<input required class="form-control col-md-3" maxLength="20" name="txtClientID" id="txtClientID" placeholder="Client ID" value="<?PHP echo $invClientID; ?>"  >
				<input required class="form-control col-md-1 btn btn-secondary" type="button" name="lookup1" id="lookup1" value="Search...">
			</div>
		</div>
      <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
      <label class="col-form-label" for="txtClientName">Client Name</label>   
		<input required class="form-control" name="txtClientName" id="txtClientName" placeholder="Client Name" value="<?PHP echo $invClientName; ?>" style="width: 400px;text-align: right"  >
      </div>
      <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
      <label class="col-form-label" for="txtClientAddress">Client Adress</label>   
		<input required class="form-control" name="txtClientAddress" id="txtClientAddress" placeholder="Client Adress" value="<?PHP echo $invClientAddress; ?>" style="width: 400px;text-align: right"  >
      </div>
		<div style="margin-top: <?PHP echo $fieldSpacer; ?>">
      <label class="col-form-label" for="txtInvAmount">Invoice Amount</label>   
		<input required class="form-control" name="txtInvAmount" id="txtInvAmount" placeholder="Invoice Amount" value="<?PHP echo $invAmount; ?>" style="width: 150px;text-align: right"  >
      </div>
      <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
        <div>Invoice Status:</div>
        <input  name="txtStatus" id="txtInvoiceStatusPaid" type="radio" value="P">
          <label for="txtInvoiceStatusPaid">Paid</label>
        <input  name="txtStatus" id="txtInvoiceStatusUnpaid" type="radio" value="U">
          <label for="txtInvoiceStatusUnpaid">Unpaid</label>
      </div>
      <input class="form-control" name="a" id="a" value="<?PHP echo $mode; ?>" type="hidden">
      <input class="form-control" name="invid" id="invid" value="<?PHP echo $invID; ?>" type="hidden">
      <input class="form-control" name="txtSearch" id="txtSearch" value="<?PHP echo $txtSearch; ?>" type="hidden">
    </form>
    <div class="optBar">
      <button type="button" class="btn btn-primary"id="btnInvoiceSave">Save</button>
      <button type="button" class="btn btn-secondary"onclick="navMan('sales.php')">Close</button>
      <?PHP if($action == "Edit") { ?>
      <button type="button" class="btn btn-danger"onclick="delRec('<?PHP echo $invID; ?>')" style="color: darkred; margin-left: 20px">DELETE</button>
      <?PHP } ?>
    </div>
  </div>
</div>
  <script>
    $(document).ready(function(){
		$('#txtDate').datepicker();
		$('#lookup1').lookupbox({
			title: 'Search Client',
			url: 'searchClient.php?chars=',
			imgLoader: 'Loading...',
			width: 600,
			onItemSelected: function(data){
				$('#txtClientID').val(data.lpa_client_ID);
				$('#txtClientName').val(data.lpa_client_name);
				$('#txtClientAddress').val(data.lpa_client_address);
			}
		});
	 });
	 var invoiceRecStatus = "<?PHP echo $invStatus; ?>";
	if (invoiceRecStatus == "P")
		$('#txtInvoiceStatusUnpaid').prop('checked', true);
	else
		$('#txtInvoiceStatusPaid').prop('checked', true);

	$('#btnInvoiceSave').on('click', function(e){
		$('#frmInvoiceRec').submit();
	});
	function delRec(ID){
		navMan("salesAddEdit.php?invid="+ ID + "&a=delRec");
	}
	setTimeout(function(){
		$('#txtDate').focus();
	});

  </script>
<?PHP
build_footer();
?>