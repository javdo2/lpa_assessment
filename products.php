<?PHP 
  $authChk = true;
  require('app-lib.php');
  build_header($displayName);
  lpa_log("User $displayName access to products page");
  isset($_POST['a'])? $action = $_POST['a'] : $action = "";
  isset($_POST['txtSearch'])? $txtSearch = $_POST['txtSearch'] : $txtSearch = "";
  if(!$txtSearch) {
    isset($_REQUEST['txtSearch'])? $txtSearch = $_REQUEST['txtSearch'] : $txtSearch = "";
  }
?>
<div class="row w-100">
  <?PHP build_navBlock(); ?>
  <div id="content"  class=" content col">
    <div class="sectionHeader"><h3>Product List</h3></div>

  <form action="" method="post">
    <div class="setionSearch">
      <div class="row pl-3 pr-3">
        <input type="text" class=" col form-control" placeholder="Search..." id="txtSearch" name="txtSearch"  value="<?PHP echo $txtSearch; ?>" >
        <button type="submit" class="btn btn-secondary" id="btnSearch">Search</button>
        <?PHP if($isAdmin){?>
          <!-- <button type="button"  class="btn btn-primary" onclick="loadURL('reg.php')" id="btnAddRec">Add</button> -->
        <?PHP } ?>
      </div>
    </div>
    <input type="hidden" name="a" value="search">

  </form>

<?PHP
    if($action == "search") {
      isset($_POST['txtSearch'])? $itmSearch = $_POST['txtSearch'] : $itmSearch = "";
      $itemNum = 1;
      openDB();
      $query = "SELECT * FROM lpa_stock " .
        "WHERE lpa_stock_name LIKE '%$itmSearch%' " .
        "AND lpa_stock_status = 'a' " .
        "ORDER BY lpa_stock_name ASC";
      $result = $db->query($query);

      while ($row = $result->fetch_assoc()) {
        if ($row['lpa_image']) {
          $prodImage = $row['lpa_image'];
        } else {
          $prodImage = "question.png";
        }
        $prodID = $row['lpa_stock_ID'];
        ?>
        <div class="productListItem">
          <div class="card mt-2">
            <div class="card-body">
              <div class="row">
                <div class="my-auto p-3">
                  <img src="images/<?PHP echo $prodImage; ?>" alt="">
                </div>
                <div class="col">
                  <div class="card">
                    <div class="card-header">
                      <div class="prodTitle"><?PHP echo $row['lpa_stock_name']; ?></div>
                      <input
                            class="form-control"
                            name="fldName-<?PHP echo $prodID; ?>"
                            id="fldName-<?PHP echo $prodID; ?>"
                            type="text" 
                            hidden
                            value="<?PHP echo $row['lpa_stock_name']; ?>">
                    </div>
                    <div class="card-body">
                      <p class="card-text"><?PHP echo $row['lpa_stock_desc']; ?></p>
                    </div>                    
                    <div class="card-footer pt-1 pb-1">
                      <div class="row">
                        <div class="col-1 p-0 pl-2 my-auto"><b>$<?PHP echo $row['lpa_stock_price']; ?></b></div> 
                        <span class="col-1 my-auto p-0 pr-2 d-flex justify-content-end">QTY:</span>
                        <div class="col-2 p-0">
                          <input
                            class="form-control"
                            name="fldQTY-<?PHP echo $prodID; ?>"
                            id="fldQTY-<?PHP echo $prodID; ?>"
                            type="number"
                            value="1">
                        </div>
                        <div class="col p-0 my-auto d-flex justify-content-end">
                          <button
                          class="btn btn-primary"
                            type="button"
                            onclick="addToCart('<?PHP echo $prodID; ?>')">
                            Add To Cart
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div style="clear: left"></div>
        </div>
      <?PHP } ?>
    </div>
    <?PHP
    } ?>
  
</div>
<?PHP
  build_footer();
?>