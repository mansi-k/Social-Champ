<?php
set_time_limit (0);
include_once 'dbconn.php';
include_once 'fbinit.php';
include_once 'fbglobals.php';
$adminsarr = array();
include_once 'fbfunc.php';
include_once 'fbngofunc.php';
include_once 'nlp.php';

use Facebook\FacebookRequest;

//$pgid = '1774494506184449';


$since = date("Y-m-d",strtotime(date("y-m-d").' -2 months '));
$until = date("Y-m-d",strtotime(date("y-m-d").' -2 days'));

$q1 = mysqli_query($conn,"SELECT n.*, a.account_id, a.account_name, a.account_token FROM ngo_social_objects AS n, user_accounts AS a, user_extended AS e WHERE n.u_id=e.u_id=a.u_id AND a.at_id=1 AND e.ut_id=4");
while($row=mysqli_fetch_array($q1)) {
    if($row['so_token']!=null || $row['so_token']!='')
        $_SESSION['fb_access_token'] = $row['so_token'];
    else
        $_SESSION['fb_access_token'] = $row['account_token'];
    //echo $_SESSION['fb_access_token'];
    $_SESSION['fb_uid'] = $row['so_id'];
    $_SESSION['fb_name'] = $row['so_name'];
    $_SESSION['fbscore'] = 0;
    $fb->setDefaultAccessToken($_SESSION['fb_access_token']);
    //$row[''] = $_SESSION['fb_uname'];
    echo "<br><br><h3>".$_SESSION['fb_name']."</h3>";
    if($row['so_type_id']==1)
        pageScan($_SESSION['fb_uid'],$fb,1);
    else if($row['so_type_id']==2)
        groupScan($_SESSION['fb_uid'],$fb,1);
    else if($row['so_type_id']==3)
        eventScan($_SESSION['fb_uid'],$fb,1);
    //echo "<br><b>Profile Score ".$_SESSION['fbscore']."</b>";
}

/*
$q2 = mysqli_query($conn,"SELECT DISTINCT n.* , a.account_id, a.account_name, a.account_token FROM ngo_social_objects AS n, user_extended AS ue, user_accounts AS a WHERE n.u_id=ue.u_id AND n.u_id=a.u_id AND ue.ut_id!=4 AND n.is_sw='yes' AND a.at_id=1");
while($row=mysqli_fetch_array($q2)) {
    echo "in q2";
    if($row['so_token']!=null || $row['so_token']!='')
        $_SESSION['fb_access_token'] = $row['so_token'];
    else
        $_SESSION['fb_access_token'] = $row['account_token'];
    $_SESSION['fb_uid'] = $row['so_id'];
    $_SESSION['fb_name'] = $row['so_name'];
    $_SESSION['fbscore'] = 0;
    $fb->setDefaultAccessToken($_SESSION['fb_access_token']);
    //$row[''] = $_SESSION['fb_uname'];
    echo "<br><br><h3>".ucwords($_SESSION['fb_name'])."</h3>";
    if($row['so_type_id']==1)
        pageScan($_SESSION['fb_uid'],$fb,0);
    else if($row['so_type_id']==2)
        groupScan($_SESSION['fb_uid'],$fb,0);
    else if($row['so_type_id']==3)
        eventScan($_SESSION['fb_uid'],$fb,0);
    //echo "<br><b>Profile Score ".$_SESSION['fbscore']."</b>";
}

$qat = mysqli_query($conn,"SELECT account_token FROM user_accounts WHERE u_id=11 AND at_id=1");
$rw = mysqli_fetch_array($qat);
$_SESSION['fb_access_token'] = $rw['account_token'];
$q3 = mysqli_query($conn,"SELECT * FROM ngo_social_objects WHERE u_id=11 AND is_sw='yes'");
while($row=mysqli_fetch_array($q3)) {
    echo " in q3";
    $_SESSION['fb_uid'] = $row['so_id'];
    $_SESSION['fb_name'] = $row['so_name'];
    $_SESSION['fbscore'] = 0;
    $fb->setDefaultAccessToken($_SESSION['fb_access_token']);
    //$row[''] = $_SESSION['fb_uname'];
    echo "<br><br><h3>".ucwords($_SESSION['fb_name'])."</h3>";
    if($row['so_type_id']==1)
        pageScan($_SESSION['fb_uid'],$fb,0);
    else if($row['so_type_id']==2)
        groupScan($_SESSION['fb_uid'],$fb,0);
    else if($row['so_type_id']==3)
        eventScan($_SESSION['fb_uid'],$fb,0);
    //echo "<br><b>Profile Score ".$_SESSION['fbscore']."</b>";
}
*/



/*
pageScan();
echo '<br><b>page scan done</b></br>';
eventScan();
echo '<br><b>event scan done</b></br>';
groupScan();

echo "<b>USERS</b>";
print_r($users);
echo '<br><br>';
echo "<b>OTHERS</b>";
print_r($others);
*/


?>