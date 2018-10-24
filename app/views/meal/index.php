<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                             Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// Meal (listing)
///////////////////////////////////////////////////////////////////////////////
require_once __DIR__.'/../../../vendor/autoload.php';
require_once( $_SERVER['DOCUMENT_ROOT'] . '/../app/views/modules/main.mod.php' );

use Base\Helpers\Session;

// Sub Title
$SUBTITLE = 'Meal';

// Plugins
$PLUGIN_SLIMSCROLL  = TRUE;
$PLUGIN_WAVES       = TRUE;
$PLUGIN_DATATABLES  = TRUE;
$PLUGIN_SIDEBARMENU = TRUE;
$PLUGIN_EXPORT      = TRUE;

// Meal
$User['id'] = 1;  // Default to 1 for testing purposes
$MealPlan = sqlRequestWhere('MealPlan', 'userid', $User['id']);
$Meals = sqlRequestWhere('Meal', 'planid', $MealPlan['id']);
$Recipes = sqlRequestWhere('Recipe', 'id', $Meals['recipe']);

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
                    <div class="col-sm-4">
                        <div class="white-box">
                            <h3 class="box-title m-b-0">Meal Plan Schedule</h3>
                            <p class="text-muted m-b-30">Export data to Copy, CSV, Excel, PDF & Print</p>
                            <div class="table-responsive">
                                <table id="export-table" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Recipe</th>
                                            <th>Scale Factor</th>
                                            <th>Complete</th>
                                            <th>Date Added</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                          <th>Date</th>
                                          <th>Recipe</th>
                                          <th>Scale Factor</th>
                                          <th>Complete</th>
                                          <th>Date Added</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>

                        <?php
                        foreach ($Meals as $meal) {
                              echo $meal['date'];
                              $Temp = sqlRequestWhere($Recipes, 'id', $meal['recipe']);
                              echo $Temp['name'];
                              echo $meal['scaleFactor'];
                              echo $meal['isComplete'];
                              echo $meal['addedDate'];
                            }
                        
                        ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="white-box">
                            <h3 class="box-title m-b-0">Options</h3>
                            <a href="/meal/create/" class="btn btn-success m-t-15">+ Create Meal</a>
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
