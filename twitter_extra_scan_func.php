<?php 
//include("twitter_func.php");
use Abraham\TwitterOAuth\TwitterOAuth;
include('twitter_timeline_func.php');
include('twitter_func_defbyme.php');
include('twitter_points.php');
include('twitter_update_record.php');
$respost=array();
function scanPost($connection,$bid)
{
	global $conn,$points_db;	
	$id=$bid;
	
	$rpostes=array();
	$respost=array();
	//$post=$connection->get('statuses/show/id',['$id'=>$id]);
	$post=$connection->get('statuses/show', array('id' => $id, 'include_entities' => true, 'include_my_retweet'=>true));
	$_SESSION['respost']=$rpostes[]=$respost[]=$post;
	//print_r($respost);
	echo "<br><b>Posted By</b><br>";
	foreach ($respost as $tg)
	{
		$ourngonm= $tg->user->name;
		$ourngosn= $tg->user->screen_name;
		$ourngoid= $tg->user->id_str;
		$time=$tg->created_at;
		//echo $ourngonm;
		
		
		if(!searchOurNGO($ourngoid,$ourngosn))
		{
			echo $ourngonm."->17";
				$test="SELECT u_id FROM user_accounts WHERE account_id='$ourngoid' AND at_id=2";
				$result=mysqli_query($conn,$test);
				if(mysqli_num_rows($result)>0)
				{
					$res=mysqli_fetch_array($result);
					$pid=$res['u_id'];
					check_existing($pid,$id,$time,$ourngonm,$ourngosn,$ourngoid,$respost);
				}	
			else
			{
				//echo "$r_prspctv_nm"."->"."$r_prspctv_scr<br>";	
						$test="SELECT u_id FROM user_accounts WHERE account_id='$ourngoid' AND at_id=2";
						$result=mysqli_query($conn,$test);
						if(mysqli_num_rows($result)==0)
						{
							$m_prspctv_scr=0;
							new_user($ourngonm,$ourngosn,$ourngoid,$m_prspctv_scr);
							$test="SELECT u_id FROM user_accounts WHERE account_id='$ourngoid' AND at_id=2";
							
							$re=mysqli_query($conn,$test);
							if(mysqli_num_rows($re)>0)
							{
							$res=mysqli_fetch_array($re);
							$pid=$res['u_id'];
							check_existing($pid,$id,$time,$ourngonm,$ourngosn,$ourngoid,$respost);
							}
						}
			}
		}		
	}		
	if(searchOurNGO($ourngoid,$ourngosn))
	{
		foreach ($respost as $tg)
		{	
			echo "<br><b>retweeters</b><br>";
			if(isset($tg->retweet_count))
			{
				$postr=$connection->get('statuses/retweets', array('id' => $id,'count'=>100));
				$respostr[]=$postr;		
				foreach($respostr as $tt)	
				{
					foreach($tt as $rt)
					{
							//echo $rt->user->location;
							//$rloc=strtolower($rt->user->location);
							//if(stripos($rt,'india')||empty($rloc))
						$r_prspctv_sn=mysqli_real_escape_string($conn,$rt->user->screen_name);
						$r_prspctv_nm=mysqli_real_escape_string($conn,$rt->user->name);
						$r_prspctv_id=$rt->user->id_str;	
						foreach($points_db as $listitem)
						{
							$type = $listitem['p_name'];
							$acc = $listitem['pa_type'];
							if ($type =='n_share' && $acc==2)
							{
								$r_prspctv_scr= $listitem['points'];
							}
						}
						//print_r($respostr);
						echo "$r_prspctv_nm"."->"."$r_prspctv_scr<br>";	
						$test="SELECT u_id FROM user_accounts WHERE account_id='$ourngoid' AND at_id=2";
						$result=mysqli_query($conn,$test);
						if(mysqli_num_rows($result)>0)
						{
							$res=mysqli_fetch_array($result);
							$pid=$res['u_id'];						json_retweeters($pid,$bid,$r_prspctv_nm,$r_prspctv_sn,$r_prspctv_id,$r_prspctv_scr);
						}
					}
					//print_r($respostr);
				}	
			}
		}
	}
	else
	{
		echo " <br><b>retweeters</b><br>";					
		if(isset($tg->retweet_count))
		{
			$postr=$connection->get('statuses/retweets', array('id' => $id,'count'=>100));
			$respostr[]=$postr;
			foreach($respostr as $tt)	
			{
				foreach($tt as $rt)
				{
					//echo $rt->user->location;
					//$rloc=strtolower($rt->user->location);
					//if(stripos($rt,'india')||empty($rloc))
					//echo " retweeted by ".$rt->user->screen_name;
					$r_prspctv_sn=mysqli_real_escape_string($conn,$rt->user->screen_name);
					$r_prspctv_nm=mysqli_real_escape_string($conn,$rt->user->name);
					$r_prspctv_id=$rt->user->id_str;		
					foreach($points_db as $listitem)
					{
						$type = $listitem['p_name'];
						$acc = $listitem['pa_type'];
						if ($type =='o_share' && $acc==2)
						{
							$r_prspctv_scr= $listitem['points'];
						}
					}
					echo "$r_prspctv_nm"."->"."$r_prspctv_scr<br>";	
					$test="SELECT u_id FROM user_accounts WHERE account_id='$ourngoid' AND at_id=2";
					$result=mysqli_query($conn,$test);
					if(mysqli_num_rows($result)>0)
					{
						$res=mysqli_fetch_array($result);
						$pid=$res['u_id'];		json_retweeters($pid,$bid,$r_prspctv_nm,$r_prspctv_sn,$r_prspctv_id,$r_prspctv_scr);
									
					}
				}
			}
			//print_r($respostr);
		}		
	}
}		
function scanList($connection,$osn,$slug)
{
	global $points_db,$conn;
	if(searchOurNGO_list($osn))
	 {
				$profilescr=array();
	
				$profile=$connection->get('users/show',['screen_name'=>$osn]);
				//echo $profile;
				$profilescr[]=$profile;
	foreach($profilescr as $key)
	{
		$onrsn=$key->screen_name;
		$onr=$prspctv_id=$key->id_str;
		$onrnm=$key->name;
	}
		
			
		 foreach($points_db as $listitem)
		{
			$type = $listitem['p_name'];
			$acc = $listitem['pa_type'];
			if ($type =='n_ownlist' && $acc==2)
			{
				$n_ownlist= $listitem['points'];
			}
		}
		//echo " owner id".$onr."<br>";
		$list=array();
			$tst="SELECT u_id FROM user_accounts WHERE account_id='$onr' AND at_id=2";
		$reslt=mysqli_query($conn,$tst);
		if(mysqli_num_rows($reslt)==0)
		{
			new_user($onrnm,$onrsn,$onr,n_ownlist);
		}
		
		
		
		
		$test="SELECT u_id FROM user_accounts WHERE account_id='$onr' AND at_id=2";
		$result=mysqli_query($conn,$test);		
		if(mysqli_num_rows($result)>0)
		{
			$res=mysqli_fetch_array($result);
			$mid=$res['u_id'];
			$last="select * from ngo_social_objects where owner_nm='$osn' AND so_name='$slug' and u_id='$mid'";
			$ex=mysqli_query($conn,$last);
			if(mysqli_num_rows($ex)==0)
			{
				$lstq="insert into ngo_social_objects(u_id,so_name,owner_nm,so_type_id,at_id,is_sw)values('$mid','$slug','$osn','4','2','yes')";
				$resl=mysqli_query($conn,$lstq);
				if(!resl)
				{
					echo " problem <br>";
				}
			}
			
			
			
			$test="select * from user_scan_response where u_id='$mid' AND us_type=4";
			$op=mysqli_query($conn,$test);
			if(mysqli_num_rows($op)>0)
			{
				$tt=mysqli_fetch_array($op);
				$list=json_decode($tt['us_response']);
				if(!empty($list))
				{
					foreach($list as $pg)
					{
						if(empty($pg->list_id))
						{
							continue;
						}
						else
						{
							$listslug[]=$pg->list_id;
						}
					}
					if(!empty($listslug))
					{
						if(!in_array($slug,$listslug))
						{
							$list[]=['listid'=>'','listslug'=>$slug,'members'=>[],'subscribers'=>[]];
							$lst=json_encode($list);;
							$ud="update user_scan_response set us_response='$lst' where user_scan_id=$mid and us_type=4";
							$rt=mysqli_query($conn,$ud);
						if($rt)
						{
							//echo " new list slug inserded<br>";
						}
						else
						{
							//echo "problem while inserting new tweet";
						}
						}
						else
						{
							//echo " list is alredy present";
						}
					}
					
				}
				else
					{
						$list[]=['listid'=>'','listslug'=>$slug,'members'=>[],'subscribers'=>[]];
						$lt=json_encode($list);
						$ud="update user_scan_response set  us_response='$lt' where user_scan_id=$mid and us_type=4";
						$rt=mysqli_query($conn,$ud);
						if($rt)
						{
							//echo " su";
							givelstpt($onr,$onrsn,$onrnm,$n_ownlist);
						}
						else{
							echo "problem 1 <br>";
						}
					}
			}
			else{
				$ltt[]=['listid'=>'','listslug'=>$slug,'members'=>[],'subscribers'=>[]];
				$lr=json_encode($ltt);
				$ud="insert into user_scan_response(user_scan_id,us_response,us_type) values ('$mid','$lr','4')";
		$rt=mysqli_query($conn,$ud);
		if($rt)
		{
			//echo " yes";
			//givelistpt();
			givelstpt($onr,$onrsn,$onrnm,$n_ownlist);
		}
		else
		{
			echo "problem 2";
		}
			}
		
		 foreach($points_db as $listitem)
		{
			$type = $listitem['p_name'];
			$acc = $listitem['pa_type'];
			if ($type =='n_listmem' && $acc==2)
			{
				$n_listmem= $listitem['points'];
			}
		}
		foreach($points_db as $listitem)
		{
			$type = $listitem['p_name'];
			$acc = $listitem['pa_type'];
			if ($type =='n_listsub' && $acc==2)
			{
				$n_listsub= $listitem['points'];
			}
		}
		scan_members($onr,$osn,$onrnm,$slug,$nlistmem);
		scan_subs($onr,$osn,$onrnm,$slug,$nlistsub);
	 }
	else
	{
		
	}
	}
	 else
	 {
		 $profilescr=array();
	
				$profile=$connection->get('users/show',['screen_name'=>$osn]);
				//echo $profile;
				$profilescr[]=$profile;	global $list;
				$list=array();
	
	
	//$trtrs=array();
	//print_r($profilescr);
	$m_prspctv_scr=0;
	foreach($profilescr as $key)
	{
		$olsn=$prspctv_sn=$key->screen_name;
		$olid=$prspctv_id=$key->id_str;
		$olnm=$prspctv_nm=$key->name;
		$prspctv_des=$key->description;
		foreach($points_db as $listitem)
		{
			$type = $listitem['p_name'];
			$acc = $listitem['pa_type'];
			if ($type =='o_listfrom' && $acc==2)
			{
				$m_prspctv_scr= $listitem['points'];
				//echo "points".$m_prspctv_scr."<br><br>";
			}
		}
	}
		//echo " ownr id"."$olid"."<br>";
		echo "$prspctv_nm"."->"."$m_prspctv_scr<br>";
		 	$tst="SELECT u_id FROM user_accounts WHERE account_id='$olid' AND at_id=2";
		$reslt=mysqli_query($conn,$tst);
		if(mysqli_num_rows($reslt)==0)
		{
			new_user($olnm,$olsn,$olid,0);
		}
		$test="SELECT u_id FROM user_accounts WHERE account_id='$olid' AND at_id=2";
		$result=mysqli_query($conn,$test);		
		if(mysqli_num_rows($result)>0)
		{
			$res=mysqli_fetch_array($result);
			$mid=$res['u_id'];
			
			
			$last="select * from ngo_social_objects where owner_nm='$osn' AND so_name='$slug' AND u_id='$mid'";
			$ex=mysqli_query($conn,$last);
			if(mysqli_num_rows($ex)==0)
			{
				$lstq="insert into ngo_social_objects(u_id,so_name,owner_nm,so_type_id,at_id,is_sw)values('$mid','$slug','$olsn','4','2','yes')";
				$resl=mysqli_query($conn,$lstq);
				if(!$resl)
				{
					echo " problem <br>";
				}
			}
			
			
			
			$test="select * from user_scan_response where user_scan_id='$mid' AND us_type=4";
			$op=mysqli_query($conn,$test);
			if(mysqli_num_rows($op)>0)
			{
				$tt=mysqli_fetch_array($op);
				$list=json_decode($tt['us_response']);
				if(!empty($list))
				{
					foreach($list as $pg)
					{
						if(empty($pg->listslug))
						{
							continue;
						}
						else
						{
							$listslug[]=$pg->listslug;
						}
					}
					if(!empty($listslug))
					{
						if(!in_array($slug,$listslug))
						{
							$list[]=['listid'=>'','listslug'=>$slug,'members'=>[],'subscribers'=>[]];
							$lst=json_encode($list);;
							$ud="update user_scan_response set us_response='$lst' where user_scan_id=$mid and us_type=4";
							$rt=mysqli_query($conn,$ud);
						if($rt)
						{
							//echo " new list slug inserded<br>";
						}
						else
						{
							//echo "problem while inserting new tweet";
						}
						}
						else
						{
							//echo " list is alredy present";
						}
					}
					
				}
				else
					{
						$lst=['listid'=>'','listslug'=>$slug,'members'=>[],'subscribers'=>[]];
						$lt=json_encode($lst);
						$ud="update user_scan_response set  us_response='$lt' where user_scan_id=$mid and us_type=4";
						$rt=mysqli_query($conn,$ud);
						if($rt)
						{
							givelstpt($olid,$olsn,$olnm,$m_prspctv_scr);
						}
						else{
							echo "problem 1 <br>";
						}
					}
			}
			else{
				$ltt[]=['listid'=>'','listslug'=>$slug,'members'=>[],'subscribers'=>[]];
				$lr=json_encode($ltt);
				$ud="insert into user_scan_response(user_scan_id,us_response,us_type) values ('$mid','$lr','4')";
		$rt=mysqli_query($conn,$ud);
		if($rt)
		{
			givelstpt($olid,$olsn,$olnm,$m_prspctv_scr);
		}
		else
		{
			echo "problem 2";
		}
			}
		
	}
	      $o_listmem=0;
		 foreach($points_db as $listitem)
		{
			$type = $listitem['p_name'];
			$acc = $listitem['pa_type'];
			if ($type =='o_listmem' && $acc==2)
			{
				$o_listmem= $listitem['points'];
			}
		}
		 foreach($points_db as $listitem)
		{
							$type = $listitem['p_name'];
							$acc = $listitem['pa_type'];
							if ($type =='o_listsub' && $acc==2)
							{
								$o_listsub= $listitem['points'];
							}
						}
		scan_members($olid,$olsn,$olnm,$slug,$o_listmem);
		scan_subs($olid,$olsn,$olnm,$slug,$o_listsub); 
	   }
	
	   
	   
}
function givelstpt($onr,$onrsn,$onrnm,$lpt)
{
	global $conn;
	$test="SELECT u_id FROM user_accounts WHERE account_id='$onr' AND at_id=2";
		$result=mysqli_query($conn,$test);
		
		if(mysqli_num_rows($result)>0)
		{		
		
		
			$t=mysqli_fetch_array($result);
			$pid=$t['u_id'];
			update_existing($pid,$lpt);
		}
}
function scan_subs($onr,$osn,$onrnm,$slug,$pt)
{
	echo "<br><b>subscribers of the list</b><br>";
	global $connection;
	
		$sbcursor=-1;
		while($sbcursor!=0)
		{
		
			//echo "<br>list...".$lst."<br>";
			$subscribers=$connection->get('lists/subscribers',['owner_screen_name'=>$osn,'slug'=>$slug,'count'=>5000,'cursor'=>$sbcursor]);
			$_SESSION['subscribers']=$total_subscribers[]=$subscribers;
			$sbcursor=$subscribers->next_cursor;
		//if(!empty($memberlist))
	//scan_membersof_lists();
			//print_r($total_subscribers);
			}
			
		global $conn;
		foreach($total_subscribers as $pg)
		{
			//echo $pg['users']->screen_name;
			foreach($pg->users as $key)	
			{
				//echo $key->screen_name."<br>";
				if(isset($key->screen_name))
				{
					//echo $key->screen_name."<br>";
					$prspctv_sn=mysqli_real_escape_string($conn,$key->screen_name);
					$prspctv_id=$key->id_str;
					$prspctv_nm=mysqli_real_escape_string($conn,$key->name);
					echo  "$prspctv_nm"."->"."$pt<br>";
					//$prspctv_des=$key->description;
					
			if(!searchOurNGO($prspctv_id,$prspctv_sn))
				{
				$test="SELECT u_id FROM user_accounts WHERE account_id='$prspctv_id' AND at_id=2";
				$result=mysqli_query($conn,$test);
				
				if(mysqli_num_rows($result)>0)
				{
					$res=mysqli_fetch_array($result);
					$pid=$res['u_id'];
					json_listsub($onr,$osn,$onrnm,$pid,$slug,$prspctv_id,$pt);
					//update_existing($pid,$pt);
					//json_listmem($onr,$osn,$onrnm,$pid,$slug,$prspctv_id,$pt);
	
				}
				else
				{
					new_user($prspctv_nm,$prspctv_sn,$prspctv_id,0)	;
					$test="SELECT u_id FROM user_accounts WHERE account_id='$prspctv_id' AND at_id=2";
					$result=mysqli_query($conn,$test);
					if(mysqli_num_rows($result)>0)
					{
					$res=mysqli_fetch_array($result);
					$ppid=$res['u_id'];
					json_listsub($onr,$osn,$onrnm,$ppid,$slug,$prspctv_id,$pt);
				//json_listmem($onr,$osn,$onrnm,$pid,$slug,$prspctv_id,$pt);
					
				}
					   
				}
			
			}}
}
}
}
function scan_members($onr,$osn,$onrnm,$slug,$pt)
{
	echo "<br><b>Members of the list</b><br>";
	global $connection;
	$mcursor=-1;
	while($mcursor!=0)
	{	
		//echo "<br>list...".$lst."<br>";
		$memberlist=$connection->get('lists/members',['owner_screen_name'=>$osn,'slug'=>$slug,'count'=>5000,'cursor'=>$mcursor]);
		$_SESSION['$total_member']=$total_member[]=$memberlist;
		//print_r($total_member);
		$mcursor=$memberlist->next_cursor;
		//if(!empty($memberlist))
		//scan_membersof_lists();
		//print_r($total_member);
	}	
	global $prospective,$conn;
	foreach($total_member as $pg)
	{
		//echo $pg['users']->screen_name;
		foreach($pg->users as $key)		
		{
			//echo $key->screen_name."<br>";
			/*if(isset($key->screen_name))
			{
			//echo $key->screen_name."<br>";*/
			$prspctv_sn=mysqli_real_escape_string($conn,$key->screen_name);
			$prspctv_id=$key->id_str;
			//$prspctv_sc=2;
			$prspctv_nm=mysqli_real_escape_string($conn,$key->name);
			$prspctv_des=$key->description;
			
			if(!searchOurNGO($prspctv_id,$prspctv_sn))
			{
				
				if(!search_prosp($prspctv_sn,$prspctv_nm,$prspctv_des))
				{
					echo  "$prspctv_nm"."->"."$pt<br>";
				//echo $prspctv_sn." <br>";
				$test="SELECT u_id FROM user_accounts WHERE account_id='$prspctv_id' AND at_id=2";
				$result=mysqli_query($conn,$test);
				
				if(mysqli_num_rows($result)>0)
				{
					$res=mysqli_fetch_array($result);
					$pid=$res['u_id'];
					json_listmem($onr,$osn,$onrnm,$pid,$slug,$prspctv_id,$pt);//update_existing($pid,$pt);
				}
				else
				{
					new_user($prspctv_nm,$prspctv_sn,$prspctv_id,0)	;
					
					$test="SELECT u_id FROM user_accounts WHERE account_id='$prspctv_id' AND at_id=2";
					$result=mysqli_query($conn,$test);
					if(mysqli_num_rows($result)>0)
					{
					$res=mysqli_fetch_array($result);
					$ppid=$res['u_id'];
					json_listmem($onr,$osn,$onrnm,$ppid,$slug,$prspctv_id,$pt);
				}
					   
			}
			
			}
}
}
	}
}



function check_existing($uid,$twt,$time,$onm,$osn,$oid,$respost)
{

	global $conn;
	
	$tweet=array();
	$rts=array();
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
			echo "<br>";
			if(!empty($tweetid))
			{
					if(!in_array($twt,$tweetid))
					{				
						$tweet[]=['tweet_id'=>$twt,'retweeters'=>[],'scanned_time'=>$time];
						$twt=json_encode($tweet);
						//print_r($twt);
						$ud="update user_scan_response set us_response='$twt' where user_scan_id=$uid and us_type=1";
						$rt=mysqli_query($conn,$ud);
						if($rt)
						{
							//echo " new tweet id inserted<br>";
							givepoints($uid,$onm,$osn,$oid,$respost);
						}
						else
						{
							echo "problem while inserting new tweet";
						}
					}
					else
					{
						//echo " tweet already scanned"."<br>";
					}
				}
			}
		
		else{
			
			$tweet[]=['tweet_id'=>$twt,'retweeters'=>[],'scanned_time'=>$time];
			$twt=json_encode($tweet);
			
		//print_r($twt);
		$ud="update user_scan_response set  us_response='$twt' where user_scan_id=$uid and us_type=1";
		$rt=mysqli_query($conn,$ud);
		if($rt)
		{
			//echo " success";
			givepoints($uid,$onm,$osn,$oid,$respost);
		}
		else{
			echo "problem1 while inserting tweet<br>";
		}
		}}
		
	else
	{
		//echo " <b>entered into else</b><br>";
		$rts=array();
		
			$rts[]=['tweet_id'=>$twt,'retweeters'=>[],'scanned_time'=>$time];
			$twt=json_encode($rts);
		//print_r($twt);
		$ud="insert into user_scan_response(user_scan_id,us_response,us_type) values ('$uid','$twt',1)";
		$rt=mysqli_query($conn,$ud);
		if($rt)
		{
			//echo " yes";
		givepoints($uid,$onm,$osn,$oid,$respost);
		}
		else
		{
			echo "problem 2 while inserting<br>";
		}
		
	}
	//delete_object();
}

function givepoints($uid,$onm,$osn,$oid,$respost)
{
	global $points_db,$conn;
	$m_prspctv_scr=0;
		foreach($points_db as $listitem)
		{
			$type = $listitem['p_name'];
			$acc = $listitem['pa_type'];
			if ($type =='o_from' && $acc==2)
			{
				$m_prspctv_scr= $listitem['points'];
				//echo "points".$m_prspctv_scr."<br><br>";
			}
		}
		$test="SELECT u_id FROM user_accounts WHERE account_id='$oid' AND at_id=2";
		$result=mysqli_query($conn,$test);
		if(mysqli_num_rows($result)>0)
		{
			$res=mysqli_fetch_array($result);
			$pid=$res['u_id'];
			update_existing($pid,$m_prspctv_scr);
			//echo " $m_prspctv_scr"."<br>";
		}
		/*else
		{
			new_user($onm,$osn,$oid,$m_prspctv_scr)	;
		}	*/
	
	//$respost=$_SESSION['respost'];
	
	//print_r($respost);
	echo "<br><b>mentioned users</b><br>";
	if(searchOurNGO($oid,$osn))
	{
		foreach ($respost as $tg)
		{			
			
			foreach($tg->entities->user_mentions as $key)
			{
					if(!empty($key))
					{
					$m_prspctv_sn=mysqli_real_escape_string($key->screen_name);
					$m_prspctv_nm=mysqli_real_escape_string($key->name);
					$m_prspctv_id=$key->id_str;		
					foreach($points_db as $listitem)
					{
						$type = $listitem['p_name'];
						$acc = $listitem['pa_type'];
						if ($type =='n_posttag' && $acc==2)
						{
							$m_prspctv_scr= $listitem['points'];
							//echo "points".$m_prspctv_scr."<br><br>";
						}
					}				
					echo "$m_prspctv_nm"."->"."$m_prspctv_scr<br>";		
					$test="SELECT u_id FROM user_accounts WHERE account_id='$m_prspctv_id' AND at_id=2";
					$result=mysqli_query($conn,$test);
					if(mysqli_num_rows($result)>0)
					{
						$res=mysqli_fetch_array($result);
						$pid=$res['u_id'];
						update_existing($pid,$m_prspctv_scr);
					}
					else
					{													       
						new_user($m_prspctv_nm,$m_prspctv_sn,$m_prspctv_id,$m_prspctv_scr)	;
					}		
												
				
			}
			
		}}
	
	
}
	else
	{
		//echo " entered into else<br>";
		foreach ($respost as $tg)
		{
			foreach($tg->entities->user_mentions as $key)
			{
				if(!empty($key))
				{
				
				$m_prspctv_sn=mysqli_real_escape_string($conn,$key->screen_name);
				$m_prspctv_nm=mysqli_real_escape_string($conn,$key->name);
				$m_prspctv_id=$key->id_str;				
				foreach($points_db as $listitem)
				{
					$type = $listitem['p_name'];
					$acc = $listitem['pa_type'];
					if ($type =='o_posttag' && $acc==2)
					{
						$m_prspctv_scr= $listitem['points'];
					}
				}
				echo "$m_prspctv_nm"."->"."$m_prspctv_scr<br>";									$test="SELECT u_id FROM user_accounts WHERE account_id='$m_prspctv_id' AND at_id=2";
				$result=mysqli_query($conn,$test);
				if(mysqli_num_rows($result)>0)
				{
					$res=mysqli_fetch_array($result);
					$pid=$res['u_id'];
					update_existing($pid,$m_prspctv_scr);
				}
				else
				{												    
					new_user($m_prspctv_nm,$m_prspctv_sn,$m_prspctv_id,$m_prspctv_scr)	;
				}										
			
			}
		}}}
	}

?>
					
			
	