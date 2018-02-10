<?php 
use Abraham\TwitterOAuth\TwitterOAuth;
include('twitter_timeline_func.php');
include('twitter_list_func.php');
include("twitter_points.php");
include("twitter_update_record.php");
//include('twitter_regular_scan_func');

function scanTimeline()
{	
	global $user_name,$account_id,$screen_name,$connection,$conn;
	//$tweet=array();
	$total_likes=array();
	$total_subscribed_lists=array("");
	try{
		$test="SELECT u_id FROM user_accounts WHERE account_id='$account_id' AND at_id=2";
		$result=mysqli_query($conn,$test);			
		if(mysqli_num_rows($result)>0)
		{
			$res=mysqli_fetch_array($result);
			$pid=$res['u_id'];
			$tst="select * from scan_mx_id where u_id=$pid AND response_type=1";
			$reslt=mysqli_query($conn,$tst);
			$lk="select * from scan_mx_id where u_id=$pid AND response_type=3";
			$lkr=mysqli_query($conn,$lk);
			if(mysqli_num_rows($reslt)>0)
			{
				$rt=mysqli_fetch_array($reslt);
				$resp=$rt['response'];
				if(!empty($resp))
				{
				regular_scan_timeline($resp,$account_id);
				}
				else
				{
				echo "<br><br><b>screen name : ".$_SESSION['screen_name']."</b><br>";
				profile_scan($account_id);
				$tweet=$connection->get('statuses/user_timeline',['user_id'=>$account_id,'count'=>200,'exclude_replies'=>false]);
				$_SESSION['tweet']=$tw[]=$tweet;
				echo "<b>TWEETS</b><br>";
				if(!empty($tweet))
				{			
				scanTweets($tweet);	
				}
				}
			}
			else
			{
				global $list;
				$list=array();
				echo "<br><br><b>screen name : ".$_SESSION['screen_name']."</b><br>";
				profile_scan($account_id);
				$tweet=$connection->get('statuses/user_timeline',['user_id'=>$account_id,'count'=>200,'exclude_replies'=>false]);
				$_SESSION['tweet']=$tw[]=$tweet;
				echo "<b>TWEETS</b><br>";
				if(!empty($tweet))
				{			
				scanTweets($tweet);	
				}
			}
		
		if(mysqli_num_rows($lkr)>0)
		{
			$lkt=mysqli_fetch_array($lkr);
			$lkrsp=$lkt['response'];
			regular_scanlikes($lkrsp,$account_id);
		}
			else{
		echo "<b>other Likes</b><br>";
		$tweet_likes= $connection->get('favorites/list',['user_id'=>$account_id,'count'=>20]);
		$total_likes[]=$tweet_likes;
		if(!empty($tweet_likes))
		scanlikes($tweet_likes);
			}
		echo "<b>comments</b><br>";
		//with_oauth();
		//scan_comments();
		retweeters();
		global $ids;
		$ids=array();
		$respostr=array();
		
	}
	}
	catch(TwitterException $e)
	{
		echo "twitter returned an error".$e->getStatusCode();
	}	
}
function scanlist()
{
	global $connection,$conn,$points_db,$screen_name,$account_id;
		echo "<b>Owned Lists</b><br>";
		$cursor=-1;
		while($cursor!=0)
		{
			$ownedlist=$connection->get('lists/ownerships',['screen_name'=>$screen_name,'count'=>100,'cursor'=>$cursor]);
			$_SESSION['$total_owned_lists']=$total_owned_lists[]=$ownedlist;
			//print_r($total_owned_lists);
			$cursor=$ownedlist->next_cursor;
			scan_owned_lists();
		}
		echo "<b>subscribed Lists by user</b><br>";		
		$scursor=-1;	
		while($scursor!=0)
		{	
			$subscribedlist=$connection->get('lists/subscriptions',['screen_name'=>$screen_name,'count'=>100,'cursor'=>$scursor]);
			$_SESSION['$total_subscribed_lists']=$total_subscribed_lists[]=$subscribedlist;
			$scursor=$subscribedlist->next_cursor;	
			scan_subscribed_lists();
		}
		echo "<b>added to</b><br>";
		$acursor=-1;	
		while($acursor!=0)
		{	
			$addedtolist=$connection->get('lists/memberships',['screen_name'=>$screen_name,'count'=>100,'cursor'=>$acursor]);
			$_SESSION['$total_addedto_lists']=$total_addedto_lists[]=$addedtolist;
			$acursor=$addedtolist->next_cursor;	
			scan_addedto_list();
		}	
}
