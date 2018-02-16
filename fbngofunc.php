<?php
//session_start();
include_once 'dbconn.php';

function eventScan($eventid,$fb,$ngo) {
    global  $adminsarr, $points, $conn;
    $since = $_SESSION['since'];
    $until = $_SESSION['until'];
    echo " Event";
    array_splice($adminsarr, 0, sizeof($adminsarr));
    $oldr = null;
    $newr = array();
    $srid = 0;
    try {
        // Returns a `Facebook\FacebookResponse` object
        $batch = [
            'e_admins' => $fb->request('GET', '/' . $eventid . '/admins?fields=id,name,profile_type'),
            'e_attending' => $fb->request('GET', '/' . $eventid . '/attending'),
            'e_interested' => $fb->request('GET', '/' . $eventid . '/interested'),
            'e_feed' => $fb->request('GET', '/' . $eventid . '/feed?fields=created_time,description,message,from,name,caption,message_tags,source,status_type,story,story_tags,to{id,name,profile_type},type,parent_id,place,privacy,shares,sharedposts{from},reactions{id,name,type,profile_type},comments{from,message,message_tags,comments,reactions}&until='.$until.'&since='.$since)
        ];
        $responses = $fb->sendBatchRequest($batch);
        $qf = mysqli_query($conn,"SELECT * FROM social_responses WHERE at_id=1 AND so_id=3 AND r_obj_id='$eventid'");
        if(mysqli_num_rows($qf)>0) {
            $old = mysqli_fetch_array($qf);
            $srid = $old['sr_id'];
            $oldr = new \Facebook\GraphNodes\GraphNode(json_decode($old['response'], true));
            //$updevent = getUpFeed($responses,$oldr);
        }
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
                $newadm = array();
                //$newr = array_merge($newr,$response->getGraphEdge()->asArray());
                $newr['admins'] = $response->getGraphEdge()->asArray();
                $admins = $newadm['admins'] = $response->getGraphEdge();
                do {
                    foreach ($admins as $ad) {
                        array_push($adminsarr, $ad['id']);
                    }
                } while (!is_array($admins) && $fb->next($admins) && $admins = $fb->next($admins));
                echo "<br><b>Admins : </b>";
                if($oldr==null)
                    $admins = $response->getGraphEdge();
                else {
                    $admins = getUpResponse($newadm, $oldr)['admins'];
                }
                //print_r($admins);
                if(!empty($admins)) {
                    $pts = ($ngo == 1) ? $points['vn_event'] : $points['vo_event'];
                    do {
                        foreach ($admins as $ad) {
                            fetchPeople($ad, $pts);
                        }
                    } while (!is_array($admins) && $fb->next($admins) && $admins = $fb->next($admins));
                    //print_r($adminsarr);
                }
            }
            if ($key == 'e_attending') {
                echo "<br><b>Attending : </b>";
                $newatt = array();
                //$newr = array_merge($newr,$response->getGraphEdge()->asArray());
                $newr['attending'] = $response->getGraphEdge()->asArray();
                $newatt['attending'] = $response->getGraphEdge();
                if($oldr==null)
                    $attending = $response->getGraphEdge();
                else
                    $attending = getUpResponse($newatt,$oldr)['attending'];
                if(!empty($attending)) {
                    $pts = ($ngo == 1) ? $points['n_attend'] : $points['o_attend'];
                    do {
                        foreach ($attending as $att) {
                            if (!isAdmin($att) && $att['id'] != $_SESSION['fb_uid'])
                                fetchPeople($att, $pts);
                        }
                    } while (!is_array($attending) && $fb->next($attending) && $attending = $fb->next($attending));
                }
            }
            if ($key == 'e_interested') {
                echo "<br><b>Interested : </b>";
                $newintr = array();
                //$newr = array_merge($newr,$response->getGraphEdge()->asArray());
                $newr['interested'] = $response->getGraphEdge()->asArray();
                $newintr['interested'] = $response->getGraphEdge();
                if($oldr==null)
                    $interested = $response->getGraphEdge();
                else
                    $interested = getUpResponse($newintr,$oldr)['interested'];
                if(!empty($interested)) {
                    $pts = ($ngo == 1) ? $points['n_interest'] : $points['o_interest'];
                    do {
                        foreach ($interested as $intr) {
                            if (!isAdmin($intr) && $intr['id'] != $_SESSION['fb_uid'])
                                fetchPeople($intr, $pts);
                        }
                    } while (!is_array($interested) && $fb->next($interested) && $interested = $fb->next($interested));
                }
            }
            if ($key == 'e_feed') {
                echo "<br><b>Feed : </b>";
                //$newr = array_merge($newr,$response->getGraphEdge()->asArray());
                //$pts = ($ngo==1)?4:1;
                $feeds = $response->getGraphEdge();
                /*
                if($oldr==null)
                    $feeds = $response->getGraphEdge();
                else
                    $feeds = getUpResponse($response->getGraphEdge(),$oldr);
                */
                //print_r($feeds->asArray());
                prepareFeeds($feeds,$ngo);
            }
        }
    }
    $newr1 = mysqli_real_escape_string($conn, json_encode($newr));
    if($srid>0) {
        mysqli_query($conn,"UPDATE social_responses SET response='$newr1' WHERE sr_id=$srid");
    }
    else {
        mysqli_query($conn,"INSERT INTO social_responses (so_id,response,r_obj_id, at_id) VALUES (3,'$newr1','$eventid',1)");
    }
}

function groupScan($groupid,$fb,$ngo) {
    global  $adminsarr, $points, $conn;
    $oldr = null;
    $srid=0;
    $newr = array();
    $since = $_SESSION['since'];
    $until = $_SESSION['until'];
    echo " Group";
    //echo " ".$since." ".$until;
    array_splice($adminsarr, 0, sizeof($adminsarr));
    try {
        // Returns a `Facebook\FacebookResponse` object
        $batch = [
            'g_admins' => $fb->request('GET', '/' . $groupid . '/admins?fields=id,name,profile_type'),
            'g_members' => $fb->request('GET', '/' . $groupid . '/members'),
            'g_feed' => $fb->request('GET', '/' . $groupid . '/feed?fields=created_time,description,message,from,name,caption,message_tags,source,status_type,story,story_tags,to{id,name,profile_type},type,parent_id,place,privacy,shares,sharedposts{from},reactions{id,name,type,profile_type},comments{from,message,message_tags,comments,reactions}&until='.$until.'&since='.$since)
        ];
        $responses = $fb->sendBatchRequest($batch);
        $qf = mysqli_query($conn,"SELECT * FROM social_responses WHERE at_id=1 AND so_id=2 AND r_obj_id='$groupid'");
        if(mysqli_num_rows($qf)>0) {
            $old = mysqli_fetch_array($qf);
            $srid = $old['sr_id'];
            $oldr = new \Facebook\GraphNodes\GraphNode(json_decode($old['response'], true));
            //$updevent = getUpFeed($responses,$oldr);
        }
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
                $newadm = array();
                //$newr = array_merge($newr,$response->getGraphEdge()->asArray());
                $newr['admins'] = $response->getGraphEdge()->asArray();
                $admins = $newadm['admins'] = $response->getGraphEdge();
                do {
                    foreach ($admins as $ad) {
                        array_push($adminsarr, $ad['id']);
                    }
                } while (!is_array($admins) && $fb->next($admins) && $admins = $fb->next($admins));
                if($oldr==null)
                    $admins = $response->getGraphEdge();
                else {
                    $admins = getUpResponse($newadm, $oldr)['admins'];
                }
                //print_r($admins);
                if(!empty($admins)) {
                    $pts = ($ngo == 1) ? $points['vo_grp'] + 5 : $points['vo_grp'];
                    do {
                        foreach ($admins as $ad) {
                            fetchPeople($ad, $pts);
                        }
                    } while (!is_array($admins) && $fb->next($admins) && $admins = $fb->next($admins));
                }
            }
            if ($key == 'g_members') {
                echo "<br><b>Members : </b>";
                $newmem = array();
                //$newr = array_merge($newr,$response->getGraphEdge()->asArray());
                $newr['members'] = $response->getGraphEdge()->asArray();
                $newmem['members'] = $response->getGraphEdge();
                if($oldr==null)
                    $members = $response->getGraphEdge();
                else {
                    $members = getUpResponse($newmem, $oldr)['members'];
                }
                //print_r($members);
                if(!empty($members)) {
                    $pts = ($ngo == 1) ? $points['n_member'] : $points['o_member'];
                    do {
                        foreach ($members as $mem) {
                            if (!$mem['administrator'])
                                fetchPeople($mem, $pts);
                        }
                    } while (!is_array($members) && $fb->next($members) && $members = $fb->next($members));
                }
            }
            if ($key == 'g_feed') {
                echo "<br><b>Feed : </b>";
                $feeds = $response->getGraphEdge();
                prepareFeeds($feeds,$ngo);
            }
        }
    }
    $newr1 = mysqli_real_escape_string($conn, json_encode($newr));
    if($srid>0) {
        mysqli_query($conn,"UPDATE social_responses SET response='$newr1' WHERE sr_id=$srid");
    }
    else {
        mysqli_query($conn,"INSERT INTO social_responses (so_id,response,r_obj_id, at_id) VALUES (2,'$newr1','$groupid',1)");
    }
}

function pageScan($pgid,$fb,$ngo,$at=0)
{
    global  $adminsarr, $points, $conn;
    $since = $_SESSION['since'];
    $until = $_SESSION['until'];
    $srid=0;
    $oldr = null;
    $newr = array();
    try {
        echo " Page";
        array_splice($adminsarr, 0, sizeof($adminsarr));
        $batch = array();
        if($ngo==0 && !$at) {
            $batch = [
                'p_posts' => $fb->request('GET', '/' . $pgid . '/posts?fields=created_time,description,message,from,name,caption,message_tags,source,status_type,story,story_tags,to{id,name,profile_type},type,parent_id,place,privacy,shares,sharedposts{from},reactions{id,name,type,profile_type},comments{from,message,message_tags,comments,reactions}&until='.$until.'&since='.$since),
                'p_visitor_posts' => $fb->request('GET', '/' . $pgid . '/visitor_posts?fields=created_time,description,message,from,name,caption,message_tags,source,status_type,story,story_tags,to{id,name,profile_type},type,parent_id,place,privacy,shares,sharedposts{from},reactions{id,name,type,profile_type},comments{from,message,message_tags,comments,reactions}&until='.$until.'&since='.$since)
            ];
        }
        else if($ngo==1 || $at) {
            $batch = [
                'p_admins' => $fb->request('GET', '/' . $pgid . '/roles?fields=id,name,role'),
                'p_ratings' => $fb->request('GET', '/' . $pgid . '/ratings?fields=reviewer,rating'),
                'p_visitor_posts' => $fb->request('GET', '/' . $pgid . '/visitor_posts?fields=created_time,description,message,from,name,caption,message_tags,source,status_type,story,story_tags,to{id,name,profile_type},type,parent_id,place,privacy,shares,sharedposts{from},reactions{id,name,type,profile_type},comments{from,message,message_tags,comments,reactions}&until='.$until.'&since='.$since),
                'p_checkin_posts' => $fb->request('GET', '/' . $pgid . '/checkin_posts?fields=created_time,description,message,from,name,caption,message_tags,source,status_type,story,story_tags,to{id,name,profile_type},type,parent_id,place,privacy,shares,sharedposts{from},reactions{id,name,type,profile_type},comments{from,message,message_tags,comments,reactions}&until='.$until.'&since='.$since),
                'p_tagged' => $fb->request('GET', '/' . $pgid . '/tagged?fields=created_time,description,message,from,name,caption,message_tags,source,status_type,story,story_tags,to{id,name,profile_type},type,parent_id,place,privacy,shares,sharedposts{from},reactions{id,name,type,profile_type},comments{from,message,message_tags,comments,reactions}&until='.$until.'&since='.$since),
                'p_posts' => $fb->request('GET', '/' . $pgid . '/posts?fields=created_time,description,message,from,name,caption,message_tags,source,status_type,story,story_tags,to{id,name,profile_type},type,parent_id,place,privacy,shares,sharedposts{from},reactions{id,name,type,profile_type},comments{from,message,message_tags,comments,reactions}&until='.$until.'&since='.$since),
                'p_conversations' => $fb->request('GET', '/' . $pgid . '/conversations?fields=senders,participants,messages{message,from},subject,name,former_participants,can_reply')

            ];
        }
        $responses = $fb->sendBatchRequest($batch);
        $qf = mysqli_query($conn,"SELECT * FROM social_responses WHERE at_id=1 AND so_id=1 AND r_obj_id='$pgid'");
        if(mysqli_num_rows($qf)>0) {
            $old = mysqli_fetch_array($qf);
            $srid = $old['sr_id'];
            $oldr = new \Facebook\GraphNodes\GraphNode(json_decode($old['response'], true));
            //$updevent = getUpFeed($responses,$oldr);
        }
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
                $newadm = array();
                $newr['admins'] = $response->getGraphEdge()->asArray();
                $admins = $newadm['admins'] = $response->getGraphEdge();
                do {
                    foreach ($admins as $ad) {
                        array_push($adminsarr, $ad['id']);
                    }
                } while (!is_array($admins) && $fb->next($admins) && $admins = $fb->next($admins));
                if ($oldr == null)
                    $admins = $response->getGraphEdge();
                else {
                    $admins = getUpResponse($newadm, $oldr)['admins'];
                }
                //print_r($admins);
                if (!empty($admins)) {
                    $pts = ($ngo == 1) ? 40 : 35;
                    do {
                        foreach ($admins as $ad) {
                            fetchPeople($ad, $pts);
                        }
                    } while (!is_array($admins) && $fb->next($admins) && $admins = $fb->next($admins));
                }
            }
            if ($key == 'p_ratings') {
                echo "<br><b>Ratings : </b>";
                $newrat = array();
                //$newr = array_merge($newr,$response->getGraphEdge()->asArray());
                $newr['ratings'] = $response->getGraphEdge()->asArray();
                $newrat['ratings'] = $response->getGraphEdge();
                if($oldr==null)
                    $ratings = $response->getGraphEdge();
                else {
                    $ratings = getUpResponse($newrat, $oldr)['ratings'];
                }
                if(!empty($ratings)) {
                    do {
                        foreach ($ratings as $rating) {
                            scanRating($rating, 1);
                        }
                    } while (!is_array($ratings) && $fb->next($ratings) && $ratings = $fb->next($ratings));
                }
            }
            if ($key == 'p_visitor_posts') {
                echo "<br><b>Visitor Posts : </b>";
                //$pts = ($ngo==1)?1:-2;
                $vposts = $response->getGraphEdge();
                prepareFeeds($vposts,$ngo);
            }
            if ($key == 'p_checkin_posts') {
                echo "<br><b>Checkin Posts : </b>";
                //$pts = ($ngo==1)?1:-2;
                $cposts = $response->getGraphEdge();
                prepareFeeds($cposts,$ngo);
            }
            if ($key == 'p_tagged') {
                echo "<br><b>Tagged Posts : </b>";
                //$pts = ($ngo==1)?1:-2;
                $taggeds = $response->getGraphEdge();
                prepareFeeds($taggeds,$ngo);
            }
            if ($key == 'p_posts') {
                echo "<br><b>Own Posts : </b>";
                //$pts = ($ngo==1)?1:-2;
                $feeds = $response->getGraphEdge();
                prepareFeeds($feeds,$ngo);
            }
            if ($key == 'p_conversations') {
                echo "<br><b>Page Conversations : </b>";
                $newconv = array();
                //$newr = array_merge($newr,$response->getGraphEdge()->asArray());
                $newr['conversations'] = $response->getGraphEdge()->asArray();
                $newconv['conversations'] = $response->getGraphEdge();
                if($oldr==null)
                    $conversations = $response->getGraphEdge();
                else {
                    $conversations = getUpResponse($newconv, $oldr)['conversations'];
                }
                //print_r($conversations);
                if(!empty($conversations)) {
                    do {
                        foreach ($conversations as $con) {
                            scanConversation($con, 1);
                        }
                    } while (!is_array($conversations) && $fb->next($conversations) && $conversations = $fb->next($conversations));
                }
            }
        }
    }
    $newr1 = mysqli_real_escape_string($conn, json_encode($newr));
    if($srid>0) {
        mysqli_query($conn,"UPDATE social_responses SET response='$newr1' WHERE sr_id=$srid");
    }
    else {
        mysqli_query($conn,"INSERT INTO social_responses (so_id,response,r_obj_id, at_id) VALUES (1,'$newr1','$pgid',1)");
    }
    // 456859381117797/insights/page_fans_by_like_source?types=mobile,search,page_suggestions
}

function prepareFeeds($feeds,$ngo) {
    global $conn, $fb;
    do {
        foreach ($feeds as $feed) {
            echo "<br><b>Story : </b>" . $feed['id'];
            $nfid = $feed['id'];
            $feedm = mysqli_real_escape_string($conn, $feed);
            $qf = mysqli_query($conn,"SELECT * FROM social_responses WHERE at_id=1 AND so_id=4 AND r_obj_id='$nfid'");
            if(mysqli_num_rows($qf)>0) {
                $old = mysqli_fetch_array($qf);
                $rid = $old['sr_id'];
                $ofeed = new \Facebook\GraphNodes\GraphNode(json_decode($old['response'], true));
                $updfeed = getUpResponse($feed,$ofeed);
                scanPageFeed($updfeed, $ngo);
                mysqli_query($conn,"UPDATE social_responses SET response='$feedm' WHERE sr_id=$rid");
            }
            else {
                scanPageFeed($feed, $ngo);
                mysqli_query($conn,"INSERT INTO social_responses (so_id,response,r_obj_id,at_id) VALUES (4,'$feedm','$nfid',1)");
            }
        }
        echo mysqli_error($conn);
    } while (!is_array($feeds) && $fb->next($feeds) && $feeds = $fb->next($feeds));
}

function scanPageFeed($feed,$ngo) {
    global $fb, $points;
    $own=0;
    //print_r($feed->asArray());
    if(isset($feed['from'])) {
        echo "<br><b>From : </b>";
        if(isAdmin($feed['from'])) $own=1;
        if($ngo && $feed['from']['id']!=$_SESSION['fb_uid'] && !isThisNGOId($feed['from']['id']))
            fetchPeople($feed['from'],$points['nn_from']);
        else if(!$ngo && $feed['from']['id']!=$_SESSION['fb_uid'] && !isThisNGOId($feed['from']['id']))
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
            } while (!is_array($shposts) && $fb->next($shposts) && $shposts = $fb->next($shposts));
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
            } while (!is_array($reactions) && $fb->next($reactions) && $reactions = $fb->next($reactions));
        }
        if (isset($feed['comments'])) {
            echo "<br><b>Comments : </b>";
            $comments = $feed['comments'];
            //print_r($comments);
            do {
                foreach ($comments as $com) {
                    if(isset($com['from']) && !isAdmin($com['from']) && $com['from']['id']!=$_SESSION['fb_uid']) {
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
                        } while (!is_array($replies) && $fb->next($replies) && $replies = $fb->next($replies));
                    }
                    if (isset($com['message_tags'])) {
                        echo "<br><b>Tagged in comment: </b>";
                        foreach ($com['message_tags'] as $mtag) {
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
            } while (!is_array($comments) && $fb->next($comments) && $comments = $fb->next($comments));
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
    if($arr['id']==0) return;
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
            if($pts>2) $pts=2;
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
                if(isset($arr['uid']) && $arr['uid']!=null && $arr['uid']!='') {
                    $uid = $arr['uid'];
                    //echo "<b>".$uid."</b>";
                    mysqli_query($conn, "UPDATE ngo_social_objects SET u_id=$uid, so_score=so_score+$pts WHERE ns_id=$abc");
                }
                else {
                    mysqli_query($conn, "UPDATE ngo_social_objects SET so_score=so_score+$pts WHERE ns_id=$abc");
                }
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

function arrDiff($arr1,$arr2,$isedge) {
    global $fb;
    $fp1 = $fp2 =0;
    $found=0;
    $newarr = array();
    if($isedge) {
        $dobrk=0;
        do {
            $fp1 = 0;
            $ary1 = $arr1->asArray();
            if($found==2) {
                if (isset($ary1[$fp1 + 1]) && isset($ary1[$fp1 + 2])) {
                    if ((isset($ary1[$fp1 + 1]) && isset($arr2[$fp2 + 1]) && $ary1[$fp1 + 1] == $arr2[$fp2 + 1]) && (isset($ary1[$fp1 + 2]) && isset($arr2[$fp2 + 2]) && $ary1[$fp1 + 2] == $arr2[$fp2 + 2])) {
                        break;
                    }
                    $found = 0;
                }
            }
            else if($found==1) {
                if (isset($ary1[$fp1 + 1])) {
                    if ((isset($ary1[$fp1 + 1]) && $ary1[$fp1 + 1] == $arr2[$fp2 + 1])) {
                        break;
                    }
                    $found = 0;
                }
            }
            foreach($ary1 as $a1) {
                $fp2 = 0;
                foreach ($arr2 as $a2) {
                    if ($a1 == $a2) {
                        $found += 1;
                        break;
                    } else $fp2++;
                }
                if ($found == 1) {
                    if (isset($ary1[$fp1 + 1]) && isset($ary1[$fp1 + 2])) {
                        if ((isset($ary1[$fp1 + 1]) && isset($arr2[$fp2 + 1]) && $ary1[$fp1 + 1] == $arr2[$fp2 + 1]) && (isset($ary1[$fp1 + 2]) && isset($arr2[$fp2 + 2]) && $ary1[$fp1 + 2] == $arr2[$fp2 + 2])) {
                            $dobrk = 1;
                            break;
                        }
                        $found = 0;
                    }
                    else if(isset($ary1[$fp1 + 1]) && !isset($ary1[$fp1 + 2])) {
                        if ((isset($ary1[$fp1 + 1]) && $ary1[$fp1 + 1] == $arr2[$fp2 + 1])) {
                            $found = 1;
                            break;
                        }
                        $found = 0;
                    }
                    else {
                        $found = 2;
                        break;
                    }
                } else {
                    $fp1++;
                    array_push($newarr, $a1);
                }
            }
            if ($dobrk) break;
        } while($fb->next($arr1) && $arr1 = $fb->next($arr1));
    }
    else {
        $ary1 = $arr1->asArray();
        foreach ($ary1 as $a1) {
            $found=0;
            foreach ($arr2 as $a2) {
                if($a1['id']==$a2['id']) {
                    $found=1;
                    break;
                }
            }
            if(!$found)
                array_push($newarr, $a1);
        }
    }
    /*
    foreach($arr2 as $a2) {
        if(isset($a2['profile_type']) && isset($a2['id']) && isset($a2['name']))
            $ay1[$a2['id']] = $a2['profile_type']."|".$a2['name'];
        else if(isset($a2['type']) && isset($a2['id']) && isset($a2['name']))
            $ay1[$a2['id']] = $a2['type']."|".$a2['name'];
        else if(isset($a2['id']) && isset($a2['name'])) {
            $objinfo = $fb->get('/' . $a2['id'] . '?metadata=1&fields=id,metadata{type}');
            $objn = $objinfo->getGraphNode();
            $ay2[$a2['id']] = $objn['metadata']['type']."|".$a2['name'];
        }
    }

    $adiff = array_diff_assoc($ay1,$ay2);
    print_r($adiff);
    return $adiff;
    */
    return $newarr;
}

function getUpResponse($newa, $olda) {
    $upda = array();
    $olda = $olda->asArray();
    if(isset($newa['id'])) $upda['id'] = $newa['id'];
    if(isset($newa['from'])) $upda['from'] = array('id'=>0,'name'=>'');
    if(isset($newa['message_tags']) && isset($olda['message_tags']) && !empty($olda['message_tags'])) {
        $upda['message_tags'] = arrDiff($newa['message_tags'],$olda['message_tags'],0);
    }
    else if(isset($newa['message_tags']) && (!isset($olda['message_tags']) || empty($olda['message_tags']))) {
        $upda['message_tags'] = $newa['message_tags'];
    }
    if(isset($newa['story_tags']) && isset($olda['story_tags']) && !empty($olda['story_tags'])) {
        $upda['story_tags'] = arrDiff($newa['story_tags'],$olda['story_tags'],0);
    }
    else if(isset($newa['story_tags']) && (!isset($olda['story_tags']) || empty($olda['story_tags']))) {
        $upda['story_tags'] = $newa['story_tags'];
    }
    if(isset($newa['to']) && isset($olda['to']) && !empty($olda['to'])) {
        $upda['to'] = arrDiff($newa['to'],$olda['to'],0);
    }
    else if(isset($newa['to']) && (!isset($olda['to']) || empty($olda['to']))) {
        $upda['to'] = $newa['to'];
    }
    if(isset($newa['sharedposts']) && isset($olda['sharedposts']) && !empty($olda['sharedposts'])) {
        $upda['sharedposts'] = arrDiff($newa['sharedposts'],$olda['sharedposts'],1);
    }
    else if(isset($newa['sharedposts']) && (!isset($olda['sharedposts']) || empty($olda['sharedposts']))) {
        $upda['sharedposts'] = $newa['sharedposts'];
    }
    if(isset($newa['reactions']) && isset($olda['reactions']) && !empty($olda['reactions'])) {
        $upda['reactions'] = arrDiff($newa['reactions'],$olda['reactions'],1);
    }
    else if(isset($newa['reactions']) && (!isset($olda['reactions']) || empty($olda['reactions']))) {
        $upda['reactions'] = $newa['reactions'];
    }
    if(isset($newa['comments']) && isset($olda['comments']) && !empty($olda['comments'])) {
        $upda['comments'] = arrDiff($newa['comments'],$olda['comments'],1);
    }
    else if(isset($newa['comments']) && (!isset($olda['comments']) || empty($olda['comments']))) {
        $upda['comments'] = $newa['comments'];
    }
    if(isset($newa['admins']) && isset($olda['admins']) && !empty($olda['admins'])) {
        $upda['admins'] = arrDiff($newa['admins'],$olda['admins'],1);
    }
    else if(isset($newa['admins']) && (!isset($olda['admins']) || empty($olda['admins']))) {
        $upda['admins'] = $newa['admins'];
    }
    if(isset($newa['attending']) && isset($olda['attending']) && !empty($olda['attending'])) {
        $upda['attending'] = arrDiff($newa['attending'],$olda['attending'],1);
    }
    else if(isset($newa['attending']) && (!isset($olda['attending']) || empty($olda['attending']))) {
        $upda['attending'] = $newa['attending'];
    }
    if(isset($newa['interested']) && isset($olda['interested']) && !empty($olda['interested'])) {
        $upda['interested'] = arrDiff($newa['interested'],$olda['interested'],1);
    }
    else if(isset($newa['interested']) && (!isset($olda['interested']) || empty($olda['interested']))) {
        $upda['interested'] = $newa['interested'];
    }
    if(isset($newa['members']) && isset($olda['members']) && !empty($olda['members'])) {
        $upda['members'] = arrDiff($newa['members'],$olda['members'],1);
    }
    else if(isset($newa['members']) && (!isset($olda['members']) || empty($olda['members']))) {
        $upda['members'] = $newa['members'];
    }
    if(isset($newa['ratings']) && isset($olda['ratings']) && !empty($olda['ratings'])) {
        $upda['ratings'] = arrDiff($newa['ratings'],$olda['ratings'],1);
    }
    else if(isset($newa['ratings']) && (!isset($olda['ratings']) || empty($olda['ratings']))) {
        $upda['ratings'] = $newa['ratings'];
    }
    if(isset($newa['roles']) && isset($olda['roles']) && !empty($olda['roles'])) {
        $upda['roles'] = arrDiff($newa['roles'],$olda['roles'],1);
    }
    else if(isset($newa['roles']) && (!isset($olda['roles']) || empty($olda['roles']))) {
        $upda['roles'] = $newa['roles'];
    }
    if(isset($newa['conversations']) && isset($olda['conversations']) && !empty($olda['conversations'])) {
        $upda['conversations'] = arrDiff($newa['conversations'],$olda['conversations'],1);
    }
    else if(isset($newa['conversations']) && (!isset($olda['conversations']) || empty($olda['conversations']))) {
        $upda['conversations'] = $newa['conversations'];
    }

    //print_r($upda);
    return $upda;
}