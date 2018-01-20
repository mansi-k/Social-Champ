<?php
set_time_limit (0);
include_once 'fbinit.php';
use Facebook\FacebookRequest;

include_once 'nlp.php';
include_once 'fbfunc.php';
include_once 'fbngofunc.php';

function scanProfile($fbuid,$fb)
{
    try {
        // Returns a `Facebook\FacebookResponse` object
        //global $fb, $fbuid;
        $batch = [
            'u_about' => $fb->request('GET', '/' . $fbuid . '?fields=about,political,religion'),
            'u_education' => $fb->request('GET', '/' . $fbuid . '?fields=education{concentration,school{id,name,category,about,mission,description,general_info,personal_info,impressum,category_list,company_overview,bio}}'),
            'u_work' => $fb->request('GET', '/' . $fbuid . '?fields=work{employer{id,name,category,about,mission,description,general_info,personal_info,impressum,category_list,company_overview,bio},location,position}'),
            'u_accounts' => $fb->request('GET', '/' . $fbuid . '/accounts?fields=id,name,access_token,category,about,mission,description,general_info,personal_info,impressum,category_list,company_overview,bio,fan_count'),
            'u_groups' => $fb->request('GET', '/' . $fbuid . '/groups?fields=privacy,administrator,purpose,description,name,owner,members.summary(true)'),
            'u_events' => $fb->request('GET', '/' . $fbuid . '/events?fields=owner{id,name,category,about,mission,description,general_info,personal_info,impressum,category_list,company_overview,bio},description,name,place,rsvp_status,end_time,start_time,category,admins,type,is_viewer_admin,attending_count'),
            'u_family' => $fb->request('GET', '/' . $fbuid . '/family')
        ];
        $responses = $fb->sendBatchRequest($batch);

        foreach ($responses as $key => $response) {
            if ($response->isError()) {
                $e = $response->getThrownException();
                echo '<p>Error! Facebook SDK Said: ' . $e->getMessage() . "\n\n";
                echo '<p>Graph Said: ' . "\n\n";
                var_dump($e->getResponse());
            } else {
                if ($key == 'u_about') {
                    echo "<br><b>About</b>";
                    $respnode = $response->getGraphNode();
                    if (isset($respnode['about']) && nlp($respnode['about'])) {
                        $_SESSION['fbscore'] += 10;
                    }
                    if (isset($respnode['religion']) && nlp($respnode['religion'])) {
                        $_SESSION['fbscore'] += 10;
                    }
                    if (isset($respnode['political']) && nlp($respnode['political'])) {
                        $_SESSION['fbscore'] += 10;
                    }
                    echo "<br><b>".$_SESSION['fbscore']."</b>";
                }
                if ($key == 'u_education') {
                    echo "<br><b>Education</b>";
                    $respnode = $response->getGraphNode();
                    if (isset($respnode['education'])) {
                        foreach ($respnode['education'] as $edu) {
                            $_SESSION['fbscore'] += scanEducation($edu);
                            //echo "<br>fbscore edu " . $_SESSION['fbscore'];
                        }
                    }
                    echo "<br><b>" . $_SESSION['fbscore']."</b>";
                }
                if ($key == 'u_work') {
                    echo "<br><b>Work</b>";
                    $respnode = $response->getGraphNode();
                    if (isset($respnode['work'])) {
                        foreach ($respnode['work'] as $work) {
                            $_SESSION['fbscore'] += scanWork($work);
                            //echo "<br>fbscore work " . $_SESSION['fbscore'];
                        }
                    }
                    echo "<br><b>" . $_SESSION['fbscore']."</b>";
                }
                if ($key == 'u_accounts') {
                    echo "<br><b>Owned Pages</b>";
                    $accounts = $response->getGraphEdge();
                    do {
                        foreach ($accounts as $acc) {
                            $_SESSION['fbscore'] += scanAccounts($acc);
                            //echo "<br>fbscore acc " . $_SESSION['fbscore'];
                        }
                    } while ($fb->next($accounts) && $accounts = $fb->next($accounts));
                    echo "<br><b>" . $_SESSION['fbscore']."</b>";
                }
                if ($key == 'u_groups') {
                    echo "<br><b>Owned Groups</b>";
                    $groups = $response->getGraphEdge();
                    do {
                        foreach ($groups as $grp) {
                            $_SESSION['fbscore'] += scanGroups($grp);
                            //echo "<br>fbscore grp " . $_SESSION['fbscore'];
                        }
                    } while ($fb->next($groups) && $groups = $fb->next($groups));
                    echo "<br><b>" . $_SESSION['fbscore']."</b>";
                }
                if ($key == 'u_events') {
                    echo "<br><b>Events</b>";
                    $events = $response->getGraphEdge();
                    do {
                        foreach ($events as $evn) {
                            $_SESSION['fbscore'] += scanEvents($evn);
                            //echo "<br>fbscore eve " . $_SESSION['fbscore'];
                        }
                    } while ($fb->next($events) && $events = $fb->next($events));
                    echo "<br><b>" . $_SESSION['fbscore']."</b>";
                }
                if ($key == 'u_family') {
                    echo "<br><b>Family</b>";
                    $family = $response->getGraphEdge();
                    do {
                        foreach ($family as $fam) {
                            //createForFetch($fam['id'],$fam['name'],5,'user');
                            $a = array('id'=>$fam['id'],'name'=>$fam['name'],'type'=>'user');
                            fetchPeople($a,10);
                        }
                    } while ($fb->next($family) && $family = $fb->next($family));

                }
            }
        }
    } catch (Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
}

function scanTimeline($fbuid,$fb,$type) {
    global $conn;
    try {
        if ($type == 'long') {
            $since = date("Y-m-d",strtotime(date("y-m-d").' -1 year'));
            $until = date("Y-m-d",strtotime(date("y-m-d").' -2 days'));
        }
        else {
            $since = date("Y-m-d",strtotime(date("y-m-d").' -5 days'));
            $until = date("Y-m-d",strtotime(date("y-m-d").' -2 days'));
        }

        $feedsget = $fb->get($fbuid . '/feed?fields=created_time,description,message,from,name,caption,message_tags,source,status_type,story,story_tags,to{id,name,profile_type},type,parent_id,place,privacy,shares,sharedposts{from,to{id,name,profile_type},story,story_tags,message_tags,shares,place},reactions.summary(true){id,name,type,profile_type},comments.summary(true){from,message,message_tags,comments}&until='.$until.'&since='.$since);
        $feeds = $feedsget->getGraphEdge();

        //var_dump($feeds);
        echo "<br><b>Timeline Feed</b>";

        if(empty($feeds->asArray()) || sizeof($feeds->asArray())==0) {
            $lfdate = null;
            if($_SESSION['inactive_from']==null || $_SESSION['inactive_from']=='') {
                $lastfeed = $fb->get($fbuid . '?fields=feed.limit(1){created_time}');
                $lfeed = $lastfeed->getGraphNode();
                if (!empty($lfeed->asArray()) && isset($lfeed['created_time'])) {
                    $lfdate = date("Y-m-d", strtotime($lfeed['created_time']));
                    if (date("Y-m-d") - $lfdate > 5) {
                        mysqli_query($conn, "UPDATE user_accounts SET inactive_from='$lfdate' WHERE account_id='$fbuid' AND at_id=1");
                    }
                }
                if(isset($_SESSION['promoter']) && $_SESSION['promoter'] && $type=="short") {
                    if (date("Y-m-d") - $lfdate > 5) $_SESSION['pro_fbscore'] -= (date("Y-m-d") - $lfdate);
                }
            }
        }
        else {
            $rp=$totf=0;
            do {
                foreach ($feeds as $feed) {
                    $totf++;
                    $feedpts = scanFeed($feed);
                    $_SESSION['fbscore'] += $feedpts;
                    if($feedpts>0) $rp++;
                }
            } while ($fb->next($feeds) && $feeds = $fb->next($feeds));
            
            if($totf>0 && $rp==0) $_SESSION['fbscore'] -= 10;
            else if($totf>0 && $rp>0 && ($totf-$rp <= $rp)) $_SESSION['fbscore'] += $rp+round($rp/$totf*10);
        }
        echo '<br><b>' . ($_SESSION['fbscore']+$_SESSION['pro_fbscore'])."</b>";
    }
    catch (Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
}



/*
 * $user = $response->getGraphNode();

$sr =  (array) $response;

echo gettype($response);
echo gettype($user);
echo $user;

echo '<br><br>' . $user['education'][0]['school']['name'];
*/
?>