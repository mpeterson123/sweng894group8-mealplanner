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
$PLUGIN_DROPIFY= TRUE;
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
                <form class="" id="" action="/Account/changePicture" method="POST" enctype="multipart/form-data">
                    <h3 class="box-title m-b-20">Upload Picture</h3>

                    <?php $data['session']->renderMessage(); ?>

                    <p>Choose a profile picture to upload and click "Upload Image". The image must not be over 5MB in size, and must be of one of the following file types: JPG, JPEG, PNG or GIF.</p>

                    <div class="form-group">
                        <label for="fileToUpload">Picture</label>
                        <input type="file" name="fileToUpload" id="fileToUpload" value="" class="dropify">
                    </div>
                    <div class="form-group">
                        <input class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit" value="Upload Image" name="submit">
                    </div>

                </form>
            </div>
        </div>
    </section>

<?php require_once( __FOOTER__ ); ?>
<script type="text/javascript">
    $(document).ready(function(e){
        $('.dropify').dropify();
    });
</script>

</body>
</html>
