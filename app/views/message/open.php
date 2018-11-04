<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                             Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// Inbox (message)
///////////////////////////////////////////////////////////////////////////////
require_once __DIR__.'/../../../vendor/autoload.php';
require_once( $_SERVER['DOCUMENT_ROOT'] . '/../app/views/modules/main.mod.php' );

///////////////////////////////////////////////////////////////////////////////
// Session Management
///////////////////////////////////////////////////////////////////////////////
use Base\Helpers\Session;

///////////////////////////////////////////////////////////////////////////////
// Externals
///////////////////////////////////////////////////////////////////////////////
$MessageID = $_REQUEST['messageid'] ?? $data['messageID'] ?? substr($_SERVER['REDIRECT_URL'], strrpos($_SERVER['REDIRECT_URL'], '/')+1) ?? 0;
$User = $data['user'];

///////////////////////////////////////////////////////////////////////////////
// Globals
///////////////////////////////////////////////////////////////////////////////
$UserIsRecipient = FALSE;
$UserIsSender    = FALSE;

///////////////////////////////////////////////////////////////////////////////
// Security Check! (START)
///////////////////////////////////////////////////////////////////////////////
if (isset(sqlRequest("SELECT id FROM messages WHERE id = {$MessageID} AND recipientid = {$User->getId()}")[0]['id']))
{
    $UserIsRecipient = TRUE;
}
if (isset(sqlRequest("SELECT id FROM messages WHERE id = {$MessageID} AND senderid = {$User->getId()}")[0]['id']))
{
    $UserIsSender = TRUE;
}

if (!$MessageID || (!$UserIsRecipient && !$UserIsSender))
{
?>
<script>
    document.location = '/errors/403/';
</script>
<?php
    exit(1);
}
///////////////////////////////////////////////////////////////////////////////
// Security Check! (END)
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// Sub Title
///////////////////////////////////////////////////////////////////////////////
$SUBTITLE = 'Inbox Message';

///////////////////////////////////////////////////////////////////////////////
// Plugins
///////////////////////////////////////////////////////////////////////////////
$PLUGIN_SLIMSCROLL  = TRUE;
$PLUGIN_WAVES       = TRUE;
$PLUGIN_DATATABLES  = TRUE;
$PLUGIN_SIDEBARMENU = TRUE;
$PLUGIN_EXPORT      = TRUE;

///////////////////////////////////////////////////////////////////////////////
// Message
///////////////////////////////////////////////////////////////////////////////
$Message = sqlRequestArrayByID('messages', $MessageID, "*, DATE_FORMAT(timesent, '%a, %b %D %I:%m %p') AS timesent2");

///////////////////////////////////////////////////////////////////////////////
// Set to Viewed (user has seen the message)
///////////////////////////////////////////////////////////////////////////////
sqlQuery("UPDATE messages SET viewed = TRUE WHERE id = {$Message['id']}");

///////////////////////////////////////////////////////////////////////////////
// Display Type Check(s)
///////////////////////////////////////////////////////////////////////////////
$Trash = $Message['trash'] ?? NULL;

///////////////////////////////////////////////////////////////////////////////
// Number of Starred Messages
///////////////////////////////////////////////////////////////////////////////
$NumStarred = sqlRequest("SELECT COUNT(messages.id) AS totalnum FROM messages WHERE starred IS TRUE AND trash IS NOT TRUE AND recipientid = {$User->getId()}")[0]['totalnum'];

///////////////////////////////////////////////////////////////////////////////
// Number of Trashed Messages
///////////////////////////////////////////////////////////////////////////////
$NumTrash = sqlRequest("SELECT COUNT(messages.id) AS totalnum FROM messages WHERE trash IS TRUE AND recipientid = {$User->getId()}")[0]['totalnum'];

///////////////////////////////////////////////////////////////////////////////
// Number of Unread Messages
///////////////////////////////////////////////////////////////////////////////
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
                <div class="row">
                    <div class="col-md-12">
                        <div class="white-box">
                            <!-- row -->
                            <div class="row">
                                <div class="col-lg-2 col-md-3  col-sm-12 col-xs-12 inbox-panel">
                                    <div> <!--<a href="/Messages/compose/" class="btn btn-custom btn-block waves-effect waves-light">Compose</a>-->
                                        <div class="list-group mail-list m-t-20"> <a href="/Messages/inbox/" class="list-group-item <?php if ($UserIsRecipient && !$Trash) { echo 'active'; } ?>">Inbox <span class="label label-rouded label-success pull-right"><?php echo $NumUnread; ?></span></a> <a href="/Messages/starred/" class="list-group-item ">Starred <span class="label label-rounded label-warning pull-right"><?php echo $NumStarred; ?></span></a> <a href="/Messages/outbox/" class="list-group-item <?php if ($UserIsSender) { echo 'active'; } ?>">Sent Mail</a> <a href="/Messages/trash/" class="list-group-item <?php if ($Trash) { echo 'active'; } ?>">Trash <span class="label label-rouded label-danger pull-right"><?php echo $NumTrash; ?></span></a> </div>
                                        <h3 class="panel-title m-t-40 m-b-0">Labels</h3>
                                        <hr class="m-t-5">
                                        <div class="list-group b-0 mail-list"> <a href="javascript:void(0);" class="list-group-item"><span class="fa fa-circle text-success m-r-10"></span>New</a> <a href="javascript:void(0);" class="list-group-item"><span class="fa fa-circle text-warning m-r-10"></span>Starred</a> <a href="javascript:void(0);" class="list-group-item"><span class="fa fa-circle text-danger m-r-10"></span>Deleted</a> </div>
                                    </div>
                                </div>
                                <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12 mail_listing">
                                    <div class="media m-b-30 p-t-20">
                                        <hr>
                                        <a class="pull-left" href="#"> <img class="media-object thumb-sm img-circle" src="/images/users/<?php echo $Message['senderid']; ?>.jpg" alt=""> </a>
                                        <div class="media-body"> <span class="media-meta pull-right"><?php echo $Message['timesent2']; ?></span><br/><span class="media-meta pull-right"><?php echo $Message['timesent']; ?></span>
                                            <h4 class="text-danger m-0">To: <?php echo sqlRequest("SELECT CONCAT(namefirst, ' ', namelast) AS name FROM users WHERE id = {$Message['recipientid']}")[0]['name']; ?></h4> <small class="text-muted">From: <?php echo sqlRequest("SELECT CONCAT(namefirst, ' ', namelast) AS name FROM users WHERE id = {$Message['senderid']}")[0]['name']; ?></small> </div>
                                    </div>
                                    <p><?php echo $Message['message']; ?></p>
                                    <hr>
<?php if ($UserIsRecipient) { ?>
                                    <div class="b-all p-20">
                                        <p class="p-b-20">click here to <a href="/Messages/compose/<?php echo $Message['senderid']; ?>">Reply</a> </p>
                                    </div>
<?php } ?>
<?php if ($UserIsRecipient && (!$Message['trash'] ?? NULL) ) { ?>
                                    <hr>
                                    <form name="trashform" id="trashform" method="post" action="/Messages/trash/">
                                        <input type="hidden" name="trashit" value="<?php echo $Message['id']; ?>">
                                        <button type="submit" class="btn btn-danger"><i class="fa fa-envelope-o"></i> Trash</button>
                                    </form>
<?php } ?>
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
