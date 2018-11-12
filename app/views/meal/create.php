<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                             Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// Create Meal
///////////////////////////////////////////////////////////////////////////////
require_once __DIR__.'/../../../vendor/autoload.php';
require_once( $_SERVER['DOCUMENT_ROOT'] . '/../app/views/modules/main.mod.php' );


use Base\Helpers\Session;

// Plugins
$PLUGIN_SLIMSCROLL  = TRUE;
$PLUGIN_WAVES       = TRUE;
$PLUGIN_SIDEBARMENU = TRUE;


// Sub Title
$SUBTITLE = "Create Meal";

?>
<?php require_once( __HEADER__ ); ?>

<body class="mini-sidebar">

    <!-- ===== Main-Wrapper ===== -->
    <div id="wrapper">
        <div class="preloader">
            <div class="cssload-speeding-wheel"></div>
        </div>

<?php require_once( __NAVBAR__ ); ?>

<?php require_once( __SIDEBAR__ ); ?>

        <!-- ===== Page-Content ===== -->
        <div class="page-wrapper">

            <!-- ===== Page-Container ===== -->
            <div class="container-fluid">
                <?php $data['session']->renderMessage(); ?>

                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <div class="white-box">
                            <h3 class="box-title m-b-0"><?php echo $SUBTITLE; ?></h3>
                            <p class="text-muted m-b-30 font-13"> <?php echo $SUBTITLE; ?>
                            <a href="/Meals/index">&laquo; Return to meals</a>
                            </p>
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <form method="post" action="/Meals/store">
                                        <!--DATE-->
                                        <div class="form-group">
                                            <label for="inputDate">Date</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                                <input type="text" class="form-control mydatepicker complex-colorpicker" placeholder="mm/dd/yyyy" id="inputDate" placeholder="Date of Meal" name="date" value="<?php echo $data['session']->getOldInput('date') ?>">
                                            </div>
                                        </div>

                                        <!--RECIPE-->
                                        <div class="form-group">
                                            <label for="inputRecipe">Recipe</label>
                                            <select class="form-control" id="inputRecipe" name="recipeId">
                                                <option value="0">Select one</option>
                                                <?php
                                                    foreach($data['recipes'] as $recipe){
                                                        echo '<option ';

                                                        if($data['session']->getOldInput('recipeId') == $recipe->getId()){
                                                            echo 'selected="selected" ';
                                                        }

                                                        echo 'value="'.$recipe->getId().'">'.$recipe->getName().'</option>';
                                                    }
                                                ?>
                                            </select>

                                        </div>
                                        <!--SCALE-->
                                        <div class="form-group">
                                            <label for="inputScaleFactor">Scale Factor</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-font"></i></div>
                                                <input type="number" step="0.01" min="1" class="form-control" id="inputScaleFactor" placeholder="" name="scaleFactor" value="<?php echo $data['session']->getOldInput('scaleFactor'); ?>">
                                            </div>
                                            <p class="help-block"></p>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Save</button>
                                        </div>
                                    </form>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

<?php require_once( __SPANEL__ ); ?>

            </div>
            <!-- ===== Page-Container-End ===== -->

            <footer class="footer t-a-c">
                <?php echo __COPYRITE__; ?>
            </footer>

        </div>
        <!-- ===== Page-Content-End ===== -->

    </div>
    <!-- ===== Main-Wrapper-End ===== -->

<?php require_once( __FOOTER__ ); ?>

</body>
</html>
