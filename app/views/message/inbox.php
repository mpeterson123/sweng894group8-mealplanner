<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                             Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// Inbox (message listing)
///////////////////////////////////////////////////////////////////////////////
require_once __DIR__.'/../../../vendor/autoload.php';
require_once( $_SERVER['DOCUMENT_ROOT'] . '/../app/views/modules/main.mod.php' );

use Base\Helpers\Session;

///////////////////////////////////////////////////////////////////////////////
// Externals
///////////////////////////////////////////////////////////////////////////////
$Show      = $_REQUEST['show'] ?? $data['displayType'] ?? NULL;
$TrashIt   = $_REQUEST['trashit'] ?? $data['input']['trashit'] ?? NULL;

///////////////////////////////////////////////////////////////////////////////
// Sub Title
///////////////////////////////////////////////////////////////////////////////
if ($Show == _DISPLAY_SENT_)
{
    $SUBTITLE = 'Outbox';
}
else
{
    $SUBTITLE = 'Inbox';
}

///////////////////////////////////////////////////////////////////////////////
// Plugins
///////////////////////////////////////////////////////////////////////////////
$PLUGIN_SLIMSCROLL  = TRUE;
$PLUGIN_WAVES       = TRUE;
$PLUGIN_DATATABLES  = TRUE;
$PLUGIN_SIDEBARMENU = TRUE;
$PLUGIN_EXPORT      = TRUE;

///////////////////////////////////////////////////////////////////////////////
// Messages
///////////////////////////////////////////////////////////////////////////////
$User = $data['user'];

// Star Message
if ($_REQUEST['s'] ?? FALSE)
{
    sqlQuery("UPDATE messages SET starred = TRUE WHERE id = {$_REQUEST['s']}");
}

// Unstar Message
if ($_REQUEST['u'] ?? FALSE)
{
    sqlQuery("UPDATE messages SET starred = FALSE WHERE id = {$_REQUEST['u']}");
}

// Trash a Message
if ($TrashIt)
{
    // We have a message we've been requested to trash
    sqlQuery("UPDATE messages SET trash = TRUE WHERE id = {$TrashIt}");
}

// Messages
if ($Show == _DISPLAY_TRASH_)
{
    $Messages = sqlRequest("SELECT *, TIME_FORMAT(timesent, '%I:%m %p') AS timesent2, DATE_FORMAT(timesent, '%b %D') AS timesent3 FROM messages WHERE recipientid = {$User->getId()} AND trash IS TRUE");
}
else if ($Show == _DISPLAY_SENT_)
{
    $Messages = sqlRequest("SELECT *, TIME_FORMAT(timesent, '%I:%m %p') AS timesent2, DATE_FORMAT(timesent, '%b %D') AS timesent3 FROM messages WHERE senderid = {$User->getId()} AND trash IS NOT TRUE");
}
else
{
    $Messages = sqlRequest("SELECT *, TIME_FORMAT(timesent, '%I:%m %p') AS timesent2, DATE_FORMAT(timesent, '%b %D') AS timesent3 FROM messages WHERE recipientid = {$User->getId()} AND trash IS NOT TRUE");
}

// Number of Starred Messages
$NumStarred = sqlRequest("SELECT COUNT(messages.id) AS totalnum FROM messages WHERE starred IS TRUE AND trash IS NOT TRUE AND recipientid = {$User->getId()}")[0]['totalnum'];

// Number of Trashed Messages
$NumTrash = sqlRequest("SELECT COUNT(messages.id) AS totalnum FROM messages WHERE trash IS TRUE AND recipientid = {$User->getId()}")[0]['totalnum'];

// Number of Unread Messages
$NumUnread  = sqlRequest("SELECT COUNT(messages.id) AS totalnum FROM messages WHERE viewed IS FALSE AND recipientid = {$User->getId()}")[0]['totalnum'];

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
                                    <div> <a href="/Messages/compose/" class="btn btn-custom btn-block waves-effect waves-light">Compose</a>
                                        <div class="list-group mail-list m-t-20"> <a href="/Messages/inbox/" class="list-group-item <?php if (!$Show || ($Show == _DISPLAY_NORMAL_)) { ?>active<?php } ?>">Inbox <span class="label label-rouded label-success pull-right"><?php echo $NumUnread; ?></span></a> <a href="/Messages/starred/" class="list-group-item <?php if (($Show == _DISPLAY_STARS_)) { ?>active<?php } ?>">Starred <span class="label label-rounded label-warning pull-right"><?php echo $NumStarred; ?></span></a> <a href="/Messages/outbox/" class="list-group-item <?php if ($Show == _DISPLAY_SENT_) { ?>active<?php } ?>">Sent Mail</a> <a href="/Messages/trash/" class="list-group-item <?php if (($Show == _DISPLAY_TRASH_)) { ?>active<?php } ?>">Trash <span class="label label-rouded label-danger pull-right"><?php echo number_format($NumTrash); ?></span></a> </div>
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
                                                                    <li><a href="/Messages/read/">Read</a></li>
                                                                    <li><a href="/Messages/unread/">Unread</a></li>
                                                                    <li class="divider"></li>
                                                                    <li><a href="/Messages/starred/">Starred</a></li>
                                                                </ul>
                                                            </div>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-default waves-effect waves-light  dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> <i class="fa fa-refresh"></i> </button>
                                                            </div>
                                                        </th>
<?php if (count($Messages) > 50) { ?>
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
                  if (($Show == _DISPLAY_STARS_) && (!$message['starred']))
                  {
                      continue;
                  }
                  if (($Show == _DISPLAY_READ_) && (!$message['viewed']))
                  {
                      continue;
                  }
                  if (($Show == _DISPLAY_NEW_) && ($message['viewed']))
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
<?php if ($Show != _DISPLAY_SENT_) { ?>
                                                        <td class="hidden-xs"><a href="?<?php if ($message['starred']) { ?>u<?php } else { ?>s<?php } ?>=<?php echo $message['id']; ?>"><i class="fa fa-star<?php if (!$message['starred']) { ?>-o<?php } ?>"></i></a></td>
<?php } ?>
                                                        <td class="hidden-xs"><?php $userId = NULL; if ($Show == _DISPLAY_SENT_) { $userId = $message['recipientid']; } else { $userId = $message['senderid']; } echo sqlRequest("SELECT CONCAT(namefirst, ' ', namelast) AS name FROM users WHERE id = {$userId}")[0]['name']; ?></td>
                                                        <td class="max-texts"> <a href="/Messages/open/<?php echo $message['id']; ?>" /><?php if (!$message['viewed']) { ?><span class="label label-success m-r-10">New</span><?php } ?> <?php echo substr($message['message'], 0, 60); ?></td>
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

<?php if (count($Messages) > 50) { ?>
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
