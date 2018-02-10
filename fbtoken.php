<?php
ob_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
session_start();
include_once 'fbinit.php';
include_once 'dbconn.php';


$helper = $fb->getRedirectLoginHelper();

try {
    $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    var_dump($helper->getError());
    exit;
}

if (! isset($accessToken)) {
    if ($helper->getError()) {
        header('HTTP/1.0 401 Unauthorized');
        echo "Error: " . $helper->getError() . "\n";
        echo "Error Code: " . $helper->getErrorCode() . "\n";
        echo "Error Reason: " . $helper->getErrorReason() . "\n";
        echo "Error Description: " . $helper->getErrorDescription() . "\n";
    } else {
        header('HTTP/1.0 400 Bad Request');
        echo 'Bad request';
    }
    exit;
}

// Logged in
//echo '<h3>Access Token</h3>';
//var_dump($accessToken->getValue());

// The OAuth 2.0 client handler helps us manage access tokens
$oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
$tokenMetadata = $oAuth2Client->debugToken($accessToken);
//echo '<h3>Metadata</h3>';
//var_dump($tokenMetadata);

// Validation (these will throw FacebookSDKException's when they fail)
$tokenMetadata->validateAppId('550812835258864'); // Replace {app-id} with your app id
// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
$tokenMetadata->validateExpiration();

if (! $accessToken->isLongLived()) {
    // Exchanges a short-lived access token for a long-lived one
    try {
        $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
        exit;
    }

    echo '<h3>Long-lived</h3>';
    var_dump($accessToken->getValue());
}

$fb_token=$_SESSION['fb_access_token'] = (string) $accessToken;
$udetails = $fb->get('/me?fields=id,name', $_SESSION['fb_access_token']);
$ud = $udetails->getGraphNode();

$fb_uid=$_SESSION['fb_uid'] = $ud['id'];
$fb_uname=$_SESSION['fb_uname'] = mysqli_real_escape_string($conn,$ud['name']);

$perm           =   $fb->get('/'.$_SESSION["fb_uid"].'/permissions',$accessToken);
$perm           =   $perm->getGraphEdge();

$expdate = $accessToken->getExpiresAt();
$exp = null;
if($expdate!=null)
	$exp = $expdate->format('Y-m-d H:i:s');
$fb_date=$_SESSION['fb_token_exp'] = $exp;

$uid=$_SESSION['u_id'];

if(isset($_SESSION['fbpost']) && $_SESSION['fbpost']=='true') {
    $res1 = mysqli_query($conn, "SELECT * FROM user_accounts WHERE u_id=$uid and at_id=1");
    if (mysqli_num_rows($res1) > 0) {
        $r = mysqli_fetch_array($res1);
        $rid = $r['a_id'];
        mysqli_query($conn, "UPDATE user_accounts SET account_token='$fb_token' WHERE a_id=$rid");
        echo mysqli_error($conn);
    }
    else {
        $res = mysqli_query($conn,"INSERT INTO user_accounts (u_id,at_id,account_id,account_name,account_token,token_expiry,account_secret)
VALUES ($uid,1,'$fb_uid','$fb_uname','$fb_token','$fb_date' ,'')");
        echo mysqli_error($conn);
    }
    include 'fbpost.php';
    postToFB($fb_uid,$fb_token);
    header('Location:oauthlinks.php');
    //twitter
}
else {
    $res = mysqli_query($conn,"INSERT INTO user_accounts (u_id,at_id,account_id,account_name,account_token,token_expiry,account_secret)
VALUES ($uid,1,'$fb_uid','$fb_uname','$fb_token','$fb_date' ,'')");
    echo mysqli_error($conn);
    if($res) {
        $_SESSION['success']="success";
        header('Location:oauthlinks.php');
    }
    else
        echo "failed";
}





//echo $perm;

// User is logged in with a long-lived access token.
// You can redirect them to a members-only page.
//header('Location: fbscan.php');
/*
echo "<br><br>".$_SESSION['fb_access_token'];
echo "<br><br>".$_SESSION['fb_uid'];
echo "<br><br>".$_SESSION['fb_token_exp'];

*/
?>