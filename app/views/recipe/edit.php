<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                             Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// View/Edit Food
///////////////////////////////////////////////////////////////////////////////
require_once __DIR__.'/../../../vendor/autoload.php';
require_once( $_SERVER['DOCUMENT_ROOT'] . '/../app/views/modules/main.mod.php' );

use Base\Helpers\Session;

// Plugins
$PLUGIN_SLIMSCROLL  = TRUE;
$PLUGIN_WAVES       = TRUE;
$PLUGIN_SIDEBARMENU = TRUE;

// Sub Title
$SUBTITLE = "Edit Recipe {$data['recipe']->getName()}";

?>
<?php require_once( __HEADER__ ); ?>

<body class="mini-sidebar">
    <!-- Confirm deletion modal -->
    <div class="modal fade" id="confirm-delete-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Recipe Deletion</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this recipe? Doing so will <strong>remove it from all of your meal plans</strong>. This cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <form class="" action="/Recipes/delete/<?php echo $data['recipe']->getId();?>" method="post">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== Main-Wrapper ===== -->
    <div id="wrapper">
<!--      <div class="preloader">
          <div class="cssload-speeding-wheel"></div>
      </div>
-->

<?php require_once( __NAVBAR__ ); ?>

<?php require_once( __SIDEBAR__ ); ?>

        <!-- ===== Page-Content ===== -->
        <div class="page-wrapper">

            <!-- ===== Page-Container ===== -->
           <div class="container-fluid">
                <?php $data['session']->renderMessage(); ?>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="white-box">
                            <h3 class="box-title m-b-0"><?php echo $data['recipe']->getName(); ?></h3>
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

                                <div class="col-sm-12 col-xs-12">
                                    <form method="post" action="/Recipes/update/<?php echo $data['recipe']->getId(); ?>">
                                        <input type="hidden" name="recipeId" value="<?php echo $data['recipe']->getId(); ?>">
                                        <div class="form-group">
                                            <label for="inputName">Name</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-font"></i></div>
                                                <input required type="text" class="form-control" id="inputName" maxlength="128" placeholder="Name of Recipe" name="name" value="<?php echo $data['recipe']->getName(); ?>"> </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputUnitsInContainer">Servings</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-font"></i></div>
                                                <input required type="number" step="0.01" min="1" class="form-control" id="inputServings" placeholder="1" name="servings" value="<?php echo $data['recipe']->getServings(); ?>">
                                            </div>
                                            <p class="help-block"></p>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputSource">Source</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-font"></i></div>
                                                <input type="text" class="form-control" id="inputSource" maxlength="64" placeholder="" name="source" value="<?php echo $data['recipe']->getSource(); ?>">
                                            </div>
                                            <p class="help-block"></p>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputName">Notes</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-font"></i></div>
                                                <input type="text" class="form-control" id="inputNotes" maxlength="128" placeholder="Notes" name="notes" value="<?php echo $data['recipe']->getNotes(); ?>"> </div>
                                        </div>


                                        <hr>
                                            <label for="ingredientsWrapper">Ingredients</label>
                                            <div id="ingredientsWrapper">

                                            <?php foreach($data['recipe']->getIngredients() as $ingredient) { ?>

                                            <div class="form-group ingredientFormGroup">
                                                <div class="col-sm-4">
                                                  <select required class="form-control selectFoodItem" name="foodId[]">
                                                      <option value="0">Select a food item</option>
                                                      <?php
                                                          foreach($data['foodItems'] as $foodItem){
                                                              echo '<option ';

                                                              echo 'data-base-unit-abbreviation="'.$foodItem->getUnit()->getBaseUnit().'" ';

                                                              if($ingredient->getFood()->getId() == $foodItem->getId()){
                                                                echo 'selected="selected" ';
                                                              }

                                                              echo 'value="'.$foodItem->getId().'">'.$foodItem->getName().'</option>';
                                                          }
                                                      ?>
                                                  </select>
                                                </div>

                                              <div class="col-sm-3">
                                                        <input required class="form-control" type="number" step="0.05" min="0.05" placeholder="" name="quantity[]" value="<?php echo $ingredient->getQuantity()->getValue(); ?>">
                                                        <input type="hidden" name="ingredientIds[]" value="<?php echo $ingredient->getId(); ?>">
                                              </div>

                                              <div class="col-sm-4">
                                                    <select required class="form-control selectUnit" name="unitId[]" data-stored-unit="<?php echo $ingredient->getQuantity()->getUnit()->getId()?>">
                                                        <option value="<?php echo $ingredient->getUnit()->getId();?>"><?php echo $ingredient->getUnit()->getName();?></option>
                                                        <?php
                                                            foreach($data['units'] as $unit){
                                                                echo '<option ';

                                                                if($ingredient->getQuantity()->getUnit()->getId() == $unit->getId()){
                                                                    echo 'selected="selected" ';
                                                                }

                                                                echo 'value="'.$unit->getId().'">'.$unit->getName().' – '.$unit->getAbbreviation().'</option>';
                                                            }
                                                        ?>
                                                    </select>
                                              </div>



                                              <div class="col-sm-1">
                                                <button class="btn-sm btn-danger btn removeIngredientBtn"><i class="fa fa-times"></i>
                                                </button>
                                              </div>

                                              <br>
                                            </div> <!-- end ingredientFormGroup -->
                                          <?php } ?>
                                          </div> <!-- end ingredientsWrapper -->

                                          <button id="addIngredientBtn" class="btn btn-success pull-right">Add Ingredient</button>

                                          <br><br>
                                          <hr>
                                          <br><br>

                                          <div class="form-group">
                                              <label for="inputDirections">Directions</label>
                                              <div class="col-sm-12">
                                                  <textarea class="form-control" rows="5" name="directions" maxlength="65535"><?php echo $data['recipe']->getDirections(); ?></textarea>
                                              </div>
                                          </div>

                                          <br><br><br><br><br><br>
                                          <hr>
                                          <br>

                                        <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Update</button>
                                   </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="white-box">
                            <h3 class="box-title m-b-0">Options</h3>

                            <!-- Button trigger modal -->
                            <button
                                type="button"
                                class="btn btn-danger m-t-15"
                                data-toggle="modal"
                                data-target="#confirm-delete-modal">Remove Recipe</button>
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

<script>
  $(document).ready(function() {
      let ingredientHTML =
          `<div class="form-group ingredientFormGroup">
                <div class="col-sm-4">
                    <select required class="form-control selectFoodItem" name="newFoodId[]">
                        <option value="0">Select a food item</option>
                        <?php
                            foreach($data['foodItems'] as $foodItem){
                                echo '<option ';

                                echo 'data-base-unit-abbreviation="'.$foodItem->getUnit()->getBaseUnit().'" ';

                                if($data['session']->getOldInput('newFoodId') == $foodItem->getId()){
                                    echo 'selected="selected" ';
                                }

                                echo 'value="'.$foodItem->getId().'">'.$foodItem->getName().'</option>';
                            }
                        ?>
                    </select>
                </div>

                <div class="col-sm-3">
                          <input required class="form-control" type="number" step="0.05" min="0.05" max="9999" placeholder="" name="newQuantity[]" value="0">
                </div>

                <div class="col-sm-4">
                      <select required class="form-control selectUnit" name="newUnitId[]" disabled>
                          <option value="0">Select a unit</option>
                          <?php
                              foreach($data['units'] as $unit){
                                  echo '<option ';

                                  if($data['session']->getOldInput('newUnitId') == $unit->getId()){
                                      echo 'selected="selected" ';
                                  }

                                  echo 'value="'.$unit->getId().'">'.$unit->getName().' – '.$unit->getAbbreviation().'</option>';
                              }
                          ?>
                      </select>
                </div>



                <div class="col-sm-1">
                  <button class="btn-sm btn-danger btn removeIngredientBtn"><i class="fa fa-times"></i>
                  </button>
                </div>

                <br>

            </div>`; //end ingredientFormGroup -->

      $("#addIngredientBtn").on("click", function(e) {
          e.preventDefault();
          $('#ingredientsWrapper').append(ingredientHTML);
      });

        $(document).on("click", ".removeIngredientBtn", function(e) {
            e.preventDefault();
            $(this).closest(".ingredientFormGroup").remove();
        });

        $('.selectFoodItem').each(function(){
            var baseUnitAbbreviation = $(this).find(':selected').data('base-unit-abbreviation');
            var selectFoodItem = $(this);

            getUnitsForFoodItem(baseUnitAbbreviation, selectFoodItem);
        });

        $(document).on("change", ".selectFoodItem", function(e) {
            e.preventDefault();

            var baseUnitAbbreviation = $(this).find(':selected').data('base-unit-abbreviation');
            var selectFoodItem = $(this);

            getUnitsForFoodItem(baseUnitAbbreviation, selectFoodItem);
        });

        function getUnitsForFoodItem(baseUnitAbbreviation, selectFoodItem){
            $.post(
                "/Units/getConvertibleFrom/"+baseUnitAbbreviation,
                function( data ) {
                    var units = JSON.parse(data);

                    var options = '<option value="0">Select a unit</option>';
                    units.forEach(function(unit){
                        options += '<option value= "'+unit.id+'">'+unit.name+' - '+unit.abbreviation+'</option>';
                    });

                    selectFoodItem.closest('.ingredientFormGroup').find('.selectUnit').html(options);

                    if(units.length == 0){
                        selectFoodItem.closest('.ingredientFormGroup').find('.selectUnit').attr('disabled', true);
                    }
                    else {
                        selectFoodItem.closest('.ingredientFormGroup').find('.selectUnit').attr('disabled', false);

                        var selectUnit = selectFoodItem.closest('.ingredientFormGroup').find('.selectUnit');
                        var storedUnit = selectUnit.data('stored-unit');

                        if(selectUnit.find("option[value='"+storedUnit+"']").length > 0){
                            selectUnit.val(storedUnit);
                        }
                    }
                }
            );
        }
  });

</script>


</body>
</html>
