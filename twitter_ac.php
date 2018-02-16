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
include('per_cal2.php');

	$token="SELECT * FROM `user_accounts` inner join user_extended on user_extended.u_id=user_accounts.u_id where user_accounts.at_id=2 AND user_extended.ut_id=4";
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
		if($connection)
		{
			scanTimeline();
			scanlist();
			
			echo "<b>Comments</b><br>";
				$lk="select * from scan_mx_id where u_id=$user_id AND response_type=2";
			$lkr=mysqli_query($conn,$lk);
			if(mysqli_num_rows($lkr)>0)
			{
				$rt=mysqli_fetch_array($lkr);
				$resp=$rt['response'];
				if(!empty($resp))
				{
					regular_comments($resp,$account_id);
				}
			}
			else{
				
				with_oauth();
			}
		}
		else
		{
			header('Location: clearsession.php');
		}
	}
}

echo " </br><br><b>scan lists </b><br></br>";
lists();

function lists()
{
	global $conn;
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET,'926448476212174849-V55q3spHf7EEJCeL14tjc2AO5yavdEt','3bJkAyye4VPbvdk9rucsRtevVqbNDWpYGFGbT9bdVPQbU');	

	//scan_nso();
	/*$su=mysqli_query($conn,"SELECT u1.name, u2.* FROM user AS u1 , user_accounts as u2, user_extended AS u3 WHERE u3.ut_id=4 AND u2.at_id=2 AND u2.account_token<>'' AND u1.u_id=u2.u_id AND u3.u_id=u1.u_id");
	if($su)
	{
		$ar=mysqli_fetch_array($su);
		$ngoname=$ar['account_name'];
		$ngoid=$ar['account_id'];*/
		$lst= "select * from ngo_social_objects where at_id=2"; //Owner_nm='$ngoname'" ;
		
		//echo "<br><br><b>members of the lists</b></br>";
		$result=mysqli_query($conn,$lst);
		if (mysqli_num_rows($result)>0)
		{
			while($test=mysqli_fetch_array($result))
			{
				
				$slug=$test['so_name'];
				$onrnm=$test['owner_nm'];
				$uid=$test['u_id'];
				echo "<b><br>".$test['so_name']."</b><br>";
				addto_usr($uid,$slug);
				echo "<b>members of the list</b><br>";
				prospective_from_list($onrnm,$slug,$uid);
				echo "<b>subscribers of the list</b><br>";
				scan_subscribers($onrnm,$slug,$uid);
				
				
			}
			$total_member=array();
			$total_subscribers=array();
		}
	
}
per_cal();
?>