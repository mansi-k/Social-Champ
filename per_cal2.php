<?php
function per_cal()
{

//include_once 'dbconn.php';
    global $conn;

    $ov = mysqli_query($conn, "select a.ue_id, sum(score) as sum from user_scores as a, user_extended as b  
where st_id <> 10 and b.ut_id <>3 and a.ue_id=b.ue_id group by ue_id");

//$res2 = mysqli_query($conn,$ov);

    while ($arr1 = mysqli_fetch_array($ov)) {
        $ueid = $arr1['ue_id'];
        $sum = $arr1['sum'];
        $s = mysqli_query($conn, "select ue_id from user_scores where ue_id=$ueid and st_id = 10");
        if (mysqli_num_rows($s) > 0) {
            $a = mysqli_fetch_array($s);
            $ueid = $a['ue_id'];
            mysqli_query($conn, "update user_scores set score = $sum where ue_id = $ueid and st_id=10");
        } else {
            mysqli_query($conn, "insert into user_scores(ue_id,st_id,score)values($ueid,10,$sum)");

        }
        echo mysqli_error($conn);
    }

    $m = mysqli_query($conn, "select ut_id from user_types");
    while ($arr2 = mysqli_fetch_array($m)) {
        $utid = $arr2['ut_id'];
        $sm = mysqli_query($conn, "select st_id from score_types");
        while ($arr3 = mysqli_fetch_array($sm)) {
            $stid = $arr3['st_id'];
            $sql = mysqli_query($conn, "select max(score) as max from user_scores as a, user_extended as b 
	where a.st_id=$stid and b.ut_id=$utid and a.ue_id=b.ue_id");
            $arr4 = mysqli_fetch_array($sql);
            $smax = $arr4['max'];
            if ($smax > 1000) {
                mysqli_query($conn, "update user_scores a join user_extended b on a.ue_id = b.ue_id set 
		a.percent_score=round((score/$smax)*1000,2) where a.st_id=$stid and b.ut_id=$utid");
            } else {
                mysqli_query($conn, "update user_scores a join user_extended b on a.ue_id = b.ue_id set 
		a.percent_score= score where a.st_id=$stid and b.ut_id=$utid");
            }
        }
    }
    echo mysqli_error($conn);
}

?>