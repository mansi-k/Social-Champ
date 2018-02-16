<?php
include('connection.php');

global $conn;
$i=1;
$po="select * from points";
$result=mysqli_query($conn,$po);
while($test=mysqli_fetch_array($result))
{
	$points_db[]=$test;
}

/*foreach($points_db as $listitem) {
    $type = $listitem['Pname'];
    $acc = $listitem['pa_type'];
	if ($type =='n_share' && $acc==2)
	{
		echo $listitem['Points'];
	}
}*/
?>


