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
$SUBTITLE = "Edit Food {$data['food']['name']}";

?>
<?php require_once( __HEADER__ ); ?>

<body class="mini-sidebar">
    <!-- Confirm deletion modal -->
    <div class="modal fade" id="confirm-delete-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Food Item Deletion</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this food item? Doing so will <strong>remove it from all of your recipes</strong>. This cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <form class="" action="/FoodItems/delete/<?php echo $data['food']['id'];?>" method="post">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
                            <h3 class="box-title m-b-0"><?php echo $data['food']['name']; ?></h3>
<?php if (isset($Errors)) { ?>
                            <p class="text-danger m-b-30 font-13">
<?php     foreach($Errors as $error) { ?>
                            <?php echo $error; ?><br/>
<?php     } ?>
                            </p>
<?php } ?>

                            <p class="text-muted m-b-30 font-13"> <?php echo $SUBTITLE; ?>
                            <a href="/FoodItems/">&laquo; Return to foods</a>
                            </p>
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <form method="post" action="/FoodItems/update/<?php echo $data['food']['id']; ?>">
                                        <input type="hidden" name="foodid" value="<?php echo $data['food']['id']; ?>">
                                        <div class="form-group">
                                            <label for="inputName">Name</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-font"></i></div>
                                                <input type="text" class="form-control" id="inputName" placeholder="Name of Food or Grocery Item" name="name" value="<?php echo $data['food']['name']; ?>"> </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputCategory">Category</label>
                                            <select class="form-control" id="inputCategory" name="category_id">
                                                <option value="0">Select one</option>
                                                <?php
                                                    foreach($data['categories'] as $category){
                                                        echo '<option ';

                                                        if($data['food']['category_id'] == $category['id']){
                                                            echo 'selected="selected"';
                                                        }

                                                        echo 'value="'.$category['id'].'">'.$category['name'].'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputUnit">Unit</label>
                                            <select class="form-control" id="inputUnit" name="unit_id">
                                                <option value="0">Select one</option>
                                                <?php
                                                    foreach($data['units'] as $unit){
                                                        echo '<option ';

                                                        if($data['food']['unit_id'] == $unit['id']){
                                                            echo 'selected="selected" ';
                                                        }

                                                        echo 'value="'.$unit['id'].'">'.$unit['name'].' – '.$unit['abbreviation'].'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputUnitsInContainer">Number of Units in Container</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-font"></i></div>
                                                <input type="number" step="0.01" min="1" class="form-control" id="inputUnitsInContainer" placeholder="1" name="units_in_container" value="<?php echo $data['food']['units_in_container']; ?>">
                                            </div>
                                            <p class="help-block"></p>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputContainerCost">Container Cost</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-money"></i></div>
                                                <input type="number" step="0.01" min="0" class="form-control" id="inputContainerCost" placeholder="1" name="container_cost" value="<?php echo $data['food']['container_cost']; ?>">
                                            </div>
                                            <p class="help-block"></p>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputStock">Number of Units in Stock</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-font"></i></div>
                                                <input type="number" step="0.01" min="0" class="form-control" id="inputStock" placeholder="Enter current stock" name="stock" value="<?php echo $data['food']['stock']; ?>"> </div>
                                        </div>


                                        <div class="form-group">
                                            <label for="inputUnitCost">Unit Cost</label>
                                            <p class="form-control-static" id="inputUnitCost" name="unit_cost">$<?php echo $data['food']['unit_cost']; ?></p>
                                        </div>
                                        <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Update</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="white-box">
                            <h3 class="box-title m-b-0">Options</h3>

                            <!-- Button trigger modal -->
                            <button
                                type="button"
                                class="btn btn-danger m-t-15"
                                data-toggle="modal"
                                data-target="#confirm-delete-modal">
                                Remove Item
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
        function calculateUnitCost(){
            let containerCost = parseFloat($('#inputContainerCost').val());
            let unitsInContainer = parseFloat($('#inputUnitsInContainer').val());

            $('#inputUnitCost').text('$'+Number(containerCost/unitsInContainer).toFixed(2));
        }

        calculateUnitCost();

        $('#inputUnitsInContainer, #inputContainerCost').on('change', function(){
            calculateUnitCost();
        });
    });
</script>
</body>
</html>
