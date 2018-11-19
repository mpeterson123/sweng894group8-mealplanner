<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                             Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// View/Edit Meal
///////////////////////////////////////////////////////////////////////////////
require_once __DIR__.'/../../../vendor/autoload.php';
require_once( $_SERVER['DOCUMENT_ROOT'] . '/../app/views/modules/main.mod.php' );

use Base\Helpers\Session;

// Plugins
$PLUGIN_SLIMSCROLL  = TRUE;
$PLUGIN_WAVES       = TRUE;
$PLUGIN_SIDEBARMENU = TRUE;
$PLUGIN_DATEPICKER  = TRUE;

// Sub Title
$SUBTITLE = "Edit meal for {$data['meal']->getRecipe()->getName()}";

?>
<?php require_once( __HEADER__ ); ?>

<body class="mini-sidebar">
    <!-- Confirm deletion modal -->
    <div class="modal fade" id="confirm-delete-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Meal Deletion</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this meal? This cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <form class="" action="/Meals/delete/<?php echo $data['meal']->getId();?>" method="post">
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
                    <div class="col-md-4 col-sm-12">
                        <div class="white-box">
                            <h3 class="box-title m-b-0"><?php echo $data['meal']->getRecipe()->getName(); ?></h3>
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
                            <form method="post" action="/Meals/update/<?php echo $data['meal']->getId(); ?>">
                                <input type="hidden" name="mealid" value="<?php echo $data['meal']->getId(); ?>">

                                <div class="form-group">
                                    <label for="inputDate">Date</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-font"></i></div>
                                        <input type="text" class="form-control datepicker" id="inputDate" placeholder="Date of Meal" name="date" value="<?php echo ($data['session']->getOldInput('date') != NULL)? $data['session']->getOldInput('date') : $data['meal']->getDate(true); ?>"> </div>
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
                                              elseif(!$data['session']->getOldInput('recipeId') && $data['meal']->getRecipe()->getId() == $recipe->getId()){
                                                  echo 'selected="selected" ';
                                              }

                                              echo 'value="'.$recipe->getId().'">'.$recipe->getName().'</option>';
                                          }
                                        ?>
                                    </select>
                                </div>

                                <!-- SCALE FACTOR -->
                                <div class="form-group">
                                    <label for="inputScaleFactor">Scale</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-font"></i></div>
                                        <input type="number" step="0.01" min="1" class="form-control" id="inputScaleFactor" placeholder="" name="scaleFactor" value="<?php echo ($data['session']->getOldInput('scaleFactor') != NULL)? $data['session']->getOldInput('scaleFactor') : $data['meal']->getScaleFactor(); ?>">
                                    </div>
                                </div>


                                <div class="form-group">
                                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-2">
                        <div class="white-box">
                            <h3 class="box-title m-b-0">Options</h3>

                            <!-- Button trigger modal -->
                            <button
                                type="button"
                                class="btn btn-danger m-t-15"
                                data-toggle="modal"
                                data-target="#confirm-delete-modal">
                                Remove Meal
                            </button>
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
<script type="text/javascript">
    $(document).ready(function(){
        $('.datepicker').datepicker({
            todayHighlight: true,
            startDate: "-0d"
        });
    });
</script>
</body>
</html>
