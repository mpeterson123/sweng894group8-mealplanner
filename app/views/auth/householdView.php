<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                             Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// Food (listing)
///////////////////////////////////////////////////////////////////////////////
require_once __DIR__.'/../../../vendor/autoload.php';
require_once($_SERVER['DOCUMENT_ROOT'] . '/../app/views/modules/main.mod.php');

use Base\Helpers\Session;

// Sub Title
$SUBTITLE = 'Household';


// Plugins
$PLUGIN_SLIMSCROLL  = true;
$PLUGIN_WAVES       = true;
$PLUGIN_DATATABLES  = true;
$PLUGIN_SIDEBARMENU = true;
$PLUGIN_EXPORT      = true;
?>
<?php require_once(__HEADER__); ?>

<script type="text/javascript">
  var selectedMemberId = 0;
  function popupUser(open){
    if(open>0){
      document.getElementById('remUser').setAttribute("href","/Household/remove/<?php echo $data['hhId']; ?>/"+open);
      document.getElementById("confirmUserBox").style.display = "block";
      document.getElementById("overlay").style.display = "block";
    }
    else{
      document.getElementById("confirmUserBox").style.display = "none";
      document.getElementById("overlay").style.display = "none";
    }
  }
  function popupDelete(open){
    if(open>0){
      document.getElementById("confirmDeleteBox").style.display = "block";
      document.getElementById("overlay").style.display = "block";
    }
    else{
      document.getElementById("confirmDeleteBox").style.display = "none";
      document.getElementById("overlay").style.display = "none";
    }
  }
  function popupLeave(open){
    if(open>0){
      document.getElementById("confirmLeaveBox").style.display = "block";
      document.getElementById("overlay").style.display = "block";
    }
    else{
      document.getElementById("confirmLeaveBox").style.display = "none";
      document.getElementById("overlay").style.display = "none";
    }
  }
  function popupRename(open){
    if(open>0){
      document.getElementById("nameBox").style.display = "block";
      document.getElementById("overlay").style.display = "block";
    }
    else{
      document.getElementById("nameBox").style.display = "none";
      document.getElementById("overlay").style.display = "none";
    }
  }
</script>

<body class="mini-sidebar">
    <!-- ===== Main-Wrapper ===== -->
    <div id="wrapper">
        <div class="preloader">
            <div class="cssload-speeding-wheel"></div>
        </div>

<?php require_once(__NAVBAR__); ?>

<?php require_once(__SIDEBAR__); ?>

        <!-- ===== Page-Content ===== -->
        <div class="page-wrapper">

            <!-- ===== Page-Container ===== -->
            <div class="container-fluid">

                <?php $data['session']->renderMessage(); ?>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="white-box">
                          <h3 class="box-title m-b-0"><?php echo $data['name']; if ($data['isOwner']) {
    ?> <a href="javascript:void(0);" onclick="popupRename(1)" style="font-size:10px">Rename</a><?php
} ?></h3>
                          <div class="table-responsive">
                                <table class="display nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Members</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if ($data['members']) {
                                                foreach ($data['members'] as $m) {
                                                    ?>
                                                <tr>
                                                    <td><?php echo $m['name']; ?></td>
                                                    <?php
                                                    if ($data['owner'] == $m['username']) {
                                                        echo '<td style="color:gray;text-align:right;"><strong>Owner</strong></td>';
                                                    } elseif ($data['isOwner']) {
                                                        echo '<td style="text-align:right;"><a href="javascript:void(0);" onclick="popupUser('.$m['id'].')"><strong style="color:red;">x</strong></a></td>';
                                                    } ?>
                                                </tr>
                                                <?php
                                                }
                                            }
                                            ?>
                                    </tbody>
                                </table>
                            </div>
                            <br><br>
                            <?php
                            if ($data['isOwner']) {
                                echo '<a href="javascript:void(0);" onclick="popupDelete(1)">Delete Household</a>';
                            } else {
                                echo '<a href="javascript:void(0);" onclick="popupLeave(1)">Leave Household</a>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div id="overlay" style="top: 0%; left: 0%;position: absolute;z=2;display:none; width:100%;height:100%;background-color: gray;opacity:0.5;"></div>
                <div id="confirmUserBox" style="top: 35%; left: 30%;position: absolute;z=3;display:none; ">
                    <div class="white-box" style="line-height:25px;width:420px;height:200px;">
                      <h4>Are you sure you want to remove this user?</h4>
                      <p>This action cannot be undone.</p>
                      <div class="form-group pull-right">
                          <a class="btn btn-default" href="javascript:void(0);"  onclick="popupUser(0)">Cancel</a>
                          <a class="btn btn-danger" id="remUser" href="/Household/remove/">Remove</a>
                      </div>
                      <br>
                    </div>
                </div>
                <div id="confirmDeleteBox" style="top: 35%; left: 30%;position: absolute;z=3;display:none; ">
                  <div class="white-box" style="line-height:25px;width:420px;height:200px;">
                      <h4>Are you sure you want to delete this household?</h4>
                      <p>This action cannot be undone.</p>
                      <div class="form-group pull-right">
                          <a class="btn btn-default" href="javascript:void(0);"  onclick="popupDelete(0)">Cancel</a>
                          <a class="btn btn-danger" href="/Household/delete/<?php echo $data['hhId']; ?>">Delete</a>
                      </div>
                      <br>
                  </div>
                </div>
                <div id="confirmLeaveBox" style="top: 35%; left: 30%;position: absolute;z=3;display:none; ">
                  <div class="white-box" style="line-height:25px;width:420px;">
                      <h4>Are you sure you want to leave this household?</h4>
                      <p>You can join again later with the owner's invite code.</p>
                      <div class="form-group pull-right">
                          <a class="btn btn-default" href="javascript:void(0);"  onclick="popupLeave(0)">Cancel</a>
                          <a class="btn btn-danger" href="/Household/leave/<?php echo $data['hhId']; ?>">Leave</a>
                      </div>
                      <br>
                  </div>
                </div>
                <div id="nameBox" style="top: 35%; left: 30%;position: absolute;z=3;display:none; ">
                  <div class="white-box" style="line-height:25px;width:420px;">
                    <form action="/Household/rename/<?php echo $data['hhId'];?>" method="POST">
                      <div class="form-group">
                          <label for="name" id="name">Set Household Name</label>
                          <input type="text" class="form-control" name="name" value="<?php echo $data['name']; ?>"/>
                      </div>
                      <div class="form-group text-center">
                          <a href="javascript:void(0);"  onclick="popupRename(0)" class="btn btn-default">Cancel</a>
                          <input type="submit" name="submit" value="Update" class="btn btn-success" />
                      </div>
                    </form>
                  </div>
                </div>


<?php require_once(__SPANEL__); ?>

            </div>
              <!-- ===== Page-Container-End ===== -->

            <footer class="footer t-a-c">
                <?php echo __COPYRITE__; ?>
            </footer>
        </div>
        <!-- ===== Page-Content-End ===== -->

    </div>
    <!-- ===== Main-Wrapper-End ===== -->

<?php require_once(__FOOTER__); ?>

</body>
</html>
