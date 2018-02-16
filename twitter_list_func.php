<?php
include("connection.php");
include("twitter_points.php");

//****.................................lists subscribed  by user........................................*****//
function scan_owned_lists()
{
	$listcnt=$listmem=$listsub=$listpop=$listpnt=0;	
	global $list;
	global $connection,$account_id,$screen_name,$user_id,$conn,$points_db;
	$slugar=array();
	$pid=0;$total_owned_lists[]=$_SESSION['$total_owned_lists'];$listno=0;
	//print_r($total_owned_lists);
	
	$test="SELECT u_id FROM user_accounts WHERE account_id='$account_id' AND at_id=2";
		$result=mysqli_query($conn,$test);			
		if(mysqli_num_rows($result)>0)
		{
			$res=mysqli_fetch_array($result);
			$pid=$res['u_id'];
		}
	
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
					$listno+=1;
						echo "$arr->name"."<br>";
						$listsub=$arr->subscriber_count;
					$slug=$arr->slug;
					$slugar[]=$slug;
					$ownsn=mysqli_real_escape_string($conn,$arr->user->screen_name);
					$ownid=$arr->user->id_str;
					$ownnm=mysqli_real_escape_string($conn,$arr->user->name);	
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
						$listid="select * from ngo_social_objects where owner_nm='$ownsn' and so_name='$slug'";
						$result=mysqli_query($conn,$listid);
						if(mysqli_num_rows($result)==0)
						{
							//echo "$slug.............................................";
							//$listno+=1;
							$test="insert into ngo_social_objects(u_id,so_name,owner_nm,so_type_id,at_id,is_sw) values('$pid','$slug','$ownsn','4','2','yes')";
							$s=mysqli_query($conn,$test);
							if(!$s)
							{
								echo " problem while adding new owned list f1<br> ";
							}
							
														
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
					$slug=$arr->slug;
					$slugar[]=$slug;
					$ownsn=$arr->user->screen_name;
					$ownid=$arr->user->id_str;
					$ownnm=$arr->user->name;	
					$listno+=1;
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
					
					$listid="select * from ngo_social_objects  where owner_nm='$ownsn'  and so_name='$slug' ";
					
					$res=mysqli_query($conn,$listid);
					if(mysqli_num_rows($res)==0)
					{
						//$listno+=1;
						$test="insert into ngo_social_objects(u_id,so_name,owner_nm,so_type_id,at_id,is_sw) values('$pid','$slug','$ownsn','4','2','yes')";
								
						$gh=mysqli_query($conn,$test);
						if(!$gh)
						{
							echo " problem while adding new owned list f2<br>";
						}
						
					}
					//print_r($list);
				}
			}
		}
	}										 
	//$_SESSION['listscr']=$listcnt+$listpnt;
	//$_SESSION['popularity']=$_SESSION['popularity']+$listpop;//subscribers addde to popularity
	//echo "subsribers of owned list".$listpop;
	//echo "popularity count  for list ".$listpop."<br>";
	//echo 'score of list owned list'.$_SESSION['listscr']."<br>";$test="SELECT u_id FROM user_accounts WHERE account_id='$account_id' AND at_id=2";
	$score=$listcnt+$listpnt+$listpop;
	if($listno>0)
	{
	$test="SELECT u_id FROM user_accounts WHERE account_id='$account_id' AND at_id=2";
				$result=mysqli_query($conn,$test);
				
				if(mysqli_num_rows($result)>0)
				{
					$res=mysqli_fetch_array($result);
					$pid=$res['u_id'];
	$gh="select * from user_scan_response where user_scan_id=$pid and us_type=5";
	$jj=mysqli_query($conn,$gh);
	if(mysqli_num_rows($jj)>0)
	{
		$test=mysqli_fetch_array($jj);
		$res=json_decode($test['us_response']);
		if(!empty($res))
		{
		foreach($res as $response )
		{
			foreach($slugar as $sa)
			{
			if(!in_array($sa,$response->owned))
			{
				//echo " new owned list is found";
				array_push($response->owned,$sa);
				$udt=json_encode($res);	
				//print_r($udt);
				$up="update user_scan_response set  us_response='$udt' where user_scan_id=$pid and us_type=5";
				$gh=mysqli_query($conn,$up);
				if($gh)
				{
					//update_existing($pid,$scr);
						update_existing($pid,$score);
						//echo " new owned is added successfully<br>";
				}
					else
					{
						echo " problem while inserting adding new owned list";
					}
							
			}
			
			else
				{
					//echo " all lists are already added<br>";
				}
			}}
		}
			   
	}
		else
		{
			//echo "$slug";
			foreach($slugar as $as)
			{
			$on[]=$as;
			}
			$urr[]=['owned'=>$on,'addedto'=>[],'subscribed'=>[]];
			
			$ur=json_encode($urr);
			//print_r($ur);
			$th="insert into user_scan_response(user_scan_id,us_response,us_type)values('$pid','$ur','5')";
			$k=mysqli_query($conn,$th);
			if($k)
			{
				update_existing($pid,$score);
				//echo " new array inserted";
			}
			else
			{
				echo " prob2";
			}
		}
	
				}
	}
	
	
					
				
}
//*******..........................for lists subscibed by user..................................*****///
function scan_subscribed_lists()
{
	$total_subscribed_lists[]=$_SESSION['$total_subscribed_lists'];
	global $list,$prospective;
	global $connection,$account_id,$screen_name,$user_id,$conn,$points_db;
	$listno=$pid=0;
	$slugar=array();
	echo "<b>subscribed lists</b><br> ";
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
					$listno+=1;
					echo "$arr->name"."<br>";
					$id=$arr->id_str;
					$subscribers=$arr->subscriber_count;
					$list[]=$id;
					$slug=$arr->slug;
					$slugar[]=$slug;
					$name=$arr->name;
					$ownid=$arr->user->id_str;
					$ownsn=$arr->user->screen_name;
					$ownnm=$arr->user->name;
					$test="SELECT u_id FROM user_accounts WHERE account_id='$ownid' AND at_id=2";
				$result=mysqli_query($conn,$test);
				
				if(mysqli_num_rows($result)>0)
				{
					$res=mysqli_fetch_array($result);
					$pid=$res['u_id'];
				}
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
					
					$listid="select * from ngo_social_objects  where owner_nm='$ownsn'  and so_name='$slug' ";
					include('connection.php');
					$res=mysqli_query($conn,$listid);
					if(mysqli_num_rows($res)==0)
					{
						//$listno+=1;
						$test="insert into ngo_social_objects(u_id,so_name,owner_nm,so_type_id,at_id,is_sw) values('$pid','$slug','$ownsn','4','2','yes')";
								
						$gh=mysqli_query($conn,$test);
						if($gh)
						{
							//addto_usr($pid,$slug);
							//echo " new subscribed list is added f1<br>";
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
					else
					{
						//echo " subscribed list is already fetched f1<br>";
					}
				
				}
				elseif((isset($arr->name) && (nlp($try1))) &&(isset($arr->description) && (nlp($try2))))
				{
					$listno+=1;
					echo "$arr->name"."<br>";
					$id=$arr->id_str;
					$list[]=$id;
					$name=$arr->name;
					$slug=$arr->slug;
					$slugar[]=$slug;
					$subscribers=$arr->subscribers_count;
					$ownid=$arr->user->id_str;
					$ownsn=$arr->user->screen_name;
					$test="SELECT u_id FROM user_accounts WHERE account_id='$ownid' AND at_id=2";
				$result=mysqli_query($conn,$test);
				
				if(mysqli_num_rows($result)>0)
				{
					$res=mysqli_fetch_array($result);
					$pid=$res['u_id'];
				}
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
					$listid="select * from ngo_social_objects  where owner_nm='$ownsn'  and so_name='$slug' ";
					
					$res=mysqli_query($conn,$listid);
					if(mysqli_num_rows($res)==0)
					{
					
						$test="insert into ngo_social_objects(u_id,so_name,owner_nm,so_type_id,at_id,is_sw) values('$pid','$slug','$ownsn','4','2','yes')";
								
						$gh=mysqli_query($conn,$test);
						if($gh)
						{//addto_usr($pid,$slug);
							//echo " success";
							
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
					else
					{
						//echo "list is already fetched";
					}
				
				}		
		}	
		$_SESSION['sub_list_scr']=$score=$listcnt;
	}
		if($listno>0)
	{
	$test="SELECT u_id FROM user_accounts WHERE account_id='$account_id' AND at_id=2";
				$result=mysqli_query($conn,$test);
				
				if(mysqli_num_rows($result)>0)
				{
					$res=mysqli_fetch_array($result);
					$pid=$res['u_id'];
	$gh="select * from user_scan_response where user_scan_id=$pid and us_type=5";
	$jj=mysqli_query($conn,$gh);
	if(mysqli_num_rows($jj)>0)
	{
		$test=mysqli_fetch_array($jj);
		$res=json_decode($test['us_response']);
		foreach($res as $response )
		{
			foreach($slugar as $sg)
			{
			if(!in_array($sg,$response->subscribed))
			{
				//echo " new subscribed list is found";
				array_push($response->subscribed,$sg);
					$udt=json_encode($res);	
				//print_r($udt);
				$up="update user_scan_response set  us_response='$udt' where user_scan_id=$pid and us_type=5";
				$gh=mysqli_query($conn,$up);
					if($gh)
				{
					//update_existing($pid,$scr);
						update_existing($pid,$score);
						//echo " new owned is added successfully<br>";
				}
					else
					{
						echo " problem while inserting adding new owned list";
					}
							
			}
				else
				{
					//echo " all lists are already added<br>";
				}
			}}
			   
	}
		else
		{
			
			$on=array();
			foreach($slugar as $sg)
			{
				
			
			$on[]=$sg;
			}
			$urr[]=['owned'=>[],'addedto'=>[],'subscribed'=>$on];
			
			$ur=json_encode($urr);
			//print_r($ur);
			$th="insert into user_scan_response(user_scan_id,us_response,us_type)values('$pid','$ur','5')";
			$k=mysqli_query($conn,$th);
			if($k)
			{
				update_existing($pid,$score);
				//echo " new array inserted";
			}
			else
			{
				echo " prob2";
			}
		}
	
				}
	}
		
			
	
}
					
function scan_addedto_list()
{
	$total_addedto_lists[]=$_SESSION['$total_addedto_lists'];
	$alistcnt=$pid=0;
	$subscribedlist=0;
	$scursor=-1;	global $list,$prospective,$conn;
	global $connection,$account_id,$screen_name,$user_id,$points_db;
	$listar=array();
	$listno=0;
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
					$listno+=1;
					$id=$arr->id_str;
					$list[]=$id;
					$name=$arr->name;
					$subscribers=$arr->subscriber_count;
					$slug=$arr->slug;
					$listar[]=$slug;
					$ownid=$arr->user->id_str;
					$ownsn=$arr->user->screen_name;
					$test="SELECT u_id FROM user_accounts WHERE account_id='$ownid' AND at_id=2";
							$result=mysqli_query($conn,$test);
				
							if(mysqli_num_rows($result)>0)
							{
								$res=mysqli_fetch_array($result);
								$pid=$res['u_id'];
							}
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
					
					
			$listid="select * from ngo_social_objects  where owner_nm='$ownsn'  and so_name='$slug' ";
					include('connection.php');
					$res=mysqli_query($conn,$listid);
					if(mysqli_num_rows($res)==0)
					{
						
						$test="insert into ngo_social_objects(u_id,so_name,owner_nm,so_type_id,at_id,is_sw) values('$pid','$slug','$ownsn','4','2','yes')";
								
						$gh=mysqli_query($conn,$test);
						if($gh)
						{
							
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
					}
					else
					{
						//echo "list is already fetched";
					}
				
				}
				elseif((isset($arr->name) && (nlp($try1))) &&(isset($arr->description) && (nlp($try2))))
				{
					echo $arr->name."<br>";
					//$alistcnt+=5;
					$listno+=1;
					$id=$arr->id_str;
					$list[]=$id;
					$name=$arr->name;
					$subscribers=$arr->subscriber_count;
					$slug=$arr->slug;
					$listar[]=$slug;
					$ownid=$arr->user->id_str;
					$ownsn=$arr->user->screen_name;
					$test="SELECT u_id FROM user_accounts WHERE account_id='$ownid' AND at_id=2";
							$result=mysqli_query($conn,$test);
				
							if(mysqli_num_rows($result)>0)
							{
								$res=mysqli_fetch_array($result);
								$pid=$res['u_id'];
							}
					
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
					$listid="select * from ngo_social_objects  where owner_nm='$ownsn'  and so_name='$slug' ";
					include('connection.php');
					$res=mysqli_query($conn,$listid);
					if(mysqli_num_rows($res)==0)
					{
						$listno+=1;
						$test="insert into ngo_social_objects(u_id,so_name,owner_nm,so_type_id,at_id,is_sw) values('$pid','$slug','$ownsn','4','2','yes')";
								
						$gh=mysqli_query($conn,$test);
						if($gh)
						{
							//addto_usr($pid,$slug);
							//echo " success";
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
					}
					else
					{
						//echo " lists is alreaddy fetched";
					}
					
				$_SESSION['added_list_scr']=$alistcnt;
				}
			}	
			
	
	$_SESSION['added_list_scr']=$score=$alistcnt;
	}
	if($listno>0)
	{
	$test="SELECT u_id FROM user_accounts WHERE account_id='$account_id' AND at_id=2";
				$result=mysqli_query($conn,$test);
				
				if(mysqli_num_rows($result)>0)
				{
					$res=mysqli_fetch_array($result);
					$pid=$res['u_id'];
	$gh="select * from user_scan_response where user_scan_id=$pid and us_type=5";
	$jj=mysqli_query($conn,$gh);
	if(mysqli_num_rows($jj)>0)
	{
		$test=mysqli_fetch_array($jj);
		$res=json_decode($test['us_response']);
		foreach($res as $response )
		{
			foreach($listar as $sg)
			{
			if(!in_array($sg,$response->addedto))
			{
				//echo " new added list is found";
				array_push($response->addedto,$sg);
					$udt=json_encode($res);	
				//print_r($udt);
				$up="update user_scan_response set  us_response='$udt' where user_scan_id=$pid and us_type=5";
				$gh=mysqli_query($conn,$up);
					if($gh)
				{
					//update_existing($pid,$scr);
						update_existing($pid,$score);
						//echo " new added to  is added successfully<br>";
				}
					else
					{
						echo " problem while inserting adding new owned list";
					}
							
			}
				else
				{
					//echo " all lists are already added<br>";
				}
			}}
			   
	}
		else
		{
			$on=array();
			foreach($listar as $sg)
			{
			$on[]=$sg;
			}
			$urr[]=['owned'=>[],'addedto'=>$on,'subscribed'=>[]];
			
			$ur=json_encode($urr);
			//print_r($ur);
			$th="insert into user_scan_response(user_scan_id,us_response,us_type)values('$pid','$ur','5')";
			$k=mysqli_query($conn,$th);
			if($k)
			{
				update_existing($pid,$score);
				//echo " new array inserted";
			}
			else
			{
				echo " prob2";
			}
		}
	
				}
	}
	
	//$_SESSION['total']=$users_total_score=$_SESSION['profile']+$_SESSION['popularity']+$_SESSION['tweetscr']+$_SESSION['likescr']+$_SESSION['listscr']+$_SESSION['sub_list_scr']+$_SESSION['added_list_scr'];
	//echo "total score of user: ".$users_total_score."<br>";
}

function addto_usr($uid,$slug)
{
	global $conn;
	$list=array();
	//echo "entered into else.................";
								$txt=" select * from user_scan_response where user_scan_id=$uid and us_type=6  ";
				$t=mysqli_query($conn,$txt);
				if(mysqli_num_rows($t)>0)
				{
					//echo " entered into if <br>";
					$tt=mysqli_fetch_array($t);
					$list=json_decode($tt['us_response']);
					//print_r($list);
					$tweetid=array();$rts=array();
					if(!empty($list))
					{	
						foreach( $list as $page)
						{
							if(empty($page->listslug))
							{
							   continue;	
							}
							else
							{
								$listslug[]=$page->listslug;
							}
						}
						echo "<br>";
						if(!empty($listslug))
						{
								if(!in_array($slug,$listslug))
								{	$list[]=['listid'=>'','listslug'=>$slug,'members'=>[],'subscribers'=>[]];			

									$twt=json_encode($list);
									print_r($twt);
									$ud="update user_scan_response set us_response='$twt' where user_scan_id=$uid and us_type=6";
									$rt=mysqli_query($conn,$ud);
									if($rt)
									{
										//echo " new list  inserted<br>";

									}
									else
									{
										echo "problem while inserting new list";
									}
								}
								else
								{
									//echo " list already scanned"."<br>";
								}
							}
						}

					else{

						$list[]=['listid'=>'','listslug'=>$slug,'members'=>[],'subscribers'=>[]];	
						$twt=json_encode($list);

					//print_r($twt);
					$ud="update user_scan_response set  us_response='$twt' where user_scan_id=$uid and us_type=6";
					$rt=mysqli_query($conn,$ud);
					if(!$rt)

						echo "problem1 while inserting list<br>";
					}
					}

											else
											{
												//echo " enterd 1st time............";
													$rts=array();
													//$arr[]=$prspctv_id;
													$rts[]=['listid'=>'','listslug'=>"$slug",'members'=>[],'subscribers'=>[]];
													$tw=json_encode($rts);
												//print_r($tw);
												$ud="insert into user_scan_response(user_scan_id,us_response,us_type) values ('$uid','$tw','6')";
												$rt=mysqli_query($conn,$ud);
												if(!$rt)
												{

													echo "problem 2 while inserting list in usr<br>";
												}
											}
}
function prospective_from_list($onm,$slug,$uid)
{
	global $connection,$points_db,$conn;
	//$li=$lid;
	$mcursor=-1;$accid=0;

	while($mcursor!=0)
	{		
		//echo "<br>list...".$lst."<br>";
		$memberlist=$connection->get('lists/members',['owner_screen_name'=>$onm,'slug'=>$slug,'count'=>5000,'cursor'=>$mcursor]);
		$_SESSION['$total_member']=$total_member[]=$memberlist;
		$mcursor=$memberlist->next_cursor;
		//print_r($total_member);
	}		
	global $prospective,$conn;
		
			$dn=mysqli_query($conn,"select * from user_accounts where u_id=$uid and at_id=2");
				if($dn)
				{
				$dnn=mysqli_fetch_array($dn);
				$accid=$dnn['account_id'];
				}
	else{
		echo "problem";
	}
	foreach($total_member as $pg)
	{
		//echo $pg['users']->screen_name;
		foreach($pg->users as $key)			
		{	
			if(searchOurNGO($accid,$onm))
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
				{
				echo $prspctv_sn." <br>";
			
				//$tt=mysqli_fetch_array($op);
				//$list=json_decode($tt['us_response']);
				
				json_rglistmem($accid,$onm,$slug,$prspctv_nm,$prspctv_sn,$prspctv_id,$prspctv_sc);
					
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
				{
					
				echo $prspctv_sn." <br>";
					json_rglistmem($accid,$onm,$slug,$prspctv_nm,$prspctv_sn,$prspctv_id,$prspctv_sc);
					
				}
				
			}
		}	
	}
}
function json_rglistmem($onr,$onrnm,$slug,$prspctv_nm,$prspctv_sn,$prspctv_id,$prspctv_sc)
{
	global $conn,$points_db;
	$sl=$slug;$mid=0;$ret;
	//echo "$onr";
	//echo "... $slug<br>";
	$tt="SELECT u_id FROM user_accounts WHERE account_id='$onr' AND at_id=2";
	$rt=mysqli_query($conn,$tt);			
	if(mysqli_num_rows($rt)>0)
	{
		
		$res=mysqli_fetch_array($rt);
		$mid=$res['u_id'];
	$tst="select * from user_scan_response where user_scan_id=$mid AND us_type=6";
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
				if(!in_array($prspctv_id,$response->members))
				{
					//echo " new meber found:".$psid."<br>";
					array_push($response->members,$prspctv_id);
					//print_r($res);
					$udt=json_encode($res);	
				//print_r($udt);
				$up="update user_scan_response set  us_response='$udt' where user_scan_id=$mid and us_type=6";
				$gh=mysqli_query($conn,$up);
					if($gh)
				{
						$test="SELECT u_id FROM user_accounts WHERE account_id='$prspctv_id' AND at_id=2";
						$result=mysqli_query($conn,$test);		
						if(mysqli_num_rows($result)==0)
						{			
							new_user($prspctv_nm,$prspctv_sn,$prspctv_id,$prspctv_sc)	;
						}
						else{
							$rt=mysqli_fetch_array($result);
							$uid=$rt['u_id'];
							update_existing ($uid,$prspctv_sc);
						}
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
//....subscriber of the list...............................//
function scan_subscribers($onrnm,$slug,$uid)
{
	global $connection,$conn,$points_db;
	
	$sbcursor=-1;
	while($sbcursor!=0)
	{
		//echo "<br>list...".$lst."<br>";
		$subscribers=$connection->get('lists/subscribers',['owner_screen_name'=>$onrnm,'slug'=>$slug,'count'=>5000,'cursor'=>$sbcursor]);
		$_SESSION['subscribers']=$total_subscribers[]=$subscribers;
		$sbcursor=$subscribers->next_cursor;
		//if(!empty($memberlist))
		//scan_membersof_lists();
		//print_r($total_subscribers);
	}	
	$dn=mysqli_query($conn,"select * from user_accounts where u_id=$uid and at_id=2");
				if($dn)
				{
				$dnn=mysqli_fetch_array($dn);
				$accid=$dnn['account_id'];
				}
		global $conn;
		foreach($total_subscribers as $pg)
		{
			//echo $pg['users']->screen_name;
			foreach($pg->users as $key)	
			{
				if(isset($key->screen_name))
				{
					if(searchOurNGO($accid,$onrnm))
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
						{
						echo $prspctv_sn." <br>";
						json_rglistsub($accid,$onrnm,$slug,$prspctv_nm,$prspctv_sn,$prspctv_id,$prspctv_sc);	
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
						{
							json_rglistsub($accid,$onrnm,$slug,$prspctv_nm,$prspctv_sn,$prspctv_id,$prspctv_sc);	
						}
					}
				}
			}
		}
}
function json_rglistsub($onr,$onrnm,$slug,$prspctv_nm,$prspctv_sn,$prspctv_id,$prspctv_sc)
{
	global $conn,$points_db;
	$sl=$slug;$mid=0;$ret;
	//echo "$onr";
	//echo "... $slug<br>";
	$tt="SELECT u_id FROM user_accounts WHERE account_id='$onr' AND at_id=2";
	$rt=mysqli_query($conn,$tt);			
	if(mysqli_num_rows($rt)>0)
	{
		//echo "++++";
		$res=mysqli_fetch_array($rt);
		$mid=$res['u_id'];
	$tst="select * from user_scan_response where user_scan_id=$mid AND us_type=6";
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
				if(!in_array($prspctv_id,$response->subscribers))
				{
					//echo " new meber found:".$psid."<br>";
					array_push($response->subscribers,$prspctv_id);
					//print_r($res);
					$udt=json_encode($res);	
				//print_r($udt);
				$up="update user_scan_response set  us_response='$udt' where user_scan_id=$mid and us_type=6";
				$gh=mysqli_query($conn,$up);
					if($gh)
				{
						$test="SELECT u_id FROM user_accounts WHERE account_id='$prspctv_id' AND at_id=2";
						$result=mysqli_query($conn,$test);		
						if(mysqli_num_rows($result)==0)
						{			
							new_user($prspctv_nm,$prspctv_sn,$prspctv_id,$prspctv_sc)	;
						}
						else{
							$rt=mysqli_fetch_array($result);
							$uid=$rt['u_id'];
							update_existing ($uid,$prspctv_sc);
						}
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

?>