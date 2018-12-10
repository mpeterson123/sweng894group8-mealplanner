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
$SUBTITLE = "Edit Food {$data['food']->getName()}";

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
                    <form class="" action="/FoodItems/delete/<?php echo $data['food']->getId();?>" method="post">
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
                <?php $data['session']->renderMessage(); ?>

                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <div class="white-box">
                            <h3 class="box-title m-b-0"><?php echo $data['food']->getName(); ?></h3>

                            <p class="text-muted m-b-30 font-13"> <?php echo $SUBTITLE; ?>
                            <a href="/FoodItems/index">&laquo; Return to foods</a>
                            </p>
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <form method="post" action="/FoodItems/update/<?php echo $data['food']->getId(); ?>">

                                        <div class="form-group">
                                            <label for="inputName">Name</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-font"></i></div>
                                                <input type="text" maxlength="50" class="form-control" id="inputName" placeholder="Name of Food or Grocery Item" name="name" value="<?php echo ($data['session']->getOldInput('name') != NULL)? $data['session']->getOldInput('name') : $data['food']->getName(); ?>"> </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputCategory">Category</label>
                                            <select class="form-control" id="inputCategory" name="categoryId">
                                                <option value="0">Select one</option>
                                                <?php
                                                    foreach($data['categories'] as $category){
                                                        echo '<option ';

                                                        if($data['food']->getCategory()->getId() == $category->getId()){
                                                            echo 'selected="selected"';
                                                        }

                                                        echo 'value="'.$category->getId().'">'.$category->getName().'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputUnit">Unit</label>
                                            <select class="form-control" id="inputUnit" name="unitId">
                                                <option value="0">Select one</option>
                                                <?php
                                                    foreach($data['units'] as $unit){
                                                        echo '<option ';

                                                        if($data['food']->getUnit()->getId() == $unit->getId()){
                                                            echo 'selected="selected" ';
                                                        }

                                                        echo 'value="'.$unit->getId().'">'.$unit->getName().' – '.$unit->getAbbreviation().'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputUnitsInContainer">Number of Units in Container</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-font"></i></div>
                                                <input type="number" step="0.01" min="1" class="form-control" id="inputUnitsInContainer" placeholder="1" name="unitsInContainer" value="<?php echo ($data['session']->getOldInput('unitsInContainer') != NULL)? $data['session']->getOldInput('unitsInContainer') : $data['food']->getUnitsInContainer(); ?>">
                                            </div>
                                            <p class="help-block"></p>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputContainerCost">Container Cost</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-money"></i></div>
                                                <input type="number" step="0.01" min="0.01" class="form-control" id="inputContainerCost" placeholder="1" name="containerCost" value="<?php echo ($data['session']->getOldInput('containerCost') != NULL)? $data['session']->getOldInput('containerCost') : $data['food']->getContainerCost(); ?>">
                                            </div>
                                            <p class="help-block"></p>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputStock">Number of Units in Stock</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-font"></i></div>
                                                <input type="number" step="0.01" min="0" class="form-control" id="inputStock" placeholder="Enter current stock" name="stock" value="<?php echo ($data['session']->getOldInput('stock') != NULL)? $data['session']->getOldInput('stock') : $data['food']->getStock(); ?>"> </div>
                                        </div>


                                        <div class="form-group">
                                            <label for="inputUnitCost">Unit Cost</label>
                                            <p class="form-control-static" id="inputUnitCost" name="unitCost">$<?php echo ($data['session']->getOldInput('unitCost') != NULL)? $data['session']->getOldInput('unitCost') : $data['food']->getUnitCost(); ?></p>
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
