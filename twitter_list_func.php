<?php
include("connection.php");
include("twitter_points.php");

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
							$test="insert into ngo_social_objects(u_id,so_id,so_name,owner_nm,owner_id,so_type_id,at_id,is_sw,so_score) values('$user_id','$id','$name','$screen_name','$account_id','4','2','yes','$listsub')";
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
						$test="insert into ngo_social_objects(u_id,so_id,so_name,owner_nm,owner_id,so_type_id,at_id,is_sw,so_score) values('$user_id','$id','$name','$screen_name','$account_id','4','2','yes','$listsub')";
						mysqli_query($conn,$test);
					}
					//print_r($list);
				}
			}
		}
	}										 
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
	echo "<b>subscriobed lists</b><br> ";
	$listcnt=0; $subscribedlist=0; $scursor=-1;
	//print_r($total_subscribed_lists);
	foreach($total_subscribed_lists as $lpage)
	{
		foreach($lpage->lists as $arr)	
		{
			$try1=strtolower($arr->name);
			$try2=strtolower($arr->description);
				if((isset($arr->name) && (nlp($try1))) ||(isset($arr->description) && (nlp($try2))))
				{
					echo "$arr->name"."<br>";
					$id=$arr->id_str;
					$subscribers=$arr->subscriber_count;
					$list[]=$id;
					$name=$arr->name;
					$ownrid=$arr->user->id_str;
					$ownrsn=$arr->user->screen_name;
					if(searchOurNGO($arr->user->id_str,$arr->user->screen_name))
					{
					   foreach($points_db as $listitem) 
					   {
							$type = $listitem['p_name'];
							$acc = $listitem['pa_type'];
							if ($type =='n_listsub' && $acc==2)
							{
								$listpt= $listitem['points'];
								$listp=listpt-5;
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
						$test="insert into ngo_social_objects(u_id,so_id,so_name,owner_nm,owner_id,so_type_id,at_id,is_sw) values('$user_id','$id','$name','$ownrsn','$ownrid','4','2','yes','$subscribers')";
						mysqli_query($conn,$test);
						
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
				if(!searchOurNGO($prspctv_id,$prspctv_sn))
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
				elseif((isset($arr->name) && (nlp($try1))) &&(isset($arr->description) && (nlp($try2))))
				{
					echo "$arr->name"."<br>";
					$id=$arr->id_str;
					$list[]=$id;
					$name=$arr->name;
					$subscribers=$arr->subscribers_count;
					$ownrid=$arr->user->id_str;
					$ownrsn=$arr->user->screen_name;
					if(searchOurNGO($arr->user->id_str,$arr->user->screen_name))
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
					$result=mysqli_query($conn,$listid);
					if(mysqli_num_rows($result)==0)
					{
						$test="insert into ngo_social_objects(u_id,so_id,so_name,owner_nm,owner_id,so_type_id,at_id,is_sw,so_score) values('$user_id','$id','$name','$ownrsn','$ownrid','4','2','yes','$subscibers')";
						mysqli_query($conn,$test);
						
					}
					/*else
					{
						$scr=$listid['so_score'];
						$scr+=5;
						$test="update ngo_social_objects so_score=$scr where u_id=$user_id";
						mysqli_query($conn,$test);
						
					}*/	
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
				if(!searchOurNGO($prspctv_id,$prspctv_sn))
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
	global $connection,$account_id,$screen_name,$user_id,$points_db;
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
					
					$id=$arr->id_str;
					$list[]=$id;
					$name=$arr->name;
					$subscribers=$arr->subscriber_count;
					$ownrid=$arr->user->id_str;
					$ownrsn=$arr->user->screen_name;
					if(searchOurNGO($arr->user->id_str,$arr->user->screen_name))
					{
					   foreach($points_db as $listitem) 
					   {
							$type = $listitem['p_name'];
							$acc = $listitem['pa_type'];
							if ($type =='n_listmem' && $acc==2)
							{
								$listpt= $listitem['points'];
								$listpt=listpt-5;
								$alistcnt+=$listpt;
							}
						}
				   }
				   else
					   {
						   foreach($points_db as $listitem) 
						   {
								$type = $listitem['p_name'];
								$acc = $listitem['pa_type'];
								if ($type =='o_listmem' && $acc==2)
								{
									$listpt= $listitem['points'];
									$listpt=$listpt-5;
									$alistcnt+=$listpt;

								}
							}
					   }
					
					
					$listid="select * from ngo_social_objects  where so_id=$id";
					include('connection.php');
					$result=mysqli_query($conn,$listid);
					if(mysqli_num_rows($result)==0)
					{
						$test="insert into ngo_social_objects(u_id,so_id,so_name,owner_nm,owner_id,so_type_id,at_id,is_sw,so_score) values('$user_id','$id','$name','$ownrsn','$ownrid','4','2','yes','$subscribers')";
						mysqli_query($conn,$test);
						
					}
					
				if(isset($arr->user->id_str))
				{
					$prspctv_sn=$arr->user->screen_name;
					$prspctv_id=$arr->user->id_str;
					$prspctv_nm=$arr->user->name;
					if(!searchOurNGO($prspctv_id,$prspctv_sn))
					{	
						foreach($points_db as $listitem) 
						   {
								$type = $listitem['p_name'];
								$acc = $listitem['pa_type'];
								if ($type =='o_listfrom' && $acc==2)
								{
									$prspctv_sc= $listitem['points'];	
								}
							}
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
				elseif((isset($arr->name) && (nlp($try1))) &&(isset($arr->description) && (nlp($try2))))
				{
					echo $arr->name."<br>";
					//$alistcnt+=5;
					$id=$arr->id_str;
					$list[]=$id;
					$name=$arr->name;
					$subscribers=$arr->subscriber_count;
					$ownrid=$arr->user->id_str;
					$ownrsn=$arr->user->screen_name;
					if(searchOurNGO($arr->user->id_str,$arr->user->screen_name))
					{
					   foreach($points_db as $listitem) 
					   {
							$type = $listitem['p_name'];
							$acc = $listitem['pa_type'];
							if ($type =='n_listmem' && $acc==2)
							{
								$listpt= $listitem['points'];
								$listp=listpt;
								$alistcnt+=$listp;
							}
						}
				   }
				   else
					   {
						   foreach($points_db as $listitem) 
						   {
								$type = $listitem['p_name'];
								$acc = $listitem['pa_type'];
								if ($type =='o_listmem' && $acc==2)
								{
									$listpt= $listitem['points'];
									$listp=$listpt;
									$alistcnt+=$listp;

								}
							}
					   }
					$listid="select * from ngo_social_objects  where so_id=$id";
					$result=mysqli_query($conn,$listid);
					if(mysqli_num_rows($result)==0)
					{
						$test="insert into ngo_social_objects(u_id,so_id,so_name,owner_nm,owner_id,so_type_id,at_id,is_sw,so_score) values('$user_id','$id','$name','$ownrsn','$ownrid','4','2','yes','$subscribers')";
						mysqli_query($conn,$test);
						
					}
					if(isset($arr->user->id_str))
				{
					$prspctv_sn=$arr->user->screen_name;
					$prspctv_id=$arr->user->id_str;
					$prspctv_nm=$arr->user->name;
					if(!searchOurNGO($prspctv_id,$prspctv_sn))
					{	
						foreach($points_db as $listitem) 
						   {
								$type = $listitem['p_name'];
								$acc = $listitem['pa_type'];
								if ($type =='o_listfrom' && $acc==2)
								{
									$prspctv_sc= $listitem['points'];
									
								}
							}
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
				$_SESSION['added_list_scr']=$alistcnt;
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
function prospective_from_list($lid,$onrid,$onrnm)
{
	global $connection,$points_db;
	$li=$lid;
	$mcursor=-1;
	while($mcursor!=0)
	{		
		//echo "<br>list...".$lst."<br>";
		$memberlist=$connection->get('lists/members',['list_id'=>$li,'count'=>5000,'cursor'=>$mcursor]);
		$_SESSION['$total_member']=$total_member[]=$memberlist;
		$mcursor=$memberlist->next_cursor;
		print_r($total_member);
	}		
	global $prospective,$conn;
	foreach($total_member as $pg)
	{
		//echo $pg['users']->screen_name;
		foreach($pg->users as $key)			
		{			
			if(searchOurNGO($onrid,$onrnm))
			{
				foreach($points_db as $listitem) 
				{
					$type = $listitem['p_name'];							
					$acc = $listitem['pa_type'];
					if ($type =='n_listmem' && $acc==2)
					{
						$prspctv_sc= $listitem['points'];							
					}
				}
				$prspctv_sn=$key->screen_name;
				$prspctv_id=$key->id_str;
				$prspctv_nm=$key->name;
				$prspctv_des=$key->description;	
				if(!search_prosp($prspctv_sn,$prspctv_nm,$prspctv_des))
				echo $prspctv_sn." <br>";
				$test="SELECT u_id FROM user_accounts WHERE account_id='$prspctv_id' AND at_id=2";
				$result=mysqli_query($conn,$test);		
				if(mysqli_num_rows($result)==0)
				{			
					new_user($prspctv_nm,$prspctv_sn,$prspctv_id,$prspctv_sc)	;
				}
			}
			else
			{
				foreach($points_db as $listitem) 
				{
					$type = $listitem['p_name'];							
					$acc = $listitem['pa_type'];
					if ($type =='o_listmem' && $acc==2)
					{
						$prspctv_sc= $listitem['points'];							
					}
				}
				$prspctv_sn=$key->screen_name;
				$prspctv_id=$key->id_str;
				$prspctv_nm=$key->name;
				$prspctv_des=$key->description;	
				if(!search_prosp($prspctv_sn,$prspctv_nm,$prspctv_des))
				echo $prspctv_sn." <br>";
				$test="SELECT u_id FROM user_accounts WHERE account_id='$prspctv_id' AND at_id=2";
				$result=mysqli_query($conn,$test);		
				if(mysqli_num_rows($result)==0)
				{			
					new_user($prspctv_nm,$prspctv_sn,$prspctv_id,$prspctv_sc)	;
				}
			}
		}	
	}
}

//....subscriber of the list...............................//
function scan_subscribers($lid,$onrid,$onrnm)
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
				if(isset($key->screen_name))
				{
					if(searchOurNGO($onrid,$onrnm))
					{
						foreach($points_db as $listitem) 
						{
							$type = $listitem['p_name'];							
							$acc = $listitem['pa_type'];
							if ($type =='n_listsub' && $acc==2)
							{
								$prspctv_sc= $listitem['points'];							
							}
						}
						$prspctv_sn=$key->screen_name;
						$prspctv_id=$key->id_str;
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
					else
					{
						foreach($points_db as $listitem) 
						{
							$type = $listitem['p_name'];							
							$acc = $listitem['pa_type'];
							if ($type =='o_listsub' && $acc==2)
							{
								$prspctv_sc= $listitem['points'];							
							}
						}
						$prspctv_sn=$key->screen_name;
						$prspctv_id=$key->id_str;
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
}


?>