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
                <?php (new Session())->renderMessage(); ?>

                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <div class="white-box">
                            <h3 class="box-title m-b-0"><?php echo $SUBTITLE; ?></h3>
<?php if (isset($Errors)) { ?>
                            <p class="text-danger m-b-30 font-13">
<?php     foreach($Errors as $error) { ?>
                            <?php echo $error; ?><br/>
<?php     } ?>
                            </p>
<?php } ?>

                            <p class="text-muted m-b-30 font-13"> <?php echo $SUBTITLE; ?>
                            <a href="/Meals/index">&laquo; Return to meals</a>
                            </p>
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <form method="post" action="/Meal/store">
                                      <!--DATE-->
                                        <div class="form-group">
                                            <label for="inputDate">Date</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-font"></i></div>
                                                <input type="text" class="form-control" id="inputDate" placeholder="Date of Meal" name="name" value="<?php echo (new Session())->getOldInput('date') ?>"> </div>
                                        </div>

                                      <!--RECIPE-->
                                        <div class="form-group">
                                          <h3 class="box-title m-b-0">Recipe</h3>
                                            <label for="inputRecipe">Recipe</label>
                                            <select class="form-control" id="inputRecipe" name="recipeid">
                                                <option value="0">Select one</option>
                                                <?php
                                                    foreach($data['recipes'] as $recipe){
                                                        echo '<option ';

                                                        if((new Session())->getOldInput('recipeid') == $recipe['id']){
                                                            echo 'selected="selected" ';
                                                        }

                                                        echo 'value="'.$recipe['id'].'">'.$recipe['name'].'</option>';
                                                    }
                                                ?>
                                            </select>

                                            <!--SCALE-->
                                            <!--<div class="form-group">-->
                                                <label for="inputScale">Scale</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon"><i class="fa fa-font"></i></div>
                                                    <input type="number" step="0.01" min="1" class="form-control" id="inputScale" placeholder="" name="scale" value="<?php echo (new Session())->getOldInput('scale'); ?>">
                                                </div>
                                                <p class="help-block"></p>
                                            <!--</div>-->

                                        <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Save</button>
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
