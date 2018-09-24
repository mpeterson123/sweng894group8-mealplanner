<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                             Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// Login
///////////////////////////////////////////////////////////////////////////////
require_once(__DIR__ . '/../modules/main.mod.php' );

// Sub Title
$SUBTITLE = 'Register';

// Plugins
$PLUGIN_SLIMSCROLL = FALSE;
$PLUGIN_WAVES      = FALSE;
$PLUGIN_SIDEBARMENU= FALSE;
?>
<?php require_once( __HEADER__ ); ?>
<?php
if(!empty($data))
  echo $data['message'];
?>
<script type="text/javascript">
  function toggle(id){
    var x = document.getElementById(id);
    if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
  }
</script>

<body class="mini-sidebar">
    <!-- Preloader -->
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>
    <section id="wrapper" >
        <div class="login-box">
            <div class="white-box">
                <form class="form-horizontal form-material" id="registerform" action="/Account/update" method="POST">
                    <h3 class="box-title m-b-20">Account</h3>
                    <!--<div class="form-group ">-->
                    <!--    <div class="col-xs-12">-->
                    <table>
                      <tr><th>Username: &nbsp;&nbsp;</th><td><input type="text" name="username" readonly placeholder="Username" value="<?php echo $data['username']; ?>"></td></tr>
                      <tr><th>Name: </th><td><input type="text" style="width:125px;" name="namefirst" required="" placeholder="First Name" value="<?php echo $data['namefirst']; ?>">
                            <input style="width:125px;" type="text" name="namelast" required="" placeholder="Last Name" value="<?php echo $data['namelast']; ?>"></td></tr>
                      <tr><th>Email: </th><td><input type="text" name="email" placeholder="Email Address" value="<?php echo $data['email']; ?>"></td></tr>
                    </table>
                    <table id="old_pass">
                      <tr><th>Password: &nbsp;&nbsp;&nbsp;</th><td><input type="text" name="ro_password" required="" placeholder="Password" readonly value="********"><a href="javascript:void(0)" onclick="toggle('update_pass');toggle('old_pass');"> Change</a></td></tr>
                    </table>
                    <table id="update_pass" style="display:none">
                      <tr><th>Password: &nbsp;&nbsp;&nbsp;</th><td><input type="text" name="password" placeholder="Password"><a href="javascript:void(0)" onclick="toggle('update_pass');toggle('old_pass');"> Change</a></td></tr>
                      <tr><th>Confirm: </th><td><input type="text" name="password2" placeholder="Password"></td></tr>
                    </table>
                    <!--    </div>-->
                    <!--</div>-->
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Update</button>
                        </div>
                    </div>
                    <div class="form-group m-b-0">
                        <div class="col-sm-12 text-center">
                            <p><a href="/Account/delete/" class="text-primary m-l-5"><b>Delete Account</b></a></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

<?php require_once( __FOOTER__ ); ?>

</body>
</html>
