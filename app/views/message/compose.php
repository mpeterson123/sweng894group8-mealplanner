<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                             Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// Compose (send message)
///////////////////////////////////////////////////////////////////////////////
require_once __DIR__.'/../../../vendor/autoload.php';
require_once( $_SERVER['DOCUMENT_ROOT'] . '/../app/views/modules/main.mod.php' );

use Base\Helpers\Session;

///////////////////////////////////////////////////////////////////////////////
// Externals
///////////////////////////////////////////////////////////////////////////////
$Recipient = $_REQUEST['r']        ?? $_REQUEST['recipient']     ?? $data['targetID'] ?? $data['input']['recipient'] ?? NULL;
$Message   = $_REQUEST['message']  ?? $data['input']['message']  ?? NULL;
$Composed  = $_REQUEST['composed'] ?? $data['input']['composed'] ?? NULL;

///////////////////////////////////////////////////////////////////////////////
// Sub Title
///////////////////////////////////////////////////////////////////////////////
if ($data['targetID'] ?? NULL)
{
    $SUBTITLE = 'Replying to Message';
}
else
{
    $SUBTITLE = 'Send a Message';
}

///////////////////////////////////////////////////////////////////////////////
// Plugins
///////////////////////////////////////////////////////////////////////////////
$PLUGIN_SLIMSCROLL  = TRUE;
$PLUGIN_WAVES       = TRUE;
$PLUGIN_DATATABLES  = TRUE;
$PLUGIN_SIDEBARMENU = TRUE;
$PLUGIN_SELECTPICKER= TRUE;

///////////////////////////////////////////////////////////////////////////////
// Misc
///////////////////////////////////////////////////////////////////////////////
$User = $data['user'];

///////////////////////////////////////////////////////////////////////////////
// Friends
///////////////////////////////////////////////////////////////////////////////
$SubQuery       = "SELECT userid AS theid FROM friends WHERE userid = {$User->getId()} OR friendid = {$User->getId()} UNION ALL SELECT friendid AS theid FROM friends WHERE userid = {$User->getId()} OR friendid = {$User->getId()}";
//$Friends        = sqlRequest("SELECT * FROM users WHERE id = ANY ({$SubQuery}) AND id != {$User->getId()}");
$Friends        = sqlRequest("SELECT * FROM users WHERE id != {$User->getId()}");

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

///////////////////////////////////////////////////////////////////////////////
// Composed a message?
///////////////////////////////////////////////////////////////////////////////
if ($Composed)
{
    ///////////////////////////////////////////////////////////////////////////
    // Check for a Recipient and Whether we have a Message or Not
    ///////////////////////////////////////////////////////////////////////////
    if ($Recipient && $Message)
    {
        // Execute query to sent message
        sqlQuery("INSERT INTO messages (senderid, recipientid, message) VALUES({$User->getId()}, {$Recipient}, '{$Message}')");
?>
<script>
    document.location = '/Messages/outbox/'; // outbox/?s=1
</script>
<?php
    exit(1);
    }
}

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
                                    <div>
                                        <div class="list-group mail-list m-t-20"> <a href="/Messages/inbox/" class="list-group-item ">Inbox <span class="label label-rouded label-success pull-right"><?php echo $NumUnread; ?></span></a> <a href="/Messages/inbox/?show=<?php echo $ShowStars; ?>" class="list-group-item ">Starred <span class="label label-rounded label-warning pull-right"><?php echo $NumStarred; ?></span></a> <a href="/Messages/outbox/" class="list-group-item">Sent Mail</a> <a href="/Messages/inbox/?show=<?php echo $ShowTrash; ?>" class="list-group-item ">Trash <span class="label label-rouded label-danger pull-right"><?php echo number_format($NumTrash); ?></span></a> </div>
                                        <h3 class="panel-title m-t-40 m-b-0">Labels</h3>
                                        <hr class="m-t-5">
                                        <div class="list-group b-0 mail-list"> <a href="javascript:void(0);" class="list-group-item"><span class="fa fa-circle text-success m-r-10"></span>New</a> <a href="javascript:void(0);" class="list-group-item"><span class="fa fa-circle text-warning m-r-10"></span>Starred</a> <a href="javascript:void(0);" class="list-group-item"><span class="fa fa-circle text-danger m-r-10"></span>Deleted</a> </div>
                                    </div>
                                </div>

                                <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12 mail_listing">
                                    <h3 class="box-title">Compose New Message</h3>
                                    <form method="post" action="/Messages/compose/" id="composeform" name="composeform">
                                    <input type="hidden" name="composed" value="1">
<?php // Check for pre-selected recipient
      if ($Recipient) { ?>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="To: <?php echo sqlRequestByID('users', $Recipient, 'namefirst'); ?>" readonly="readonly"> </div>
                                        <input type="hidden" name="recipient" value="<?php echo $Recipient; ?>">
<?php } else { ?>
                                    <div class="form-group">
                                        <select class="selectpicker m-b-20 m-r-10" data-style="btn-primary btn-outline" name="recipient">
                                            <option>Select a Recipient..</option>
<?php     // Loop through list of users available to send messages to
          foreach ($Friends as $friend) { ?>
                                            <option value="<?php echo $friend['id']; ?>"><?php echo "{$friend['namefirst']} {$friend['namelast']}"; ?></option>
<?php     } ?>
                                        </select>
                                    </div>
<?php } ?>
                                    <div class="form-group">
                                        <textarea class="textarea_editor form-control" rows="15" name="message" placeholder="Enter text ..."></textarea>
                                    </div>
                                    <hr>
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>
                                    <button class="btn btn-default" type="reset"><i class="fa fa-times"></i> Discard</button>
                                    </form>
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
