<?php
session_start();
require('config.php');
require 'autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;
include('nlp.php');
include("twitter_scan.php");
include('connection.php');
$token="SELECT * FROM `user_accounts` inner join user_extended on user_extended.u_id=user_accounts.u_id where user_accounts.at_id=2 AND user_extended.ut_id=1";
//$token="select * from user_accounts where at_id=2";
global $conn;
$result=mysqli_query($conn,$token);
while($test=mysqli_fetch_array($result))
{
	$nm=$test['account_name'];
	$oauth=$test['account_token'];
	$oauth_secret=$test['account_secret'];
		$_SESSION['account_id']=$account_id=$test['account_id'];
		$_SESSION['screen_name']=$screen_name=$test['account_name'];
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
			/*$check_existing="select * from social_responses where so_id=$account_id ";
			$check=mysqli_query($conn,$check_existing);
			$tst=mysqli_fetch_array($check);
			if((mysqli_num_rows($check))>0 && !empty($tst['response']))
			{
				$_SESSION['connection']	=$connection;
				fsttime_scanTimeline();
			}
			else
			{
				scanTimeline();
			}*/
			scanTimeline();
			
			
		}
		else
		{
			header('Location: clearsession.php');
		}
}
		
		$lst= "select * from ngo_social_objects where at_id=2" ;
		echo "<br><br><b>members of the lists</b></br>";
		$result=mysqli_query($conn,$lst);
		if (mysqli_num_rows($result)>0)
		{
			while($test=mysqli_fetch_array($result))
			{
				$lid=$test['so_id'];
				echo "<b>".$test['so_name']."</b<br>";
				prospective_from_list($lid);
			
			}
			$total_member=array();
		}	
	
		$lst= "select * from ngo_social_objects where at_id=2" ;
		echo "<br><br><b>subscribers of the lists</b></br>";
		$result=mysqli_query($conn,$lst);
		if (mysqli_num_rows($result)>0)
		{
			while($test=mysqli_fetch_array($result))
			{
				$lid=$test['so_id'];
				echo "<b>".$test['so_name']."</b><br>";
				scan_subscribers($lid);
			
			}
			$total_subscribers=array();
		}


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
