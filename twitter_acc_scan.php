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


$screen_name="SparshNGO";
ps($screen_name);
function ps($sn)
{
	$account_id=0;$screen_name;$user_name;
	global $conn;
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET,'926448476212174849-V55q3spHf7EEJCeL14tjc2AO5yavdEt','3bJkAyye4VPbvdk9rucsRtevVqbNDWpYGFGbT9bdVPQbU');	
	if($connection)
	{
	$profile=$connection->get('users/show',['screen_name'=>$sn]);
	$profilescr[]=$profile;
	foreach($profilescr as $pro)
	{
		//echo "followers_count".$pro->followers_count;
		//echo "friends_count".$pro->friends_count;
				
		$un=$pro->name;	
		$aid=$pro->id_str;
		$snm=$pro->screen_name;
	
	}
	}
	$ck="select * from user_accounts where account_id='$aid' and at_id=2";
		$c=mysqli_query($conn,$ck);
		if(mysqli_num_rows($c)>0)
		{
			$v=mysqli_fetch_array($c);
				$account_id=$v['account_id'];	$screen_name=$v['account_name'];
				$user_id=$v['u_id'];
				scanTimeline();
		//		scanlist();
		}
		else
		{
				new_user($un,$snm,$aid,0);
				$ck="select * from user_accounts where account_id='$aid' and at_id=2";
				$cc=mysqli_query($conn,$ck);
			if(mysqli_num_rows($cc)>0)
			{
				$gg=mysqli_fetch_array($cc);
				$account_id=$gg['account_id'];	$screen_name=$gg['account_name'];
				$user_id=$gg['u_id'];
				scanTimeline();
					//scanlist();
			}	
	
}
}
	//print_r($profilescr);

?>