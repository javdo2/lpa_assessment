<?PHP 
  $authChk = true;
  require('app-lib.php');
  build_header($displayName);
  lpa_log("User $displayName completed a payment");
  setcookie('cart', null);
  setcookie('total', null);
?>
<div class="row w-100">
  <?PHP build_navBlock(); ?>
  <div id="content"  class="content col d-flex">
    <div class="sectionHeader my-auto col-8 mx-auto">
      <h3>
        <?php echo $displayName; ?> your payment was successful and the order is now complete
      </h3>
  </div>

</div>
<?PHP
  build_footer();
?>
