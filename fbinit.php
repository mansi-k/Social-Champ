<?php

ob_start();
if (!session_id()) {
    session_start();
}

require_once 'fbapi/vendor/autoload.php';
$fb = new Facebook\Facebook([
    'app_id' => '550812835258864',
    'app_secret' => 'faa34441ad17e5f9fbcd1ccfbd071817',
    'default_graph_version' => 'v2.10',
]);

$fbApp = new Facebook\FacebookApp('550812835258864', 'faa34441ad17e5f9fbcd1ccfbd071817');

?>