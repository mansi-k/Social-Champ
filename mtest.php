<?php
/**
 * Created by PhpStorm.
 * User: Pradeep
 * Date: 12/25/2017
 * Time: 10:47 AM
 */
session_start();

include_once 'dbconn.php';

$id = '1774494506184449';
$q1 = mysqli_query($conn,"SELECT us_id FROM user_scores WHERE u_id=1");
$row = mysqli_fetch_array($q1);
print_r($row);
if($row['us_id']==null) echo "sdcs";