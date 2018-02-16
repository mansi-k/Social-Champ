<?php
set_time_limit (0);
include_once 'dbconn.php';
include_once 'fbinit.php';
include_once 'fbglobals.php';
include_once 'fbfunc.php';
include_once 'fbngofunc.php';
include_once 'nlp.php';
include_once 'per_cal2.php';

use Facebook\FacebookRequest;

$_SESSION['since'] = date("Y-m-d",strtotime(date("y-m-d").' -10 days'));
$_SESSION['until'] = date("Y-m-d",strtotime(date("y-m-d").' +1 day'));

$_SESSION['fb_uid']='147881722563636';
$_SESSION['fb_name']='Udgam NGO';
$_SESSION['fb_access_token']='EAAH09htiIfABANIUQMLnwBfS1ezMJxKNMF72epPC4oZCqth4hniUey0SZCphqKRknREgZA3X2Ue5Fhc1p9XrEZCZAS0BcFqa2inHPuR6Btwkw3DcjEly9nNUIuJnv2fK7qj2EjEjfFZAMqM7u8nH1ABdhlNrVnF2Evgx9xHaZBblh8gK2yTgmxI';
$fb->setDefaultAccessToken($_SESSION['fb_access_token']);

echo $_SESSION['fb_name'];

pageScan($_SESSION['fb_uid'],$fb,1);

per_cal();
fbDefaultTokens();
?>