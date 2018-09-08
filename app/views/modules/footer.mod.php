<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                             Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// HTML Footer Module
///////////////////////////////////////////////////////////////////////////////
?>
    <!-- ==============================
        Required JS Files
    =============================== -->
    <!-- ===== jQuery ===== -->
    <script src="/plugins/components/jquery/dist/jquery.min.js"></script>
    <!-- ===== Bootstrap JavaScript ===== -->
    <script src="/bootstrap/dist/js/bootstrap.min.js"></script>
<?php if ($PLUGIN_SIDEBARMENU) { ?>
    <!-- ===== Menu Plugin JavaScript ===== -->
    <script src="/js/sidebarmenu.js"></script>
<?php } ?>
<?php if ($PLUGIN_SLIMSCROLL) { ?>
    <!-- ===== Slimscroll JavaScript ===== -->
    <script src="/js/jquery.slimscroll.js"></script>
<?php } ?>
<?php if ($PLUGIN_WAVES) { ?>
    <!-- ===== Wave Effects JavaScript ===== -->
    <script src="/js/waves.js"></script>
<?php } ?>
    <!-- ===== Custom JavaScript ===== -->
    <script src="/js/custom.js"></script>
    <!-- ===== Plugin JS ===== -->
<?php if ($PLUGIN_CHARTIST) { ?>
    <script src="/plugins/components/chartist-js/dist/chartist.min.js"></script>
    <script src="/plugins/components/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js"></script>
    <script src="/plugins/components/sparkline/jquery.sparkline.min.js"></script>
    <script src="/plugins/components/sparkline/jquery.charts-sparkline.js"></script>
    <script src="/plugins/components/knob/jquery.knob.js"></script>
    <script src="/plugins/components/easypiechart/dist/jquery.easypiechart.min.js"></script>
    <script src="/js/db1.js"></script>
<?php } ?>
<?php if ($PLUGIN_DATATABLES) { ?>
    <script src="/plugins/components/datatables/jquery.dataTables.min.js"></script>
<?php } ?>
<?php if ($PLUGIN_EXPORT) { ?>
    <!-- start - This is for export functionality only -->
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <!-- end - This is for export functionality only -->
    <script>

    $('#export-table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
    </script>
<?php } ?>
    <!-- ===== Style Switcher JS ===== -->
    <script src="/plugins/components/styleswitcher/jQuery.style.switcher.js"></script>
