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
$SUBTITLE = "Add Food Item";

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
<?php if (isset($Errors)) { ?>
                            <p class="text-danger m-b-30 font-13">
<?php     foreach($Errors as $error) { ?>
                            <?php echo $error; ?><br/>
<?php     } ?>
                            </p>
<?php } ?>

                            <p class="text-muted m-b-30 font-13"> <?php echo $SUBTITLE; ?>
                            <a href="/FoodItems/index">&laquo; Return to foods</a>
                            </p>
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <form method="post" action="/FoodItems/store">
                                        <div class="form-group">
                                            <label for="inputName">Name</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-font"></i></div>
                                                <input type="text" class="form-control" id="inputName" placeholder="Name of Food or Grocery Item" name="name" value="<?php echo $data['session']->getOldInput('name') ?>"> </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputCategory">Category</label>
                                            <select class="form-control" id="inputCategory" name="categoryId">
                                                <option value="0">Select one</option>
                                                <?php
                                                    foreach($data['categories'] as $category){
                                                        echo '<option ';

                                                        if($data['session']->getOldInput('categoryId') == $category->getId()){
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

                                                        if($data['session']->getOldInput('unitId') == $unit->getId()){
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
                                                <input type="number" step="0.01" min="1" class="form-control" id="inputUnitsInContainer" placeholder="" name="unitsInContainer" value="<?php echo $data['session']->getOldInput('unitsInContainer'); ?>">
                                            </div>
                                            <p class="help-block"></p>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputContainerCost">Container Cost</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-money"></i></div>
                                                <input type="number" step="0.01" min="0" class="form-control" id="inputContainerCost" placeholder="" name="containerCost" value="<?php echo $data['session']->getOldInput('containerCost'); ?>">
                                            </div>
                                            <p class="help-block"></p>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputStock">Number of Units in Stock</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-font"></i></div>
                                                <input type="number" step="0.01" min="0" class="form-control" id="inputStock" placeholder="Enter current stock" name="stock" value="<?php echo $data['session']->getOldInput('stock'); ?>"> </div>
                                        </div>


                                        <div class="form-group">
                                            <label for="inputUnitCost">Unit Cost</label>
                                            <p class="form-control-static" id="inputUnitCost" name="unitCost">$<?php echo $data['session']->getOldInput('unitCost'); ?></p>
                                        </div>
                                        <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Save</button>
                                    </form>
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

<script type="text/javascript">
    $(document).ready(function(){
        function calculateUnitCost(){
            let containerCost = parseFloat($('#inputContainerCost').val());
            let unitsInContainer = parseFloat($('#inputUnitsInContainer').val());

            let unitCost = Number(containerCost/unitsInContainer).toFixed(2);

            if(!isNaN(unitCost)){
                $('#inputUnitCost').text('$'+unitCost);
            }
            else {
                $('#inputUnitCost').text('Enter valid container cost and units in container.');
            }

        }

        calculateUnitCost();

        $('#inputUnitsInContainer, #inputContainerCost').on('change', function(){
            calculateUnitCost();
        });
    });
</script>
</body>
</html>
