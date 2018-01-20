<?php
//session_start();
include_once 'dbconn.php';

function eventScan($eventid,$fb,$ngo) {
    global  $adminsarr, $since, $until, $points;
    /*
    if($ngo) {
        $since=$slong;
        $until=$ulong;
    }
    else {
        $since=$sshort;
        $until=$ushort;
    }
    */
    //$eventid ='1672564486140269';
    echo " Event";
    array_splice($adminsarr, 0, sizeof($adminsarr));
    try {
        // Returns a `Facebook\FacebookResponse` object
        $batch = [
            'e_admins' => $fb->request('GET', '/' . $eventid . '/admins?fields=id,name,profile_type'),
            'e_attending' => $fb->request('GET', '/' . $eventid . '/attending'),
            'e_interested' => $fb->request('GET', '/' . $eventid . '/interested'),
            'e_feed' => $fb->request('GET', '/' . $eventid . '/feed?fields=created_time,description,message,from,name,caption,message_tags,source,status_type,story,story_tags,to{id,name,profile_type},type,parent_id,place,privacy,shares,sharedposts{from,to{id,name,profile_type},story,story_tags,message_tags,shares,place},reactions{id,name,type,profile_type},comments{from,message,message_tags,comments,reactions}&until='.$until.'&since='.$since),
        ];
        $responses = $fb->sendBatchRequest($batch);
    } catch (Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
    foreach ($responses as $key => $response) {
        if ($response->isError()) {
            $e = $response->getThrownException();
            echo '<p>Error! Facebook SDK Said: ' . $e->getMessage() . "\n\n";
            echo '<p>Graph Said: ' . "\n\n";
            var_dump($e->getResponse());
        } else {
            //echo '<br><br>'.$respnode.'<br><br>';
            if ($key == 'e_admins') {
                echo "<br><b>Admins : </b>";
                $admins = $response->getGraphEdge();
                $pts = ($ngo==1)?$points['vn_event']:$points['vo_event'];
                do {
                    foreach ($admins as $ad) {
                        array_push($adminsarr,$ad['id']);
                        fetchPeople($ad,$pts);
                    }
                } while ($fb->next($admins) && $admins = $fb->next($admins));
            }
            if ($key == 'e_attending') {
                echo "<br><b>Attending : </b>";
                $pts = ($ngo==1)?$points['n_attend']:$points['o_attend'];
                $attending = $response->getGraphEdge();
                do {
                    foreach ($attending as $att) {
                        if(!isAdmin($att) && $att['id']!=$_SESSION['fb_uid'])
                            fetchPeople($att,$pts);
                    }
                } while ($fb->next($attending) && $attending = $fb->next($attending));
            }
            if ($key == 'e_interested') {
                echo "<br><b>Interested : </b>";
                $pts = ($ngo==1)?$points['n_interest']:$points['o_interest'];
                $interested = $response->getGraphEdge();
                do {
                    foreach ($interested as $intr) {
                        if(!isAdmin($intr) && $intr['id']!=$_SESSION['fb_uid'])
                            fetchPeople($intr,$pts);
                    }
                } while ($fb->next($interested) && $interested = $fb->next($interested));
            }
            if ($key == 'e_feed') {
                echo "<br><b>Feed : </b>";
                //$pts = ($ngo==1)?4:1;
                $feeds = $response->getGraphEdge();
                print_r($feeds->asArray());
                do {
                    foreach ($feeds as $feed) {
                        echo "<br><b>Story : </b>".$feed['id'];
                        scanPageFeed($feed,$ngo);
                    }
                } while ($fb->next($feeds) && $feeds = $fb->next($feeds));
            }
        }
    }
}

function groupScan($groupid,$fb,$ngo) {
    global  $adminsarr, $since, $until, $points;
    /*
    if($ngo) {
        $since=$slong;
        $until=$ulong;
    }
    else {
        $since=$sshort;
        $until=$ushort;
    }
    */
    //$groupid ='261102497389631';
    echo " Group";
    //echo " ".$since." ".$until;
    array_splice($adminsarr, 0, sizeof($adminsarr));
    try {
        // Returns a `Facebook\FacebookResponse` object
        $batch = [
            'g_admins' => $fb->request('GET', '/' . $groupid . '/admins?fields=id,name,profile_type'),
            'g_members' => $fb->request('GET', '/' . $groupid . '/members'),
            'g_feed' => $fb->request('GET', '/' . $groupid . '/feed?fields=created_time,description,message,from,name,caption,message_tags,source,status_type,story,story_tags,to{id,name,profile_type},type,parent_id,place,privacy,shares,sharedposts{from,to{id,name,profile_type},story,story_tags,message_tags,shares,place},reactions{id,name,type,profile_type},comments{from,message,message_tags,comments,reactions}&until='.$until.'&since='.$since),
        ];
        $responses = $fb->sendBatchRequest($batch);
    } catch (Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
    foreach ($responses as $key => $response) {
        if ($response->isError()) {
            $e = $response->getThrownException();
            echo '<p>Error! Facebook SDK Said: ' . $e->getMessage() . "\n\n";
            echo '<p>Graph Said: ' . "\n\n";
            var_dump($e->getResponse());
        } else {
            //echo '<br><br>'.$respnode.'<br><br>';
            if ($key == 'g_admins') {
                echo "<br><b>Admins : </b>";
                $admins = $response->getGraphEdge();
                $pts = ($ngo==1)?$points['vo_grp']+5:$points['vo_grp'];
                do {
                    foreach ($admins as $ad) {
                        array_push($adminsarr,$ad['id']);
                        fetchPeople($ad,$pts);
                    }
                } while ($fb->next($admins) && $admins = $fb->next($admins));
            }
            if ($key == 'g_members') {
                echo "<br><b>Members : </b>";
                $pts = ($ngo==1)?$points['n_member']:$points['o_member'];
                $members = $response->getGraphEdge();
                do {
                    foreach ($members as $mem) {
                        if(!$mem['administrator'])
                            fetchPeople($mem,$pts);
                    }
                } while ($fb->next($members) && $members = $fb->next($members));
            }
            if ($key == 'g_feed') {
                echo "<br><b>Feed : </b>";
                $feeds = $response->getGraphEdge();
                //print_r($feeds);
                do {
                    //$pts = ($ngo==1)?4:1;
                    foreach ($feeds as $feed) {
                        echo "<br><b>Story : </b>".$feed['id'];
                        scanPageFeed($feed,$ngo);
                    }
                } while ($fb->next($feeds) && $feeds = $fb->next($feeds));
            }
        }
    }
}

function pageScan($pgid,$fb,$ngo)
{
    try {
        echo " Page";
        // Returns a `Facebook\FacebookResponse` object
        global  $adminsarr, $since, $until, $points;
        /*
        if($ngo) {
            $since=$slong;
            $until=$ulong;
        }
        else {
            $since=$sshort;
            $until=$ushort;
        }
        */
        //$pgatget = $fb->get('/1774494506184449?fields=access_token', $fbtoken);
        //$fb->setDefaultAccessToken($pgatget->getGraphNode()['access_token']);
        array_splice($adminsarr, 0, sizeof($adminsarr));
        $batch = array();
        if($ngo==0) {
            $batch = [
                'p_posts' => $fb->request('GET', '/' . $pgid . '/posts?fields=created_time,description,message,from,name,caption,message_tags,source,status_type,story,story_tags,to{id,name,profile_type},type,parent_id,place,privacy,shares,sharedposts{from,to{id,name,profile_type},story,story_tags,message_tags,shares,place},reactions{id,name,type,profile_type},comments{from,message,message_tags,comments,reactions}&until='.$until.'&since='.$since),
                'p_visitor_posts' => $fb->request('GET', '/' . $pgid . '/visitor_posts?fields=created_time,description,message,from,name,caption,message_tags,source,status_type,story,story_tags,to{id,name,profile_type},type,parent_id,place,privacy,shares,sharedposts{from,to{id,name,profile_type},story,story_tags,message_tags,shares,place},reactions{id,name,type,profile_type},comments{from,message,message_tags,comments,reactions}&until='.$until.'&since='.$since)
            ];
        }
        else if($ngo==1) {
            $batch = [
                'p_admins' => $fb->request('GET', '/' . $pgid . '/roles?fields=id,name,role'),
                'p_ratings' => $fb->request('GET', '/' . $pgid . '/ratings'),
                'p_visitor_posts' => $fb->request('GET', '/' . $pgid . '/visitor_posts?fields=created_time,description,message,from,name,caption,message_tags,source,status_type,story,story_tags,to{id,name,profile_type},type,parent_id,place,privacy,shares,sharedposts{from,to{id,name,profile_type},story,story_tags,message_tags,shares,place},reactions{id,name,type,profile_type},comments{from,message,message_tags,comments,reactions}&until='.$until.'&since='.$since),
                'p_checkin_posts' => $fb->request('GET', '/' . $pgid . '/checkin_posts?fields=created_time,description,message,from,name,caption,message_tags,source,status_type,story,story_tags,to{id,name,profile_type},type,parent_id,place,privacy,shares,sharedposts{from,to{id,name,profile_type},story,story_tags,message_tags,shares,place},reactions{id,name,type,profile_type},comments{from,message,message_tags,comments,reactions}&until='.$until.'&since='.$since),
                'p_tagged' => $fb->request('GET', '/' . $pgid . '/tagged?fields=created_time,description,message,from,name,caption,message_tags,source,status_type,story,story_tags,to{id,name,profile_type},type,parent_id,place,privacy,shares,sharedposts{from,to{id,name,profile_type},story,story_tags,message_tags,shares,place},reactions{id,name,type,profile_type},comments{from,message,message_tags,comments,reactions}&until='.$until.'&since='.$since),
                'p_posts' => $fb->request('GET', '/' . $pgid . '/posts?fields=created_time,description,message,from,name,caption,message_tags,source,status_type,story,story_tags,to{id,name,profile_type},type,parent_id,place,privacy,shares,sharedposts{from,to{id,name,profile_type},story,story_tags,message_tags,shares,place},reactions{id,name,type,profile_type},comments{from,message,message_tags,comments,reactions}&until='.$until.'&since='.$since),
                'p_conversations' => $fb->request('GET', '/' . $pgid . '/conversations?fields=senders,participants,messages{message,from},subject,name,former_participants,can_reply')

            ];
        }
        $responses = $fb->sendBatchRequest($batch);
    } catch (Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
    foreach ($responses as $key => $response) {
        if ($response->isError()) {
            $e = $response->getThrownException();
            echo '<p>Error! Facebook SDK Said: ' . $e->getMessage() . "\n\n";
            echo '<p>Graph Said: ' . "\n\n";
            var_dump($e->getResponse());
        } else {
            //echo '<br><br>'.$respnode.'<br><br>';
            if ($key == 'p_admins') {
                echo "<br><b>Admins : </b>";
                $admins = $response->getGraphEdge();
                $pts = ($ngo==1)?40:35;
                do {
                    foreach ($admins as $ad) {
                        array_push($adminsarr,$ad['id']);
                        fetchPeople($ad,$pts);
                    }
                } while ($fb->next($admins) && $admins = $fb->next($admins));
            }
            if ($key == 'p_ratings') {
                echo "<br><b>Ratings : </b>";
                $ratings = $response->getGraphEdge();
                do {
                    foreach ($ratings as $rating) {
                        scanRating($rating,1);
                    }
                } while ($fb->next($ratings) && $ratings = $fb->next($ratings));
            }
            if ($key == 'p_visitor_posts') {
                echo "<br><b>Visitor Posts : </b>";
                //$pts = ($ngo==1)?1:-2;
                $vposts = $response->getGraphEdge();
                do {
                    foreach ($vposts as $vpost) {
                        echo "<br><b>Story : </b>".$vpost['id'];
                        scanPageFeed($vpost,$ngo);
                    }
                } while ($fb->next($vposts) && $vposts = $fb->next($vposts));
            }
            if ($key == 'p_checkin_posts') {
                echo "<br><b>Checkin Posts : </b>";
                //$pts = ($ngo==1)?1:-2;
                $cposts = $response->getGraphEdge();
                do {
                    foreach ($cposts as $cpost) {
                        echo "<br><b>Story : </b>".$cpost['id'];
                        scanPageFeed($cpost,$ngo);
                    }
                } while ($fb->next($cposts) && $cposts = $fb->next($cposts));
            }
            if ($key == 'p_tagged') {
                echo "<br><b>Tagged Posts : </b>";
                //$pts = ($ngo==1)?1:-2;
                $taggeds = $response->getGraphEdge();
                do {
                    foreach ($taggeds as $tagged) {
                        echo "<br><b>Story : </b>".$tagged['id'];
                        scanPageFeed($tagged,$ngo);
                    }
                } while ($fb->next($taggeds) && $taggeds = $fb->next($taggeds));
            }
            if ($key == 'p_posts') {
                echo "<br><b>Own Posts : </b>";
                //$pts = ($ngo==1)?1:-2;
                $feeds = $response->getGraphEdge();
                do {
                    foreach ($feeds as $feed) {
                        echo "<br><b>Story : </b>".$feed['id'];
                        scanPageFeed($feed,$ngo);
                    }
                } while ($fb->next($feeds) && $feeds = $fb->next($feeds));
            }
            if ($key == 'p_conversations') {
                echo "<br><b>Page Conversations : </b>";
                $conversations = $response->getGraphEdge();
                do {
                    foreach ($conversations as $con) {
                        scanConversation($con,1);
                    }
                } while ($fb->next($conversations) && $conversations = $fb->next($conversations));
            }
        }
    }
    // 456859381117797/insights/page_fans_by_like_source?types=mobile,search,page_suggestions
}

function scanPageFeed($feed,$ngo) {
    global $fb, $points;
    $own=0;
    //print_r($feed->asArray());
    if(isset($feed['from'])) {
        echo "<br><b>From : </b>";
        if(isAdmin($feed['from'])) $own=1;
        if($ngo && !$own && $feed['from']['id']==$_SESSION['fb_uid'])
            fetchPeople($feed['from'],$points['nn_from']);
        else if(!$ngo && !$own && $feed['from']['id']!=$_SESSION['fb_uid'])
            fetchPeople($feed['from'],$points['o_from']);
    }
    if(isset($feed['story_tags'])) {
        echo "<br><b>Story Tags : </b>";
        foreach ($feed['story_tags'] as $stag) {
            //echo " ".$stag['name']." ";
            if($stag['id']!=$feed['from']['id'] && !isAdmin($stag) && $stag['id']!=$_SESSION['fb_uid'] && isset($stag['type']) && $stag['type']=='user') {
                if ($ngo && $own)
                    fetchPeople($stag,$points['n_posttag']);
                else if($ngo && !$own)
                    fetchPeople($stag,$points['nn_posttag']);
                else if(!$ngo)
                    fetchPeople($stag,$points['o_posttag']);
            }
            else if($stag['id']!=$feed['from']['id'] && (isset($stag['type']) && $stag['type']!='user') && scanTags($stag))
                fetchPeople($stag,1);
        }
    }
    if(isset($feed['message_tags']) && !isset($feed['to'])) {
        echo "<br><b>Message Tags : </b>";
        foreach ($feed['message_tags'] as $mtag) {
            if($mtag['id']!=$feed['from']['id'] && !isAdmin($mtag) && $mtag['id']!=$_SESSION['fb_uid'] && isset($mtag['type']) && $mtag['type']=='user') {
                if ($ngo && $own)
                    fetchPeople($mtag,$points['n_posttag']);
                else if($ngo && !$own)
                    fetchPeople($mtag,$points['nn_posttag']);
                else if(!$ngo)
                    fetchPeople($mtag,$points['o_posttag']);
            }
            else if($mtag['id']!=$feed['from']['id'] && $mtag['type']!='user' && scanTags($mtag))
                fetchPeople($mtag,1);
        }
    }
    if(isset($feed['to'])) {
        echo "<br><b>To : </b>";
        foreach ($feed['to'] as $to) {
            if($to['id']!=$feed['from']['id'] && !isAdmin($to) && $to['id']!=$_SESSION['fb_uid'] && isset($to['profile_type']) && $to['profile_type']=='user') {
                if ($ngo && $own)
                    fetchPeople($to,$points['n_posttag']);
                else if($ngo && !$own)
                    fetchPeople($to,$points['nn_posttag']);
                else if(!$ngo)
                    fetchPeople($to,$points['o_posttag']);
            }
            else if($to['id']!=$feed['from']['id'] && $to['profile_type']!='user' && scanTags($to))
                fetchPeople($to,1);
        }
    }
    //if($ngo==1) {
        if (isset($feed['sharedposts'])) {
            echo "<br><b>Shares : </b>";
            $shposts = $feed['sharedposts'];
            do {
                foreach ($shposts as $sp) {
                    //scanPageFeed($sp, $pts - 5,$ngo);
                    if(isset($sp['from'])) {
                        echo "<br><b>From : </b>";
                        $own1=0;
                        if(isAdmin($sp['from'])) $own1=1;
                        if($ngo && !$own1 && $sp['from']!=$_SESSION['fb_uid'])
                            fetchPeople($sp['from'],$points['n_share']);
                        else if(!$ngo && !$own1 && $sp['from']!=$_SESSION['fb_uid'])
                            fetchPeople($sp['from'],$points['o_share']);
                    }
                }
            } while ($fb->next($shposts) && $shposts = $fb->next($shposts));
        }
        if (isset($feed['reactions'])) {
            echo "<br><b>Reactions : </b>";
            $reactions = $feed['reactions'];
            do {
                foreach ($reactions as $rct) {
                    if($rct['id']!=$feed['from']['id'] && !isAdmin($rct) && $rct['id']!=$_SESSION['fb_uid']) {
                        if (isset($rct['type']) && (strcasecmp($rct['type'], "LOVE") == 0 || strcasecmp($rct['type'], "WOW") == 0)) {
                            if ($ngo && $own)
                                fetchPeople($rct, $points['n_love']);
                            else if ($ngo && !$own)
                                fetchPeople($rct, $points['nn_love']);
                            else if (!$ngo)
                                fetchPeople($rct, $points['o_love']);
                        } else {
                            if ($ngo && $own)
                                fetchPeople($rct, $points['n_like']);
                            else if ($ngo && !$own)
                                fetchPeople($rct, $points['nn_like']);
                            else if (!$ngo)
                                fetchPeople($rct, $points['o_like']);
                        }
                    }
                }
            } while ($fb->next($reactions) && $reactions = $fb->next($reactions));
        }
        if (isset($feed['comments'])) {
            echo "<br><b>Comments : </b>";
            $comments = $feed['comments'];
            //print_r($comments);
            do {
                foreach ($comments as $com) {
                    if(!isAdmin($com['from']) && $com['from']['id']!=$_SESSION['fb_uid']) {
                        echo "<br><b>Commented By : </b>";
                        if (isset($com['from'])) {
                            //print_r($com['from']);
                            if ($ngo && $own)
                                fetchPeople($com['from'], $points['n_comment']);
                            else if ($ngo && !$own)
                                fetchPeople($com['from'], $points['nn_comment']);
                            else if (!$ngo)
                                fetchPeople($com['from'], $points['o_comment']);
                        }
                    }
                    if (isset($com['comments'])) {
                        $replies = $com['comments'];
                        echo "<br><b>Replied By : </b>";
                        do {
                            foreach ($replies as $reply) {
                                if(!isAdmin($reply['from'])) {
                                    if (isset($reply['from']) && $reply['from']['id'] != $_SESSION['fb_uid']) {
                                        //print_r($reply['from']);
                                        if ($ngo && $own)
                                            fetchPeople($reply['from'], $points['n_reply']);
                                        else if ($ngo && !$own)
                                            fetchPeople($reply['from'], $points['nn_reply']);
                                        else if (!$ngo)
                                            fetchPeople($reply['from'], $points['o_reply']);
                                    }
                                }
                                if (isset($reply['message_tags'])) {
                                    echo "<br><b>Tagged in reply : </b>";
                                    foreach ($reply['message_tags'] as $mtag) {
                                        if($mtag['id']!=$_SESSION['fb_uid'] && !isAdmin($mtag)) {
                                            //print_r($mtag);
                                            if ($ngo && $own)
                                                fetchPeople($mtag,$points['n_tagcom']);
                                            else if($ngo && !$own)
                                                fetchPeople($mtag,$points['nn_tagcom']);
                                            else if(!$ngo)
                                                fetchPeople($mtag,$points['o_tagcom']);
                                        }
                                    }
                                }
                            }
                        } while ($fb->next($replies) && $replies = $fb->next($replies));
                    }
                    if (isset($com['message_tags'])) {
                        echo "<br><b>Tagged in comment: </b>";
                        foreach ($com['message_tags'] as $mtag) {
                            if($mtag['id']!=$_SESSION['fb_uid'] && !isAdmin($mtag)) {
                                print_r($mtag);
                                if ($ngo && $own)
                                    fetchPeople($mtag,$points['n_tagcom']);
                                else if($ngo && !$own)
                                    fetchPeople($mtag,$points['nn_tagcom']);
                                else if(!$ngo)
                                    fetchPeople($mtag,$points['o_tagcom']);
                            }
                        }
                    }
                }
            } while ($fb->next($comments) && $comments = $fb->next($comments));
        }
    //}
}

function scanRating($ratg,$ngo) {
    global $objt;
    //for
    $pts=1;
    if($ngo==1 && isset($ratg['rating'])) {
        if($ratg['rating']>2) {
            $pts = $ratg['rating']*4;
            if(isset($ratg['review_text']))
                $pts += 10;
            fetchPeople($ratg['reviewer'],$pts);
        }
        else
            fetchPeople($ratg['reviewer'],4);
    }
    else if($ngo==0 && isset($ratg['rating'])) {
        if($ratg['rating']>2) {
            $pts *= 3;
            if(isset($ratg['review_text']))
                $pts += 10;
            fetchPeople($ratg['reviewer'],$pts);
        }
        else
            fetchPeople($ratg['reviewer'],3);
    }
}

function scanConversation($con,$ngo) {
    global $points;
    $pts = ($ngo==1)?$points['n_conv']:$points['o_conv'];
    echo "<br><b>Participants : </b>";
    if(isset($con['participants'])) {
        foreach ($con['participants'] as $cp) {
            //fetchPeople($con,$ngo,$objt[11]);
            if($cp['id']!=$_SESSION['fb_uid'] && !isAdmin($cp))
                fetchPeople($cp,$pts);
        }
    }
}

/**
 * @param $arr
 * @param $pts
 */
function fetchPeople($arr, $pts) {
    global $fb, $fbtoken, $conn;
    $sw='no';
    $id = $arr['id'];
    $name = mysqli_real_escape_string($conn,$arr['name']);
    if((isset($arr['profile_type']) && strcasecmp($arr['profile_type'],'user')==0) || (isset($arr['type']) && strcasecmp($arr['type'],'user')==0)) {
        echo "   ".$arr['name']."->".$pts;
        $abc = ifExists($arr['id'],'user');
        if(!$abc) {
            mysqli_autocommit($conn, false);
            $q1 = mysqli_query($conn,"INSERT INTO user (name) VALUES ('$name')");
            if($q1) {
                $uid = mysqli_insert_id($conn);
                $q2 = mysqli_query($conn,"INSERT INTO user_accounts (u_id,at_id,account_id,account_name) VALUES ($uid,1,'$id','$name')");
                if($q2) {
                    $q3 = mysqli_query($conn,"INSERT INTO user_extended (u_id,ut_id,level) VALUES ($uid,3,5)");
                    if($q3) {
                        $ueid = mysqli_insert_id($conn);
                        mysqli_query($conn,"INSERT INTO user_scores (ue_id,st_id,score) VALUES ($ueid,1,$pts)");
                    }
                }
            }
            if(!mysqli_error($conn)) {
                mysqli_commit($conn);
                mysqli_autocommit($conn, true);
            }
            else {
                echo mysqli_error($conn);
                mysqli_rollback($conn);
                mysqli_autocommit($conn, true);
            }
        }
        else {
            if(isset($_SESSION['promoter']) && $_SESSION['promoter'] && isset($_SESSION['fb_uid']) && $_SESSION['fb_uid']==$arr['id'])
                $q0 = mysqli_query($conn,"SELECT ue_id FROM user_extended WHERE u_id=$abc AND ut_id=2");
            else if(isset($_SESSION['promoter']) && !$_SESSION['promoter'] && isset($_SESSION['fb_uid']) && $_SESSION['fb_uid']==$arr['id'])
                $q0 = mysqli_query($conn,"SELECT ue_id FROM user_extended WHERE u_id=$abc AND ut_id<>2");
            else
                $q0 = mysqli_query($conn,"SELECT ue_id FROM user_extended WHERE u_id=$abc");
            while($rw = mysqli_fetch_array($q0)) {
                $ueid = $rw['ue_id'];
                $q1 = mysqli_query($conn, "SELECT us_id FROM user_scores WHERE ue_id=$ueid AND st_id=1");
                $row = mysqli_fetch_array($q1);
                if ($row['us_id'])
                    mysqli_query($conn, "UPDATE user_scores SET score=score+$pts WHERE ue_id=$ueid AND st_id=1");
                else
                    mysqli_query($conn, "INSERT INTO user_scores (ue_id,st_id,score) VALUES ($ueid,1,$pts)");
                echo mysqli_error($conn);
            }
        }
    }
    else if(isset($arr['profile_type']) || isset($arr['type']) && ($arr['type']=='page' || $arr['type']=='group' || $arr['type']=='event')) {
        if(isset($arr['sw']) && $arr['sw']!=null && $arr['sw']!='') $sw = $arr['sw'];
        else
            if(scanTags($arr)) $sw='yes';
        if($sw=='yes') {
            echo "   ".$arr['name']."->".$pts;
            //echo "objname = ".$arr['name'];
            $abc = ifExists($arr['id'], 'other');
            if (!$abc) {
                if (isset($arr['uid']) && $arr['uid'] != null && $arr['uid'] != '') $uid = $arr['uid'];
                else
                    $uid = 11;
                if (isset($arr['token']) && $arr['token'] != null && $arr['token'] != '') $token = $arr['token'];
                else
                    $token = '';
                //insert into social objects table
                if (isset($arr['profile_type']))
                    $type = $arr['profile_type'];
                else if (isset($arr['type']))
                    $type = $arr['type'];
                else $type = '';
                $q1 = mysqli_query($conn, "SELECT ot_id FROM social_object_types WHERE ot_type='$type'");
                $row = mysqli_fetch_array($q1);
                $tid = $row['ot_id'];
                mysqli_query($conn, "INSERT INTO ngo_social_objects (u_id,so_id,so_name,so_token,so_type_id,at_id,is_sw,so_score) 
                                    VALUES ($uid,'$id','$name','$token',$tid,1,'$sw',$pts)");
                echo mysqli_error($conn);
            }
            else {
                mysqli_query($conn, "UPDATE ngo_social_objects SET so_score=so_score+$pts WHERE ns_id=$abc");
                echo mysqli_error($conn);
            }
        }
    }
    else if(!isset($arr['type']) && !isset($arr['profile_type'])) {
        if(isset($arr['uid']) && $arr['uid']!=null && $arr['uid']!='') $uid = $arr['uid'];
        else $uid='';
        if(isset($arr['token']) && $arr['token']!=null && $arr['token']!='') $token = $arr['token'];
        else $token='';
        if(isset($arr['sw']) && $arr['sw']!=null) $sw = $arr['sw'];
        else $sw = '';
        $objinfo = $fb->get('/' . $arr['id'] . '?metadata=1&fields=id,metadata{type}');
        $objn = $objinfo->getGraphNode();
        $objarr = array('id'=>$arr['id'], 'name'=>$arr['name'], 'token'=>$token, 'type'=>$objn['metadata']['type'], 'sw'=>$sw, 'uid'=>$uid);
        //echo "sw=".$sw;
        fetchPeople($objarr,$pts);
    }
    //print_r($users);
}

function isAdmin($adm) {
    global $adminsarr, $thisngo;
    if(!empty($adminsarr) && in_array($adm['id'],$adminsarr) || in_array($adm['id'],$thisngo))
        return true;
    else return false;
}

function ifExists($id,$type) {
    global $conn;
    if($type=='user') {
        $q1 = mysqli_query($conn,"SELECT u_id FROM user_accounts WHERE account_id='$id' AND at_id=1");
        $row = mysqli_fetch_array($q1);
        return $row['u_id'];
    }
    else if($type=='other') {
        $q1 = mysqli_query($conn,"SELECT ns_id FROM ngo_social_objects WHERE so_id='$id' AND at_id=1");
        $row = mysqli_fetch_array($q1);
        return $row['ns_id'];
    }
    else
        return null;
}