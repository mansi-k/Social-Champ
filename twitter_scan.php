<?php 
//include("twitter_func.php");
use Abraham\TwitterOAuth\TwitterOAuth;
include('twitter_func.php');

function scanTimeline()
{	
	global $user_name,$account_id,$screen_name,$connection;
	//$tweet=array();
	$total_likes=array();
	$total_subscribed_lists=array("");
	try{
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
		
			
		/*$tweethash=$connection->get('search/tweets',['q'=>'#ngo','count'=>50,'result_type'=>'recent']);
		$_SESSION['tweet']=$tweethash;
			$totaltweets[]=$tweethash;
		//print_r($totaltweets);
		scanTweets_byhash($tweethash);*/
		
		/*echo "<b>other Likes</b><br>";
		$tweet_likes= $connection->get('favorites/list',['screen_name'=>$screen_name,'count'=>20]);
		$total_likes[]=$tweet_likes;
		if(!empty($tweet_likes))
		scanlikes($tweet_likes);*/
		
		echo "<b>comments</b><br>";
		with_oauth();
		//scan_comments();

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
		/*
echo "<b>added to</b><br>";
$acursor=-1;	
while($acursor!=0)
{	
	$addedtolist=$connection->get('lists/memberships',['screen_name'=>$screen_name,'count'=>100,'cursor'=>$acursor]);
	$_SESSION['$total_addedto_lists']=$total_addedto_lists[]=$addedtolist;
	$acursor=$addedtolist->next_cursor;	
	scan_addedto_list();
}
	*/	

		//print_r($total_member);
		//echo "<br><br>";
	
		//$t_member[]=$total_member;
		//print_r($total_member);
		//print_r($prospective);
		/*retweeters();
		global $ids;*/
			$ids=array();
		
	}
	catch(TwitterException $e)
	{
		echo "twitter returned an error".$e->getStatusCode();
	}
	
	
}
