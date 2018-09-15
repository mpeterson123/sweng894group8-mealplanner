<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                             Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// View/Edit Food
///////////////////////////////////////////////////////////////////////////////
require_once( $_SERVER['DOCUMENT_ROOT'] . '/../app/views/modules/main.mod.php' );

// Externals
$FoodID   = $_REQUEST['foodid'] ?? substr($_SERVER['REDIRECT_URL'], strrpos($_SERVER['REDIRECT_URL'], '/')+1) ?? 0;
$EditItem = $_REQUEST['edit'] ?? FALSE;
$EditName = $_REQUEST['name'] ?? NULL;
$EditCost = $_REQUEST['cost'] ?? NULL;

// Globals
$Success = FALSE;

// Check for valid Food Item
if (!$FoodID)
{
?>
<script>document.location='/foods/';</script>
<?php
   exit(0);
}

// Check to see if User can see this food (SECURITY check)
// ...

// Plugins
$PLUGIN_SLIMSCROLL  = TRUE;
$PLUGIN_WAVES       = TRUE;
$PLUGIN_SIDEBARMENU = TRUE;

// Update the Food Item?
if ($EditItem)
{
    $Errors = array();

    // Missing Fields
    if (!$EditName)
    {
        $Errors[] = 'Missing name.';
    }

    if (!$EditCost)
    {
        $Errors[] = 'Missing item cost.';
    }

    // Sanitization (e.g. mysql_real_escape_string)

    // Are we good to go?
    if (count($Errors) == 0)
    {
        sqlQuery("UPDATE food SET name = '{$EditName}', unitcost = {$EditCost} WHERE id = {$FoodID}");
        $Success = TRUE;
    }
}

// PHPUnit check (should be placed after any regular errors are detected)
PHPUnitCheck($Errors);

// Retrieve Food (needs to be done AFTER update)
$Food = sqlRequestArrayByID('food', $FoodID, '*');

// Sub Title
$SUBTITLE = "Viewing Food {$Food['name']}";

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
                            <h3 class="box-title m-b-0"><?php echo $Food['name']; ?></h3>
<?php if (isset($Errors)) { ?>
                            <p class="text-danger m-b-30 font-13">
<?php     foreach($Errors as $error) { ?>
                            <?php echo $error; ?><br/>
<?php     } ?>
                            </p>
<?php } ?>
<?php if ($Success) { ?>
                            <p class="text-success m-b-30 font-13"> Update successful
                            <a href="/foods/">&laquo; Return to foods</a>
                            </p>
<?php } else { ?>
                            <p class="text-muted m-b-30 font-13"> Food Item Properties
                            <a href="/foods/">&laquo; Return to foods</a>
                            </p>
<?php } ?>
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <form method="post" action="/foods/food/">
                                        <input type="hidden" name="edit" value="1">
                                        <input type="hidden" name="foodid" value="<?php echo $Food['id']; ?>">
                                        <div class="form-group">
                                            <label for="inputName">Name</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-font"></i></div>
                                                <input type="text" class="form-control" id="inputName" placeholder="Name of Food or Grocery Item" name="name" value="<?php echo $Food['name']; ?>"> </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputUnitCost">Cost</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-money"></i></div>
                                                <input type="text" class="form-control" id="inputUnitCost" placeholder="Enter Cost (e.g. 2.99)" name="cost" value="<?php echo $Food['unitcost']; ?>"> </div>
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
                            <a href="/foods/food/?foodid=<?php echo $Food['id']; ?>&delete=1" class="btn btn-danger m-t-15">Remove Item</a>
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
