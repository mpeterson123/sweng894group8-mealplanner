<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                             Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// View/Edit Meal
///////////////////////////////////////////////////////////////////////////////
require_once __DIR__.'/../../../vendor/autoload.php';
require_once( $_SERVER['DOCUMENT_ROOT'] . '/../app/views/modules/main.mod.php' );

// Externals
$MealID   = $_REQUEST['id'] ?? substr($_SERVER['REDIRECT_URL'], strrpos($_SERVER['REDIRECT_URL'], '/')+1) ?? 0;
$EditRecipe = $_REQUEST['recipe'] ?? NULL;
$EditDate = $_REQUEST['date'] ?? NULL;
$EditScale = $_REQUEST['scale'] ?? NULL;
$IsComplete = $_REQUEST['isComplete'] ?? FALSE;

$EditMeal = $_REQUEST['edit'] ?? FALSE; //check

// Globals
$Success = FALSE;

// Check for valid Meal
if (!$MealID)
{
?>
<script>document.location='/meal/';</script>
<?php
   exit(0);
}

// Plugins
$PLUGIN_SLIMSCROLL  = TRUE;
$PLUGIN_WAVES       = TRUE;
$PLUGIN_SIDEBARMENU = TRUE;

// Update the Meal?
if ($EditMeal)
{
    $Errors = array();

    // Missing Fields
    if (!$EditScale)
    {
        $Errors[] = 'Missing scale.';
    }

    if (!$EditDate)
    {
        $Errors[] = 'Missing date.';
    }

    if (!$EditRecipe)
    {
        $Errors[] = 'Missing recipe.';
    }

    // Sanitization (e.g. mysql_real_escape_string)

    if (count($Errors) == 0)
    {
        sqlQuery("UPDATE meal SET date = {$EditDate}, scale = {$EditScale}, recipe = {$EditRecipe->getId()} WHERE id = {$MealID}");
        $Success = TRUE;
    }
}

// Retrieve Meals
$Meal = sqlRequestArrayByID('meal', $MealID, '*');

// Sub Title
$SUBTITLE = "Viewing Meal {$Meal['date']}";

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
                            <h3 class="box-title m-b-0"><?php echo $meal['date']; ?></h3>
<?php if (isset($Errors)) { ?>
                            <p class="text-danger m-b-30 font-13">
<?php     foreach($Errors as $error) { ?>
                            <?php echo $error; ?><br/>
<?php     } ?>
                            </p>
<?php } ?>
<?php if ($Success) { ?>
                            <p class="text-success m-b-30 font-13"> Update successful
                            <a href="/meal/">&laquo; Return to meals</a>
                            </p>
<?php } else { ?>
                            <p class="text-muted m-b-30 font-13"> Meal Properties
                            <a href="/meal/">&laquo; Return to meals</a>
                            </p>
<?php } ?>
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <form method="post" action="/meals/meal/">
                                        <input type="hidden" name="edit" value="1">
                                        <input type="hidden" name="mealid" value="<?php echo $meal['id']; ?>">
                                        <div class="form-group">
                                            <label for="inputDate">Date</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-font"></i></div>
                                                <input type="text" class="form-control" id="inputDate" placeholder="Date of Meal" name="date" value="<?php echo $meal['date']; ?>"> </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputRecipe">Recipe</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-font"></i></div>
                                                <input type="text" class="form-control" id="inputRecipe" placeholder="Enter Recipe" name="recipe" value="<?php echo $meal['recipe']; ?>"> </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputScale">Scale</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-font"></i></div>
                                                <input type="text" class="form-control" id="inputScale" placeholder="Enter scale (e.g. 2.50)" name="scale" value="<?php echo $meal['scale']; ?>"> </div>
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
                            <a href="/meal/?mealid=<?php echo $meal['id']; ?>&delete=1" class="btn btn-danger m-t-15">Remove Meal</a>
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
