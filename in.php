<?php 
session_start();

require('config.php');
require 'autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

if(empty($_SESSION['access_token'])||empty($_SESSION['access_token' ]['oauth_token'])||empty($_SESSION['access_token']['oauth_token_secret'])){
	header("Location: clearsession.php");
}
$access_token=$_SESSION['access_token'];
$connection=new TwitterOAuth(CONSUMER_KEY,CONSUMER_SECRET,$access_token['oauth_token'],$access_token['oauth_token_secret']);
/*
echo "oauth token: ".$access_token['oauth_token']."<br>";
echo "oauth token secret: ".$access_token['oauth_token_secret']."<br>";
echo "screen name of user: ".$access_token['screen_name']."<br>";
echo "user id :".$access_token['user_id']."<br>";
echo "expiry :".$access_token['x_auth_expires']."<br>";
echo "<a href='clearsession.php'>log out</a>";
*/

$db = mysqli_connect('localhost', 'root', '', 'sci3');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
} 

$T_uid = $access_token['user_id'];
$T_uname = $access_token['screen_name'];
$T_token = $access_token['oauth_token'];
$T_secret = $access_token['oauth_token_secret'];
$T_date = $access_token['x_auth_expires'];

$uid=$_SESSION['u_id'];
$query = "INSERT INTO user_accounts (u_id,at_id,account_id,account_name,account_token,token_expiry,account_secret)
VALUES ('$uid',2,'$T_uid','$T_uname','$T_token','$T_date','$T_secret')";
$res = mysqli_query($db,$query);

//header('location:oauthlinks.php');
if($res) 
//{
	//echo "stored Successfully".$_SESSION['fbsuccess'];
  	header('location:oauthlinks.php');
	//echo "stored Successfully";
	//$smsg = "user created Successfully";
	//echo $smsg;
//}
else
{
	echo "failed";
	//$fmsg = "failed";
	//echo $fmsg;
}
$_SESSION['success']="";
?>
