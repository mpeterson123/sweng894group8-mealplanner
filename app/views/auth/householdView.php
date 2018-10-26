<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                             Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// Food (listing)
///////////////////////////////////////////////////////////////////////////////
require_once __DIR__.'/../../../vendor/autoload.php';
require_once( $_SERVER['DOCUMENT_ROOT'] . '/../app/views/modules/main.mod.php' );

use Base\Helpers\Session;

// Sub Title
$SUBTITLE = 'Household';


// Plugins
$PLUGIN_SLIMSCROLL  = TRUE;
$PLUGIN_WAVES       = TRUE;
$PLUGIN_DATATABLES  = TRUE;
$PLUGIN_SIDEBARMENU = TRUE;
$PLUGIN_EXPORT      = TRUE;

// echo "<pre>".print_r($data)."</pre>";

?>
<?php require_once( __HEADER__ ); ?>
<style>
  .selectBox{
    width:200px;
    height:150px;
    border:1px solid black;
    text-align:center;
    line-height:150px;
    float:left;
    margin-left: 10px;
    margin-right: 10px;
    background-color: white;
    border-radius: 10px;
  }
</style>
<script type="text/javascript">
  function popupUser(open){
    if(open>0){
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
</script>

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
                    <div class="col-sm-4">
                        <div class="white-box">
                          <h3 class="box-title m-b-0"><?php echo $data['name']; ?></h3>
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
                                            if($data['members']){
                                                foreach ($data['members'] as $m) { ?>
                                                <tr>
                                                    <td><?php echo $m['name']; ?></td>
                                                    <?php
                                                    if($data['owner'] == $m['username'])  echo '<td style="color:gray;text-align:right;">Owner</td>';
                                                    else if($data['isOwner']) echo '<td style="text-align:right;"><a href="javascript:void(0);" onclick="popupUser('.$m['id'].')" style="color:red;">x</a></td>';
                                                    ?>
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
                            if($data['isOwner'])
                              echo '<a href="javascript:void(0);" onclick="popupDelete(1)">Delete Household</a>';
                            else{
                              echo '<a href="javascript:void(0);" onclick="popupLeave(1)">Leave Household</a>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div id="overlay" style="top: 0%; left: 0%;position: absolute;z=2;display:none; width:100%;height:100%;background-color: gray;opacity:0.5;"></div>
                <div id="confirmUserBox" style="top: 35%; left: 30%;position: absolute;z=3;display:none; ">
                  <div class="selectBox" style="line-height:25px;width:420px;">
                      <br><p><h3>Are you sure you want to remove this user?</h3><p>
                      <a href="/Household/remove/">Yes</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <a href="javascript:void(0);"  onclick="popupUser(0)">No</a>
                  </div>
                </div>
                <div id="confirmDeleteBox" style="top: 35%; left: 30%;position: absolute;z=3;display:none; ">
                  <div class="selectBox" style="line-height:25px;width:420px;height:200px;">
                      <br><p><h3>Are you sure you want to delete this household?</h3><br>This action cannot be undone!<p>
                      <a href="/Household/delete/<?php echo $data['hhId']; ?>">Yes</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <a href="javascript:void(0);"  onclick="popupDelete(0)">No</a>
                  </div>
                </div>
                <div id="confirmLeaveBox" style="top: 35%; left: 30%;position: absolute;z=3;display:none; ">
                  <div class="selectBox" style="line-height:25px;width:420px;">
                      <br><p><h3>Are you sure you want to leave this household?</h3><p>
                      <a href="/Household/leave/<?php echo $data['hhId']; ?>">Yes</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <a href="javascript:void(0);"  onclick="popupLeave(0)">No</a>
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
