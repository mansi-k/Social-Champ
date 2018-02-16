<?php
set_time_limit (0);
include_once 'fbinit.php';
use Facebook\FacebookRequest;

include_once 'nlp.php';
include_once 'fbfunc.php';
include_once 'fbngofunc.php';

function scanProfile($fbuid,$fb,$tdbg)
{
    try {
        // Returns a `Facebook\FacebookResponse` object
        //global $fb, $fbuid;
        $batch = array();
        if(isset($tdbg['scopes'])) {
            $scopes = $tdbg['scopes']->asArray();
            if(in_array("user_about_me",$scopes) && in_array("user_religion_politics",$scopes))
                $batch['u_about'] = $fb->request('GET', '/' . $fbuid . '?fields=about,political,religion');
            else if(in_array("user_about_me",$scopes) && !in_array("user_religion_politics",$scopes))
                $batch['u_about'] = $fb->request('GET', '/' . $fbuid . '?fields=about');
            else if(!in_array("user_about_me",$scopes) && in_array("user_religion_politics",$scopes))
                $batch['u_about'] = $fb->request('GET', '/' . $fbuid . '?fields=political,religion');

            if(in_array("user_education_history",$scopes))
                $batch['u_education'] = $fb->request('GET', '/' . $fbuid . '?fields=education{concentration,school{id,name,category,about,mission,description,general_info,personal_info,impressum,category_list,company_overview,bio}}');

            if(in_array("user_work_history",$scopes))
                $batch['u_work'] = $fb->request('GET', '/' . $fbuid . '?fields=work{employer{id,name,category,about,mission,description,general_info,personal_info,impressum,category_list,company_overview,bio},location,position}');

            if(in_array("pages_show_list",$scopes) || in_array("manage_pages",$scopes))
                $batch['u_accounts'] = $fb->request('GET', '/' . $fbuid . '/accounts?fields=id,name,access_token,category,about,mission,description,general_info,personal_info,impressum,category_list,company_overview,bio,fan_count');

            if(in_array("user_managed_groups",$scopes)) {
                $batch['u_groups'] = $fb->request('GET', '/' . $fbuid . '/groups?fields=privacy,administrator,purpose,description,name,owner,members.summary(true)');
            }

            if(in_array("user_events",$scopes))
                $batch['u_events'] = $fb->request('GET', '/' . $fbuid . '/events?fields=owner{id,name,category,about,mission,description,general_info,personal_info,impressum,category_list,company_overview,bio},description,name,place,rsvp_status,end_time,start_time,category,admins,type,is_viewer_admin,attending_count');

            if(in_array("user_relationships",$scopes))
                $batch['u_family'] = $fb->request('GET', '/' . $fbuid . '/family');
        }

        //print_r($batch);
        /*
        $batch = [
            'u_about' => $fb->request('GET', '/' . $fbuid . '?fields=about,political,religion'),
            'u_education' => $fb->request('GET', '/' . $fbuid . '?fields=education{concentration,school{id,name,category,about,mission,description,general_info,personal_info,impressum,category_list,company_overview,bio}}'),
            'u_work' => $fb->request('GET', '/' . $fbuid . '?fields=work{employer{id,name,category,about,mission,description,general_info,personal_info,impressum,category_list,company_overview,bio},location,position}'),
            'u_accounts' => $fb->request('GET', '/' . $fbuid . '/accounts?fields=id,name,access_token,category,about,mission,description,general_info,personal_info,impressum,category_list,company_overview,bio,fan_count'),
            'u_groups' => $fb->request('GET', '/' . $fbuid . '/groups?fields=privacy,administrator,purpose,description,name,owner,members.summary(true)'),
            'u_events' => $fb->request('GET', '/' . $fbuid . '/events?fields=owner{id,name,category,about,mission,description,general_info,personal_info,impressum,category_list,company_overview,bio},description,name,place,rsvp_status,end_time,start_time,category,admins,type,is_viewer_admin,attending_count'),
            'u_family' => $fb->request('GET', '/' . $fbuid . '/family')
        ];
        */
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
					//echo $respnode['about'];
                    if (isset($respnode['about']) && nlp($respnode['about'])) {
						echo $respnode['about'];
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
                    //print_r($groups->asArray());
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

function scanTimeline($fbuid,$fb,$type,$tdbg) {
    global $conn;
    $scopes=array();
    if(isset($tdbg['scopes']))
        $scopes = $tdbg['scopes']->asArray();

    if(in_array("user_posts",$scopes)) {
        try {
            if ($type == 'long') {
                $since = date("Y-m-d", strtotime(date("y-m-d") . ' -1 year'));
                $until = date("Y-m-d", strtotime(date("y-m-d") . ' +1 days'));
            } else {
                $since = date("Y-m-d", strtotime(date("y-m-d") . ' -3 days'));
                $until = date("Y-m-d", strtotime(date("y-m-d") . ' +1 days'));
            }

            $feedsget = $fb->get($fbuid . '/feed?fields=created_time,description,message,from,name,caption,message_tags,source,status_type,story,story_tags,to{id,name,profile_type},type,parent_id,place,privacy,shares,sharedposts{from},reactions.summary(true){id,name,type,profile_type},comments.summary(true){from,message,message_tags,comments}&until=' . $until . '&since=' . $since);
            $feeds = $feedsget->getGraphEdge();

            //var_dump($feeds);
            echo "<br><b>Timeline Feed</b>";

            if (empty($feeds->asArray()) || sizeof($feeds->asArray()) == 0) {
                echo "empty";
                $lfdate = null;
                if ($_SESSION['inactive_from'] == null || $_SESSION['inactive_from'] == '') {
                    $lastfeed = $fb->get($fbuid . '?fields=feed.limit(1){created_time}');
                    $lfeed = $lastfeed->getGraphNode()->asArray();
                    //print_r($lfeed);
                    //print_r($lfeed['feed'][0]['created_time']);
                    if (isset($lfeed['feed'][0]['created_time'])) {
                        echo "hello";
                        //$lfdate = date("Y-m-d", $lfeed['feed'][0]['created_time']);
                        //$lfdate = DateTime::createFromFormat('Y-m-d', $lfeed['feed'][0]['created_time']);
                        $lfdate = date_format($lfeed['feed'][0]['created_time'],"Y-m-d");
                        echo "lfdate=";
                        print_r($lfeed['feed'][0]['created_time']);
                        echo " // ";
                        print_r($lfdate);
                        echo "<b>".date_diff(date_create($lfdate),date_create(date("Y-m-d")))->format("%R%a days")."</b>";
                        if (date_diff(date_create($lfdate),date_create(date("Y-m-d")))->format("%R%a") > 5) {
                            mysqli_query($conn, "UPDATE user_accounts SET inactive_from='$lfdate' WHERE account_id='$fbuid' AND at_id=1");
                        }
                        if (isset($_SESSION['promoter']) && $_SESSION['promoter'] && $type == "short") {
                            if (date_diff(date_create($lfdate),date_create(date("Y-m-d")))->format("%R%a") > 5) $_SESSION['pro_fbscore'] -= date_diff(date_create($lfdate),date_create(date("Y-m-d")))->format("%R%a");
                        }
                    }
                }
                else {
                    if (isset($_SESSION['promoter']) && $_SESSION['promoter'] && $type == "short")
                        $_SESSION['pro_fbscore'] -= date_diff(date_create($_SESSION['inactive_from']),date_create(date("Y-m-d")))->format("%R%a");
                }
            } else {
                if ($_SESSION['inactive_from'] != null && $_SESSION['inactive_from'] != '') {
                    mysqli_query($conn, "UPDATE user_accounts SET inactive_from=null WHERE account_id='$fbuid' AND at_id=1");
                    $_SESSION['inactive_from'] = null;
                }
                $rp = $totf = 0;
                do {
                    foreach ($feeds as $feed) {
                        $totf++;
                        $feedpts = scanFeed($feed);
                        $_SESSION['fbscore'] += $feedpts;
                        if ($feedpts > 0) $rp++;
                    }
                } while ($fb->next($feeds) && $feeds = $fb->next($feeds));

                if ($totf > 0 && $rp == 0) $_SESSION['fbscore'] -= 10;
                else if ($totf > 0 && $rp > 0 && ($totf - $rp <= $rp)) $_SESSION['fbscore'] += $rp + round($rp / $totf * 10);
            }
            if(isset($_SESSION['pro_fbscore']))
                echo '<br><b>' . ($_SESSION['fbscore'] + $_SESSION['pro_fbscore']) . "</b>";
            else
                echo '<br><b>' . ($_SESSION['fbscore']) . "</b>";
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
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