<?php
include_once 'dbconn.php';
include_once 'fbinit.php';
include_once 'fbglobals.php';
$adminsarr = array();
include_once 'fbfunc.php';
include_once 'fbngofunc.php';
include_once 'nlp.php';
include_once 'per_cal2.php';

use Abraham\TwitterOAuth\TwitterOAuth;
require('config.php');
require 'autoload.php';
include("twitter_extra_scan_func.php");
//include('connection.php');
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET,'926448476212174849-V55q3spHf7EEJCeL14tjc2AO5yavdEt','3bJkAyye4VPbvdk9rucsRtevVqbNDWpYGFGbT9bdVPQbU');	


//$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET,'926448476212174849-V55q3spHf7EEJCeL14tjc2AO5yavdEt','3bJkAyye4VPbvdk9rucsRtevVqbNDWpYGFGbT9bdVPQbU');

fbDefaultTokens();

$goid=null;
$gat=null;
$got=null;

if(isset($_GET['oid']) && isset($_GET['at']) && isset($_GET['ot'])) {
    $goid = $_GET['oid'];
    $gat = $_GET['at'];
    $got = $_GET['ot'];
}

if(isset($_POST['esbtn'])) {
    global $conn;
    $oid = $_POST['objid'];
    $platform = $_POST['optradio'];
    $type = $_POST['objtype'];
    if ($platform == 'facebook') {
        $_SESSION['since'] = date("Y-m-d", strtotime(date("y-m-d") . ' -10 days '));
        $_SESSION['until'] = date("Y-m-d", strtotime(date("y-m-d") . ' +1 day'));
        //echo $since." - ".$until;
        $objget = $fb->get($oid . '?fields=id,name');
        $obj = $objget->getGraphNode();
        $a = array('id' => $obj['id'], 'name' => $obj['name'], 'type' => $type, 'uid' => null, 'token' => null, 'sw' => null);
        fetchPeople($a, 2);
        $soid = $_SESSION['fb_uid'] = $obj['id'];
        echo "fb_uid=".$_SESSION['fb_uid'];
        if ($type == 'page') {
            $pq = mysqli_query($conn,"SELECT * FROM ngo_social_objects WHERE so_id='$soid' and so_token!=null and so_token!=''");
            if(mysqli_num_rows($pq)>0) {
                $prow = mysqli_fetch_array($pq);
                $fb->setDefaultAccessToken($prow['so_token']);
                pageScan($obj['id'], $fb, 0, 1);
                fbDefaultTokens();
            }
            else
                pageScan($obj['id'], $fb, 0);
        }
		else if ($type == 'event' || $type == 'group'){
			$pq1 = mysqli_query($conn,"SELECT account_token from user_accounts where u_id in (select u_id from ngo_social_objects where so_id='$soid')");
			if(mysqli_num_rows($pq1)>0) {
                $prow = mysqli_fetch_array($pq1);
                $fb->setDefaultAccessToken($prow['account_token']);
                //pageScan($obj['id'], $fb, 0, 1);
                fbDefaultTokens();
            }
		}
        if ($type == 'event') {
            eventScan($obj['id'], $fb, 0);
        }
        else if ($type == 'group') {
            groupScan($obj['id'], $fb, 0);
        }
        else if ($type == 'post') {
            $postid = $obj['id']."_".$_POST['exid'];
            $feedget = $fb->get($postid . '?fields=created_time,description,message,from,name,caption,message_tags,source,status_type,story,story_tags,to{id,name,profile_type},type,parent_id,place,privacy,shares,sharedposts{from},reactions.summary(true){id,name,type,profile_type},comments.summary(true){from,message,message_tags,comments}');
            $feed['feed'] = $feedget->getGraphNode();
            prepareFeeds($feed,0);
        }
    }
    else if ($platform == 'twitter') {
        if ($type == 'list') {
			$ownersn=$oid;
			$listslug=$_POST['exid'];
            scanList($connection, $ownersn, $listslug);
        } else if ($type == 'tweet') {
            scanPost($connection, $oid);
        }
    }
   
}

per_cal();
fbDefaultTokens();
?>


<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="build.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<script type="text/javascript">




function showfield(name){
if(name =='post' || name =="list")
{
	
document.getElementById('div1').innerHTML='<div class="form-group"><label class="col-md-2 control-label">URL/ID</label><div class="col-md-6  inputGroupContainer"><div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span><input class="form-control" placeholder="Enter URL/ID" type="text" name="exid" /></div></div></div> ';}
  else {document.getElementById('div1').innerHTML='';
}
}

</script>


    <style>
        .inputstl {
            padding: 9px;
            border: solid 1px #B3FFB3;
            outline: 0;
            background: -webkit-gradient(linear, left top, left 25, from(#FFFFFF), color-stop(4%, #A4FFA4), to(#FFFFFF));
            background: -moz-linear-gradient(top, #FFFFFF, #A4FFA4 1px, #FFFFFF 25px);
            box-shadow: rgba(0,0,0, 0.1) 0px 0px 8px;
            -moz-box-shadow: rgba(0,0,0, 0.1) 0px 0px 8px;
            -webkit-box-shadow: rgba(0,0,0, 0.1) 0px 0px 8px;

        }

    </style>

</head>

<body>

<div class="container">

    <form class="well form-horizontal" method="post"  id="contact_form">
        <fieldset>

            <!-- Form Name -->
            <legend><center><h2><b>Form</b></h2></center></legend><br>

            <div class="form-group">
                <label for="URL/ID" class="col-md-2 control-label">URL/ID</label>
                <div class="col-md-6  inputGroupContainer">
                    <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                        <input  placeholder="Enter URL/ID" class="form-control"  type="text" id="URL/ID" name="objid"
                        <?php if($goid) echo "value='".$goid."'"?>>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="Type" class="col-sm-2 control-label">Type</label>
                <div class="col-sm-5">
                    <div class="radio">
                        <label>
                            <input type="radio"  name="optradio" id="Radios1" value="facebook" onclick="showfield(this)" <?php if($gat=='facebook') echo "checked";?>>Facebook</label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="optradio" id="Radios2" value="twitter" <?php if($gat=='twitter') echo "checked";?>>
                            Twitter
                        </label>
                    </div>
                </div>
            </div>
			
            <div class="form-group">
                <label for="Menu" class="col-sm-2 control-label">Menu</label>
                <div class="col-md-6  inputGroupContainer">
                    <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-list"></i></span>
                        <select class="form-control" name="objtype" id="myselect" onchange="showfield(this.options[this.selectedIndex].value)">
                            <option value="">Please select your type</option>
                            <option value="page" <?php if($got=='page') echo "selected";?>>Page</option>
                            <option value="event" <?php if($got=='event') echo "selected";?>>Event</option>
                            <option value="group" <?php if($got=='group') echo "selected";?>>Group</option>
                            <option value="list" <?php if($got=='list') echo "selected";?>>List</option>
                            <option value="post" <?php if($got=='post') echo "selected";?>>Post</option>
							<option value="tweet">Tweet</option>
                        </select>
                    </div>
                </div>
            </div>
			
			<div  id="div1" >
            </div>
			
			
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-2">
        <button type="submit" class="btn btn-info" name="esbtn">Submit</button>
    </div>
</div>
</fieldset>
</form>
</div>



</body>
</html>