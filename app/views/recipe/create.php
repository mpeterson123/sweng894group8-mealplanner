<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                             Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// Create Food
///////////////////////////////////////////////////////////////////////////////
require_once __DIR__.'/../../../vendor/autoload.php';
require_once( $_SERVER['DOCUMENT_ROOT'] . '/../app/views/modules/main.mod.php' );


use Base\Helpers\Session;

// Plugins
$PLUGIN_SLIMSCROLL  = TRUE;
$PLUGIN_WAVES       = TRUE;
$PLUGIN_SIDEBARMENU = TRUE;


// Sub Title
$SUBTITLE = "Add Recipe";

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
                <?php Session::renderMessage(); ?>

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
                            <a href="/Recipes/">&laquo; Return to recipes</a>
                            </p>
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <form method="post" action="/Recipes/store">
                                        <div class="form-group">
                                            <label for="inputName">Name</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-font"></i></div>
                                                <input type="text" class="form-control" id="inputName" placeholder="Name of Recipe" name="name" value="<?php echo Session::getOldInput('name') ?>"> </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputDescription">Description</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i clas="fa fa-font"></i></div>
                                                <input type="text" class="form-control" id="inputDescription" placeholder="Description" name="description" value-"<?php echo Session::getOldInput('description') ?>"></div>

                                        <div class="form-group">
                                            <label for="inputServings">Servings</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-font"></i></div>
                                                <input type="number" step="0.01" min="1" class="form-control" id="inputServings" placeholder="" name="servings" value="<?php echo Session::getOldInput('servings'); ?>">
                                            </div>
                                            <p class="help-block"></p>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputSource">Source</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-font"></i></div>
                                                <input type="text" class="form-control" id="inputSource" placeholder="" name="source" value="<?php echo Session::getOldInput('source'); ?>">
                                            </div>
                                            <p class="help-block"></p>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputSource">Notes</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-font"></i></div>
                                                <input type="text" class="form-control" id="inputNotes" placeholder="" name="notes" value="<?php echo Session::getOldInput('notes'); ?>">
                                            </div>
                                            <p class="help-block"></p>
                                        </div>

                                        <div class="form-group">
                                          <h3 class="box-title m-b-0">Ingredients</h3>
                                            <label for="inputFoodItem">FoodItem</label>
                                            <select class="form-control" id="inputFoodItem" name="foodid">
                                                <option value="0">Select one</option>
                                                <?php
                                                    foreach($data['fooditems'] as $fooditem){
                                                        echo '<option ';

                                                        if(Session::getOldInput('foodid') == $fooditem['id']){
                                                            echo 'selected="selected" ';
                                                        }

                                                        echo 'value="'.$fooditem['id'].'">'.$fooditem['name'].'</option>';
                                                    }
                                                ?>
                                            </select>

                                            <!--<div class="form-group">-->
                                                <label for="inputQuantity">Quantity</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon"><i class="fa fa-font"></i></div>
                                                    <input type="number" step="0.01" min="1" class="form-control" id="inputQuantity" placeholder="" name="quantity" value="<?php echo Session::getOldInput('quantity'); ?>">
                                                </div>
                                                <p class="help-block"></p>
                                            <!--</div>-->

                                            <!--<div class="form-group">-->
                                                <label for="inputUnit">Unit</label>
                                                <select class="form-control" id="inputUnit" name="unit_id">
                                                    <option value="0">Select one</option>
                                                    <?php
                                                        foreach($data['units'] as $unit){
                                                            echo '<option ';

                                                            if(Session::getOldInput('unit_id') == $unit['id']){
                                                                echo 'selected="selected" ';
                                                            }

                                                            echo 'value="'.$unit['id'].'">'.$unit['name'].' – '.$unit['abbreviation'].'</option>';
                                                        }
                                                    ?>
                                                </select>
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
