<?PHP 
  $authChk = true;
  require('app-lib.php');
  build_header($displayName);
  lpa_log("User $displayName access to checkout page");
?>
<div class="row w-100">
  <?PHP build_navBlock(); ?>
  <div id="content"  class=" content col">
    <div class="sectionHeader"><h3>Checkout</h3></div>
<?PHP
      $cart = isset($_COOKIE['cart']) && $_COOKIE['cart'] != null && $_COOKIE['cart'] != '' ? json_decode($_COOKIE['cart'], true) : [];
      $toFind = implode(",", array_keys($cart));
      $itemNum = 1;
      openDB();
      $query = "SELECT * FROM lpa_stock " .
        "WHERE lpa_stock_ID IN ($toFind) " .
        "AND lpa_stock_status = 'a' " .
        "ORDER BY lpa_stock_name ASC";
      $result = $db->query($query);
      ?>
      <table class="table">
        <thead>
          <th>ID</th>
          <th>Image</th>
          <th>Name</th>
          <th>Price</th>
          <th>Qty</th>
          <th>Total</th>
          <th>Remove</th>
        </thead>
        <tbody>
      <?php
      while ($result && $row = $result->fetch_assoc()) {
        if ($row['lpa_image']) {
          $prodImage = $row['lpa_image'];
        } else {
          $prodImage = "question.png";
        }
        $prodID = $row['lpa_stock_ID'];
        ?>
        <tr>
          <td><?PHP echo $row['lpa_stock_ID']; ?></td>
          <td><img src="images/<?PHP echo $prodImage; ?>" alt=""></td>
          <td><?PHP echo $row['lpa_stock_name']; ?></td>
          <td><?PHP echo $row['lpa_stock_price']; ?></td>
          <td>
            <input
            class="form-control"
            name="fldQTY-<?PHP echo $prodID; ?>"
            id="fldQTY-<?PHP echo $prodID; ?>"
            type="number"
            onchange="qtyChanged(<?PHP echo $prodID; ?>,<?PHP echo $row['lpa_stock_price']; ?>)"
            value="<?PHP echo $cart[$row['lpa_stock_ID']]; ?>">
          </td>
          <td class="total" id="fldTotal-<?PHP echo $prodID; ?>"><?PHP echo $cart[$row['lpa_stock_ID']] * $row['lpa_stock_price'] ; ?></td>
          <td>
            <button
            class="btn btn-primary"
            type="button"
            onclick="removeFromCart('<?PHP echo $prodID; ?>','checkout.php')">
            Remove
            </button>
          </td>
        </tr>
      <?PHP } ?>
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="text-right"><b>Total:</b></td>
            <td id="tableTotal"></td>
            <td></td>
          </tr>
        </tbody>
      </table>
      <div class="row justify-content-end pr-4">
        <button
          class="btn btn-success btn-lg"
          type="button"
          onclick="loadURL('checkoutPayment.php')">
          Confirm
        </button>
      </div>
    </div>
    <script>
      getTotal();
      function getTotal(){
        var total = 0
        $(".total").each((value,element)=>{
          total += parseFloat(element.textContent);
        })
        $("#tableTotal").html(total);
        setTotal(total);
      }
      function qtyChanged(id,price){
        let qty = $("#fldQTY-"+id).val();
        if(qty < 0 ){
          $("#fldQTY-"+id).val(0);
          qty = 0;
        }
        $("#fldTotal-"+id).html(price*qty);
        addToCart(id,false);
        getTotal();
      }
    </script>
</div>
<?PHP
  build_footer();
?>
