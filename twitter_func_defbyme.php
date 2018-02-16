<?php
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
$hashtag= array('social work','ngo','charitabletrust','AChildIsAChild','socialservices','donate','nonprofit','educategirls','educatechild','NGO','foundation');
//$prospective=array();
function searchOurNGO($oid,$sn) {
    global $ourngo;
    foreach ($ourngo as $tn) {
      // echo $tn['name']; 
		if(($oid==$tn['id'])&&(stripos($sn,$tn['screen_name'])!==FALSE)){
            return true;
			//echo "matched";
        }
    }
}
function searchOurNGO_list($sn)
{
	global $ourngo;
    foreach ($ourngo as $tn) {
      // echo $tn['name']; 
		if(stripos($sn,$tn['screen_name'])!==FALSE){
            return true;
			//echo "matched";
        }
    }
}
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
    			echo mysqli_error($conn);					}
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
?>