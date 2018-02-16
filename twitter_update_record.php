<?php
include( "connection.php");
function update_response($uid)
{
	global $ids,$conn;
	//global $conn;
	$tweet=array();
	$rts=array();
	$twt;
	$txt=" select * from user_scan_response where user_scan_id=$uid and us_type=1";
	$t=mysqli_query($conn,$txt);
	if(mysqli_num_rows($t)>0)
	{
		//echo " entered into if <br>";
		$tt=mysqli_fetch_array($t);
		$tweet=json_decode($tt['us_response']);
		$tweetid=array();$rts=array();
		if(!empty($tweet))
		{	
			foreach( $tweet as $page)
			{
				if(empty($page->tweet_id))
				{
				   continue;
				}
				else
				{
					$tweetid[]=$page->tweet_id;
				}
			}
			//echo " id array<br>";
			//print_r($ids);
			//echo "<br>";
			if(!empty($tweetid))
			{
				foreach ($ids as $idz)
				{
					if(empty ($idz['tweetid']))
					{
						continue;
					}
					if(!in_array($idz['tweetid'],$tweetid))
					{				
						$tweet[]=['tweet_id'=>$idz['tweetid'],'retweeters'=>[],'scanned_time'=>$idz['created_at']];
						$twt=json_encode($tweet);
						//print_r($twt);
						
						$ud="update user_scan_response set us_response='$twt' where user_scan_id=$uid and us_type=1";
						$rt=mysqli_query($conn,$ud);
						if($rt)
						{
							echo " new retweet id inserded<br>";
						}
						else
						{
							echo "problem while inserting new tweet";
						}
					}
					else
					{
						echo "tweet is already present"."<br>";
					}
				}
			}
		}
		else{
			foreach($ids as $ti)
			{
				if(empty($ti))
			{continue;}
				else{
			$tweet[]=['tweet_id'=>$ti['tweetid'],'retweeters'=>[],'scanned_time'=>$ti['created_at']];
			$twt=json_encode($tweet);}
			}
		//print_r($twt);
			if(!empty($twt))
		{
		$ud="update user_scan_response set  us_response='$twt' where user_scan_id=$uid and us_type=1";
		$rt=mysqli_query($conn,$ud);
		if(!$rt)
		{
			echo " problem while updating usr<br>";
		}
			}
		
		}
	}	
	else
	{
		//echo " <b>entered into else</b><br>";
		$rts=array();
		//print_r($ids);
		foreach($ids as $ti)
		{
			if(empty($ti))
			{continue;}
			else
			{
				
			$rts[]=['tweet_id'=>$ti['tweetid'],'retweeters'=>[],'scanned_time'=>$ti['created_at']];
			$twt=json_encode($rts);
			}
		}
		//print_r($twt);
		if(!empty($twt))
		{
		$ud="insert into user_scan_response(user_scan_id,us_response,us_type) values ('$uid','$twt','1')";
		$rt=mysqli_query($conn,$ud);
		if(!$rt)
		{
			echo " problem while inserting response in usr<br>";
		}
		}
		
		
	}
	//delete_object();
}
function delete_object()
{
	$txt=" select * from user_scan_response where user_scan_id=$uid";
	$t=mysqli_query($conn,$txt);
	if(mysqli_num_rows($t)>0)
	{
		$test=mysqli_fetch_array($t);
		$res=json_decode($test['us_response']);
		date_default_timezone_set("UTC");
		$c_date= date("Y-m-d h:i:s");
		foreach($res as $response=>$value)
		{
			//if(in_array($response))
			print_r($value);
			echo "<br>";
		}
	}
}
function json_retweeters($uid,$rt_id,$r_prspctv_nm,$r_prspctv_sn,$r_prspctv_id,$r_prspctv_scr)
{
	global $conn;
	$txt=" select * from user_scan_response where user_scan_id=$uid and us_type=1";
	$t=mysqli_query($conn,$txt);
	if(mysqli_num_rows($t)>0)
	{
			$test=mysqli_fetch_array($t);
		$res=json_decode($test['us_response']);
		foreach($res as $response )
		
		{
			//echo " retweet found.".$rt_id."<br>";
			//echo " db tw id:".$response->tweet_id."<br>";
			if($response->tweet_id==$rt_id)
			{
				if(!in_array($r_prspctv_id,$response->retweeters))
				{
				//echo "  tweet id found in db:".$rt_id."<br>";
				array_push($response->retweeters,$r_prspctv_id);
				//print_r($res);
				$udt=json_encode($res);	
				//print_r($udt);
				$up="update user_scan_response set  us_response='$udt' where user_scan_id=$uid and us_type=1";
				$gh=mysqli_query($conn,$up);
				if($gh)
				{
					
					$test="SELECT u_id FROM user_accounts WHERE account_id='$r_prspctv_id' AND at_id=2";
					$result=mysqli_query($conn,$test);							if(mysqli_num_rows($result)>0)
					{
								$res=mysqli_fetch_array($result);
								$pid=$res['u_id'];	
							//scan_regular_retweeters($pid,$rid,$prspctv_id);
								
							update_existing($pid,$r_prspctv_scr);
							}
							else
							{
								new_user($r_prspctv_nm,$r_prspctv_sn,$r_prspctv_id,$r_prspctv_scr)	;
							}
				}
				else
				{
					echo "problem while inserting retweeters"."<br>";
				}  
				break;
				}
				else
				{
					//echo " this retweeters is already scanned<br>";
					break;
				}
			}
		}
		
	}
}
function json_listmem($onr,$osn,$onrnm,$pid,$slug,$psid,$scr)
{
	global $conn,$points_db;
	$sl=$slug;
	
	$test="SELECT u_id FROM user_accounts WHERE account_id='$onr' AND at_id=2";
	$result=mysqli_query($conn,$test);			
	if(mysqli_num_rows($result)>0)
	{
		$res=mysqli_fetch_array($result);
		$mid=$res['u_id'];
	$tst="select * from user_scan_response where user_scan_id=$mid AND us_type=4";
	$op=mysqli_query($conn,$tst);
	if(mysqli_num_rows($op)>0)
	{
		$tt=mysqli_fetch_array($op);
		$res=json_decode($tt['us_response']);
		foreach($res as $response)
		{
			//echo "$response->slug";
			if($response->listslug==$sl)
			{
				if(!in_array($psid,$response->members))
				{
					//echo " new meber found:".$psid."<br>";
					array_push($response->members,$psid);
					//print_r($res);
					$udt=json_encode($res);	
				//print_r($udt);
				$up="update user_scan_response set  us_response='$udt' where user_scan_id=$mid AND us_type=4";
				$gh=mysqli_query($conn,$up);
					if($gh)
				{
					update_existing($pid,$scr);
						
				}
					else
					{
						echo " problem while inserting adding new member";
					}
							
				}
				else
				{
					//echo " all members are already added<br>";
				}
				break;
			}
		}
	}
	
}}
function json_listsub($onr,$osn,$onrnm,$pid,$slug,$psid,$scr)
{
	global $conn,$points_db;
	$sl=$slug;
	
	$test="SELECT u_id FROM user_accounts WHERE account_id='$onr' AND at_id=2";
	$result=mysqli_query($conn,$test);			
	if(mysqli_num_rows($result)>0)
	{
		$res=mysqli_fetch_array($result);
		$mid=$res['u_id'];
	$tst="select * from user_scan_response where user_scan_id=$mid AND us_type=4";
	$op=mysqli_query($conn,$tst);
	if(mysqli_num_rows($op)>0)
	{
		$tt=mysqli_fetch_array($op);
		$res=json_decode($tt['us_response']);
		foreach($res as $response)
		{
			//echo "$response->slug";
			if($response->listslug==$sl)
			{
				if(!in_array($psid,$response->subscribers))
				{
					//echo " new meber found:".$psid."<br>";
					array_push($response->subscribers,$psid);
					//print_r($res);
					$udt=json_encode($res);	
				//print_r($udt);
				$up="update user_scan_response set  us_response='$udt' where user_scan_id=$mid AND us_type=4";
				$gh=mysqli_query($conn,$up);
					if($gh)
				{
					update_existing($pid,$scr);
						
				}
					else
					{
						echo " problem while inserting adding new subscriberr";
					}
							
				}
				else
				{
					//echo " all subscriber are already added<br>";
				}
				break;
			}
		}
	}
	
}}
?>