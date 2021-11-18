<?PHP
  $authChk = true;
  require('app-lib.php');
  isset($_POST['a'])? $action = $_POST['a'] : $action = "";
  if(!$action) {
    isset($_REQUEST['a'])? $action = $_REQUEST['a'] : $action = "";
  }
  isset($_POST['txtSearch'])? $txtSearch = $_POST['txtSearch'] : $txtSearch = "";
  if(!$txtSearch) {
    isset($_REQUEST['txtSearch'])? $txtSearch = $_REQUEST['txtSearch'] : $txtSearch = "";
  }
  build_header($displayName);
  lpa_log("User $displayName access to Sales page.");
?>
<div class="row w-100">
  <?PHP build_navBlock(); ?>

  
  <div id="content" class="col">
  <h3>Sales/invoices Management Search</h3>

  <!-- Search Section Start -->
<form name="frmSearchInvoices" method="post"
	id="frmSearchInvoices"
	action="<?PHP echo $_SERVER['PHP_SELF']; ?>">
	<div class="displayPane">
		<div class="form-group">
			<label class="col-form-label" for="inputDefault">Search</label>
			<div class="row">
					
				<input type="text" class=" col form-control" placeholder="Search Invoices" id="txtSearch" name="txtSearch"  value="<?PHP echo $txtSearch; ?>" >
				<button type="button" class="btn btn-secondary" id="btnSearch">Search</button>
				<?PHP if($isAdmin){?>
					<button type="button"  class="btn btn-primary" id="btnAddRec">Add</button>
				<?PHP
				} ?>
				
			</div>
		</div>
	</div>
	<input type="hidden" name="a" value="listInvoices">
</form>
    <!-- Search Section End -->
    <!-- Search Section List Start -->
    <?PHP
      if($action == "listInvoices") {
    ?>
    <div>
      <table class="table table-bordered">
        <tr style="background: #eeeeee">
			 <td style="width: 80px;border-left: #cccccc solid 1px"><b>Invoice #</b></td>
          <td style="width: 160px;border-left: #cccccc solid 1px"><b>Invoice Date</b></td>
          <td style="border-left: #cccccc solid 1px"><b>Client Name</b></td>
          <td style="width: 150px;text-align: right;border-left: #cccccc solid 1px"><b>Amount</b></td>
        </tr>
    <?PHP
      openDB();
      $query =
        "SELECT
            *
         FROM
            lpa_invoices
         WHERE
            (lpa_inv_no LIKE '%$txtSearch%' 
		OR 
			lpa_inv_client_address LIKE '%$txtSearch%'
		OR 
			lpa_inv_amount LIKE '%$txtSearch%'
		OR 
			lpa_inv_date LIKE '%$txtSearch%'
		OR
            lpa_inv_client_name LIKE '%$txtSearch%') AND lpa_inv_status <> 'D'

         ";
      $result = $db->query($query);
      $row_cnt = $result->num_rows;
      if($row_cnt >= 1) {
		$totalAmount = 0;
        while ($row = $result->fetch_assoc()) {
          $invID = $row['lpa_inv_no'];
          ?>
          <tr class="hl">
			<td 
			<?PHP if($isAdmin){?>
				onclick="loadInvoice(<?PHP echo $invID; ?>,'Edit')"
				 style="cursor: pointer;border-left: #cccccc solid 1px"
			<?PHP } ?>
			>
                <?PHP echo $row['lpa_inv_no']; ?>
            </td>
            <td <?PHP if($isAdmin){?>
				onclick="loadInvoice(<?PHP echo $invID; ?>,'Edit')"
				 style="cursor: pointer;border-left: #cccccc solid 1px"
			<?PHP } ?>
			>
                <?PHP echo $row['lpa_inv_date']; ?>
            </td>
            <td <?PHP if($isAdmin){?>
				onclick="loadInvoice(<?PHP echo $invID; ?>,'Edit')"
				 style="cursor: pointer;border-left: #cccccc solid 1px"
			<?PHP } ?>
			>
                <?PHP echo $row['lpa_inv_client_name']; ?>
            </td>
            <td style="text-align: right;border-left: #cccccc solid 1px">
              <?PHP echo number_format($row['lpa_inv_amount'],2);
			  $totalAmount = $totalAmount + $row['lpa_inv_amount']; ?>
            </td>
          </tr>
        <?PHP }
		?>
		<tr>
			<td style="text-align: right;border: #cccccc solid 1px" colspan="3">
                <b>Total:</b>
            </td>
            <td style="text-align: right;border: #cccccc solid 1px">
              $ <?PHP echo number_format($totalAmount,2); ?>
            </td>
          </tr>
		<?PHP 
      } else { ?>
        <tr>
          <td colspan="4" style="text-align: center">
            No Records Found for: <b><?PHP echo $txtSearch; ?></b>
          </td>
        </tr>
      <?PHP } ?>
      </table>
    </div>
    <?PHP } ?>
    <!-- Search Section List End -->
  </div>
  </div>
  <script>
    var action = "<?PHP echo $action; ?>";
    var search = "<?PHP echo $txtSearch; ?>";
	 switch (action) {
		 case "recUpdate":
			alert("Record Updated!");
     		navMan("sales.php?a=listInvoices&txtSearch=" + search);
			break;
			case "recInsert":
			alert("Record Added!");
     		navMan("sales.php?a=listInvoices&txtSearch=" + search);
			break;
			case "recDel":
			alert("Record Deleted!");
     		navMan("sales.php?a=listInvoices&txtSearch=" + search);
			break;
	 
		 default:
			 break;
	 }
    function loadInvoice(ID,MODE) {
      window.location = "salesAddEdit.php?invid=" +
      ID + "&a=" + MODE + "&txtSearch=" + search;
    }
    $("#btnSearch").click(function() {
      $("#frmSearchInvoices").submit();
    });
    $("#btnAddRec").click(function() {
      loadInvoice("","Add");
    });
    setTimeout(function(){
      $("#txtSearch").select().focus();
    },1);
  </script>
<?PHP
build_footer();
?>