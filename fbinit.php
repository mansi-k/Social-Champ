<?php

ob_start();
if (!session_id()) {
    session_start();
}
include_once 'dbconn.php';
require_once 'fbapi/vendor/autoload.php';

$app_id = '550812835258864';
$app_secret = '';

$fb = new Facebook\Facebook([
    'app_id' => $app_id,
    'app_secret' => $app_secret,
    'default_graph_version' => 'v2.10',
]);

$fbApp = new Facebook\FacebookApp($app_id, $app_secret);

function fbDefaultTokens() {
    global $conn,$fb, $app_id, $app_secret;
    $q1 = mysqli_query($conn,"SELECT u1.name, u2.* FROM user AS u1 , user_accounts as u2, user_extended AS u3 WHERE u3.ut_id=4 AND u2.at_id=1 AND u2.account_token<>'' AND u1.u_id=u2.u_id AND u3.u_id=u1.u_id");
    while($row = mysqli_fetch_array($q1)) {
        $tdbg = $fb->get("/debug_token?input_token=" . $row['account_token'], $app_id . "|" . $app_secret);
        if ($tdbg->getGraphNode()['is_valid'] && $tdbg->getGraphNode()['expires_at'] > date('Y-m-d H:i:s')) {
            $_SESSION['fb_access_token'] = $row['account_token'];
            $_SESSION['fb_uid'] = $row['account_id'];
            $_SESSION['fb_name'] = $row['account_name'];
            $_SESSION['uname'] = $row['name'];
            $_SESSION['uid'] = $row['u_id'];
            $_SESSION['inactive_from'] = $row['inactive_from'];
            $_SESSION['fbscore'] = 0;
            $fb->setDefaultAccessToken($_SESSION['fb_access_token']);
            //print_r($_SESSION['fb_access_token']);
        }
    }
}




?>
