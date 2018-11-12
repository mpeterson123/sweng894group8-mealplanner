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

$houseHoldID  = sqlRequestByID("users", $data['user']->getId(), "currHouseholdId");
$numFoodItems = sqlRequest("COUNT(id) AS totalnum FROM foods WHERE householdId = {$houseHoldID}")[0]['totalnum'];
$numRecipes   = sqlRequest("COUNT(id) AS totalnum FROM recipes WHERE householdId = {$houseHoldID}")[0]['totalnum'];
$numRecipesUsed = 0; // Based off of meals
$numFoodCost    = 0; // Based off of meals (for month to date)
$numFoodCostYear= 0; // Based off of meals (for year to date)
$usersList = sqlRequest("SELECT * FROM users");
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
                            <h3 class="info-count text-blue">&#36;<?php $numFoodCost = $numFoodCost ?? 0; echo number_format($numFoodCost); ?></h3>
                            <p class="info-text font-12">Food Cost</p>
                            <span class="hr-line"></span>
                            <p class="info-ot font-15">Year : <span class="text-blue font-semibold">&#36;<?php $numFoodCostYear = $numFoodCostYear ?? 0; echo number_format($numFoodCostYear); ?></span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 info-box b-r-0">
                    <div class="media">
                        <div class="media-left p-r-5">
                            <div id="earning" class="e" data-percent="12">
                                <div id="pending" class="p" data-percent="55"></div>
                                <div id="booking" class="b" data-percent="50"></div>
                            </div>
                        </div>
                        <div class="media-body">
                            <h2 class="text-blue font-22 m-t-0">Report</h2>
                            <ul class="p-0 m-b-20">
                                <li><i class="fa fa-circle m-r-5 text-primary"></i>0% Recipes Used</li>
                                <li><i class="fa fa-circle m-r-5 text-primary"></i>0% Food Wasted</li>
                                <li><i class="fa fa-circle m-r-5 text-info"></i>0% Meal Increase</li>
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
                                        <li class="list-group-item bl-info">
                                            <div class="checkbox checkbox-success">
                                                <input id="c7" type="checkbox">
                                                <label for="c7">
                                                    <span class="font-16">None</span>
                                                </label>
                                                <h6 class="p-l-30 font-bold">-</h6>
                                            </div>
                                        </li>
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
                                        <h1 class="text-primary">00 <span class="font-16 text-muted">Meals</span></h1>
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
                            <div class="ct-revenue chart-pos"></div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="white-box bg-success color-box">
                            <h1 class="text-white font-light m-b-0">0</h1>
                            <span class="hr-line"></span>
                            <p class="cb-text">current groceries</p>
                            <h6 class="text-white font-semibold">+0% <span class="font-light">Last Week</span></h6>
                            <div class="chart">
                                <div class="ct-visit chart-pos"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="white-box bg-danger color-box">
                            <h1 class="text-white font-light m-b-0">0%</h1>
                            <span class="hr-line"></span>
                            <p class="cb-text">Finished Meals</p>
                            <h6 class="text-white font-semibold">+0% <span class="font-light">Last Week</span></h6>
                            <!--
                            <div class="chart">
                                <input class="knob" data-min="0" data-max="100" data-bgColor="#f86b4a" data-fgColor="#ffffff" data-displayInput=false data-width="96" data-height="96" data-thickness=".1" value="25" readonly>
                            </div>
-->
                        </div>
                    </div>
                </div>
                <div class="row" style="display:none;">
                    <div class="col-md-12">
                        <div class="white-box user-table">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h4 class="box-title">Table Format/User Data</h4>
                                </div>
                                <div class="col-sm-6">
                                    <ul class="list-inline">
                                        <li>
                                            <a href="javascript:void(0);" class="btn btn-default btn-outline font-16"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" class="btn btn-default btn-outline font-16"><i class="fa fa-commenting" aria-hidden="true"></i></a>
                                        </li>
                                    </ul>
                                    <select class="custom-select">
                                        <option selected>Sort by</option>
                                        <option value="1">Name</option>
                                        <option value="2">Location</option>
                                        <option value="3">Type</option>
                                        <option value="4">Role</option>
                                        <option value="5">Action</option>
                                    </select>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="checkbox checkbox-info">
                                                    <input id="c1" type="checkbox">
                                                    <label for="c1"></label>
                                                </div>
                                            </th>
                                            <th>Name</th>
                                            <th>Location</th>
                                            <th>Type</th>
                                            <th>Role</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="checkbox checkbox-info">
                                                    <input id="c2" type="checkbox">
                                                    <label for="c2"></label>
                                                </div>
                                            </td>
                                            <td><a href="javascript:void(0);" class="text-link">Daniel Kristeen</a></td>
                                            <td>Texas, US</td>
                                            <td>Recipes 564</td>
                                            <td><span class="label label-success">Admin</span></td>
                                            <td>
                                                <select class="custom-select">
                                                    <option value="1">Modulator</option>
                                                    <option value="2">Admin</option>
                                                    <option value="3">Staff</option>
                                                    <option value="4">User</option>
                                                    <option value="5">General</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="checkbox checkbox-info">
                                                    <input id="c3" type="checkbox">
                                                    <label for="c3"></label>
                                                </div>
                                            </td>
                                            <td><a href="javascript:void(0);" class="text-link">Prof. Sangwan</a></td>
                                            <td>Los Angeles, US</td>
                                            <td>Recipes 451</td>
                                            <td><span class="label label-info">Staff</span> </td>
                                            <td>
                                                <select class="custom-select">
                                                    <option value="1">Modulator</option>
                                                    <option value="2">Admin</option>
                                                    <option value="3">Staff</option>
                                                    <option value="4">User</option>
                                                    <option value="5">General</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="checkbox checkbox-info">
                                                    <input id="c4" type="checkbox">
                                                    <label for="c4"></label>
                                                </div>
                                            </td>
                                            <td><a href="javascript:void(0);" class="text-link">Jeffery Brown</a></td>
                                            <td>Houston, US</td>
                                            <td>Recipes 978</td>
                                            <td><span class="label label-danger">User</span> </td>
                                            <td>
                                                <select class="custom-select">
                                                    <option value="1">Modulator</option>
                                                    <option value="2">Admin</option>
                                                    <option value="3">Staff</option>
                                                    <option value="4">User</option>
                                                    <option value="5">General</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="checkbox checkbox-info">
                                                    <input id="c5" type="checkbox">
                                                    <label for="c5"></label>
                                                </div>
                                            </td>
                                            <td><a href="javascript:void(0);" class="text-link">Elliot Dugteren</a></td>
                                            <td>San Antonio, US</td>
                                            <td>Recipes 34</td>
                                            <td><span class="label label-warning">General</span> </td>
                                            <td>
                                                <select class="custom-select">
                                                    <option value="1">Modulator</option>
                                                    <option value="2">Admin</option>
                                                    <option value="3">Staff</option>
                                                    <option value="4">User</option>
                                                    <option value="5">General</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="checkbox checkbox-info">
                                                    <input id="c6" type="checkbox">
                                                    <label for="c6"></label>
                                                </div>
                                            </td>
                                            <td><a href="javascript:void(0);" class="text-link">Sergio Milardovich</a></td>
                                            <td>Jacksonville, US</td>
                                            <td>Recipes 31</td>
                                            <td><span class="label label-primary">Partial</span> </td>
                                            <td>
                                                <select class="custom-select">
                                                    <option value="1">Modulator</option>
                                                    <option value="2">Admin</option>
                                                    <option value="3">Staff</option>
                                                    <option value="4">User</option>
                                                    <option value="5">General</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <ul class="pagination">
                                <li class="disabled"> <a href="#">1</a> </li>
                                <li class="active"> <a href="#">2</a> </li>
                                <li> <a href="#">3</a> </li>
                                <li> <a href="#">4</a> </li>
                                <li> <a href="#">5</a> </li>
                            </ul>
                            <a href="javascript:void(0);" class="btn btn-success pull-right m-t-10 font-20">+</a>
                        </div>
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
                    <div class="col-md-4" style="display:none;">
                        <div class="white-box chat-widget">
                            <a href="javascript:void(0);" class="pull-right"><i class="icon-settings"></i></a>
                            <h4 class="box-title">Chat</h4>
                            <ul class="chat-list slimscroll" style="overflow: hidden;" tabindex="5005">
                                <li>
                                    <div class="chat-image"> <img alt="male" src="/images/users/avatar2.jpg"> </div>
                                    <div class="chat-body">
                                        <div class="chat-text">
                                            <p><span class="font-semibold">Prof. Sangwan</span> Hey Daniel, This is just a sample chat. </p>
                                        </div>
                                        <span>2 Min ago</span>
                                    </div>
                                </li>
                                <li class="odd">
                                    <div class="chat-body">
                                        <div class="chat-text">
                                            <p> buddy </p>
                                        </div>
                                        <span>2 Min ago</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="chat-image"> <img alt="male" src="/images/users/avatar2.jpg"> </div>
                                    <div class="chat-body">
                                        <div class="chat-text">
                                            <p><span class="font-semibold">Prof. Sangwan</span> Bye now. </p>
                                        </div>
                                        <span>1 Min ago</span>
                                    </div>
                                </li>
                                <li class="odd">
                                    <div class="chat-body">
                                        <div class="chat-text">
                                            <p> We have been busy all the day to make your website proposal and finally came with the super excited offer. </p>
                                        </div>
                                        <span>5 Sec ago</span>
                                    </div>
                                </li>
                            </ul>
                            <div class="chat-send">
                                <input type="text" class="form-control" placeholder="Write your message">
                                <i class="fa fa-camera"></i>
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
