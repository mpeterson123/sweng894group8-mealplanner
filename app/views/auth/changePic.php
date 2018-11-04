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
$SUBTITLE = 'Change Picture';

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

    <?php require_once( __NAVBAR__ ); ?>

    <?php require_once( __SIDEBAR__ ); ?>

    <section id="wrapper" >
        <div class="login-box">
            <div class="white-box">
                <form class="" id="" action="/Account/changePicture" method="POST">
                    <h3 class="box-title m-b-20">Upload Picture</h3>

                    <?php (new Session())->renderMessage(); ?>

                    <div class="form-group">
                        <input type="file" name="picture" />
                    </div>

                </form>
            </div>
        </div>
    </section>

<?php require_once( __FOOTER__ ); ?>

</body>
</html>
