<?php
ob_start();
include 'dbconn.php' ;
include 'fbinit.php' ;
include 'fbpost.php';
if(isset($_POST['dosubmit'])) {
    $_SESSION['u_id'] = $uid = $_POST['doname'];
    $qd1 = mysqli_query($conn,"SELECT * FROM user_accounts WHERE u_id=$uid AND at_id=1");
    if(mysqli_num_rows($qd1)>0) {
        echo "q>0";
        $row = mysqli_fetch_array($qd1);
        $tdbg = $fb->get("/debug_token?input_token=".$row['account_token'],$app_id."|".$app_secret);
        if($tdbg->getGraphNode()['is_valid'] && $tdbg->getGraphNode()['expires_at']>date('Y-m-d H:i:s')) {
            echo "tval";
            //$fb->setDefaultAccessToken($row['account_token']);
            $scopes = $tdbg->getGraphNode()->asArray()['scopes'];
            if(in_array("user_posts",$scopes) && in_array("publish_actions",$scopes)) {
                echo "tps";
                postToFB($row['account_id'], $row['account_token']);
            }
            else {
                //$fb->setDefaultAccessToken("hvg");
                echo "red1";
                $_SESSION['fbpost']="true";
                //include 'fboauth.php';
                header("Location : oauthlinks.php");
            }
        }
        else {
            echo "red2";
            //$fb->setDefaultAccessToken("gh");
            $_SESSION['fbpost']="true";
            //include 'fboauth.php';
            header("Location:oauthlinks.php");
            echo "end";
        }
    }
    else {
        echo "red2";
        //$fb->setDefaultAccessToken("gh");
        $_SESSION['fbpost'] = "true";
        //include 'fboauth.php';
        header("Location:oauthlinks.php");
    }
}
?>

<html>
<head>
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap-theme.min.css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>

</head>
<body>
<div class="container">
<?php



$sql="select distinct u_id, name from user where u_id in(select u_id from user_extended 
 where ut_id in (1,2))order by name ASC";

$result=mysqli_query($conn,$sql);


if($result === FALSE) {
die(mysqli_error());  
}
?>
<form class="well form-horizontal" method="post">
<fieldset>
<div class="form-group">
        <label class="col-md-4 control-label">name:</label>
        <div class="col-md-6 selectContainer">
          <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-list"></i></span>

<?php
//echo "<select name='name'>";
echo "<select name='doname' class='form-control selectpicker'>";
echo "<option value='' >Please select your name</option>";
while ($row = mysqli_fetch_array($result)) {

echo "<option value='" . $row['u_id'] ."'>" . $row['u_id']."".$row['name']."</option>";
}
echo "</select>";
?>
</div>
</div>
  </div>
  

	<div class="form-group">
        <label class="col-md-4 control-label">Amount:</label>
        <div class="col-md-6  inputGroupContainer">
          <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
            <input  name="amount" placeholder="Enter the amount" class="form-control"  type="text" required>
          </div>
        </div>
      </div>
      
  
  <div class="form-group">
        <label class="col-md-4 control-label"></label>
        <div class="col-md-4">
          <button type="submit" class="btn btn-warning" name="dosubmit">Submit<span class="glyphicon glyphicon-send"></span></button>
        </div>
      </div>
  

</fieldset>
</form>
</body>
</html>