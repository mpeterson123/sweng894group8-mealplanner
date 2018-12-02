<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                             Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// Login
///////////////////////////////////////////////////////////////////////////////
require_once __DIR__.'/../../../vendor/autoload.php';
require_once(__DIR__ . '/../modules/main.mod.php' );

// Sub Title
$SUBTITLE = 'Select Household';

// Plugins
$PLUGIN_SLIMSCROLL = TRUE;
$PLUGIN_WAVES      = TRUE;
$PLUGIN_SIDEBARMENU= TRUE;
?>
<?php require_once( __HEADER__ ); ?>
<?php
  if(!empty($data['message']))
    echo $data['message'];
?>

<script type="text/javascript">
  function popup(open){
    if(open)
      document.getElementById("inviteCodeBox").style.display = "block";
    else
      document.getElementById("inviteCodeBox").style.display = "none";
  }
  function popupName(open){
    if(open)
      document.getElementById("nameBox").style.display = "block";
    else
      document.getElementById("nameBox").style.display = "none";
  }
</script>

<body class="mini-sidebar">
    <!-- Preloader -->
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>
   <div style="top: 35%; left: 30%;position: absolute;">
        <div class="white-box" style="line-height:25px;width:420px;height:260px;">
            <h3>To continue, create a new household, or join an existing one.</h3>
            <div class="form-group">
                <a href="javascript:void(0);" onclick="popupName(1)" class="btn btn-success btn-lg btn-block">
                    Create Household
                </a>
            </div>
            <div class="form-group">
                <a href="javascript:void(0);" onclick="popup(1)" class="btn btn-success btn-lg btn-block">
                    Join Household
                </a>
            </div>
        </div>
    </div>
    <div id="inviteCodeBox" style="top: 35%; left: 30%;position: absolute;z=2;display:none; ">
      <div class="white-box" style="line-height:25px;width:420px;height:260px;">
        <form action="/Household/join" method="POST">
            <h4>Enter the invitation code for the household you want to join.</h4>
            <div class="form-group">
                <label for="name" id="name">Invitation Code</label>
                <input type="text" class="form-control" name="invite_code" />
            </div>
            <div class="form-group text-center">
                <a href="javascript:void(0);"  onclick="popup(0)" class="btn btn-default">Back</a>
                <input type="submit" name="submit" value="Join" class="btn btn-success"/>
            </div>
        </form>
      </div>
    </div>
    <div id="nameBox" style="top: 35%; left: 30%;position: absolute;z=2;display:none; ">
      <div class="white-box" style="line-height:25px;width:420px;height:260px;">
        <form action="/Household/create" method="POST">
            <h4>Choose a name for your household.</h4>
            <div class="form-group">
                <label for="name" id="name">Household Name</label>
                <input type="text" class="form-control" name="name" value="<?php echo $data['name']; ?> Household"/>
            </div>
            <div class="form-group text-center">
                <a href="javascript:void(0);"  onclick="popupName(0)" class="btn btn-default">Back</a>
                <input type="submit" name="submit" value="Create" class="btn btn-success" />
            </div>
        </form>
      </div>
    </div>

<?php require_once( __FOOTER__ ); ?>

</body>
</html>
