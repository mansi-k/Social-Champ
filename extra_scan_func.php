<?php 
//include("twitter_func.php");
use Abraham\TwitterOAuth\TwitterOAuth;
include('twitter_func.php');
function scanPost($connection,$bid)
		{
			global $conn,$points_db;
			$id=$bid;
			echo "$id";
			//$post=$connection->get('statuses/show/id',['$id'=>$id]);
			$post=$connection->get('statuses/show', array('id' => $id, 'include_entities' => true, 'include_my_retweet'=>true));

			$respost[]=$post;
			//print_r($respost);
	echo "<br><b>Posted By</b><br>";
			foreach ($respost as $tg)
			{
				$ourngonm= $tg->user->name;
				$ourngosn= $tg->user->screen_name;
				$ourngoid= $tg->user->id_str;
				//echo $ourngonm;
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
						echo $ourngosn."->".$m_prspctv_scr."<br>";
				if(!searchOurNGO($ourngonm,$ourngosn))
				{
				$test="SELECT u_id FROM user_accounts WHERE account_id='$ourngoid' AND at_id=2";
									$result=mysqli_query($conn,$test);
				
									if(mysqli_num_rows($result)>0)
									{
										$res=mysqli_fetch_array($result);
										$pid=$res['u_id'];
										update_existing($pid,$m_prspctv_scr);

									}
									else
									{
										new_user($ourngonm,$ourngosn,$ourngoid,$m_prspctv_scr)	;
									}		
									}		
			}

				echo "<br><b>mentioned users</b><br>";
				if(searchOurNGO($ourngonm,$ourngosn))
				{
					
					foreach ($respost as $tg)
					{	
						
					foreach($tg->entities->user_mentions as $key)
					{
						if(!empty($tg->entities->user_mentions))
						{
						
						$m_prspctv_sn=$key->screen_name;
						$m_prspctv_nm=$key->name;
						$m_prspctv_id=$key->id_str;
						
						foreach($points_db as $listitem)
						{
							$type = $listitem['p_name'];
							$acc = $listitem['pa_type'];
							if ($type =='n_posttag' && $acc==2)
							{
								$m_prspctv_scr= $listitem['points'];
								echo "points".$m_prspctv_scr."<br><br>";
							}
						}
							
						echo "$m_prspctv_sn"."->"."$m_prspctv_scr<br>";
							
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
					}
				
				echo "<br><b> quoted status mentions</b><br>";
				if(!empty($tg->quoted_status))
				{
					echo "<b><br>quote posted by </b><br>". $tg->quoted_status->user->screen_name;
					foreach($tg->quoted_status->entities->user_mentions as $qm)
					{
						//echo " quated user mentions:".$qm->screen_name."<br><br><br>";
						$q_prspctv_sn=$qm->screen_name;
						$q_prspctv_nm=$qm->name;
						$q_prspctv_id=$qm->id_str;
					
						foreach($points_db as $listitem)
						{
							$type = $listitem['p_name'];
							$acc = $listitem['pa_type'];
							if ($type =='n_posttag' && $acc==2)
							{
								$q_prspctv_scr= $listitem['points'];
							}
						}
								echo "$q_prspctv_sn"."->"."$q_prspctv_scr<br>";
									$test="SELECT u_id FROM user_accounts WHERE account_id='$q_prspctv_id' AND at_id=2";
									$result=mysqli_query($conn,$test);
				
									if(mysqli_num_rows($result)>0)
									{
										$res=mysqli_fetch_array($result);
										$pid=$res['u_id'];
										update_existing($pid,$q_prspctv_scr);

									}
									else
									{
										new_user($q_prspctv_nm,$q_prspctv_sn,$q_prspctv_id,$q_prspctv_scr)	;
									}		
					}
				}
						echo "<br><b>retweeters</b><br>";
				if(isset($tg->retweet_count))
				{
					$postr=$connection->get('statuses/retweets', array('id' => $id));
					$respostr[]=$postr;
					foreach($respostr as $tt)	
					{
						foreach($tt as $rt)
						{
							//echo $rt->user->location;
							//$rloc=strtolower($rt->user->location);
							//if(stripos($rt,'india')||empty($rloc))
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
								echo "$r_prspctv_sn"."->"."$r_prspctv_scr<br>";
							
						$test="SELECT u_id FROM user_accounts WHERE account_id='$r_prspctv_id' AND at_id=2";
									$result=mysqli_query($conn,$test);
				
									if(mysqli_num_rows($result)>0)
									{
										$res=mysqli_fetch_array($result);
										$pid=$res['u_id'];
										update_existing($pid,$r_prspctv_scr);

									}
									else
									{
										new_user($r_prspctv_nm,$r_prspctv_sn,$r_prspctv_id,$r_prspctv_scr)	;
									}		
						}
					}
					//print_r($respostr);
				}
				
			}
				}
				
				else
				{
					foreach ($respost as $tg)
					{	
					foreach($tg->entities->user_mentions as $key)
					{
						$m_prspctv_sn=$key->screen_name;
						$m_prspctv_nm=$key->name;
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
							echo "$m_prspctv_sn"."->"."$m_prspctv_scr<br>";
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
				
				
				echo "<br><b> quoted status mentions</b><br>";
				if(!empty($tg->quoted_status))
				{
					 echo $tg->quoted_status->user->screen_name."->17";
					foreach($tg->quoted_status->entities->user_mentions as $qm)
					{
						//echo " quated user mentions:".$qm->screen_name."<br><br><br>";
						$q_prspctv_sn=$qm->screen_name;
						$q_prspctv_nm=$qm->name;
						$q_prspctv_id=$qm->id_str;
						
						foreach($points_db as $listitem)
						{
							$type = $listitem['p_name'];
							$acc = $listitem['pa_type'];
							if ($type =='o_posttag' && $acc==2)
							{
								$q_prspctv_scr= $listitem['points'];
							}
						}
							echo "$q_prspctv_sn"."->"."$q_prspctv_scr<br>";
									$test="SELECT u_id FROM user_accounts WHERE account_id='$q_prspctv_id' AND at_id=2";
									$result=mysqli_query($conn,$test);
				
									if(mysqli_num_rows($result)>0)
									{
										$res=mysqli_fetch_array($result);
										$pid=$res['u_id'];
										update_existing($pid,$q_prspctv_scr);

									}
									else
									{
										new_user($q_prspctv_nm,$q_prspctv_sn,$q_prspctv_id,$q_prspctv_scr)	;
									}		
					}
				}
						echo " <br><b>retweeters</b><br>";
						
				if(isset($tg->retweet_count))
				{
					$postr=$connection->get('statuses/retweets', array('id' => $id));
					$respostr[]=$postr;
					foreach($respostr as $tt)	
					{
						foreach($tt as $rt)
						{
							//echo $rt->user->location;
							//$rloc=strtolower($rt->user->location);
							//if(stripos($rt,'india')||empty($rloc))
							//echo " retweeted by ".$rt->user->screen_name;
							$r_prspctv_sn=$rt->user->screen_name;
							$r_prspctv_nm=$rt->user->name;
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
							echo "$r_prspctv_sn"."->"."$r_prspctv_scr<br>";	
						$test="SELECT u_id FROM user_accounts WHERE account_id='$r_prspctv_id' AND at_id=2";
									$result=mysqli_query($conn,$test);
				
									if(mysqli_num_rows($result)>0)
									{
										$res=mysqli_fetch_array($result);
										$pid=$res['u_id'];
										update_existing($pid,$r_prspctv_scr);

									}
									else
									{
										new_user($r_prspctv_nm,$r_prspctv_sn,$r_prspctv_id,$r_prspctv_scr)	;
									}	
						}
					}
					//print_r($respostr);
				}
				
			}}
		}
function scanList($connection,$osn,$onm,$slug)
{
	global $points_db;
	if(searchOurNGO($onm,$osn))
	 {
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
		scan_members($osn,$slug,nlistmem);
		//scan_subs($osn,$slug,nlistsub);
	 }
	   else
	   {
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
		scan_members($osn,$slug,$o_listmem);
		scan_subs($osn,$slug,$o_listsub); 
	   }
	
	   
	   }
function scan_subs($osn,$slug,$pt)
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
					$prspctv_sn=$key->screen_name;
					$prspctv_id=$key->id_str;
				
					$prspctv_nm=$key->name;
					echo  "$prspctv_sn"."->"."$pt<br>";
					//$prspctv_des=$key->description;
				
				/*$test="SELECT u_id FROM user_accounts WHERE account_id='$prspctv_id' AND at_id=2";
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
				}*/
					   
			
			
			}
}
}
}
function scan_members($osn,$slug,$pt)
{
	echo "<br><b>Members of the list</b><br>";
	global $connection;
	
		$mcursor=-1;
	
	
		while($mcursor!=0)
		{
		
			//echo "<br>list...".$lst."<br>";
			$memberlist=$connection->get('lists/members',['owner_screen_name'=>$osn,'slug'=>$slug,'count'=>5000,'cursor'=>$mcursor]);
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
						echo  "$prspctv_sn"."->"."$pt<br>";
				/*if(!search_prosp($prspctv_sn,$prspctv_nm,$prspctv_des))
					//echo $prspctv_sn." <br>";
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
				}*/
					   
			}
			
			}
}