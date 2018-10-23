<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                             Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// Login
///////////////////////////////////////////////////////////////////////////////
require_once __DIR__.'/../../../vendor/autoload.php';
require_once(__DIR__ . '/../modules/main.mod.php' );
use Base\Helpers\Session;

// Sub Title
$SUBTITLE = 'Register';

// Plugins
$PLUGIN_SLIMSCROLL = FALSE;
$PLUGIN_WAVES      = FALSE;
$PLUGIN_SIDEBARMENU= FALSE;
?>
<?php require_once( __HEADER__ ); ?>
<?php
  //foreach($data as $d){
  //  echo $d.'<br>';
  //}
 ?>
<body class="mini-sidebar">
    <!-- Preloader -->
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>
    <section id="wrapper" >
        <div class="login-box">
            <div class="white-box">
                <form class="form-horizontal form-material" id="registerform" action="/Account/register/" method="POST">
                    <h3 class="box-title m-b-20">Register</h3>
                    <?php (new Session())->renderMessage(); ?>
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input class="form-control" type="text" name="reg_username" required="" placeholder="Username">
                            <input class="form-control" type="password" name="reg_password" required="" placeholder="Password">
                            <input class="form-control" type="password" name="reg_password2" required="" placeholder="Confirm Password">
                            <input class="form-control" type="text" name="reg_namefirst" required="" placeholder="First Name">
                            <input class="form-control" type="text" name="reg_namelast" required="" placeholder="Last Name">
                            <input class="form-control" type="text" name="reg_email" required="" placeholder="Email Address">
                        </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Register</button>
                        </div>
                    </div>
                    <div class="form-group m-b-0">
                        <div class="col-sm-12 text-center">
                            <p>Already have an account? <a href="/" class="text-primary m-l-5"><b>Log In</b></a></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

<?php require_once( __FOOTER__ ); ?>

</body>
</html>
