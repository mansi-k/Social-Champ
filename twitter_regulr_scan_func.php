<?php
include('connection.php');
//include('twitter_update_record.php');
//include('twitter_points.php');
//echo " scan tillfrom  ..."."$max_id"."<br>";
function regular_scan_timeline($response,$account_id)
{
	$ids=array();
	global $conn,$connection,$screen_name;
	echo "<b>Sreen Name: ".$screen_name."</b><br>";
	//echo $response;
	$pg=0;$ckonrec=0;$max_id=0;$lastid=0;$tweetno=0;
	try{
	$tweet=$connection->get('statuses/user_timeline',['user_id'=>$account_id,'count'=>2,'exclude_replies'=>false,'since_id'=>$response]);
	
	if(!empty($tweet))
	{
	$mx[]=$tweet;
		//print_r($mx);
	//$end=$mx[0][0]->id_str;
	//echo $end."<br>";
	$ckonrec=count($mx[$pg])-1;
		
	if($ckonrec==0)
	{
	$totaltweets[]=$mx;	
		//break;
	}
		else{
	$max_id=$mx[0][0]->id_str;
		}
	}
	
	$page=0;
	$totaltweets=array();
	while(!empty($tweet)&& !empty($max_id) && $ckonrec!=0)
	{
		
	  	$tweet=$connection->get('statuses/user_timeline',['user_id'=>$account_id,'count'=>3,'exclude_replies'=>false,'since_id'=>$response,'max_id'=>$max_id]);
		$tott[]=$tweet;
		if(empty($tweet)||empty($tott))
		{
			break;
		}	
		//print_r($tott);
		$rec=count($tott[$pg])-1;
		//echo " records.... ".$rec."<br>";
		if($rec==0)
		{
			
			break;
		}
		else	
		{		
			$mx_id=$tott[$pg][$rec]->id_str;
			//echo "$mx_id"."<br>";
			unset($tott[$pg][$rec]);
			$totaltweets[]=$tott;
			$max_id=$mx_id;
			$page+=1;	  
			$tott=array();
		}
	}
	//print_r($totaltweets);
	$tweetscr=0;$tweetlike=0;$tweets=0;$retweets=0;$retweetcnt=0;
		$repopcnt=$popcnt=$repop=$pop=0;$retweetscount=$otrngoshare=$retweet_s=$tweet_s=$retweet_s=0;
		$tweet_ids=array();
		$k=0;
		global $points_db;
		/*foreach($totaltweets as $tt)
		{
			foreach($tt as $gg)
			{
				foreach($gg as $key)
				{
					echo $key->text."<br><br><br>";
				}
			}
		}*/
		foreach($totaltweets as $page)
		{ 	
			foreach( $page as $pg)
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
						echo "..retweet..".$key->text."<br><br>";
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
								echo" mentioned SA in post this retweet->n_share ->12 points<br>";
								
								$retweet_s=$retweet_s+$ourngoshare;//points sharing SA's post
								echo  " total retwets points: ".$retweet_s."<br>";
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
								echo " shared someone else posts ->o_share->10<br>";
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
											echo " SA is mentioned ->points given to source-> nn_from ->18 : "."$sourcesn"."<br>";
											
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
											echo " SA not mentioned ->other mentioned users->15->"."$sn"."<br>";
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
								echo " SA is mentioned in self post ->nn_from-> points 18"."<br>";
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
									
									echo " other's mentioned in self post ->o_posttag points 15->".$prspctv_sn."<br>";
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
										$tweet_sc= $listitem['points'];
									}
								}
								$tweet_s+=$tweet_sc;
								echo " self posted another tweet not mentioned anyone: o_from->17:"."<br>" ;
							}
						}	
					}
				}	
			 
			}//$start+=1;
		}
				
				$_SESSION['tweetscr']=$tweetscr=($tweet_s)+($retweet_s);
				$popularity=ceil($popcnt+$repopcnt);
				$_SESSION['popularity']=$popularity;
				//echo "likes for retweet: "."$repopcnt"."<br>";
				//echo "likes for tweet: "."$popcnt"."<br>";	
				echo "$popularity"."<br>";
				echo "<br>total tweet score: "."$tweetscr"."<br>";		
				$score=$tweetscr+$popularity;
				$lastid=$max_id;
	
				echo "<br>";
	if($tweetno>0)
	{
		$check_oauth_user="SELECT u_id FROM user_accounts WHERE account_id='$account_id' AND at_id=2";
		$ck_res=mysqli_query($conn,$check_oauth_user);
		
		if(mysqli_num_rows($ck_res)>0)
		{
			$ck_resar=mysqli_fetch_array($ck_res);
			$uid=$ck_resar['u_id'];
			update_response($uid);
		}
	
				$test="SELECT u_id FROM user_accounts WHERE account_id='$account_id' AND at_id=2";
				$result=mysqli_query($conn,$test);
				
				if(mysqli_num_rows($result)>0)
				{
					$res=mysqli_fetch_array($result);
					$pid=$res['u_id'];
					update_existing($pid,$score);
					$lq="select * from scan_mx_id where u_id=$pid and response_type=1";
					$lc=mysqli_query($conn,$lq);
					if(!$lq || mysqli_num_rows($lc)==0)
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
		echo $e->getErrorMessage();
	}
	finally
	{
		$ids=array();
	}
			
}

//....tweets liked by user......//
function regular_scanlikes($res,$accid)
{
	global $connection,$account_id,$screen_name,$points_db,$conn;
	try
	{
		global $user_name,$i,$conn;
		$total_likes=array();
		$total_like=array();
		$pglike=0;
		$i=0;$lastid=0;
		$likes=$likescr=0;$max_id=0;
		$tweet_likes=$connection->get('favorites/list',['screen_name'=>$screen_name,'count'=>20,'since_id'=>$res]);
		//
		//
		
		//	
		if(!empty($tweet_likes))
		{	
			$total_like[]=$tweet_likes;
			$max2=count($total_like[$pglike])-1;
			//echo "no of records in page..."."$max"."<br>";
			if($max2==0)
			{
				$total_likes[]=$tweet_likes;
				//break;
			}
			else{
				$max_id=$total_like[$pglike][0]->id_str;	
			}
		}
			while(!empty($tweet_likes)&& !empty($max_id) && $max2!=0)
	{
		
			//echo " scan till ..."."$max_id"."<br>";
			//print_r($total_likes);
			$tweet_likes=$connection->get('favorites/list',['screen_name'=>$screen_name,'count'=>20,'since_id'=>$res,'max_id'=>$max_id]);
			$totl[]=$tweet_likes;
			if (empty($tweet_likes)||empty($totl))
			{
				break;
			}
			//$totl[]=$tweet_likes;
			$max=count($totl[$pglike])-1;
			if ($max==0)
			{
				break;
			}
				else{
					$mx_id=$totl[$pglike][$max]->id_str;
					unset($totl[$pglike][$max]);
			$total_likes[]=$totl;
					$max_id=$mx_id;
					$totl=array();
			/*echo " last tweet no:".$max."<br>";
			$since_id=$total_likes[$pglike][$max]->id_str;
			$max_id=$since_id;
			echo " lower record (next max id)...".$max_id."<br>";
			$pglike+=1;*/
		}	
			}
		//print_r($total_likes);
		foreach($total_likes as  $lk)
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
								//echo $like->text."<br>";
							//$likes=$likes+1;
							$lk_sn=$like->user->screen_name;
							$lk_nm=$like->user->name;
							$lk_id=$like->user->id_str;
							
							if(searchOurNGO($lk_id,$lk_sn))
							{
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
		$lastid=$max_id;
		//$likescr=ceil(($likes/100)*10);
		$_SESSION['likescr']=$likes;
			echo "tweets liked by user: "."$likes"."<br>";
			
		
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
					mysqli_query($conn,$lstid);
					}
					else
					{
						$lstid=" update scan_mx_id set response=$lastid where u_id=$pid and response_type=3";
						mysqli_query($conn,$lstid);
					}
				}
		
		
		
		
	}
	catch(TwitterException $e)
	{
		echo "twitter returned an error".$e->getStatusCode();
		echo $e->getErrorMessage()."<br>";
	}
}
function regular_comments($resp,$account_id)
{
	
	global $connection,$account_id,$screen_name,$p_name,$conn,$points_db;
	try
	{
		$clike=0;$commentno=0;$idtw=array();$totl=array();$max_id=0;
		$comments=$connection->get('statuses/mentions_timeline',['count'=>20,'include_entities'=>true,'since_id'=>$resp]);

	if(!empty($comments))
	{
		$total_comment[]=$comments;
			$max2=count($total_comment[$clike])-1;
			//echo "no of records in page..."."$max"."<br>";
			if($max2==0)
			{
				$total_comments[]=$comments;
				//break;
			}
			else{
				$max_id=$total_comment[$clike][0]->id_str;	
			}
		}
		
	while(!empty($comments)&& !empty($max_id) && $max2!=0)
	{

		$comments=$connection->get('statuses/mentions_timeline',['count'=>20,'include_entities'=>true,'since_id'=>$resp,'max_id'=>$max_id]);
				$totl[]=$comments;
		if (empty($comments)||empty($totl))
			{
				break;
			}
		
		//$ttrt[]=$retweeters;
			$max=count($totl[$clike])-1;
			if ($max==0)
			{
				break;
			}
		else
		{
			$mx_id=$totl[$clike][$max]->id_str;
			unset($totl[$clike][$max]);
			$total_comments[]=$totl;
			
			$max_id=$mx_id;
			$totl=array();
		}
	}
		//print_r($total_comments);
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
			foreach($total_comments as $gg)
			{
				
				foreach($gg as $rtr)
				{
				foreach($rtr as $key)
				{
					
					//print_r($key);
						//echo $key->text;
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
								$prspctv_sn=mysqli_real_escape_string($conn,$key->user->screen_name);
								//$prspctv_sc=2;
								$prspctv_nm=mysqli_real_escape_string($conn,$key->user->name);
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
					echo "<br>...prospective donars from comments :".$prspctv_nm."". "$prspctv_id"."".$prspctv_sn.""."->"."$comment_s<br>";	
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
		$last_id=$max_id;
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
	catch(TwitterException $e)
	{
		echo $e->getErrorMessage();
	}
}

?>