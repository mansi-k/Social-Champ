<?php
$conn = new mysqli("localhost","root","","sci3");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$qry = mysqli_query($conn,"SELECT * FROM points WHERE pa_type=1");
$points = array();
while($qparr = mysqli_fetch_array($qry)) {
    $points[$qparr['p_name']] = $qparr['points'];
}


?>