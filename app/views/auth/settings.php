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

<body class="mini-sidebar">
    <!-- Preloader -->
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>

    <!-- Confirm account deletion modal -->
    <div class="modal fade" id="confirm-delete-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Account Deletion</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete your account? If no other users belongs to your Household, all date will be deleted. This cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <form class="" action="/Account/delete" method="post">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <section id="wrapper" >
        <div class="login-box">
            <div class="white-box">
                <form class="" id="" action="/Account/update" method="POST">
                    <h3 class="box-title m-b-20">Account</h3>

                    <?php Session::renderMessage(); ?>

                    <div class="form-group">
                        <label for="readonlyUsername">Username</label>
                        <p class="form-control-static"><?php echo $data['user']->getUsername() ?></p>
                    </div>
                    <div class="form-group">
                        <label for="inputFirstName">First Name</label>
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-font"></i></div>
                            <input type="text" required class="form-control" id="inputFirstName" placeholder="" name="firstName" value="<?php echo (Session::getOldInput('firstName') != NULL)? Session::getOldInput('firstName') : $data['user']->getFirstName(); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputLastName">Last Name</label>
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-font"></i></div>
                            <input type="text" required class="form-control" id="inputLastName" name="lastName" value="<?php echo (Session::getOldInput('lastName') != NULL)? Session::getOldInput('lastName') : $data['user']->getLastName(); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail">Email</label>
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-envelope"></i></div>
                            <input type="email" required class="form-control" id="inputEmail" name="email" value="<?php echo (Session::getOldInput('email') != NULL)? Session::getOldInput('email') : $data['user']->getEmail(); ?>">
                        </div>
                    </div>

                    <div class="form-group" id ="passwordPlaceholder">
                        <label for="readonlyPassword">Password</label>
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-lock"></i></div>
                            <input type="password" class="form-control" id="readOnlyPassword" name="password" value="*****" readonly>
                        </div>
                        <a href="#" class="togglePasswordChange pull-right">Change</a><br>
                    </div>

                    <div id="passwordChange">
                        <div class="form-group">
                            <label for="inputPassword">Password</label>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-lock"></i></div>
                                <input type="password" class="form-control" id="inputPassword" name="password">
                            </div>
                            <a href="#" class="togglePasswordChange pull-right">Change</a>
                        </div>

                        <div class="form-group">
                            <label for="inputName">Confirm Password</label>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-lock"></i></div>
                                <input type="password" class="form-control" id="inputConfirmPassword" name="confirmPassword">
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-center m-t-20">
                        <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Update</button>
                    </div>
                    <div class="form-group m-b-0">
                        <p class="text-center">
                            <a href="#" class="text-primary m-l-5" data-toggle="modal"
                        data-target="#confirm-delete-modal">
                                <b>Delete Account</b>
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </section>

<?php require_once( __FOOTER__ ); ?>
<script type="text/javascript">
    $(document).ready(function(){
        $('#passwordChange').hide();

        $('.togglePasswordChange').on('click', function(e){
            e.preventDefault();

            $('#passwordPlaceholder').toggle();
            $('#passwordChange').toggle();
        });
    });
</script>


</body>
</html>
