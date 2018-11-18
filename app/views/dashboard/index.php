<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                             Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// Login
///////////////////////////////////////////////////////////////////////////////
require_once __DIR__.'/../../../vendor/autoload.php';
require_once( __DIR__ . '/../modules/main.mod.php' );

use Base\Helpers\Session;

// Sub Title
$SUBTITLE = 'Login';

// Plugins
$PLUGIN_SLIMSCROLL = TRUE;
$PLUGIN_WAVES      = TRUE;
$PLUGIN_SIDEBARMENU= TRUE;

// Dashboard Settings
define('NUM_USERS_TO_LIST', 6);

// Dashboard Statistics

$houseHoldID    = $data['user']->getCurrHousehold()->getId();
$numFoodItems   = sqlRequest("SELECT COUNT(id) AS totalnum FROM foods WHERE householdId = {$houseHoldID}")[0]['totalnum'];
$numRecipes     = sqlRequest("SELECT COUNT(id) AS totalnum FROM recipes WHERE householdId = {$houseHoldID}")[0]['totalnum'];
$numMeals       = sqlRequest("SELECT COUNT(id) AS numMeals FROM meal WHERE householdid = 1")[0]['numMeals']; 
$numMealsEaten  = sqlRequest("SELECT COUNT(id) AS mealsEaten FROM meal WHERE isComplete = TRUE AND householdid = 1")[0]['mealsEaten']; // Based off of meals
$numMealsEatenPercentage = ($numMealsEaten / $numMeals);
$numRecipeCost  = sqlRequest("SELECT recipes.householdid, SUM(unitCost * quantity * servings) AS totalCost FROM ingredients, recipes, foods WHERE recipes.id = ingredients.recipeid AND ingredients.foodid = foods.id AND recipes.householdid = {$houseHoldID}")[0]['totalCost']; // Based off of recipes only (nothing consumed)
$numFoodCost    = sqlRequest("SELECT recipes.householdid, SUM(unitCost * quantity * servings) AS totalCost FROM ingredients, recipes, foods, meal WHERE recipes.id = ingredients.recipeid AND ingredients.foodid = foods.id AND meal.recipeid = recipes.id AND recipes.householdid = {$houseHoldID}")[0]['totalCost']; // Based off of meals (lifetime)
$numFoodCostMon = sqlRequest("SELECT recipes.householdid, SUM(unitCost * quantity * servings) AS totalCost FROM ingredients, recipes, foods, meal WHERE recipes.id = ingredients.recipeid AND ingredients.foodid = foods.id AND meal.recipeid = recipes.id AND YEAR(addedDate) = YEAR(CURDATE()) AND MONTH(addedDate) = MONTH(CURDATE()) AND recipes.householdid = {$houseHoldID}")[0]['totalCost']; // Based off of meals (for month to date)
$numFoodCostYear= sqlRequest("SELECT recipes.householdid, SUM(unitCost * quantity * servings) AS totalCost FROM ingredients, recipes, foods, meal WHERE recipes.id = ingredients.recipeid AND ingredients.foodid = foods.id AND meal.recipeid = recipes.id AND YEAR(addedDate) = YEAR(CURDATE()) AND recipes.householdid = {$houseHoldID}")[0]['totalCost']; // Based off of meals (for year to date)
$numFoods       = sqlRequest("SELECT COUNT(id) AS numFoods FROM foods WHERE stock > 0 AND householdid = {$houseHoldID}")[0]['numFoods'];
$numStock       = sqlRequest("SELECT SUM(stock) AS numStock FROM foods WHERE stock > 0 AND householdid = {$houseHoldID}")[0]['numStock'];
$usersList      = sqlRequest("SELECT * FROM users");

function writeTime($total)
{
    $secsMinute = 60;
    $secsHour   = 3600;
    $secsDay    = 86400;
    $secsWeek   = 604800;
    $secsMonth  = 2592000;
    $secsYear   = 31104000;

    $result = '';

    $seconds = 0;
    $minutes = 0;
    $hours   = 0;
    $days    = 0;
    $weeks   = 0;
    $months  = 0;
    $years   = 0;

    if (!$total)
        return FALSE;

    if ($total < 1)
        return 'a fraction of a second ';

    if ($total >= $secsYear)
    {
        $years = floor($total / $secsYear);
        $total = 0;
    }
    if ($total >= $secsMonth)
    {
        $months = floor($total / $secsMonth);
        $total = 0;
    }
    if ($total >= $secsWeek)
    {
        $weeks = floor($total / $secsWeek);
        $total = 0;
    }
    if ($total >= $secsDay)
    {
        $days = floor($total / $secsDay);
        $total = 0;
    }
    if ($total >= $secsHour)
    {
        $hours = floor($total / $secsHour);
        $total = 0;
    }
    if ($total >= $secsMinute)
    {
        $minutes = floor($total / $secsMinute);
        $total = 0;
    }
    $seconds = $total;

    if ($years)  { $result = $years.' years'; }
    if ($months) { $result = $months.' months'; }
    if ($weeks) { $result = $weeks.' weeks'; }
    if ($days) { $result = $days.' days'; }
    if ($hours) { $result = $hours.' hours'; }
    if ($minutes) { $result = $minutes.' minutes'; }
    if ($seconds) { $result = $seconds.' seconds'; }

    return $result;
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
            <div class="row">
                <div class="col-xs-12">
                    <?php $data['session']->renderMessage(); ?>
                </div>
            </div>
            <div class="row m-0">
                <div class="col-md-3 col-sm-6 info-box">
                    <div class="media">
                        <div class="media-left">
                            <span class="icoleaf bg-primary text-white"><i class="mdi mdi-checkbox-marked-circle-outline"></i></span>
                        </div>
                        <div class="media-body">
                            <br/>
                            <h3 class="info-count text-blue"><?php $numFoodItems = $numFoodItems ?? 0; if ($numFoodItems) { echo number_format($numFoodItems); } else { echo 'None!'; } ?></h3>
                            <p class="info-text font-12">Food Items</p>
                            <span class="hr-line"></span>
                            <p class="info-ot font-15">Target<span class="label label-rounded label-success">300</span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 info-box">
                    <div class="media">
                        <div class="media-left">
                            <span class="icoleaf bg-primary text-white"><i class="mdi mdi-comment-text-outline"></i></span>
                        </div>
                        <div class="media-body">
                            <br/>
                            <h3 class="info-count text-blue"><?php $numRecipes = $numRecipes ?? 0; if ($numRecipes) { echo number_format($numRecipes); } else { echo 'None!'; } ?></h3>
                            <p class="info-text font-12">Recipes</p>
                            <span class="hr-line"></span>
                            <p class="info-ot font-15">Total Used<span class="label label-rounded label-danger">0</span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 info-box">
                    <div class="media">
                        <div class="media-left">
                            <span class="icoleaf bg-primary text-white"><i class="mdi mdi-coin"></i></span>
                        </div>
                        <div class="media-body">
                            <br/>
                            <h3 class="info-count text-blue">&#36;<?php $numFoodCostMonth = $numFoodCostMonth ?? 0; echo number_format($numFoodCostMonth, 2); ?></h3>
                            <p class="info-text font-12">Food Cost</p>
                            <span class="hr-line"></span>
                            <p class="info-ot font-15">Year : <span class="text-blue font-semibold">&#36;<?php $numFoodCostYear = $numFoodCostYear ?? 0; echo number_format($numFoodCostYear); ?></span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 info-box b-r-0">
                    <div class="media">
                        <div class="media-body">
                            <br/>
                            <h2 class="text-blue font-22 m-t-0">Report</h2>
                            <ul class="p-0 m-b-20">
                                <li><i class="fa fa-circle m-r-5 text-primary"></i><?php echo round(($numMealsEatenPercentage * 100), 2); ?>% Recipes Used</li>
                                <li><i class="fa fa-circle m-r-5 text-primary"></i>0% </li>
                                <li><i class="fa fa-circle m-r-5 text-info"></i><?php echo $numMealIncrease ?? 0; ?>% Meal Increase</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ===== Page-Container ===== -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="white-box">
                            <div class="task-widget2">
                                <div class="task-image">
                                    <img src="/images/task.jpg" alt="task" class="img-responsive">
                                    <div class="task-image-overlay"></div>
                                    <div class="task-detail">
                                        <h2 class="font-light text-white m-b-0"><?php echo date('D jS, F'); ?></h2>
                                        <h4 class="font-normal text-white m-t-5">Your moments for today</h4>
                                    </div>
                                    <div class="task-add-btn">
                                        <a href="javascript:void(0);" class="btn btn-success">+</a>
                                    </div>
                                </div>
                                <div class="task-total">
                                    <p class="font-16 m-b-0"><strong>0</strong> for <a href="javascript:void(0);" class="text-link"><?php echo $user->getFirstName(); ?></a></p>
                                </div>
                                <div class="task-list">
                                    <ul class="list-group">
<?php $LastFewMeals = sqlRequest("SELECT * FROM meal WHERE householdid = {$houseHoldID} ORDER BY addedDate DESC LIMIT 5"); $i = 0; foreach ($LastFewMeals as $meal) { $i++; ?>
                                        <li class="list-group-item bl-info">
                                            <div class="checkbox checkbox-success">
                                                <input id="c<?php echo $i; ?>" type="checkbox" checked>
                                                <label for="c<?php echo $i; ?>">
                                                    <span class="font-16">New meal added <?php echo writeTime(time() - strtotime($meal['addedDate'])); ?>ago.</span>
                                                </label>
                                                <h6 class="p-l-30 font-bold"><?php echo $meal['addedDate']; ?></h6>
                                            </div>
                                        </li>
<?php } ?>                                        
                                        <!--
                                        <li class="list-group-item bl-warning">
                                            <div class="checkbox checkbox-success">
                                                <input id="c8" type="checkbox" checked>
                                                <label for="c8">
                                                    <span class="font-16">Send daughter to pickup <strong>50 onions</strong> on 23 May to <a href="javascript:void(0);" class="text-link">Daniel Kristeen</a> for the friend onion fest.</span>
                                                </label>
                                                <h6 class="p-l-30 font-bold">03:00 PM</h6>
                                            </div>
                                        </li>
                                        <li class="list-group-item bl-danger">
                                            <div class="checkbox checkbox-success">
                                                <input id="c9" type="checkbox">
                                                <label for="c9">
                                                    <span class="font-16">It is a long established fact that a reader will be distracted by the readable.</span>
                                                </label>
                                                <h6 class="p-l-30 font-bold">04:45 PM</h6>
                                            </div>
                                        </li>
                                        <li class="list-group-item bl-success">
                                            <div class="checkbox checkbox-success">
                                                <input id="c10" type="checkbox">
                                                <label for="c10">
                                                    <span class="font-16">It is a long established fact that a reader will be distracted by the readable.</span>
                                                </label>
                                                <h6 class="p-l-30 font-bold">05:30 PM</h6>
                                            </div>
                                        </li>
-->
                                    </ul>
                                </div>
                                <div class="task-loadmore">
                                    <a href="javascript:void(0);" class="btn btn-default btn-outline btn-rounded">Load More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="white-box">
                            <h4 class="box-title">Task Progress</h4>
                            <div class="task-widget t-a-c">
                                <div class="task-chart" id="sparklinedashdb"></div>
                                <div class="task-content font-16 t-a-c">
                                    <div class="col-sm-6 b-r">
                                        Moments
                                        <h1 class="text-primary">00 <span class="font-16 text-muted">Updates</span></h1>
                                    </div>
                                    <div class="col-sm-6">
                                        Recipes Eaten
                                        <h1 class="text-primary"><?php echo number_format($numMealsEaten); ?> <span class="font-16 text-muted">Meals</span></h1>
                                    </div>
                                </div>
                                <div class="task-assign font-16">
                                    Meal Planners
                                    <ul class="list-inline">
                                        <!--
                                        <li class="p-l-0">
                                            <img src="/images/users/avatar1.jpg" alt="user" data-toggle="tooltip" data-placement="top" title="" data-original-title="Steave">
                                        </li>
-->
<?php $numListed = 0; foreach ($usersList as $aUser) { if ($numListed == NUM_USERS_TO_LIST) { break; } $numListed++; ?>
                                        <li>
                                            <img src="/images/users/<?php if ($aUser['profilePic'] ?? NULL)
                                                                          {
                                                                              // File check
                                                                              if ($aUser['profilePic'] == '')
                                                                              {
                                                                                  echo 'avatar.png';
                                                                              }
                                                                              else if (!file_exists(__DIR__ . '/../../../public/images/users/' . $aUser['profilePic']))
                                                                              {
                                                                                  echo 'avatar.png';
                                                                              }
                                                                              else
                                                                              {
                                                                                  echo $aUser['profilePic'];
                                                                              }
                                                                          }
                                                                          else
                                                                          {
                                                                              echo 'avatar.png';
                                                                          } ?>" alt="<?php echo "{$aUser['namefirst']} {$aUser['namelast']}"; ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo "{$aUser['namefirst']}"; ?>">
                                        </li>
<?php } ?>
                                        <?php if (count($usersList) > NUM_USERS_TO_LIST) { ?>
                                        <li class="p-r-0">
                                            <a href="javascript:void(0);" class="btn btn-success font-16"><?php echo (count($usersList) - NUM_USERS_TO_LIST); ?>+</a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <div class="white-box bg-primary color-box">
                            <h1 class="text-white font-light">&#36;0 <span class="font-14">Lifetime Food Cost</span><br /><br /></h1>
                            <div class="ct-revenue chart-pos"$<?php echo number_format($numFoodCost, 2); ?></div>
                        </div>
                        <br/>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="white-box bg-success color-box">
                            <h1 class="text-white font-light m-b-0"><?php echo $numFoods; ?></h1>
                            <span class="hr-line"></span>
                            <p class="cb-text">current groceries</p>
                            <h6 class="text-white font-semibold"><?php echo $numStock; ?> <span class="font-light"># of stock</span></h6>
                        </div>
                        <br/>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="white-box bg-danger color-box">
                            <h1 class="text-white font-light m-b-0"><?php echo round(($numMealsEatenPercentage * 100), 2); ?>%</h1>
                            <span class="hr-line"></span>
                            <p class="cb-text">Finished Meals</p>
                            <h6 class="text-white font-semibold">+0% <span class="font-light">Last Week</span></h6>
                        </div>
                        <br/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 col-sm-12">
                        <div class="white-box stat-widget">
                            <div class="row">
                                <div class="col-md-3 col-sm-3">
                                    <h4 class="box-title">Statistics</h4>
                                </div>
                                <div class="col-md-9 col-sm-9">
                                    <select class="custom-select">
                                        <option selected value="0">Oct - Nov</option>
                                        <option value="1">Sep - Oct</option>
                                        <option value="2">Aug - Sep</option>
                                        <option value="3">Jul - Aug</option>
                                    </select>
                                    <ul class="list-inline">
                                        <li>
                                            <h6 class="font-15"><i class="fa fa-circle m-r-5 text-success"></i>New Groceries</h6>
                                        </li>
                                        <li>
                                            <h6 class="font-15"><i class="fa fa-circle m-r-5 text-primary"></i>Existing Groceries</h6>
                                        </li>
                                    </ul>
                                </div>
                                <div class="stat chart-pos"></div>
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
