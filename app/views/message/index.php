<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                             Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// Inbox (message listing)
///////////////////////////////////////////////////////////////////////////////
require_once __DIR__.'/../../../vendor/autoload.php';
require_once( $_SERVER['DOCUMENT_ROOT'] . '/../app/views/modules/main.mod.php' );

use Base\Helpers\Session;

// Sub Title
$SUBTITLE = 'Inbox';

// Externals
$Show      = $_REQUEST['show'] ?? NULL;

// Definitions
$ShowNormal = 1;
$ShowStars  = 2;
$ShowRead   = 3;
$ShowNew    = 4;
$ShowTrash  = 5;

// Plugins
$PLUGIN_SLIMSCROLL  = TRUE;
$PLUGIN_WAVES       = TRUE;
$PLUGIN_DATATABLES  = TRUE;
$PLUGIN_SIDEBARMENU = TRUE;
$PLUGIN_EXPORT      = TRUE;

// Messages
$Messages = $data['messages'];
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

                <?php $data['session']->renderMessage(); ?>

                <div class="row">
                    <div class="col-md-12">
                        <div class="white-box">
                            <!-- row -->
                            <div class="row">
                                <div class="col-lg-2 col-md-3  col-sm-12 col-xs-12 inbox-panel">
                                    <div> <a href="/compose/" class="btn btn-custom btn-block waves-effect waves-light">Compose</a>
                                        <div class="list-group mail-list m-t-20"> <a href="/inbox/" class="list-group-item <?php if (!$Show || ($Show == $ShowNormal)) { ?>active<?php } ?>">Inbox <span class="label label-rouded label-success pull-right"><?php echo $NumUnread; ?></span></a> <a href="/inbox/?show=<?php echo $ShowStars; ?>" class="list-group-item <?php if (($Show == $ShowStars)) { ?>active<?php } ?>">Starred <span class="label label-rounded label-warning pull-right"><?php echo $NumStarred; ?></span></a> <a href="/outbox/" class="list-group-item">Sent Mail</a> <a href="/inbox/?show=<?php echo $ShowTrash; ?>" class="list-group-item <?php if (($Show == $ShowTrash)) { ?>active<?php } ?>">Trash <span class="label label-rouded label-danger pull-right"><?php echo number_format($NumTrash); ?></span></a> </div>
                                        <h3 class="panel-title m-t-40 m-b-0">Labels</h3>
                                        <hr class="m-t-5">
                                        <div class="list-group b-0 mail-list"> <a href="javascript:void(0);" class="list-group-item"><span class="fa fa-circle text-success m-r-10"></span>New</a> <a href="javascript:void(0);" class="list-group-item"><span class="fa fa-circle text-warning m-r-10"></span>Starred</a> <a href="javascript:void(0);" class="list-group-item"><span class="fa fa-circle text-danger m-r-10"></span>Deleted</a> </div>
                                    </div>
                                </div>
                                <div class="col-lg-10 col-md-9 col-sm-12 col-xs-12 mail_listing">
                                    <div class="inbox-center">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th width="30">
                                                            <div class="checkbox m-t-0 m-b-0 ">
                                                                <input id="checkbox0" type="checkbox" class="checkbox-toggle" value="check all">
                                                                <label for="checkbox0"></label>
                                                            </div>
                                                        </th>
                                                        <th colspan="4">
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-primary dropdown-toggle waves-effect waves-light m-r-5" data-toggle="dropdown" aria-expanded="false"> Filter <b class="caret"></b> </button>
                                                                <ul class="dropdown-menu" role="menu">
                                                                    <li><a href="/inbox/?show=<?php echo $ShowRead; ?>">Read</a></li>
                                                                    <li><a href="/inbox/?show=<?php echo $ShowNew; ?>">Unread</a></li>
                                                                    <li class="divider"></li>
                                                                    <li><a href="/inbox/?show=<?php echo $ShowStars; ?>">Starred</a></li>
                                                                </ul>
                                                            </div>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-default waves-effect waves-light  dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> <i class="fa fa-refresh"></i> </button>
                                                            </div>
                                                        </th>
<?php if (count($Message) > 50) { ?>
                                                        <th class="hidden-xs" width="100">
                                                            <div class="btn-group pull-right">
                                                                <button type="button" class="btn btn-default waves-effect"><i class="fa fa-chevron-left"></i></button>
                                                                <button type="button" class="btn btn-default waves-effect"><i class="fa fa-chevron-right"></i></button>
                                                            </div>
                                                        </th>
<?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
<?php if ($Messages ?? FALSE)
      {
          $index = 0;

          foreach ($Messages as $message)
          {
              if ($Show)
              {
                  if (($Show == $ShowStars) && (!$message['starred']))
                  {
                      continue;
                  }
                  if (($Show == $ShowRead) && (!$message['viewed']))
                  {
                      continue;
                  }
                  if (($Show == $ShowNew) && ($message['viewed']))
                  {
                      continue;
                  }
              }

              $index++; ?>
                                                    <tr class="<?php if (!$message['viewed']) { ?>unread<?php } ?>">
                                                        <td>
                                                            <div class="checkbox m-t-0 m-b-0">
                                                                <input type="checkbox" id="ch<?php echo $index; ?>">
                                                                <label for="ch<?php echo $index; ?>"></label>
                                                            </div>
                                                        </td>
                                                        <td class="hidden-xs"><a href="?<?php if ($message['starred']) { ?>u<?php } else { ?>s<?php } ?>=<?php echo $message['id']; ?>"><i class="fa fa-star<?php if (!$message['starred']) { ?>-o<?php } ?>"></i></a></td>
                                                        <td class="hidden-xs"><?php echo sqlRequest("SELECT CONCAT(namefirst, ' ', namelast) AS name FROM users WHERE id = {$message['senderid']}")[0]['name']; ?></td>
                                                        <td class="max-texts"> <a href="/inbox/message/<?php echo $message['id']; ?>" /><?php if (!$message['viewed']) { ?><span class="label label-success m-r-10">New</span><?php } ?> <?php echo substr($message['message'], 0, 60); ?></td>
                                                        </td>
                                                        <td class="hidden-xs"><?php if ($message['attachment'] ?? FALSE) { ?><i class="fa fa-paperclip"></i><?php } ?></td>
                                                        <td class="text-right"> <?php if (date('M j', time()) == $message['timesent3']) { echo $message['timesent2']; } else { echo $message['timesent3']; } ?> </td>
                                                    </tr>
<?php } } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-7 m-t-20"> Showing <?php if (count($Messages)) { echo 1; } else { echo 0; } ?> - <?php echo number_format(count($Messages)); ?> </div>

<?php if (count($Message) > 50) { ?>
                                        <div class="col-xs-5 m-t-20">
                                            <div class="btn-group pull-right">
                                                <button type="button" class="btn btn-default waves-effect"><i class="fa fa-chevron-left"></i></button>
                                                <button type="button" class="btn btn-default waves-effect"><i class="fa fa-chevron-right"></i></button>
                                            </div>
                                        </div>
<?php } ?>
                                    </div>
                                </div>
                            </div>
                            <!-- /.row -->
                        </div>
                    </div>
                </div>
                <!-- /.row -->

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
