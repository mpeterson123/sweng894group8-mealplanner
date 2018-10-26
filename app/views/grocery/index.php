<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                             Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// Food (listing)
///////////////////////////////////////////////////////////////////////////////
require_once __DIR__.'/../../../vendor/autoload.php';
require_once( $_SERVER['DOCUMENT_ROOT'] . '/../app/views/modules/main.mod.php' );

// Sub Title
$SUBTITLE = 'Grocery';

// Plugins
$PLUGIN_SLIMSCROLL  = TRUE;
$PLUGIN_WAVES       = TRUE;
$PLUGIN_DATATABLES  = TRUE;
$PLUGIN_SIDEBARMENU = TRUE;
$PLUGIN_EXPORT      = TRUE;

// Food
$User['id'] = 1;  // Default to 1 for testing purposes
$Recipes = sqlRequestWhere('recipe', 'userid', $User['id']);
$Ingredents = sqlRequestWhere('ingredent', 'recipeid', $Recipes['id']);
$Foods = sqlRequestWhere('food', 'foodid', $Ingredents['id']);

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
                            <h3 class="box-title m-b-0">Food Directory</h3>
                            <p class="text-muted m-b-30">Export data to Copy, CSV, Excel, PDF & Print</p>
                            <div class="table-responsive">
                                <table id="export-table" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Food</th>
                                            <th>Unit Cost</th>
                                            <th>Quantity</th>
                                            <th>Total Cost</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Food</th>
                                            <th>Unit Cost</th>
                                            <th>Quantity</th>
                                            <th>Total Cost</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>

                                    <?php
                                    foreach ($Ingredents as $ingredent) {
                                        if ($ingredent['quantity'] > $food['stock']) {
                                          echo $food['id'];
                                          echo $food['unitcost'];
                                          echo $food['name'];
                                          echo ($food['unitcost'] * ($ingredent['quantity']) - $food['stock']);
                                        }
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
                            <a href="/foods/add/" class="btn btn-success m-t-15">+ Add Food Item</a>
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
