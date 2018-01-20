<?php
include("connection.php");
include("points.php");

$thisngo = array (
    "name"  => array('NGO Publicity'),
    "screen_name" => array('dogoodforNGO'));

$ourngo = array (array(
"name"  =>'NGO Publicity',"screen_name"=>'dogoodforNGO','id'=>942609048599379968));
$thisngo=array('this_ngo','ngo');
$thisngoloc=array('kamothe');
global $user_name,$hashtag;
//global $list;
$prospective=array('ngo','non-government','nonprofit','orphanage',
    'big international ngo','business organized ngo','environmental ngo',
    'government organized ngo','charitable trust');
$user_name=array('Reshma_Khot','dogoodforNgo','Rsangeeta123');
global $hashtag;
$hashtag= array('social work','ngo','charitabletrust','AChildIsAChild','socialservices','donate','nonprofit');
//$prospective=array();
function searchOurNGO($nm,$sn) {
    global $ourngo;
    foreach ($ourngo as $tn) {
      // echo $tn['name']; 
		if((stripos($nm,$tn['name'])!==FALSE)&&(stripos($sn,$tn['screen_name'])!==FALSE)){
            return true;
			//echo "matched";
        }
    }
}
/*
function searchThisNGO($text) {
    global $thisngo;
    foreach ($thisngo as $tn) {
        if(stripos($text,$tn)!==FALSE) {
            return true;
        }
    }
   	
}
*/

function search_hashtags($text) 
{
	global $hashtag;
    foreach ($hashtag as  $name) {
        if (stripos($text, $name) !== FALSE) {
            return true;
        }
    }
}
function search_prospective($rn,$nme)
{
	global $prospective;
    foreach ($prospective as  $name) 
	{
        if ((stripos($nme, $name) )||(stripos($rn,$name))){	
            return true;
        }
    }
	
}
function search_prosp($rn,$nme,$description)
{
	global $prospective;
    foreach ($prospective as  $name) 
	{
        if ((stripos($rn, $name) )||(stripos($nme, $name) )||(stripos($description,$name))){	
            return true;
        }
    }
	
}

function update_existing($pid,$prspctv_scr)
{
	global $conn;
	$q0 = mysqli_query($conn,"SELECT ue_id FROM user_extended WHERE u_id=$pid");
					while($rw = mysqli_fetch_array($q0)) 
					{
    				$ueid = $rw['ue_id'];
    				$q1 = mysqli_query($conn, "SELECT us_id FROM user_scores WHERE ue_id=$ueid AND st_id=2");
    				$row = mysqli_fetch_array($q1);
    			if ($row['us_id'])
        		mysqli_query($conn, "UPDATE user_scores SET score=score+$prspctv_scr WHERE ue_id=$ueid AND st_id=2");
    			else
        		mysqli_query($conn, "INSERT INTO user_scores (ue_id,st_id,score) VALUES ($ueid,2,$prspctv_scr)");
    			echo mysqli_error($conn);
					}
}
function new_user($name,$screen_name,$id,$pts)
{					global $conn;
					mysqli_autocommit($conn,false);
					$q1 = mysqli_query($conn,"INSERT INTO user (name) VALUES ('$name')");
					if($q1) {
    				$uid = mysqli_insert_id($conn);
    				$q2 = mysqli_query($conn,"INSERT INTO user_accounts (u_id,at_id,account_id,account_name) VALUES ($uid,2,'$id','$screen_name')");
    				if($q2) {
						$q3 = mysqli_query($conn,"INSERT INTO user_extended (u_id,ut_id,level) VALUES ($uid,3,5)");
						if($q3) {
            $ueid = mysqli_insert_id($conn);
            mysqli_query($conn,"INSERT INTO user_scores (ue_id,st_id,score) VALUES ($ueid,2,$pts)");
        }
    }
}
if(!mysqli_error($conn)) {
    mysqli_commit($conn);
    mysqli_autocommit($conn, true);
}
else {
    echo mysqli_error($conn);
    mysqli_rollback($conn);
    mysqli_autocommit($conn, true);
}
}

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
	$des=$p_follo=$p_frnd=$followers_count=$friends_count=0;
	$ids=array();
	global $ids;
	global $connection,$account_id,$screen_name,$conn;
	$max=-1;
	try
	{
		$totaltweet[]=$tweet;
		$page=0;	  
		echo "<br><br><br>";
		$max2=count($totaltweet[$page])-1;
		$max_id=$totaltweet[$page][0]->id_str;		
		while(!empty($tweet))
		{	
			//echo "no of records in page..."."$max"."<br>";
			if($max2==0)
			{
				$totaltweets[]=$totaltweet;
				break;
			}
			echo " scan till ..."."$max_id"."<br>";
			$tweet=$connection->get('statuses/user_timeline',['user_id'=>$account_id,'count'=>3,'exclude_replies'=>false,'max_id'=>$max_id]);
			if (empty($tweet))
			{
				break;
			}
			$tott[]=$tweet;
			$max=count($tott[$page])-1;
			if ($max==0)
			{
				break;
			}
			$totaltweets[]=$tweet;
			echo " last tweet no:".$max."<br>";
			$since_id=$totaltweets[$page][$max]->id_str;
			$max_id=$since_id;
			echo " lower record (next max id)...".$max_id."<br>";
			$page+=1;
			echo "<br><br>";
			//$max=count($totaltweets[$page])-1;
		}
		//print_r($totaltweets);	
		//$start=1;
		$tweetscr=0;$tweetlike=0;$tweets=0;$retweets=0;$retweetcnt=0;
		$repopcnt=$popcnt=$repop=$pop=0;$retweetscount=$otrngoshare=$retweet_s=0;
		$tweet_ids=array();
		$k=0;
		global $points_db;
		foreach($totaltweets as $page)
		{ 	
			foreach($page as $key)
			{		
				$try=strtolower($key->text);
				//echo "$try"."<br><";
			 	if(isset($key->text)&&	(nlp($try) ||search_hashtags($try)))
				{		
					//++++++retweet+++++++++++++++//
					if(isset($key->retweeted_status)&& ($key->retweeted==1))//retweets
					{				
						echo "<br>...retweet..".$key->text."<br><br>";
					 	$repop=$key->favorite_count;//likes
						$retweetscount=$key->retweet_count;//shares
						$retweetcnt=$retweetcnt+$retweetscount;
						$repopcnt=$repopcnt+$repop;
						$ids[]=$key->id_str;
							$sourcenm=$key->retweeted_status->user->name;
							$sourcesn=$key->retweeted_status->user->screen_name;
							$sourceid=$key->retweeted_status->user->id_str;
							
							if (searchOurNGO($sourcenm,$sourcesn))//check if this user shared SA tweet
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
								echo" mentioned SA in post this retweet...."."<br>"." n_share 12 points<br>";
								
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
								echo " shared someone else posts.....total retweet score of user"."$retweet_s"."<br>";
								//check if SA is mentioned in others post
								if(!empty($key->entities->user_mentions ))
								{
									foreach($key->entities->user_mentions as $tg)
									{
										$sn=$tg->screen_name;
										$nm=$tg->name;
										$tgid=$tg->id_str;
										if(searchOurNGO($nm,$sn))//if SA is mentioned
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
											echo " SA not mentioned ->other mentioned users"."$sn"."<br>";
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
						echo "..likes..".$repop;
						echo "..shares..".$retweetscount;
					}
					else//self posted tweets
					{
						echo "<br>*self post*..".$key->text."<br>";
						//$tweetcount+=17;
						$pop=$key->favorite_count;//likes
						$retweetscount=$key->retweet_count;//shares
						$retweetcnt=$retweetcnt+$retweetscount;
						$popcnt=$popcnt+$pop;
						$ids[]=$key->id_str;
						
						echo "..likes..".$pop;
						echo "..shares..".$retweetscount;
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
							if(searchOurNGO($sn,$nm))
							{
								foreach($points_db as $listitem)
								{
    								$type = $listitem['p_name'];
									$acc = $listitem['pa_type'];
									if ($type =='nn_from' && $acc==2)
									{
										$tweet_s= $listitem['points'];
									}
								}
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
								echo " self posted another tweet not mentioned anyone: o_from:"."<br>" ;
							}
						}	
					}
				}	
			 
			 //$start+=1;
		}
	
				//print_r($ids);		
				//echo "retweetcount"."$retweetcount"."<br>";
				//echo "tweetcount"."$tweetcount"."<br>";
				//echo "tweetshares"."$retweetcnt"."<br>";
				$_SESSION['tweetscr']=$tweetscr=($tweets)+($retweets);
				//$_SESSION['retweetscr']=$retweetscr=$retweetcount*5;
				$popularity=ceil(($popcnt+$repopcnt+$retweetcnt)/200*10);
				$_SESSION['popularity']=$popularity;
				//echo "likes for retweet: "."$repopcnt"."<br>";
				//echo "likes for tweet: "."$popcnt"."<br>";	
				//echo "retweet score: "."$retweetscr"."<br>";
		
				echo "<br>total tweet score: "."$tweetscr"."<br>";
				echo "popularity: "."$popularity"."<br>";
				$followers_count=ceil(($p_follo/200)*10);
				$friends_count=ceil(($p_frnd/200)*10);
				$_SESSION['profile']=$profile=ceil($des+$followers_count+$friends_count);
				echo "profile score".$profile."<br>";
				$lastid=$totaltweets[0][0]->id_str;
				echo " we scanned till tweet having id: ".$lastid."<br>";
				$score=$tweetscr+$popularity+$profile;
				$test="SELECT u_id FROM user_accounts WHERE account_id='$account_id' AND at_id=2";
				$result=mysqli_query($conn,$test);
				
				if(mysqli_num_rows($result)>0)
				{
					$res=mysqli_fetch_array($result);
					$pid=$res['u_id'];
					update_existing($pid,$score);
					$lastid="insert into social_responses(so_id,response) values('$pid','$lastid')";
					mysqli_query($conn,$lastid);
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
}
//****.................................likes by user........................................*****//
function scanlikes($tweet_likes)
{
	global $connection,$account_id,$screen_name;
	try
	{
		global $user_name,$i,$conn;
		$total_like[]=$tweet_likes;
		$pglike=0;
		$i=0;
		$likes=$likescr=0;
		$max2=count($total_like[$pglike])-1;
		$max_id=$total_like[$pglike][0]->id_str;		
		while(!empty($tweet_likes))
		{	
			//echo "no of records in page..."."$max"."<br>";
			if($max2==0)
			{
				$total_likes[]=$tweet_likes;
				break;
			}
			echo " scan till ..."."$max_id"."<br>";
			//print_r($total_likes);
			$tweet_likes=$connection->get('favorites/list',['screen_name'=>$screen_name,'count'=>20,'max_id'=>$max_id]);
			//$total_likes[]=$tweet_likes;
			if (empty($tweet_likes))
			{
				break;
			}
			$totl[]=$tweet_likes;
			$max=count($totl[$pglike])-1;
			if ($max==0)
			{
				break;
			}
			$total_likes[]=$tweet_likes;
			echo " last tweet no:".$max."<br>";
			$since_id=$total_likes[$pglike][$max]->id_str;
			$max_id=$since_id;
			echo " lower record (next max id)...".$max_id."<br>";
			$pglike+=1;
		}	
		print_r($total_likes);
		foreach($total_likes as  $lk)
		{
			foreach($lk as $like)
			{
				if (isset($like->retweeted)&&isset($like->user))
				{
					if(
					($like->user->id_str!=$account_id))//($like->retweeted)!=true)&& removed
					{
						if (nlp($like->text)||searchThisNgo($like->text) ||search_hashtags($like->text))
						{
								//echo $like->text."<br>";
							//$likes=$likes+1;
							$lk_sn=$like->user->screen_name;
							$lk_nm=$like->user->name;
							
							if(searchOurNGO($lk_nm,$lk_sn))
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

		//$likescr=ceil(($likes/100)*10);
		$test="SELECT u_id FROM user_accounts WHERE account_id='$account_id' AND at_id=2";
		$result=mysqli_query($conn,$test);			
		if(mysqli_num_rows($result)>0)
		{
			$res=mysqli_fetch_array($result);
			$pid=$res['u_id'];
			update_existing($pid,$likes);
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
	global $ids;
	global $connection,$account_id,$screen_name,$p_name,$conn,$points_db;
	//$connection=$_SESSION['connection'];	
	$total_retweeter[]=$retweeters;
	//print_r($total_retweeter);
	$tlike=0;$i=0;
	$max2=count($total_retweeter[$tlike])-1;
		$max_id=$total_retweeter[$tlike][0]->id_str;
	//print_r($ids);
	//$likes=$likescr=0;
	//print_r($total_retweeters);
	//$ids=scanTweets();
	while(!empty($retweeters))
	{
		if($max2==0)
			{
				$totaltweets[]=$totaltweet;
				break;
			}
			echo " scan till ..."."$max_id"."<br>";
		//$max=count($total_retweeters[$tlike])-1;
		$retweeters=$connection->get('statuses/mentions_timeline',['count'=>20,'include_entities'=>true,'max_id'=>$max_id]);
		if (empty($retweeters))
			{
				break;
			}
		//$retweeters=$connection->get('statuses/retweets/:id',['id'=> 940282576253489154]);not working
		$totalretweetrs[]=$retweeters;
			$max=count($totalretweetrs[$tlike])-1;
			if ($max==0)
			{
				break;
			}
			$total_retweeters[]=$retweeters;
			echo " last tweet no:".$max."<br>";
			$since_id=$total_retweeters[$tlike][$max]->id_str;
			$max_id=$since_id;
			echo " lower record (next max id)...".$max_id."<br>";
		$tlike+=1;
	}
	//print_r($total_retweeters);
	$n=count($ids);
	//print_r($ids);
	$i=0;
	foreach($total_retweeters as $rtr)
	{
		foreach($rtr as $key)
		{
			if (isset($key))
			{
				for($i=0;$i<$n;$i++)
				{
					if($ids[$i]==$key->in_reply_to_status_id_str)
					{
						echo "comment: "." $key->text"."<br>";
						//prospectv
						$prspctv_id= $key->user->id_str;
						$prspctv_sn=$key->user->screen_name;
						//$prspctv_sc=2;
						$prspctv_nm=$key->user->name;
						
						echo"$p_name";
						echo"$screen_name";
						if(searchOurNGO($p_name,$screen_name))
						{
						foreach($points_db as $listitem) {
    $type = $listitem['p_name'];
    $acc = $listitem['pa_type'];
	if ($type =='n_comment' && $acc==2)
	{
		$comment_s= $listitem['points'];
	}
						}}		
						else
						{
								foreach($points_db as $listitem) {
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
//foreach($total_retweeters as )
//****.................................lists subscribed  by user........................................*****//
function scan_owned_lists()
{
	$listcnt=$listmem=$listsub=$listpop=$listpnt=0;	
	global $list;
	global $connection,$account_id,$screen_name,$user_id,$conn,$points_db;
	$total_owned_lists[]=$_SESSION['$total_owned_lists'];
	//print_r($total_owned_lists);
	if (is_array($total_owned_lists) || is_object($total_owned_lists))
	{
		foreach($total_owned_lists as $lpage)
		{
			foreach($lpage->lists as $arr)	
			{
				$try1=strtolower($arr->name);
				$try2=strtolower($arr->description);
				if((isset($arr->name) && (nlp($try1)||search_hashtags($try1))) &&(isset($arr->description) && (nlp($try2)||search_hashtags($try2))))
				{
						echo "$arr->name"."<br>";
						$listsub=$arr->subscriber_count;
						$listmem=$arr->member_count;
						$listpop+=$listsub;
						$listpnt+=$listmem;
						//$listcnt+=10;
						foreach($points_db as $listitem) {
    						$type = $listitem['p_name'];
    						$acc = $listitem['pa_type'];
							if ($type =='o_ownlist' && $acc==2)
							{
								$listcnt=$listitem['points'];
							}
				}
						$name=$arr->name;
						$id=$arr->id_str;
						//$list[]=array('list'=>$id);
						$list[]=$id;
						include('connection.php');
						$listid="select * from ngo_social_objects where so_id=$id";

						$result=mysqli_query($conn,$listid);
						if(mysqli_num_rows($result)==0)
						{
							$test="insert into ngo_social_objects(u_id,so_id,so_name,so_type_id,at_id,is_sw,so_score) values('$user_id','$id','$name','4','2','yes','$listsub')";
								mysqli_query($conn,$test);
						}
					//print_r($list);
				}
				elseif((isset($arr->name) && (nlp($try1)||search_hashtags($try1))) ||(isset($arr->description) && (nlp($try2)||search_hashtags($try2))))
				{
						echo "$arr->name"."<br>";
						$listsub=$arr->subscriber_count;
						$listmem=$arr->member_count;
						$listpop+=$listsub;
						$listpnt+=$listmem;
						//$listcnt+=5;
						foreach($points_db as $listitem) {
    					$type = $listitem['p_name'];
    					$acc = $listitem['pa_type'];
						if ($type =='o_ownlist' && $acc==2)
						{
							$hlist=$listitem['points'];
							$listcnt=ceil($hlist/2);
						}
}
					$id=$arr->id_str;
					$name=$arr->name;	
					//$list[]=array('list'=>$id,'slug'=>$slug);
					//$list[]=array('list'=>$id);
					$list[]=$id;
					
					$listid="select * from ngo_social_objects  where so_id=$id";
					include('connection.php');
					$result=mysqli_query($conn,$listid);
					if(mysqli_num_rows($result)==0)
					{
						$test="insert into ngo_social_objects(u_id,so_id,so_name,so_type_id,at_id,is_sw,so_score) values('$user_id','$id','$name','4','2','yes','$listsub')";
						mysqli_query($conn,$test);
					}
					//print_r($list);
				}
			}
		}
	}										 
	//echo "owned list count"."$listcnt"."<br>";
	/*if($listpop>10)
	{
		$listpt=ceil(($listsub/10)*10);
	}*/
	
	$_SESSION['listscr']=$listcnt+$listpnt;
	//$_SESSION['popularity']=$_SESSION['popularity']+$listpop;//subscribers addde to popularity
	//echo "subsribers of owned list".$listpop;
	echo "popularity count  for list ".$listpop."<br>";
	echo 'score of list owned list'.$_SESSION['listscr']."<br>";
	
	$score=$listcnt+$listpnt+$listpop;
	
	$test="SELECT u_id FROM user_accounts WHERE account_id='$account_id' AND at_id=2";
				$result=mysqli_query($conn,$test);
				
				if(mysqli_num_rows($result)>0)
				{
					$res=mysqli_fetch_array($result);
					$pid=$res['u_id'];
					update_existing($pid,$score);
				}
	
	
}
//*******..........................for lists subscibed by user..................................*****///
function scan_subscribed_lists()
{
	$total_subscribed_lists[]=$_SESSION['$total_subscribed_lists'];
	global $list,$prospective;
	global $connection,$account_id,$screen_name,$user_id,$conn,$points_db;
	
	$listcnt=0;
	$subscribedlist=0;
	$scursor=-1;	
	print_r($total_subscribed_lists);
	foreach($total_subscribed_lists as $lpage)
	{
		foreach($lpage->lists as $arr)	
		{
			$try1=strtolower($arr->name);
			$try2=strtolower($arr->description);
				if((isset($arr->name) && nlp($try1)) ||(isset($arr->description) && nlp($try2)))
				{
					echo "$arr->name"."<br>";
					$id=$arr->id_str;
					$subscribers=$arr->subscriber_count;
					$list[]=$id;
					$name=$arr->name;
					
					if(searchOurNGO($arr->user->name,$arr->user->screen_name))
					   {
						   foreach($points_db as $listitem) 
						   {
								$type = $listitem['p_name'];
								$acc = $listitem['pa_type'];
								if ($type =='n_listsub' && $acc==2)
								{
									$listpt= $listitem['points'];
									$listp=$listpt-5;
									$listcnt+=$listp;

								}
							}
					   }
					   else
					   {
						   foreach($points_db as $listitem) 
						   {
								$type = $listitem['p_name'];
								$acc = $listitem['pa_type'];
								if ($type =='o_listsub' && $acc==2)
								{
									$listpt= $listitem['points'];
									$listp=$listpt-5;
									$listcnt+=$listp;

								}
							}
					   }
						  // $listcnt=$listcnt+5;
					$listid="select * from ngo_social_objects  where so_id=$id";
					include('connection.php');
					$result=mysqli_query($conn,$listid);
					if(mysqli_num_rows($result)==0)
					{
						$test="insert into ngo_social_objects(u_id,so_id,so_name,so_type_id,at_id,is_sw) values('$user_id','$id','$name','4','2','yes','$subscribers')";
						mysqli_query($conn,$test);
						
					}
				}
				elseif((isset($arr->name) && (nlp($try1))) &&(isset($arr->description) && (nlp($try2))))
				{
					echo "$arr->name"."<br>";
					$listcnt+=5;
					$id=$arr->id_str;
					$list[]=$id;
					$name=$arr->name;
					$subscribers=$arr->subscribers_count;						if(searchOurNGO($arr->user->name,$arr->user->screen_name))
					{
					   foreach($points_db as $listitem) 
					   {
						   $type = $listitem['p_name'];
						   $acc = $listitem['pa_type'];
							if ($type =='n_listsub' && $acc==2)
							{
								$listcnt= $listitem['points'];
							}
					   }
					}
					else
				   {
					   foreach($points_db as $listitem)
					   {
							$type = $listitem['p_name'];
							$acc = $listitem['pa_type'];
							if ($type =='o_listsub' && $acc==2)
							{
								$listcnt= $listitem['points'];
							}
						}
					}
					$listid="select * from ngo_social_objects  where so_id=$id";
					include('connection.php');
					$result=mysqli_query($conn,$listid);
					if(mysqli_num_rows($result)==0)
					{
						$test="insert into ngo_social_objects(u_id,so_id,so_name,so_type_id,at_id,is_sw,so_score) values('$user_id','$id','$name','4','2','yes','$subscibers')";
						mysqli_query($conn,$test);
						
					}
					/*else
					{
						$scr=$listid['so_score'];
						$scr+=5;
						$test="update ngo_social_objects so_score=$scr where u_id=$user_id";
						mysqli_query($conn,$test);
						
					}*/	
				}
			if(!empty($arr->user->id_str))
				{
					$prspctv_sn=$arr->user->screen_name;
					$prspctv_id=$arr->user->id_str;
					//$prspctv_sc=5;
					$prspctv_nm=$arr->user->name;
					//mysqli_query($conn,$test);
					foreach($points_db as $listitem) 
					{
						$type = $listitem['p_name'];
						$acc = $listitem['pa_type'];
						if ($type =='o_listfrom' && $acc==2)
						{
							 $olistfrm=$listitem['points'];
						}
					}
				if(!searchOurNGO($prspctv_nm,$prspctv_sn))
				{	
					echo "<br>...prospective donars from subscribed lists  :".$prspctv_nm."". "$prspctv_id"."".$prspctv_sn."<br>";
					$test="SELECT u_id FROM user_accounts WHERE account_id='$prspctv_id' AND at_id=2";
					$result=mysqli_query($conn,$test);
					if(mysqli_num_rows($result)>0)
					{
						$res=mysqli_fetch_array($result);
						$pid=$res['u_id'];
						update_existing($pid,$olistfrm);
					}
					else
					{
						new_user($prspctv_nm,$prspctv_sn,$prspctv_id,$olistfrm)	;
					}
				}				   
			}		
		}	
		$_SESSION['sub_list_scr']=$score=$listcnt;
		echo 'subscribed list score'."$listcnt"."<br>";
		$test="SELECT u_id FROM user_accounts WHERE account_id='$account_id' AND at_id=2";
		$result=mysqli_query($conn,$test);	
		if(mysqli_num_rows($result)>0)
		{
			$res=mysqli_fetch_array($result);
			$pid=$res['u_id'];
			update_existing($pid,$score);
		}		
	}
}
function scan_addedto_list()
{
	
	$total_addedto_lists[]=$_SESSION['$total_addedto_lists'];
	$alistcnt=0;
	$subscribedlist=0;
	$scursor=-1;	global $list,$prospective,$conn;
	global $connection,$account_id,$screen_name,$user_id;
	//$total_subscribed_lists=array("");
	//print_r($total_addedto_lists);
	foreach($total_addedto_lists as $lpage)
	{
		foreach($lpage->lists as $arr)	
		{
			$try1=strtolower($arr->name);
			$try2=strtolower($arr->description);
				if((isset($arr->name) && (nlp($try1))) ||(isset($arr->description) && (nlp($try2))))
				{
					echo $arr->name."<br>";	
					$alistcnt+=2;
					$id=$arr->id_str;
					$list[]=$id;
					$name=$arr->name;
					$subscribers=$arr->subscriber_count;
					$listid="select * from ngo_social_objects  where so_id=$id";
					include('connection.php');
					$result=mysqli_query($conn,$listid);
					if(mysqli_num_rows($result)==0)
					{
						$test="insert into ngo_social_objects(u_id,so_id,so_name,so_type_id,at_id,is_sw,so_score) values('$user_id','$id','$name','4','2','yes','$subscribers')";
						mysqli_query($conn,$test);
						
					}
					
				}
				elseif((isset($arr->name) && (nlp($try1))) &&(isset($arr->description) && (nlp($try2))))
				{
					echo $arr->name."<br>";
						$alistcnt+=5;
					$id=$arr->id_str;
					$list[]=$id;
					$name=$arr->name;
						$subscribers=$arr->subscriber_count;
					$listid="select * from ngo_social_objects  where so_id=$id";
					include('connection.php');
					$result=mysqli_query($conn,$listid);
					if(mysqli_num_rows($result)==0)
					{
						$test="insert into ngo_social_objects(u_id,so_id,so_name,so_type_id,at_id,is_sw,so_score) values('$user_id','$id','$name','4','2','yes','$subscribers')";
						mysqli_query($conn,$test);
						
					}
					/*else
					{
						$scr=$listid['so_score'];
						$scr+=5;
						$test="update ngo_social_objects so_score=$scr where u_id=$user_id";
						mysqli_query($conn,$test);
						
					}*/
					//echo $arr->name;
				}
				$_SESSION['added_list_scr']=$alistcnt;
				if(isset($arr->user->id_str))
				{
					$prspctv_sn=$arr->user->screen_name;
					$prspctv_id=$arr->user->id_str;
					$prspctv_sc=5;
					$prspctv_nm=$arr->user->name;
					
					echo "<br>prospective donars from added lists :".$prspctv_nm."". "$prspctv_id"."".$prspctv_sn."<br>";
							
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
		}	
	}	
	$_SESSION['added_list_scr']=$score=$alistcnt;
	echo 'added to list score'."$alistcnt"."<br>";
	$test="SELECT u_id FROM user_accounts WHERE account_id='$account_id' AND at_id=2";
				$result=mysqli_query($conn,$test);
				
				if(mysqli_num_rows($result)>0)
				{
					$res=mysqli_fetch_array($result);
					$pid=$res['u_id'];
					update_existing($pid,$score);
				}
	
	
	//$_SESSION['total']=$users_total_score=$_SESSION['profile']+$_SESSION['popularity']+$_SESSION['tweetscr']+$_SESSION['likescr']+$_SESSION['listscr']+$_SESSION['sub_list_scr']+$_SESSION['added_list_scr'];
	//echo "total score of user: ".$users_total_score."<br>";
}

/*function scanTweets_byhash($tweethash)
{
	$connection=$_SESSION['connection'];
	try
	{
		$totaltweets[]=$tweethash;
		//print_r($totaltweets);
	
		$page=0;	   //for($count=200;$count<1000;$count+=200)
		if(!empty($tweethash))
		{
			$max=count($totaltweets[0]->statuses)-1;
			echo $max;
			
			$_SESSION['max_id']=$max_id=
			$totaltweets[0]->statuses[$max]->text;
			
			//echo $max_id;
			//$tweethash=$connection->get('search/tweets',['q'=>'#ngo','since_id'=>$max_id,'count'=>200,'result_type'=>'recent']);
			//$totaltweets[]=$tweethash;
			//$page+=1;
			
		}
		//print_r($totaltweets);
	}
}

	catch(TwitterException $e)
	{
		echo "twitter returned an error".$e->getStatusCode();
	}
}*/

function prospective_from_list($lid)
{
	global $connection;
	$li=$lid;
		$mcursor=-1;
	
	
		while($mcursor!=0)
		{
		
			//echo "<br>list...".$lst."<br>";
			$memberlist=$connection->get('lists/members',['list_id'=>$li,'count'=>5000,'cursor'=>$mcursor]);
			$_SESSION['$total_member']=$total_member[]=$memberlist;
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
					
					
					$prspctv_sn=$key->screen_name;
					$prspctv_id=$key->id_str;
					$prspctv_sc=2;
					$prspctv_nm=$key->name;
					$prspctv_des=$key->description;
				if(!search_prosp($prspctv_sn,$prspctv_nm,$prspctv_des))
					echo $prspctv_sn." <br>";
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
			
			}
}
//.........retweeters of post....................//



function retweeters()
{
	global $ids,$connection;
	//echo "nfkhkhjgkjkj";
	//print_r($ids);
	global $connection,$account_id,$screen_name,$conn;
	$trtrs=array();
	foreach ($ids as $rid)
	{
		$rcursor=-1;
		while($rcursor!=0)
		{
			//echo "....tid"."$rid"."...<br>";
			$rtrs=$connection->get('statuses/retweeters/ids',['id'=>$rid,'cursor'=>$rcursor]);
			$trtrs[]=$rtrs;
			//print_r($total_owned_lists);
			$rcursor=$rtrs->next_cursor;
			
		}
	}
		//print_r($trtrs);
	$profilescr=array();
		foreach($trtrs as $tt)
		{
			foreach($tt->ids as $tr)
			{
				echo "...."."$tr"."<br>";
				$rscr=5;
				//$rtrsids[]=$tr;
				if(!empty($tr))
				{
				$profile=$connection->get('users/show',['user_id'=>$tr]);
					//echo $profile;
				$profilescr[]=$profile;
				}
			}
		}
		//$trtrs=array();
		//print_r($profilescr);
	foreach($profilescr as $key)
	{
					$prspctv_sn=$key->screen_name;
					$prspctv_id=$key->id_str;
					$prspctv_sc=5;
					$prspctv_nm=$key->name;
					$prspctv_des=$key->description;
		if(!search_prosp($prspctv_sn,$prspctv_nm,$prspctv_des))
					echo $prspctv_sn." <br>";
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
	
}

//....subscriber of the list...............................//
function scan_subscribers($lid)
{
	global $connection;
	$li=$lid;
		$sbcursor=-1;
	
	
		while($sbcursor!=0)
		{
		
			//echo "<br>list...".$lst."<br>";
			$subscribers=$connection->get('lists/subscribers',['list_id'=>$li,'count'=>5000,'cursor'=>$sbcursor]);
			$_SESSION['subscribers']=$total_subscribers[]=$subscribers;
			$sbcursor=$subscribers->next_cursor;
		//if(!empty($memberlist))
	//scan_membersof_lists();
			print_r($total_subscribers);
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
					
					
					$prspctv_sn=$key->screen_name;
					$prspctv_id=$key->id_str;
					$prspctv_sc=5;
					$prspctv_nm=$key->name;
					$prspctv_des=$key->description;
				if(!search_prosp($prspctv_sn,$prspctv_nm,$prspctv_des))
					echo $prspctv_sn." <br>";
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
