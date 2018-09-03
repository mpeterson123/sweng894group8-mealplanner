<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                             Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// Food (listing)
///////////////////////////////////////////////////////////////////////////////
require_once( $_SERVER['DOCUMENT_ROOT'] . '/modules/main.mod.php' );

// Sub Title
$SUBTITLE = 'Food';

// Plugins
$PLUGIN_SLIMSCROLL  = TRUE;
$PLUGIN_WAVES       = TRUE;
$PLUGIN_DATATABLES  = TRUE;
$PLUGIN_SIDEBARMENU = TRUE;
$PLUGIN_EXPORT      = TRUE;

// Food
$User['id'] = 1;  // Default to 1 for testing purposes
$Foods = sqlRequestWhere('food', 'userid', $User['id']);

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
                                <table id="export-table" class="display nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Unit Cost</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Name</th>
                                            <th>Unit Cost</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
<?php foreach ($Foods as $food) { ?>
                                        <tr>
                                            <td><a href="/foods/food/<?php echo $food['id']; ?>"><?php echo $food['name']; ?></a></td>
                                            <td>$<?php echo $food['unitcost']; ?></td>
                                        </tr>
<?php } ?>
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
