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
                <?php (new Session())->renderMessage(); ?>

                <div class="row">
                    <div class="col-sm-12">
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
                            <a href="/Recipes/index">&laquo; Return to recipes</a>
                            </p>
                            <div class="row">
                                <div class="col-sm-12">
                                    <form method="post" action="/Recipes/store">
                                        <div class="form-group">
                                            <label for="inputName">Name</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-font"></i></div>
                                                <input type="text" class="form-control" id="inputName" placeholder="Name of Recipe" name="name" value="<?php echo (new Session())->getOldInput('name') ?>"> </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputDescription">Description</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-font"></i></div>
                                                <input type="text" class="form-control" id="inputDescription" placeholder="Description" name="description" value-"<?php echo (new Session())->getOldInput('description') ?>"></div>

                                        <div class="form-group">
                                            <label for="inputServings">Servings</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-font"></i></div>
                                                <input type="number" step="0.01" min="1" class="form-control" id="inputServings" placeholder="" name="servings" value="<?php echo (new Session())->getOldInput('servings'); ?>">
                                            </div>
                                            <p class="help-block"></p>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputSource">Source</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-font"></i></div>
                                                <input type="text" class="form-control" id="inputSource" placeholder="" name="source" value="<?php echo (new Session())->getOldInput('source'); ?>">
                                            </div>
                                            <p class="help-block"></p>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputSource">Notes</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-font"></i></div>
                                                <input type="text" class="form-control" id="inputNotes" placeholder="" name="notes" value="<?php echo (new Session())->getOldInput('notes'); ?>">
                                            </div>
                                            <p class="help-block"></p>
                                        </div>

                                    <hr>
                                        <label for="ingredientsWrapper">Ingredients</label>
                                        <div id="ingredientsWrapper">

                                        <div class="form-group ingredientFormGroup">

                                          <div class="col-sm-3">
                                                    <input class="form-control" type="number" step="0.05" min="0" placeholder="" name="quantity[]" value="<?php echo (new Session())->getOldInput('quantity'); ?>">
                                          </div>

                                          <div class="col-sm-4">
                                                <select class="form-control" name="unit_id[]">
                                                    <option value="0">Select a unit</option>
                                                    <?php
                                                        foreach($data['units'] as $unit){
                                                            echo '<option ';

                                                            if((new Session())->getOldInput('unit_id') == $unit->getId()){
                                                                echo 'selected="selected" ';
                                                            }

                                                            echo 'value="'.$unit->getId().'">'.$unit->getName().' – '.$unit->getAbbreviation().'</option>';
                                                        }
                                                    ?>
                                                </select>
                                          </div>

                                          <div class="col-sm-4">
                                            <select class="form-control" name="foodid[]">
                                                <option value="0">Select a food item</option>
                                                <?php
                                                    foreach($data['fooditems'] as $fooditem){
                                                        echo '<option ';

                                                        if((new Session())->getOldInput('foodid') == $fooditem->getId()){
                                                            echo 'selected="selected" ';
                                                        }

                                                        echo 'value="'.$fooditem->getId().'">'.$fooditem->getName().'</option>';
                                                    }
                                                ?>
                                            </select>
                                          </div> <!-- div class col-xs-5 -->

                                          <div class="col-sm-1">
                                            <button class="btn-sm btn-danger btn removeIngredientBtn"><i class="fa fa-times"></i>
                                            </button>
                                          </div>

                                        </div> <!-- end ingredientFormGroup -->
                                      </div> <!-- end ingredientsWrapper -->

                                      <br><br><br>
                                      <button id="addIngredientBtn" class="btn btn-success pull-right">Add Ingredient</button>

                                      <br><br>
                                      <hr>
                                      <br><br>
                                      <button type="submit" class="btn btn-success waves-effect waves-light m-r-10 pull-center">Save Recipe</button>
                                    </form>
                                      <br><br>

                                </div> <!-- end class col-sm-12 -->
                            </div> <!-- end row -->
                        </div> <!-- end class white-box -->
                    </div> <!-- end class col-sm-9-->
                </div> <!-- end row -->

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

<script>
  $(document).ready(function() {
      let ingredientHTML =
      `<div class="form-group ingredientFormGroup">

      <div class="col-sm-3">
                <input class="form-control" type="number" step="0.05" min="0" placeholder="" name="quantity[]" value="<?php echo (new Session())->getOldInput('quantity'); ?>">
      </div>

      <div class="col-sm-4">
            <select class="form-control" name="unit_id[]">
                <option value="0">Select a unit</option>
                <?php
                    foreach($data['units'] as $unit){
                        echo '<option ';

                        if((new Session())->getOldInput('unit_id') == $unit->getId()){
                            echo 'selected="selected" ';
                        }

                        echo 'value="'.$unit->getId().'">'.$unit->getName().' – '.$unit->getAbbreviation().'</option>';
                    }
                ?>
            </select>
      </div>

      <div class="col-sm-4">
        <select class="form-control" name="foodid[]">
            <option value="0">Select a food item</option>
            <?php
                foreach($data['fooditems'] as $fooditem){
                    echo '<option ';

                    if((new Session())->getOldInput('foodid') == $fooditem->getId()){
                        echo 'selected="selected" ';
                    }

                    echo 'value="'.$fooditem->getId().'">'.$fooditem->getName().'</option>';
                }
            ?>
        </select>
      </div> <!-- div class col-xs-5 -->

        <div class="col-sm-1">
          <button class="btn-sm btn-danger btn removeIngredientBtn"><i class="fa fa-times"></i>
          </button>
        </div>

    </div>`; //end ingredientFormGroup -->
    
      $("#addIngredientBtn").on("click", function(e) {
          e.preventDefault();
          $('#ingredientsWrapper').append(ingredientHTML);
      });

      $(document).on("click", ".removeIngredientBtn", function(e) {
          e.preventDefault();
          $(this).closest(".ingredientFormGroup").remove();
      });
  });

</script>

</body>
</html>
