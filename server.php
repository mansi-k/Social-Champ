<?php
//session_start();
include_once 'dbconn.php';

$name  = "";
$phone = "";
$email = "";
$address = "";
$city = "";
$pin  = "";
$type = "";



if (isset($_POST['reg_user'])) {
 
  $name = $_POST['name'];
  $phone = $_POST['phone'];
  $email =  $_POST['email'];
  $address = $_POST['address'];
  $city = $_POST['city'];
  $pin = $_POST['pin'];
  $type = $_POST['type'];	
  
	$sql = "INSERT INTO user (name,phone_no,email_id,address,city,pin)
VALUES ('$name','$phone','$email','$address','$city','$pin')";
$result = mysqli_query($conn,$sql);
$u_id = mysqli_insert_id($conn);
$_SESSION['u_id']=$u_id;


$ut_id = "select ut_id from user_types where user_type ='$type'";
$restype = mysqli_query($conn,$ut_id);
//$res = mysqli_query($conn,$ut_id);
$rtarr = mysqli_fetch_array($restype);
$utid = $_SESSION['ut_id']= $rtarr['ut_id'];
$sql1 = "insert into user_extended(u_id,ut_id) values ($u_id,$utid)";
$result1 = mysqli_query($conn,$sql1);
/*
if ($result1 === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql1 . "<br>" . $conn->error;
}
*/

if($result1){
	//$smsg = "user created Successfully";
	
  	header('location:oauthlinks.php');
	//$_SESSION['success'] = "user created Successfully";
	//print_r($_SESSION);
}
else {
	//$fmsg = "user registration failed";
	echo "failed";
}

}


?>

