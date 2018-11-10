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
$SUBTITLE = "Add item to grocery list";

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
                            <a href="/GroceryListItems/index">&laquo; Return to grocery list</a>
                            </p>
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <form method="post" action="/GroceryListItems/store">
                                        <div class="form-group">
                                            <label for="inputFoodItem">Food Item</label>
                                            <select class="form-control" id="inputFoodItem" name="foodItemId">
                                                <option value="0" data-unit-abbrev="units">Select one</option>
                                                <?php
                                                    foreach($data['foodItems'] as $foodItem){
                                                        $option = '<option data-unit-abbrev="'.$foodItem->getUnit()->getAbbreviation().'"';

                                                        if($data['session']->getOldInput('foodItemId') == $foodItem->getId()){
                                                            $option .= 'selected="selected"';
                                                        }
                                                        $option .= 'value="'.$foodItem->getId().'">'.$foodItem->getName().'</option>';

                                                        echo $option;
                                                    }
                                                ?>
                                            </select>
                                            <p class="help-block">Note: If your item is not on the list, it has already been added to the grocery list.</p>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputAmount">Amount</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" min="1" max="9999.99" class="form-control" id="inputAmount" placeholder="" name="amount" value="<?php echo $data['session']->getOldInput('amount'); ?>">
                                                <div id="unitAbbrev" class="input-group-addon">units</i></div>
                                            </div>
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
        function updateUnits(){
            let selectedItem = $('#inputFoodItem').find(':selected');
            let abbreviation = selectedItem.data('unit-abbrev');
            $('#unitAbbrev').text(abbreviation);
        }

        updateUnits();

        $('#inputFoodItem').on('change', function(){
            updateUnits();
        });
    });
</script>
</body>
</html>
