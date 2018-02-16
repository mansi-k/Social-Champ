<?php
include("connection.php");

//$connection=$_SESSION['connection'];

//...........profile scan.............//
function profile_scan($accid)
{
	global $connection,$account_id,$screen_name,$conn;
	$des=0;$p_frnd=0;$p_follo=0;
	$profile=$connection->get('users/show',['user_id'=>$accid]);
	$profilescr[]=$profile;
	foreach($profilescr as $pro)
	{
		//echo "followers_count".$pro->followers_count;
		//echo "friends_count".$pro->friends_count;
		$p_des=$pro->description;					$p_name=$pro->name;	
		if(isset($pro->friends_count))
		{
				$p_frnd=$pro->friends_count;
		}
		if(isset($pro->followers_count))
		{
				$p_follo=$pro->followers_count;
		}							
		if(isset($pro->description) && nlp($p_des))
		{
			$des=5;
		}
									
		$frnd=ceil(($p_frnd/200)*10);							
		echo "profile score:".($des+$frnd+$p_follo)."<br>"	;					
	}
	//print_r($profilescr);
}
//............FOR POSTED TWEETS and retweets & LIKES,SHARES,COMMENTS OF THAT POSTS	............//
//global $ids;
	
function scanTweets($tweet)
{
	global $i,$user_name;
	$flag1=0;$flag2=0;
	$des=$p_follo=$p_frnd=$followers_count=$friends_count=0;$max_id=0;
	$ids=array();
	global $ids;
	global $connection,$account_id,$screen_name,$conn;
	$m_id=0;$lastid=0;
	$max=-1;
	echo "<b>Screen Name: ".$screen_name."</b><br>";
	try
	{
		$totaltweet[]=$tweet;
		$page=0;	  
		echo "<br>";
		$max2=count($totaltweet[$page])-1;
		if($max2==0)
			{
				$totaltweets[]=$totaltweet;
				
			}
		else{
		$m_id=$max_id=$totaltweet[$page][0]->id_str;	
		}
		while(!empty($tweet) && $max2!=0)
		{	
			//echo "no of records in page..."."$max"."<br>";
			
			//echo " scan till ..."."$max_id"."<br>";
			$tweet=$connection->get('statuses/user_timeline',['user_id'=>$account_id,'count'=>3,'exclude_replies'=>false,'max_id'=>$max_id]);
			if (empty($tweet))
			{
				break;
			}
			$tott[]=$tweet;
			$max=count($tott[$page])-1;
			if($max==0)
			{
				//$totaltweets[]=$tweet;
				break;
			}
			else{
			$mx_id=$tott[$page][$max]->id_str;
			unset($tott[$page][$max]);
			$totaltweets[]=$tott;
			//echo " last tweet no:".$max."<br>";
			//$since_id=$totaltweets[$page][$max]->id_str;
			$max_id=$mx_id;
			//echo " lower record (next max id)...".$max_id."<br>";
			//$page+=1;
			//$max=count($totaltweets[$page])-1;
				$tott=array();
		}
		}
		//print_r($totaltweets);	
		//$start=1;
		$tweetscr=0;$tweetlike=0;$tweets=0;$retweets=0;$retweetcnt=0;
		$repopcnt=$popcnt=$repop=$pop=0;$retweetscount=$otrngoshare=$retweet_s=0;$tweet_s=0;$tweet_sc=0;
		$tweet_ids=array();
		$ids[]=array();
		$k=0;$tweetno=0;
		global $points_db;
		foreach($totaltweets as $page)
		{ 	
			foreach ( $page as $pg)
			{
			foreach($pg as $key)
			{		
				$try=strtolower($key->text);
				//echo "$try"."<br><";
			 	if(isset($key->text)&&	(nlp($try) ||search_hashtags($try)))
				{		
					//++++++retweet+++++++++++++++//
					if(isset($key->retweeted_status)&& ($key->retweeted==1))//retweets
					{				
						echo "retweet..".$key->text."<br>";
						$tweetno+=1;
					 	$repop=$key->favorite_count;//likes
						$retweetscount=$key->retweet_count;//shares
						$retweetcnt=$retweetcnt+$retweetscount;
						$repopcnt=$repopcnt+$repop;
						
						//$ids[]=$key->id_str;
						
						$ids[]=['tweetid'=>$key->id_str,'created_at'=>$key->created_at];
							$sourcenm=$key->retweeted_status->user->name;
							$sourcesn=$key->retweeted_status->user->screen_name;
							$sourceid=$key->retweeted_status->user->id_str;
							
							if (searchOurNGO($sourceid,$sourcesn))//check if this user shared SA tweet
							{
								
								foreach($points_db as $listitem)
								{
    								$type = $listitem['p_name'];
    								$acc = $listitem['pa_type'];
									if ($type =='n_share' && $acc==2)
									{
										$ourngoshare= $listitem['points'];
									}
								}
								echo" mentioned SA in this retweet...."." n_share 12 points<br>";
								
								$retweet_s=$retweet_s+$ourngoshare;//points sharing SA's post
								//echo  " total retwets points: ".$retweet_s."<br>";
							}
							else
							{
								foreach($points_db as $listitem) 
								{
									$type = $listitem['p_name'];
									$acc = $listitem['pa_type'];
									if ($type =='o_share' && $acc==2)
									{
										$otrngoshare= $listitem['points'];
									}
								}
								$retweet_s=$retweet_s+$otrngoshare;	//points for sharing others post
								echo " shared someone else posts...."."o_share 10 points"."<br>";
								//check if SA is mentioned in others post
								if(!empty($key->entities->user_mentions ))
								{
									foreach($key->entities->user_mentions as $tg)
									{
										$sn=$tg->screen_name;
										$nm=$tg->name;
										$tgid=$tg->id_str;
										if(searchOurNGO($tgid,$sn))//if SA is mentioned
										{
											echo " SA is mentioned ->points given to source nn_from 18 : "."$sourcesn"."<br>";
											
											$test="SELECT u_id FROM user_accounts WHERE account_id='$source_id' AND at_id=2";
											$result=mysqli_query($conn,$test);
											foreach($points_db as $listitem)
											{
												$type = $listitem['p_name'];
												$acc = $listitem['pa_type'];
												if ($type =='nn_from' && $acc==2)
												{
													$posttagfrom_sc=$listitem['points'];//points given to source user who tagged SA in the post
												}
											}
											if(mysqli_num_rows($result)>0)
											{
												$res=mysqli_fetch_array($result);
												$pid=$res['u_id'];
												update_existing($pid,$posttagfrom_sc);
											}
											else
											{
											new_user($sourcenm,$sourcesn,$sourceid,$posttagfrom_sc)	;
											}
										}
										else
										{
											if(!search_prospective($nm,$sn))
											{
											echo " SA not mentioned ->other mentioned users->15"."$sn"."<br>";
											$test="SELECT u_id FROM user_accounts WHERE account_id='$tgid' AND at_id=2";
											$result=mysqli_query($conn,$test);
											
												foreach($points_db as $listitem) 
												{
													$type = $listitem['p_name'];
													$acc = $listitem['pa_type'];
													if ($type =='o_posttag' && $acc==2)
													{
														$prosp_s= $listitem['points'];
													}
												}
											if(mysqli_num_rows($result)>0)
											{
												$res=mysqli_fetch_array($result);
												$pid=$res['u_id'];
												update_existing($pid,$prosp_s);
											}
											else
											{
											new_user($nm,$sn,$tgid,$prosp_s	);
											}
									
										}
									}
								}
							}			
						}
						//echo "..likes..".$repop;
						//echo "..shares..".$retweetscount;
					}
					
					else//self posted tweets
					{
						echo "*self post*..".$key->text."<br>";
						//$tweetcount+=17;
						$tweetno+=1;
						$pop=$key->favorite_count;//likes
						$retweetscount=$key->retweet_count;//shares
						$retweetcnt=$retweetcnt+$retweetscount;
						$popcnt=$popcnt+$pop;
						//$ids[]=$key->id_str;
						$ids[]=['tweetid'=>$key->id_str,'created_at'=>$key->created_at];
						
						//echo "..likes..".$pop;
						//echo "..shares..".$retweetscount;
						if (!empty ($key->entities->user_mentions))
						{
						foreach($key->entities->user_mentions as $tag)
						{
							//if(isset($tag))
							//{ 
								//prospective
							$prspctv_sn= strtolower($tag->screen_name);
							$prspctv_nm=strtolower( $tag->name);
							$prspctv_id=$tag->id_str;
							if(searchOurNGO($prspctv_id,$prspctv_sn))
							{
								foreach($points_db as $listitem)
								{
    								$type = $listitem['p_name'];
									$acc = $listitem['pa_type'];
									if ($type =='nn_from' && $acc==2)
									{
										$tweet_sc= $listitem['points'];
									}
								}
								$tweet_s+=$tweet_sc;
								echo " SA is mentioned in self post ->nn_from points 18"."<br>";
							}
							else  //(!searchOurNGO($prspctv_nm,$prspctv_sn))
							{
								if(!search_prospective($prspctv_nm,$prspctv_sn))
								{
									foreach($points_db as $listitem)
									{
										$type = $listitem['p_name'];
										$acc = $listitem['pa_type'];
										if ($type =='o_posttag' && $acc==2)
										{
											$prspctv_sc= $listitem['points'];
										}
									}
									
									echo " other's mentioned in self post ->o_posttag points 15".$prspctv_sn."<br>";
									$test="SELECT u_id FROM user_accounts WHERE account_id='$prspctv_id' AND at_id=2";
									$result=mysqli_query($conn,$test);
				
									if(mysqli_num_rows($result)>0)
									{
										$res=mysqli_fetch_array($result);
										$pid=$res['u_id'];
										update_existing($pid,$prspctv_sc);

									}
									else
									{
										new_user($prspctv_nm,$prspctv_sn,$prspctv_id,$prspctv_sc)	;
									}		
								}
							}}}
							else 
							{
								foreach($points_db as $listitem) 
								{
									$type = $listitem['p_name'];
									$acc = $listitem['pa_type'];
									if ($type =='o_from' && $acc==2)
									{
										$tweets= $listitem['points'];
									}
								}
								$tweet_s+=$tweets;
								echo " self posted another tweet not mentioned anyone: o_from 17:"."<br>" ;
							}
						}	
					}
				}}	
			 
			 //$start+=1;
		}
	
				//print_r($ids);		
				//echo "retweetcount"."$retweetcount"."<br>";
				//echo "tweetcount"."$tweetcount"."<br>";
				//echo "tweetshares"."$retweetcnt"."<br>";
				$_SESSION['tweetscr']=$tweetscr=($tweet_s)+($retweet_s);
				//$_SESSION['retweetscr']=$retweetscr=$retweetcount*5;
				$popularity=ceil($popcnt+$repopcnt);
				$_SESSION['popularity']=$popularity;
				//echo "likes for retweet: "."$repopcnt"."<br>";
				//echo "likes for tweet: "."$popcnt"."<br>";	
				//echo "retweet score: "."$retweetscr"."<br>";
		
				echo "<br>total tweet score: "."$tweetscr"."<br>";
				echo "popularity: "."$popularity"."<br>";
				//$followers_count=ceil(($p_follo/200)*10);
				//$friends_count=ceil(($p_frnd/200)*10);
				//$_SESSION['profile']=$profile=ceil($des+$followers_count+$friends_count);
				//echo "profile score".$profile."<br>";
				$lastid=$m_id;
				//echo " we scanned till tweet having id: ".$lastid."<br>";
				$score=$tweetscr+$popularity;
		//............insert id into user_scan_response........//
		//print_r($ids);
		echo "<br>";
		$check_oauth_user="SELECT u_id FROM user_accounts WHERE account_id='$account_id' AND at_id=2";
		$ck_res=mysqli_query($conn,$check_oauth_user);
		
		if(mysqli_num_rows($ck_res)>0)
		{
			//echo " entered into update_response";
			$ck_resar=mysqli_fetch_array($ck_res);
			$uid=$ck_resar['u_id'];
			update_response($uid);
		}
		if($tweetno>0)
		{
			$test="SELECT u_id FROM user_accounts WHERE account_id='$account_id' AND at_id=2";
				$result=mysqli_query($conn,$test);
					
				if(mysqli_num_rows($result)>0)
				{
					$res=mysqli_fetch_array($result);
					$pid=$res['u_id'];
					update_existing($pid,$score);
					$lq="select * from scan_mx_id where u_id=$pid and response_type=1";
					$lc=mysqli_query($conn,$lq);
					if( mysqli_num_rows($lc)==0)
					{
					$lstid="insert into scan_mx_id(u_id,response,response_type) values('$pid','$lastid','1')";
					mysqli_query($conn,$lstid);
					}
					else
					{
						$lstid=" update scan_mx_id set response=$lastid where u_id=$pid and response_type=1";
						mysqli_query($conn,$lstid);
					}
				}
		}
				
				if(!mysqli_error($conn))
				{
    					mysqli_commit($conn);
    					mysqli_autocommit($conn, true);
				}
				else 
				{
    				echo mysqli_error($conn);
					mysqli_rollback($conn);
					mysqli_autocommit($conn, true);
				}
			
	}
	catch(TwitterException $e)
	{
		echo "twitter returned an error".$e->getMessage();
	}
	finally{
		$ids=array();
	}
}
//****.................................likes by user........................................*****//
function scanlikes($tweet_likes)
{
	global $connection,$account_id,$screen_name,$points_db,$conn;
	try 
	{
		global $user_name,$i,$conn;
		$total_like[]=$tweet_likes;
		$pglike=0;
		$i=0;$lastid=0;
		$likes=$likescr=0;$max_id=0;$likeno=0;
		$max2=count($total_like[$pglike])-1;
		
		if($max2==0)
			{
				$total_likes[]=$total_like;
				
			}	
		else
		{
			$m_lkid=$max_id=$total_like[$pglike][0]->id_str;
		}
		while(!empty($tweet_likes) && $max2!=0)
		{	
			//echo "no of records in page..."."$max"."<br>";
			
			//echo " scan till ..."."$max_id"."<br>";
			//print_r($total_likes);
			$tweet_likes=$connection->get('favorites/list',['user_id'=>$account_id,'count'=>20,'max_id'=>$max_id]);
			//$total_likes[]=$tweet_likes;
			if (empty($tweet_likes))
			{
				break;
			}
			$tt_l[]=$tweet_likes;
			$max=count($tt_l[$pglike])-1;
			if ($max==0)
			{
				break;
			}
			else
			{
				$mx_id=$tt_l[$pglike][$max]->id_str;
				unset($tt_l[$pglike][$max]);
			$total_likes[]=$tt_l;
			//echo " last tweet no:".$max."<br>";
			
			$max_id=$mx_id;
			//echo " lower record (next max id)...".$max_id."<br>";
			//$pglike+=1;tt_l
				
				$tt_l=array();
			}
		}	
		//print_r($total_likes);
		foreach($total_likes as  $lkk)
		{
			foreach( $lkk as $lk)
			{
			foreach($lk as $like)
			{
				if (isset($like->retweeted)&&isset($like->user))
				{
					if(
					($like->user->id_str!=$account_id))//($like->retweeted)!=true)&& removed
					{
						if (nlp($like->text) ||search_hashtags($like->text))
						{
								echo $like->text."<br>";
							//$likes=$likes+1;
							$likeno+=1;
							$lk_sn=$like->user->screen_name;
							$lk_nm=$like->user->name;
							$lk_id=$like->user->id_str;
							
							if(searchOurNGO($lk_id,$lk_sn))
							{
								echo "liked our ngo's post->n_like <br>";
								foreach($points_db as $listitem) 
								{
									$type = $listitem['p_name'];
									$acc = $listitem['pa_type'];
									if ($type =='n_like' && $acc==2)
									{
										$lk= $listitem['points'];
									}
								}
								$likes+=$lk;
								
							}
							else
							{
								echo "liked other ngo <br>";
								foreach($points_db as $listitem) 
								{
									$type = $listitem['p_name'];
									$acc = $listitem['pa_type'];
									if ($type =='o_like' && $acc==2)
									{
										$lk= $listitem['points'];
									}
								}
								$likes+=$lk;
							}
						}
						
					}
				}
			}
		}
		}
			$lastid=$max_id;
		//echo " db max id:".$lastid."<br>";
		//$likescr=ceil(($likes/100)*10);
		if($likeno>0)
		{
		$test="SELECT u_id FROM user_accounts WHERE account_id='$account_id' AND at_id=2";
		$result=mysqli_query($conn,$test);			
		if(mysqli_num_rows($result)>0)
		{
			$res=mysqli_fetch_array($result);
			$pid=$res['u_id'];
			update_existing($pid,$likes);
			
					$lq="select * from scan_mx_id where u_id=$pid and response_type=3";
					$lc=mysqli_query($conn,$lq);
					if(!$lq || mysqli_num_rows($lc)==0)
					{
					$lstid="insert into scan_mx_id(u_id,response,response_type) values('$pid','$lastid','3')";
					$h=mysqli_query($conn,$lstid);
						if(!$h)
						{
							echo "ndgfjh";
						}
					}
					else
					{
						$lstid=" update scan_mx_id set response=$lastid where so_id=$pid and response_type=3";
						mysqli_query($conn,$lstid);
					}
				}
		}
			$_SESSION['likescr']=$likes;
			echo "tweets liked by user: "."$likes"."<br>";
	}
	catch(TwitterException $e)
	{
		echo "twitter returned an error".$e->getStatusCode();
	}
}


//................scan comments for authenticated user...........//
function scan_comments($retweeters)
{
	//global $ids;
	global $connection,$account_id,$screen_name,$p_name,$conn,$points_db;
	//$connection=$_SESSION['connection'];	
	$total_retweeter[]=$retweeters;
	//print_r($total_retweeter);
	$tlike=0;$i=0;$idtw=array();$n=0;$commentno=0;$max_id=0;
	$max2=count($total_retweeter[$tlike])-1;
	if($max2==0)	
	{
		$total_retweeters[]=$total_retweeter;
	}
	else
	{
	$max_id=$total_retweeter[$tlike][0]->id_str;
	}
	//print_r($ids);
	//$likes=$likescr=0;
	//print_r($total_retweeters);
	//$ids=scanTweets();
	while(!empty($retweeters) && $max2!=0)
	{
		
		$retweeters=$connection->get('statuses/mentions_timeline',['count'=>20,'include_entities'=>true,'max_id'=>$max_id]);
		if (empty($retweeters))
			{
				break;
			}
		//$retweeters=$connection->get('statuses/retweets/:id',['id'=> 940282576253489154]);not working
		$ttrt[]=$retweeters;
			$max=count($ttrt[$tlike])-1;
			if ($max==0)
			{
				break;
			}
		else
		{
			$mx_id=$ttrt[$tlike][$max]->id_str;
			unset($ttrt[$tlike][$max]);
			$total_retweeters[]=$ttrt;
			
			$max_id=$mx_id;
			$ttrt=array();
		}
	}
	//print_r($total_retweeters);
	$i=0;
	$lastid=$max_id;
	$s="SELECT u_id FROM user_accounts WHERE account_id='$account_id' AND at_id=2";
	$ck=mysqli_query($conn,$s);	
	if(mysqli_num_rows($ck)>0)
	{
		$c=mysqli_fetch_array($ck);
		$uid=$c['u_id'];
		$tf="select * from user_scan_response where user_scan_id=$uid and us_type=1";
		$ff=mysqli_query($conn,$tf);
		if(mysqli_num_rows($ff)>0)	
		{
			$q=mysqli_fetch_array($ff);
			
			$tid=json_decode($q['us_response']);
			foreach($tid as $td)
				{ 
				if(empty($td->tweet_id))
				{
					continue;
				}
				else
				{
			$idtw[]=$td->tweet_id;
				}
			}
		}
	}
		$n=count($idtw);
			foreach($total_retweeters as $gg)
			{
				foreach($gg as $rtr)
				{
				foreach($rtr as $key)
				{
					
					//print_r($key);
						//	echo $key->text;
						//echo $key->in_reply_to_status_id_str;
						if(!empty($key->in_reply_to_status_id_str))
						{
						for($i=0;$i<$n;$i++)
						{
							if($idtw[$i]==$key->in_reply_to_status_id_str)
							{
								$commentno+=1;
								echo "comment: "." $key->text"."<br>";
								//prospectv
								$prspctv_id= $key->user->id_str;
								$prspctv_sn=$key->user->screen_name;
								//$prspctv_sc=2;
								$prspctv_nm=$key->user->name;
								echo"$p_name";
								echo"$screen_name";
								if(searchOurNGO($account_id,$screen_name))
								{
									foreach($points_db as $listitem)
									{
										$type = $listitem['p_name'];
										$acc = $listitem['pa_type'];
										if ($type =='n_comment' && $acc==2)
										{
											$comment_s= $listitem['points'];
										}
									}
								}		
								else
								{
									foreach($points_db as $listitem)
									{
										$type = $listitem['p_name'];
										$acc = $listitem['pa_type'];
										if ($type =='nn_comment' && $acc==2)
										{
											$comment_s= $listitem['points'];
										}
									}
								}
					echo "<br>...prospective donars from comments :".$prspctv_nm."". "$prspctv_id"."".$prspctv_sn."$comment_s<br>";	
						$test="SELECT u_id FROM user_accounts WHERE account_id='$prspctv_id' AND at_id=2";
						$result=mysqli_query($conn,$test);

						if(mysqli_num_rows($result)>0)
						{
							$res=mysqli_fetch_array($result);
							$pid=$res['u_id'];
							update_existing($pid,$comment_s);
						}
						else
						{
							new_user($prspctv_nm,$prspctv_sn,$prspctv_id,$comment_s)	;
						}
					}
				}
			}
		}
			}
			}
	if($commentno>0)
		{
			$test="SELECT u_id FROM user_accounts WHERE account_id='$account_id' AND at_id=2";
				$result=mysqli_query($conn,$test);
					
				if(mysqli_num_rows($result)>0)
				{
					$res=mysqli_fetch_array($result);
					$pid=$res['u_id'];
					$lq="select * from scan_mx_id where u_id=$pid and response_type=2";
					$lc=mysqli_query($conn,$lq);
					if( mysqli_num_rows($lc)==0)
					{
					$lstid="insert into scan_mx_id(u_id,response,response_type) values('$pid','$lastid','2')";
					mysqli_query($conn,$lstid);
					}
					else
					{
						$lstid=" update scan_mx_id set response=$lastid where u_id=$pid and response_type=2";
						mysqli_query($conn,$lstid);
					}
				}
		}

}
	
		
	

		
	
	

//.........retweeters of post....................//
function retweeters()
{
	
	global $ids,$connection;
	global $connection,$account_id,$screen_name,$conn,$points_db;
	$trtrs=array();
	//print_r($ids);
	$check_oauth_user="SELECT u_id FROM user_accounts WHERE account_id='$account_id' AND at_id=2";
	$ck_res=mysqli_query($conn,$check_oauth_user);
		
	if(mysqli_num_rows($ck_res)>0)
		{
		$ck_resar=mysqli_fetch_array($ck_res);
		$uid=$ck_resar['u_id'];
		
			$txt=" select * from user_scan_response where user_scan_id=$uid and us_type=1";
	$t=mysqli_query($conn,$txt);
	if(mysqli_num_rows($t)>0)
	{
		//echo " entered into if <br>";
		$tt=mysqli_fetch_array($t);
		$tweet=json_decode($tt['us_response']);
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
					//echo " retweeters for tweet id : $page->tweet_id";
					$rtrs=$connection->get('statuses/retweets',array('id'=>$page->tweet_id));
					$respostr[]=$rtrs;
	
	if(!empty($respostr))
	{
				//echo "retweeters";
				foreach($respostr as $tt)	
				{
					foreach($tt as $rt)
					{
							//echo $rt->user->location;
							//$rloc=strtolower($rt->user->location);
							//if(stripos($rt,'india')||empty($rloc))
						if(searchOurNGO($rt->retweeted_status->user->id_str,$rt->retweeted_status->user->screen_name))
						{
							$rt_id=$rt->retweeted_status->id_str;
							$r_prspctv_sn=$rt->user->screen_name;
							$r_prspctv_nm=$rt->user->name;
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
							json_retweeters($uid,$rt_id,$r_prspctv_nm,$r_prspctv_sn,$r_prspctv_id,$r_prspctv_scr);
							//print_r($respostr);
							/*echo "<br>";
							echo "$r_prspctv_sn"."->"."$r_prspctv_scr<br>";
							$test="SELECT u_id FROM user_accounts WHERE account_id='$r_prspctv_id' AND at_id=2";
							$result=mysqli_query($conn,$test);								if(mysqli_num_rows($result)>0)
							{
								$res=mysqli_fetch_array($result);
								$pid=$res['u_id'];	
							scan_regular_retweeters($pid,$rid,$prspctv_id);
								
							//update_existing($pid,$r_prspctv_scr);
							}
							else
							{
								new_user($r_prspctv_nm,$r_prspctv_sn,$r_prspctv_id,$r_prspctv_scr)	;
							}*/
							
						}
						else
						{	
							$r_prspctv_sn=$rt->user->screen_name;
							$r_prspctv_nm=$rt->user->name;
							$r_prspctv_id=$rt->user->id_str;
							$rt_id=$rt->retweeted_status->id_str;echo $r_prspctv_sn."<br>";
							
							foreach($points_db as $listitem)
							{
								$type = $listitem['p_name'];
								$acc = $listitem['pa_type'];
								if ($type =='o_share' && $acc==2)
								{
									$r_prspctv_scr= $listitem['points'];
								}
							}
							json_retweeters($uid,$rt_id,$r_prspctv_nm,$r_prspctv_sn,$r_prspctv_id,$r_prspctv_scr);
							//print_r($respostr);
							/*echo "<br>";
							echo "$r_prspctv_sn"."->"."$r_prspctv_scr<br>";
							$test="SELECT u_id FROM user_accounts WHERE account_id='$r_prspctv_id' AND at_id=2";
							$result=mysqli_query($conn,$test);								if(mysqli_num_rows($result)>0)
							{
								$res=mysqli_fetch_array($result);
								$pid=$res['u_id'];
								update_existing($pid,$r_prspctv_scr);
							}
							else
							{
								new_user($r_prspctv_nm,$r_prspctv_sn,$r_prspctv_id,$r_prspctv_scr)	;
							}		*/
							
						}
					}
				}
	}
					}
				$respostr=array();
					}	
				}
		
			}
		}
}

function with_oauth()
{
	global $connection;
	$retweeters=$connection->get('statuses/mentions_timeline',['count'=>20,'include_entities'=>true]);
	//$trt[]=$retweeters;
	//print_r($trt);
	if(!empty($retweeters))
	{
	scan_comments($retweeters);
	}
}
