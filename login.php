<?PHP 
  require('app-lib.php');
  isset($_POST['a'])? $action = $_POST['a'] : $action = "";
  $msg = null;
  if($action == "doLogin") {
    $chkLogin = false;
    isset($_POST['fldUsername'])?
      $uName = $_POST['fldUsername'] : $uName = "";
    isset($_POST['fldPassword'])?
      $uPassword = $_POST['fldPassword'] : $uPassword = "";
    $uPassword = hash("sha512", $uPassword . "kDl*63(7");
    openDB();
    $query =
      "
      SELECT
        lpa_user_ID,
        lpa_user_username,
        lpa_user_password,
		lpa_user_group
      FROM
        lpa_users
      WHERE
        lpa_user_username = '$uName'
      AND
        lpa_user_password = '$uPassword'
      LIMIT 1
      ";
    $result = $db->query($query);
    $row = $result->fetch_assoc();
    if($row['lpa_user_username'] == $uName) {
      if($row['lpa_user_password'] == $uPassword) {
        $_SESSION['authUser'] = $row['lpa_user_ID'];
		    $_SESSION['isAdmin'] = (($row['lpa_user_group']=="administrator")?true:false);
        lpa_log("User $displayName successfully logged in.");
        header("Location: index.php");
        exit;
      }
    }

    if($chkLogin == false) {
      $msg = "Login failed! Please try again.";
      lpa_log("User {$uName} Failed to Log in.");
    }

  }
 build_header();
?>
<div id="contentLogin">
	<form name="frmLogin" id="frmLogin" method="post" action="login.php">
		<div class="card bg-light mb-5" style="max-width: 40rem;">
			<div class="card-header titleBar text-center p-3"><h4>Customer Login</h4></div>
			<div class="card-body">
				<div class="msgTitle">Please supply your login details:</div>
				<div class="form-group">
					<label class="col-form-label" for="inputDefault">Username</label>
					<input type="text" class="form-control" placeholder="Username" id="fldUsername" name="fldUsername" >
				</div>
				<div class="form-group">
					<label class="col-form-label" for="inputDefault">Password</label>
					<input type="password" class="form-control" placeholder="Username" id="fldPassword" name="fldPassword" >
				</div>
				<div class="row justify-content-md-center">
					<button type="button" class="btn btn-primary btn-lg" onclick="do_login()">Login</button>
				</div>        
				<div class="row justify-content-md-center pt-2">
          <button type="button" class="btn btn-default btn-sm" onclick="loadURL('register.php')">Register</button>
				</div>
				<input type="hidden" name="a" value="doLogin">
			</div>
		</div>
	</form>
</div>
<script>
  var msg = "<?PHP echo $msg; ?>";
  if(msg) {
    alert(msg);
  }
  $( "#contentLogin").center().cs_draggable({
      handle : ".titleBar",
      containment : "window"
    });

  $("#frmLogin").keypress(function(e) {
    if(e.which == 13) {
      $(this).submit();
    }
  });

</script>
<?PHP
build_footer();
?>