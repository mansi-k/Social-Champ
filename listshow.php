<?php
require('config.php');
require 'autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;
//global $connection;
//echo $connection;
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET,'926448476212174849-V55q3spHf7EEJCeL14tjc2AO5yavdEt','3bJkAyye4VPbvdk9rucsRtevVqbNDWpYGFGbT9bdVPQbU');	
 $profile=$connection->get(' lists/show',['owner_screen_name'=>"NanhiKali",'slug'=>"scottfrog"]);
				//echo $profile;
				$profilescr[]=$profile;
print_r($profilescr);
?>