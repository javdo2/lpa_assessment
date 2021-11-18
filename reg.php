<?PHP 
  require('app-lib.php');
  build_header();
  isset($_REQUEST['action'])? $action = $_REQUEST['action'] : $action = null;

  if($action == "register") {
    isset($_REQUEST['fldfirstName'])? $fldfirstName = $_REQUEST['fldfirstName'] : $fldfirstName = "";
    isset($_REQUEST['fldlastName'])? $fldlastName = $_REQUEST['fldlastName'] : $fldlastName = "";
    isset($_REQUEST['fldaddress'])? $fldaddress = $_REQUEST['fldaddress'] : $fldaddress = "";
    isset($_REQUEST['fldphone'])? $fldphone = $_REQUEST['fldphone'] : $fldphone = "";
    isset($_REQUEST['flduserName'])? $flduserName = $_REQUEST['flduserName'] : $flduserName = "";
    isset($_REQUEST['fldpassword'])? $fldpassword = $_REQUEST['fldpassword'] : $fldpassword = "";
    $encfldpassword = hash("sha512", $fldpassword . "kDl*63(7");
    lpa_log("User access to register page");

    $query =
      "INSERT INTO lpa_users (
        lpa_user_username,
        lpa_user_password,
        lpa_user_firstname,
        lpa_user_lastname,
        lpa_user_phone,
        lpa_user_address,
        lpa_user_group,
        lpa_user_status
       ) VALUES (
        '$flduserName',
        '$encfldpassword',
        '$fldfirstName',
        '$fldlastName',
        '$fldphone',
        '$fldaddress',
        'user',
        '1'
       )
      ";
    openDB();
    $result = $db->query($query);
    if($db->error) {
      $error = $db->error;
      lpa_log("Error creating user: $flduserName");
      printf("Here is an error 2: %s\n", $db->error);
      exit;
    } else {
      lpa_log("User $flduserName created");
      header("Location: login.php");
      exit;
    }
  }
?>
  <div class="card text-center">
    <div class="card-header">
    <h5 class="card-title mb-0">New Customer Registration</h5>
    </div>
    <div class="card-body">
      <form name="frmRegister" id="frmRegister" method="post" action="<?PHP echo $_SERVER['PHP_SELF']; ?>">
        <div class="col-md-5 mx-auto">
          
          <div class="form-group row">
            <label for="fldfirstName" class="col-5 col-form-label text-right">First Name</label>
            <div class="col-6">
              <input required maxlength="50" type="text" class="form-control" id="fldfirstName" name="fldfirstName" placeholder="First Name">
            </div>
          </div>
          <div class="form-group row">
            <label for="fldlastName" class="col-5 col-form-label text-right">Last Name</label>
            <div class="col-6">
              <input required maxlength="50" type="text" class="form-control" id="fldlastName" name="fldlastName" placeholder="Last Name">
            </div>
          </div>
          <div class="form-group row">
            <label for="fldaddress" class="col-5 col-form-label text-right">Address</label>
            <div class="col-6">
              <input required maxlength="100" type="text" class="form-control" id="fldaddress" name="fldaddress" placeholder="Address">
            </div>
          </div>
          <div class="form-group row">
            <label for="fldphone" class="col-5 col-form-label text-right">Phone</label>
            <div class="col-6">
              <input required maxlength="50" type="text" class="form-control" id="fldphone" name="fldphone" placeholder="Phone">
            </div>
          </div>
          <div class="form-group row">
            <label for="flduserName" class="col-5 col-form-label text-right">User Name</label>
            <div class="col-6">
              <input required maxlength="50" type="text" class="form-control" id="flduserName" name="flduserName" placeholder="User Name">
            </div>
          </div>
          <div class="form-group row">
            <label for="fldpassword" class="col-5 col-form-label text-right">Password</label>
            <div class="col-6">
              <input required maxlength="50" type="password" class="form-control" id="fldpassword" name="fldpassword" onchange="validatePassword()" placeholder="Password">
            </div>
          </div>
          <div class="form-group row">
            <label for="fldretypePassword" class="col-5 col-form-label text-right">Confirm Password</label>
            <div class="col-6">
              <input required maxlength="50" type="password" class="form-control" id="fldretypePassword" name="fldretypePassword" onchange="validatePassword()" placeholder="Password">
              <input type="text" hidden id="action" name="action" value="register">
            </div>
          </div>
          <div class="row text-center ">
            <div class="alert alert-dismissible alert-danger col-8 mx-auto p-3" id="errorMsg">
              Your password and confirmation password do not match
            </div>
          </div>
          <div class="form-group row">
            <div class="col-sm-12">
              <button type="submit" class="btn btn-primary" id="btnregister" disabled>Register</button>
              <button type="button" class="btn btn-default" onclick="loadURL('login.php')"> Cancel</button>
            </div>
          </div>
        </div>



      </form>
    </div>
  </div>
  <script>
    $('#errorMsg').hide();
    function validatePassword() {
      if ($("#fldpassword").val() != $("#fldretypePassword").val()) {
        $('#errorMsg').show();
        $('#btnregister').prop("disabled", true);
      }
      else{
        $('#errorMsg').hide();
        $('#btnregister').prop("disabled", false);
      }      
    }
  </script>
<?PHP
build_footer();
?>