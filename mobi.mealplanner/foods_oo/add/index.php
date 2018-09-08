<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                             Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// Add Food
///////////////////////////////////////////////////////////////////////////////
require_once( $_SERVER['DOCUMENT_ROOT'] . '/modules/main.mod.php' );
require_once('../classes/DatabaseHandler.class.php');
require_once('../classes/FoodItemRepo.class.php');
require_once('../classes/FoodItem.class.php');
require_once('../classes/FoodItemController.class.php');

// Externals
$AddItem = $_REQUEST['add'] ?? FALSE;
$AddName = $_REQUEST['name'] ?? NULL;
$AddCost = $_REQUEST['cost'] ?? NULL;

// Sub Title
$SUBTITLE = 'Add Food';

// Plugins
$PLUGIN_SLIMSCROLL  = TRUE;
$PLUGIN_WAVES       = TRUE;
$PLUGIN_SIDEBARMENU = TRUE;

// Process request
if($AddItem){
  try{
    $dbh = new DatabaseHandler();
    $foodItemRepo = new FoodItemRepo($dbh);
    $foodItem = new FoodItem($_REQUEST['name'], $_REQUEST['cost']);
    $foodItemRepo->add($foodItem);
    header('Location: /foods_oo/');
  }
  catch (Exception $e)
  {
    $Errors[] = 'Unable to create FoodItem. Try again';
  }
}

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
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <div class="white-box">
                            <h3 class="box-title m-b-0">Add a New Food Item</h3>
<?php if (isset($Errors)) { ?>
                            <p class="text-danger m-b-30 font-13">
<?php     foreach($Errors as $error) { ?>
                            <?php echo $error; ?><br/>
<?php     } ?>
                            </p>
<?php } ?>
                            <p><a href="/foods_oo/">&laquo; Return to foods</a></p>
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <form method="post" action="/foods_oo/add/">
                                        <input type="hidden" name="add" value="1">
                                        <div class="form-group">
                                            <label for="inputName">Name</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-font"></i></div>
                                                <input type="text" class="form-control" id="inputName" placeholder="Name of Food or Grocery Item" name="name" value="<?php echo $AddName; ?>"> </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputUnitCost">Cost</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-money"></i></div>
                                                <input type="text" class="form-control" id="inputUnitCost" placeholder="Enter Cost (e.g. 2.99)" name="cost" value="<?php echo $AddCost; ?>"> </div>
                                        </div>
                                        <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">+ Add Item</button>
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

</body>
</html>
