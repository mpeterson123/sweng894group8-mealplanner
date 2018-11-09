<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                             Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// Meals (listing)
///////////////////////////////////////////////////////////////////////////////
require_once __DIR__.'/../../../vendor/autoload.php';
require_once( $_SERVER['DOCUMENT_ROOT'] . '/../app/views/modules/main.mod.php' );

use Base\Helpers\Session;

// Sub Title
$SUBTITLE = 'Meal Plan';


// Plugins
$PLUGIN_SLIMSCROLL  = TRUE;
$PLUGIN_WAVES       = TRUE;
$PLUGIN_DATATABLES  = TRUE;
$PLUGIN_SIDEBARMENU = TRUE;
$PLUGIN_EXPORT      = TRUE;
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

                <?php (new Session())->renderMessage(); ?>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="white-box">
                            <h3 class="box-title m-b-0">Meal Plan</h3>
                            <p class="text-muted m-b-30">Export data to Copy, CSV, Excel, PDF & Print</p>
                            <div class="table-responsive">
                                <table id="export-table" class="table table-striped" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Recipe</th>
                                            <th>Scale</th>
                                            <th>Date Added</th>
                                            <th>Complete</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr class="column-search">
                                            <th><input class="column-search-bar form-control" type="text" placeholder="Search"/></th>
                                            <th><input class="column-search-bar form-control" type="text" placeholder="Search"/></th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                            if($data['meals']){
                                                foreach ($data['meals'] as $meal) { ?>
                                                <tr>
                                                    <td><a href="/meal/edit/<?php echo $meal['id']; ?>"><?php echo $meal->getDate(); ?></a></td>
                                                    <td><?php echo $meal->getRecipe()->getName(); ?></td>
                                                    <td><?php echo $meal->getScaleFactor(); ?></td>
                                                    <td><?php echo $meal->getAddedDate(); ?></td>
                                                    <td><?php echo $meal->isComplete(); ?></td>
                                                </tr>
                                                <?php
                                                }
                                            }
                                            ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="white-box">
                            <h3 class="box-title m-b-0">Actions</h3>
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
