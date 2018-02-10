<?php

set_time_limit (0);
include 'fbinit.php';
include_once 'fbglobals.php';
include_once 'per_cal2.php';
use Facebook\FacebookRequest;



include 'fbscan.php';

// for each user form database call these functions

$q1 = mysqli_query($conn,"SELECT u1.name, u2.* FROM user AS u1 , user_accounts as u2, user_extended AS u3 WHERE u3.ut_id=4 AND u2.at_id=1 AND u2.account_token<>'' AND u1.u_id=u2.u_id AND u3.u_id=u1.u_id");
while($row = mysqli_fetch_array($q1)) {
    $tdbg = $fb->get("/debug_token?input_token=".$row['account_token'],$app_id."|".$app_secret);
    if($tdbg->getGraphNode()['is_valid'] && $tdbg->getGraphNode()['expires_at']>date('Y-m-d H:i:s')) {
        $_SESSION['fb_access_token'] = $row['account_token'];
        $_SESSION['fb_uid'] = $row['account_id'];
        $_SESSION['fb_name'] = $row['account_name'];
        $_SESSION['uname'] = $row['name'];
        $uid = $_SESSION['uid'] = $row['u_id'];
        $_SESSION['inactive_from'] = $row['inactive_from'];
        $_SESSION['fbscore'] = 0;
        $fb->setDefaultAccessToken($_SESSION['fb_access_token']);
        //$row[''] = $_SESSION['fb_uname'];
        echo "<br><br><h3>" . ucwords($_SESSION['uname']) . "</h3>";
        scanProfile($_SESSION['fb_uid'], $fb, $tdbg->getGraphNode());
        $qs = mysqli_query($conn, "select percent_score from user_scores where ue_id in (select ue_id from user_extended where u_id=$uid)");
        if ($rw = mysqli_fetch_array($qs)) {
            if ($rw['percent_score'] == null || $rw['percent_score'] == '')
                scanTimeline($_SESSION['fb_uid'], $fb, 'long', $tdbg->getGraphNode());
            else
                scanTimeline($_SESSION['fb_uid'], $fb, 'short', $tdbg->getGraphNode());
        } else
            scanTimeline($_SESSION['fb_uid'], $fb, 'long', $tdbg->getGraphNode());
        $a = array('id' => $_SESSION['fb_uid'], 'name' => $_SESSION['fb_name'], 'type' => 'user');
        //createForFetch($_SESSION['fb_uid'],$_SESSION['uname'],$_SESSION['fbscore'],'user');
        fetchPeople($a, $_SESSION['fbscore']);
        echo "<br><b>Profile Score " . $_SESSION['fbscore'] . "</b>";
    }
}

$q2 = mysqli_query($conn,"SELECT DISTINCT u1.name, u2.* FROM user AS u1 , user_accounts as u2, user_extended AS u3 WHERE u3.ut_id<>4 AND u3.ut_id<>3 AND u2.at_id=1 AND u2.account_token<>'' AND u1.u_id=u2.u_id AND u3.u_id=u1.u_id");
while($row = mysqli_fetch_array($q2)) {
    $tdbg = $fb->get("/debug_token?input_token=".$row['account_token'],$app_id."|".$app_secret);
    if($tdbg->getGraphNode()['is_valid'] && $tdbg->getGraphNode()['expires_at']>date('Y-m-d H:i:s')) {
        $_SESSION['fb_access_token'] = $row['account_token'];
        $_SESSION['fb_uid'] = $row['account_id'];
        $_SESSION['fb_name'] = $row['account_name'];
        $_SESSION['uname'] = $row['name'];
        $uid = $_SESSION['uid'] = $row['u_id'];
        $_SESSION['inactive_from'] = $row['inactive_from'];
        $_SESSION['fbscore'] = 0;
        $fb->setDefaultAccessToken($_SESSION['fb_access_token']);
        $qpro = mysqli_query($conn, "SELECT u_id FROM user_extended WHERE u_id=$uid AND ut_id=2");
        if (mysqli_num_rows($qpro) > 0) {
            $_SESSION['promoter'] = true;
            $_SESSION['pro_fbscore'] = 0;
        }
        //$row[''] = $_SESSION['fb_uname'];
        echo "<br><br><h3>" . ucwords($_SESSION['uname']) . "</h3>";
        //scanProfile($_SESSION['fb_uid'], $fb, $tdbg->getGraphNode());
        $qs = mysqli_query($conn, "select percent_score from user_scores where st_id=1 AND ue_id in (select ue_id from user_extended where u_id=$uid)");
        if ($rw = mysqli_fetch_array($qs)) {
            if ($rw['percent_score'] == null || $rw['percent_score'] == '') {
                scanTimeline($_SESSION['fb_uid'], $fb, 'long', $tdbg->getGraphNode());
                scanProfile($_SESSION['fb_uid'], $fb, $tdbg->getGraphNode());
            }
            else
                scanTimeline($_SESSION['fb_uid'], $fb, 'short', $tdbg->getGraphNode());
        } else
            scanTimeline($_SESSION['fb_uid'], $fb, 'long', $tdbg->getGraphNode());
        $a = array('id' => $_SESSION['fb_uid'], 'name' => $_SESSION['fb_name'], 'type' => 'user');
        //createForFetch($_SESSION['fb_uid'],$_SESSION['uname'],$_SESSION['fbscore'],'user');
        if(isset($_SESSION['pro_fbscore']))
        echo " promoter_score=" . $_SESSION['pro_fbscore'];
        if (isset($_SESSION['promoter']) && $_SESSION['promoter']) {
            fetchPeople($a, $_SESSION['fbscore'] + $_SESSION['pro_fbscore']);
            unset($_SESSION['pro_fbscore']);
            $_SESSION['promoter'] = false;
        }
        fetchPeople($a, $_SESSION['fbscore']);
        echo "<br><b>Profile Score " . $_SESSION['fbscore'] . "</b>";
        unset($_SESSION['promoter']);
    }
}

fbDefaultTokens();
//echo $_SESSION['fb_name'];
//echo "sdbjhbsedcfcsd";
//include_once 'fbngoscan.php';

per_cal();

?>