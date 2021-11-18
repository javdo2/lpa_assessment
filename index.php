<?PHP

  $authChk = true;
  require('app-lib.php');
  build_header($displayName);
  lpa_log("User $displayName access to Index page");
?>
<div class="row w-100">
  <?PHP build_navBlock(); ?>
  <div id="content" class="col">
      Welcome to Logical Peripherals Australia
      <br>
      A company named Logic Peripherals Australia (LPA) has
      decided to invest in a new computer software package to
      manage the sales and stock of their computer peripheral line to
      rollout across their corporate network an internet web site.
      The new system they require will be a customised application
      to manage the following:
      <ul>
        <li>Stock (Computer Peripherals)</li>
        <li>Sales & Invoicing</li>
        <li>eCommerce web site store with payment gateway</li>
      </ul>
      The project will be divided into three sections with the following user interfaces:
      <ul>
        <li>Desktop application
          <br> This application will be used for internal intranet management of the system
          and will only be accessible on the corporate network or via VPN access. This
          interface will have full access to the system core with all features.</li>
        <li>Mobile Application
          <br>
          The mobile application will be used for external management of the system
          and will have limited access to only allow management of the stock, sales
          and invoicing, system administration level will not be available through this
          interface.
        </li>
        <li>
          eCommerce web site store
          <br>
          This is the end point interface for the customer to purchase products
          (computer peripherals) via the internet and will have no access to the system
          manage core.
        </li>
      </ul>
    </div>
  </div>
<script>
</script>
<?PHP
build_footer();
?>