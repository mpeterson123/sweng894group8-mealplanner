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
   <div style="top: 35%; left: 30%;position: absolute; ">
      <a href="javascript:void(0);" onclick="popupName(1)">
        <div class="selectBox">
          Start new Household
        </div>
      </a>
      <a href="javascript:void(0);" onclick="popup(1)">
        <div class="selectBox">
          Join existing Household
        </div>
      </a>
    </div>
    <div id="inviteCodeBox" style="top: 35%; left: 30%;position: absolute;z=2;display:none; ">
      <div class="selectBox" style="line-height:25px;width:420px;">
        <form action="/Household/join" method="POST">
          Enter invite code:<br>
          <input type="text" name="invite_code" /><br>
          <input type="submit" name="submit" value="Join" />
        </form>
        <p><br>
        <a href="javascript:void(0);"  onclick="popup(0)">or go back</a>
      </div>
    </div>
    <div id="nameBox" style="top: 35%; left: 30%;position: absolute;z=2;display:none; ">
      <div class="selectBox" style="line-height:25px;width:420px;">
        <form action="/Household/create" method="POST">
          Set Household Name:<br>
          <input type="text" name="name" value="<?php echo $data['name']; ?> Household"/><br>
          <input type="submit" name="submit" value="Create" />
        </form>
        <p><br>
        <a href="javascript:void(0);"  onclick="popupName(0)">or go back</a>
      </div>
    </div>

<?php require_once( __FOOTER__ ); ?>

</body>
</html>
