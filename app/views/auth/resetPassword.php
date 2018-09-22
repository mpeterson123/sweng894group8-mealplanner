<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                             Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// Login
///////////////////////////////////////////////////////////////////////////////
require_once(__DIR__ . '/../modules/main.mod.php' );

// Sub Title
$SUBTITLE = 'Reset Password';

// Plugins
$PLUGIN_SLIMSCROLL = FALSE;
$PLUGIN_WAVES      = FALSE;
$PLUGIN_SIDEBARMENU= FALSE;
?>
<?php require_once( __HEADER__ ); ?>
<body class="mini-sidebar">
    <!-- Preloader -->
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>
    <section id="wrapper" >
        <div class="login-box">
            <div class="white-box">
                <form class="form-horizontal form-material" id="registerform" action="/Account/ResetPassword/<?php echo $data['email'].'/'.$data['code']; ?>/" method="POST">
                    <h3 class="box-title m-b-20">Reset Password</h3>
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input class="form-control" type="text" name="email" readonly required="" value="<?php echo $data['email']; ?>">
                            <input class="form-control" type="password" name="rst_password" required="" placeholder="Password">
                            <input class="form-control" type="password" name="rst_password2" required="" placeholder="Confirm Password">
                        </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

<?php require_once( __FOOTER__ ); ?>

</body>
</html>
