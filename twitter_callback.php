<?php
session_start();
require('config.php');
require 'autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;
include('nlp.php');
include("twitter_scan.php");
include("twitter_regulr_scan_func.php");
include("twitter_func_defbyme.php");
include('connection.php');
include('listshow.php');
include 'per_cal2.php';
$token="SELECT * FROM `user_accounts` inner join user_extended on user_extended.u_id=user_accounts.u_id where user_accounts.at_id=2 AND user_extended.ut_id=1";
//$token="select * from user_accounts where at_id=2";
global $conn;
$result=mysqli_query($conn,$token);
while($test=mysqli_fetch_array($result))
{
	$nm=$test['account_name'];
	$oauth=$test['account_token'];
	$oauth_secret=$test['account_secret'];
	$_SESSION['account_id']=$account_id=$test['account_id'];	$_SESSION['screen_name']=$screen_name=$test['account_name'];
	$_SESSION['user_id']=$user_id=$test['u_id'];
	if(!empty($oauth)&& !empty($oauth_secret))
	{	
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET,$oauth,$oauth_secret);	
	}
	else
	{
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET,'926448476212174849-V55q3spHf7EEJCeL14tjc2AO5yavdEt','3bJkAyye4VPbvdk9rucsRtevVqbNDWpYGFGbT9bdVPQbU');	
	}
	if($connection)
	{
		
			scanTimeline();
			
		//$check_likes="select * from user_scan_response_types where "
	}
	else
	{
		header('Location: clearsession.php');
	}
}
function lists()
{
	global $conn;
	scanlist();
	$lst= "select * from ngo_social_objects where at_id=2" ;
		echo "<br><br><b>members of the lists</b></br>";
		$result=mysqli_query($conn,$lst);
		if (mysqli_num_rows($result)>0)
		{
			while($test=mysqli_fetch_array($result))
			{
				$lid=$test['so_id'];
				$onrid=$test['owner_id'];
				$onrnm=$test['owner_nm'];
				echo "<b>".$test['so_name']."</b><br>";
				prospective_from_list($lid,$onrid,$onrnm);
				scan_subscribers($lid,$onrid,$onrnm);
			}
			$total_member=array();
			$total_subscribers=array();
		}	
	
}

per_cal();
	/*$lst= "select * from ngo_social_objects where at_id=2" ;
	$res=mysqli_query($conn,$lst);
		$since=0;
		if(mysqli_num_rows($res)>0)
		{
			while($test=mysqli_fetch_array($res))
			{
				$listnm=$test['so_name'];
				$listid=$test['so_id'];
				echo $listnm;
		$listtweet=$connection->get('lists/statuses',['list_id'=>$listid,'count'=>200]);
		$_SESSION['listtweet']=$ltw[]=$listtweet;
		if(!empty($listtweet))
		{
			print_r($ltw);
		//scanTweets($tweet);
		}
			}
			$ltw=array();
		}*/
?>
