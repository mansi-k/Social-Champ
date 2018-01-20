<?php
//print_r ($_GET["hub_challenge"]);

session_start();
require('config.php');
require 'autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

if (!isset($_SESSION['access_token']))
{
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
	$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
	$_SESSION['oauth_token'] =$token= $request_token['oauth_token'];
	$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
	
	if(!connection)
	{
		echo "oops something went wrong!!";
	}
	else
	{
	$url= $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
	header('Location:'.$url);
	}
}
else
{
	header('Location: in.php');
}
?>