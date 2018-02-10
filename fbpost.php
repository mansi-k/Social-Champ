<?php
include_once 'fbinit.php';
//fbDefaultTokens();
//postToFB($_SESSION['fb_uid'],$_SESSION['fb_access_token']);
function postToFB($id,$token)
{
    global $fb;
    try {
        // Returns a `FacebookFacebookResponse` object
        $response = $fb->post(
            '/' . $id . '/feed',
            array(
                'message' => 'Hello Friends :) 
                 I have recently donated to Udgam NGO. It is a developing NGO that helps the poor children become independent by providing necessary education. I highly recommend you to aid them in this good work.
                 https://facebook.com/1774494506184449'
            ),
            $token
        );
        $graphNode = $response->getGraphNode();
        print_r($graphNode);
    } catch (FacebookExceptionsFacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch (FacebookExceptionsFacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
}
//EAAH09htiIfABAGiLaQ6rRGADZBQUs2xH5TRar2XnddnCt3aLswNwUebi5fWZAuPYGUJxytIZBX39Fapuw6Td8ZCjnfgqkQ0y1DUwlw2n7nLyQ9dbxgplQgSArN2HFkdEbUajllaBYi9P9MR0cZBlHRTjO6t2s8eU1VWZC7LtdMPues2g4n4P6b
?>