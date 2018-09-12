<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                             Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// HTML Header Module
///////////////////////////////////////////////////////////////////////////////
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon.png">
    <title><?php echo __SITENAME__; if (isset($SUBTITLE)) { echo " {$SUBTITLE}"; } ?></title>
    <!-- ===== Bootstrap CSS ===== -->
    <link href="<?php echo $_SERVER['DOCUMENT_ROOT'];?>/../vendor/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- ===== Plugin CSS ===== -->
<?php if ($PLUGIN_CHARTIST) { ?>
    <link href="<?php echo $_SERVER['DOCUMENT_ROOT'];?>/../vendor/chartist-js/dist/chartist.min.css" rel="stylesheet">
    <link href="<?php echo $_SERVER['DOCUMENT_ROOT'];?>/../vendor/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css" rel="stylesheet">
<?php } ?>
<?php if ($PLUGIN_DATATABLES) { ?>
    <link href="<?php echo $_SERVER['DOCUMENT_ROOT'];?>/../vendor/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<?php } ?>
    <!-- ===== Animation CSS ===== -->
    <link href="<?php echo $_SERVER['DOCUMENT_ROOT'];?>/css/animate.css" rel="stylesheet">
    <!-- ===== Custom CSS ===== -->
    <link href="<?php echo $_SERVER['DOCUMENT_ROOT'];?>/css/style.css" rel="stylesheet">
    <!-- ===== Color CSS ===== -->
    <link href="<?php echo $_SERVER['DOCUMENT_ROOT'];?>/css/colors/default.css" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
