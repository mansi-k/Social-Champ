<?php
include_once 'dbconn.php';
include_once 'fbinit.php';
include_once 'fbglobals.php';
$adminsarr = array();
include_once 'fbfunc.php';
include_once 'fbngofunc.php';
include_once 'nlp.php';

use Abraham\TwitterOAuth\TwitterOAuth;
require('config.php');
require 'autoload.php';
include("extra_scan_func.php");
//include('connection.php');
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET,'926448476212174849-V55q3spHf7EEJCeL14tjc2AO5yavdEt','3bJkAyye4VPbvdk9rucsRtevVqbNDWpYGFGbT9bdVPQbU');	
$bid=954257679202988033;
$ownernm=" Pinky Rathod";
$ownersn="rathodpinky371";
$listslug="charitable-orientation";


//$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET,'926448476212174849-V55q3spHf7EEJCeL14tjc2AO5yavdEt','3bJkAyye4VPbvdk9rucsRtevVqbNDWpYGFGbT9bdVPQbU');

if(isset($_POST['esbtn'])) {
    global $conn;
    $id = $_POST['objid'];
    $platform = $_POST['optradio'];
    $type = $_POST['objtype'];
    if ($platform == 'facebook') {
        $qry = mysqli_query($conn, "SELECT a.account_id, a.account_name, a.account_token FROM user_accounts AS a, user_extended AS e WHERE e.u_id=a.u_id AND a.at_id=1 AND e.ut_id=4");
        $qar = mysqli_fetch_array($qry);
        $_SESSION['fb_access_token'] = $qar['account_token'];
        $_SESSION['fb_uid'] = $id;
        $fb->setDefaultAccessToken($_SESSION['fb_access_token']);
        $since = date("Y-m-d", strtotime(date("y-m-d") . ' -5 days '));
        $until = date("Y-m-d", strtotime(date("y-m-d") . ' -1 day'));
        $objget = $fb->get($id . '?fields=name');
        $obj = $objget->getGraphNode();
        $a = array('id' => $id, 'name' => $obj['name'], 'type' => $type, 'uid' => null, 'token' => null);
        if (scanTags($a)) {
            $a['sw'] = 'yes';
            fetchPeople($a, 2);
        }
        if ($type == 'page') {
            pageScan($id, $fb, 0);
        } else if ($type == 'event') {
            eventScan($id, $fb, 0);
        } else if ($type == 'group') {
            groupScan($id, $fb, 0);
        } else if ($type == 'post') {
            $feedget = $fb->get($id . '?fields=created_time,description,message,from,name,caption,message_tags,source,status_type,story,story_tags,to{id,name,profile_type},type,parent_id,place,privacy,shares,sharedposts{from,to{id,name,profile_type},story,story_tags,message_tags,shares,place},reactions.summary(true){id,name,type,profile_type},comments.summary(true){from,message,message_tags,comments}&until=' . $until . '&since=' . $since);
            $feed = $feedget->getGraphNode();
            scanPageFeed($feed, 0);
        }
    } else if ($platform == 'twitter') {
        if ($type == 'list') {
            scanList($connection, $ownersn, $ownernm, $listslug);
        } else if ($type == 'post') {
            scanPost($connection, $id);
        }
    }
}

?>


<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="build.css">

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
                        <input  placeholder="Enter URL/ID" class="form-control"  type="text" id="URL/ID" name="objid">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="Type" class="col-sm-2 control-label">Type</label>
                <div class="col-sm-5">
                    <div class="radio">
                        <label>
                            <input type="radio"  name="optradio" id="Radios1" value="facebook">Facebook</label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="optradio" id="Radios1" value="twitter">
                            Twitter
                        </label>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <label for="Menu" class="col-sm-2 control-label">Menu</label>
                <div class="col-md-6  inputGroupContainer">
                    <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-list"></i></span>
                        <select class="form-control" name="objtype" >
                            <option value="">Please select your type</option>
                            <option value="page">Page</option>
                            <option value="event">Event</option>
                            <option value="group">Group</option>
                            <option value="list">List</option>
                            <option value="post">Post/Tweet</option>
                        </select>
                    </div>
                </div>
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