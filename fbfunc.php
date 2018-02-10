<?php

function createForFetch($id,$name,$points,$type=null,$sw=null) {
    $arr = array('id'=>$id, 'name'=>$name, 'points'=>$points, 'type'=>$type, 'sw'=>$sw);
    fetchPeople($arr,$points);
}

function searchCategory($cat) {
    global $fbcats;
    if(is_string($cat) && in_array(strtolower($cat),$fbcats)) {
        return true;
    }
    else if (is_array($cat)) {
        foreach ($cat as $c) {
            if(isset($c['name']) && in_array(strtolower($c['name']),$fbcats)) {
                return true;
            }
        }
    }
    return false;
}

function searchThisNGO($text)
{
    global $conn;
    $q1 = mysqli_query($conn,"SELECT so_name FROM this_ngo_accs_view");
    $text = "*" . $text;
    while ($row = mysqli_fetch_array($q1)) {
        if (stripos($text, $row['so_name']))
            return true;
    }
    return false;
}

function isThisNGOId($id) {
    global $conn;
    $q1 = mysqli_query($conn,"SELECT ns_id FROM this_ngo_accs_view WHERE so_id='$id'");
    $row = mysqli_fetch_array($q1);
    if($row['ns_id'])
        return true;
    return false;
}

function searchThisNGOTags($tags) {
    foreach ($tags as $tag) {
        if(isset($tag['id']) && isThisNGOId($tag['id'])) {
            return true;
        }
    }
    return false;
}

function isSWAccount($acc) {
    if(isset($acc['id']) && isThisNGOId($acc['id'])) {
        return true;
    }
    else if(isset($acc['category']) && searchCategory((string)($acc['category']))) {
        return true;
    }
    else if(isset($acc['category_list']) && searchCategory(array($acc['category_list']))) {
        return true;
    }
    /*
    else if( ((isset($acc['about']) && nlp($acc['about'])) || (isset($acc['mission']) && nlp($acc['mission'])) || (isset($acc['description']) && nlp($acc['description'])) || (isset($acc['general_info']) && nlp($acc['general_info'])) || (isset($acc['personal_info']) && nlp($acc['personal_info'])) || (isset($acc['company_overview']) && nlp($acc['company_overview'])) || (isset($acc['bio']) && nlp($acc['bio'])) || (isset($acc['impressum']) && nlp($acc['impressum'])))) {
        return true;
    }
    */
    return false;
}

function isSWGroup($grp) {
    if(isset($grp['id']) && (isThisNGOId($grp['id']) || (isset($grp['owner']['id']) && isThisNGOId($grp['owner']['id']))) ) {
        return true;
    }
    else if(isset($grp['description'])) {
        if(isset($grp['purpose']) && nlp('The purpose of this group is to '.$grp['purpose'].'. '.$grp['description'])) {
            return true;
        }
    }
    return false;
}

function isSWEvent($evn) {
    if((isset($evn['id']) && isThisNGOId($evn['id'])) || (isset($evn['description']) && searchThisNGO($evn['description'])) || (isset($evn['place']['id']) && isThisNGOId($evn['place']['id'])) || (isset($evn['owner']['id']) && isThisNGOId($evn['owner']['id'])) ) {
        return true;
    }
    else if ((isset($evn['description']) && nlp($evn['description'])) || (isset($evn['owner']) && isSWAccount($evn['owner'])))  {
        return true;
    }
    return false;
}

function scanTags($tag) {
    global $fb;
    if ($tag['id'] != $_SESSION['fb_uid'] && ((isset($tag['type']) && $tag['type'] != 'user') || (isset($tag['profile_type']) && $tag['profile_type'] != 'user'))) {
        if ((isset($tag['type']) && $tag['type'] == 'page') || (isset($tag['profile_type']) && $tag['profile_type'] == 'page')) {
            $pginfo = $fb->get('/' . $tag['id'] . '?fields=id,name,category,about,mission,description,general_info,personal_info,impressum,category_list,company_overview,bio');
            $pgnode = $pginfo->getGraphNode();
            if(isSWAccount($pgnode)) {
                return true;
            }
        }
        else if((isset($tag['type']) && $tag['type'] == 'group') || (isset($tag['profile_type']) && $tag['profile_type'] == 'group')) {
            $grinfo = $fb->get('/'.$tag['id'].'?fields=privacy,purpose,description,name,owner');
            $grnode = $grinfo->getGraphNode();
            if(isSWGroup($grnode)) {
                return true;
            }
        }
        else if((isset($tag['type']) && $tag['type'] == 'event') || (isset($tag['profile_type']) && $tag['profile_type'] == 'event')) {
            $evinfo = $fb->get('/'.$tag['id'].'?fields=id,place,description,name,owner{id,name,category,category_list}');
            $evnode = $evinfo->getGraphNode();
            if(isSWEvent($evnode)) {
                return true;
            }
        }
    }
    return false;
}

function scanShares($share) {
    global $fb;
    if(!isset($share['error']) && isset($share['metadata']['type']) && $share['metadata']['type']!='user') {
        if ($share['metadata']['type'] == 'page') {
            $pginfo = $fb->get('/' . $share['id'] . '?fields=id,name,category,about,mission,description,general_info,personal_info,impressum,category_list,company_overview,bio');
            $pgnode = $pginfo->getGraphNode();
            if(isSWAccount($pgnode)) {
                return true;
            }
        }
        else if($share['metadata']['type']=='group') {
            $grinfo = $fb->get('/'.$share['id'].'?fields=privacy,purpose,description,name,owner');
            $grnode = $grinfo->getGraphNode();
            if(isSWGroup($grnode)) {

                return true;
            }
        }
        else if($share['metadata']['type']=='event') {
            $evinfo = $fb->get('/'.$share['id'].'?fields=id,place,description,name,owner{id,name,category,category_list}');
            $evnode = $evinfo->getGraphNode();
            if(isSWEvent($evnode)) {
                return true;
            }
        }
    }
    return false;
}

function scanEducation($edu) {
    global $points;
    $eduscore =0;
    if(!$eduscore && isset($edu['school']['id']) && isThisNGOId($edu['school']['id'])) {
        //$eduscore = 25;
        $eduscore = $points['vn_edu'];
    }
    else if(!$eduscore && isset($edu['school']['category']) && searchCategory((string)$edu['school']['category'])) {
        //$eduscore = 20;
        $eduscore = $points['vo_edu'];
    }
    else if(!$eduscore && isset($edu['school']['category_list']) && searchCategory(array($edu['school']['category_list']))) {
        //$eduscore = 20;
        $eduscore = $points['vo_edu'];
    }
    /*
    else if(!$eduscore && ((isset($edu['school']['about']) && nlp($edu['school']['about'])) || (isset($edu['school']['mission']) && nlp($edu['school']['mission'])) || (isset($edu['school']['description']) && nlp($edu['school']['description'])) || (isset($edu['school']['general_info']) && nlp($edu['school']['general_info'])) || (isset($edu['school']['personal_info']) && nlp($edu['school']['personal_info'])) || (isset($edu['school']['company_overview']) && nlp($edu['school']['company_overview'])) || (isset($edu['school']['bio']) && nlp($edu['school']['bio'])) || (isset($edu['school']['impressum']) && nlp($edu['school']['impressum'])) )) {
        $eduscore = 5;
    }
    */
    //if(isset($edu['concentration']) && !$goteds)
    if($eduscore>0) {
        echo "<br>".$edu['school']['name']."->".$edu['school']['id']."->".$eduscore;
        //createForFetch($edu['school']['id'],$edu['school']['name'],15,'page','yes');
        $a = array('id'=>$edu['school']['id'],'name'=>$edu['school']['name'],'token'=>'','type'=>'page','sw'=>'yes','uid'=>'');
        fetchPeople($a,2);
    }
    return $eduscore;
}

function scanWork($work) {
    global $points;
    $workscore=0;
    if(!$workscore && isset($work['employer']['id']) && isThisNGOId($work['employer']['id'])) {
        //$workscore = 30;
        $workscore = $points['vn_work'];
    }
    else if(!$workscore && isset($work['employer']['category']) && searchCategory((string)$work['employer']['category'])) {
        //$workscore = 25;
        $workscore = $points['vo_work'];
    }
    else if(!$workscore && isset($work['employer']['category_list']) && searchCategory(array($work['employer']['category_list']))) {
        //$workscore = 25;
        $workscore = $points['vo_work'];
    }
    /*
    else if(!$workscore && ((isset($work['employer']['about']) && nlp($work['employer']['about'])) || (isset($work['employer']['mission']) && nlp($work['employer']['mission'])) || (isset($work['employer']['description']) && nlp($work['employer']['description'])) || (isset($work['employer']['general_info']) && nlp($work['employer']['general_info'])) || (isset($work['employer']['personal_info']) && nlp($work['employer']['personal_info'])) || (isset($work['employer']['company_overview']) && nlp($work['employer']['company_overview'])) || (isset($work['employer']['bio']) && nlp($work['employer']['bio'])) || (isset($work['employer']['impressum']) && nlp($work['employer']['impressum'])))) {
        $workscore = 15;
    }
    */
    else if((isset($work['position']) && $work['position']=="social worker") && !$workscore) {
        //$workscore = 30;
        $workscore = $points['vn_work'];
    }
    if($workscore>0) {
        echo "<br>".$work['employer']['name']."->".$work['employer']['id']."->".$workscore;
        //createForFetch($work['employer']['id'],$work['employer']['name'],15,'page','yes');
        $a = array('id'=>$work['employer']['id'],'name'=>$work['employer']['name'],'token'=>'','type'=>'page','sw'=>'yes','uid'=>'');
        fetchPeople($a,2);
    }
    return $workscore;
}

function scanAccounts($acc) {
    global $points;
    $accscore = 0;
    $sw = 1;
    /*
    if(!$accscore && isset($acc['id']) && isThisNGOId($acc['id'])) {
        $accscore = 35;
    }
    */
    if(!$accscore && isset($acc['category']) && searchCategory((string)($acc['category']))) {
        //$accscore = 30;
        $accscore = $points['vo_acc'];
    }
    else if(!$accscore && isset($acc['category_list']) && searchCategory(array($acc['category_list']))) {
        //$accscore = 30;
        $accscore = $points['vo_acc'];
    }
    else if( ((isset($acc['about']) && nlp($acc['about'])) || (isset($acc['mission']) && nlp($acc['mission'])) || (isset($acc['description']) && nlp($acc['description'])) || (isset($acc['general_info']) && nlp($acc['general_info'])) || (isset($acc['personal_info']) && nlp($acc['personal_info'])) || (isset($acc['company_overview']) && nlp($acc['company_overview'])) || (isset($acc['bio']) && nlp($acc['bio'])) || (isset($acc['impressum']) && nlp($acc['impressum']))) && !$accscore) {
        //$accscore = 20;
        $accscore = $points['vo_acc'];
        $sw = 0;
    }
    if(isset($acc['fan_count']) && $accscore) {
        if($acc['fan_count'] > 100) {
            $fans = ceil($acc['fan_count'] / 50000 * 20);
            if ($fans > 20)
                $accscore += 20;
            else
                $accscore += $fans;
        }
    }
    if($accscore>0 && $sw) {
        echo "<br>".$acc['name']."->".$acc['id']."->".$accscore;
        //createForFetch($acc['id'],$acc['name'],15,'page','yes');
        if(!isThisNGOId($acc['id'])) {
            $a = array('id' => $acc['id'], 'name' => $acc['name'], 'token' => $acc['access_token'], 'type' => 'page', 'sw' => 'yes', 'uid' => $_SESSION['uid']);
            fetchPeople($a, 2);
        }
    }
    return $accscore;
}

function scanGroups($grp) {
    global $points, $conn;
    $grpscore = 0;
    /*
    if(!$grpscore && (isset($grp['id']) && isThisNGOId($grp['id'])) && ((isset($grp['administrator']) && $grp['administrator']) || (isset($grp['owner']['id']) && isThisNGOId($grp['owner']['id']))) ) {
        $grpscore = 35;
    }
    else if(!$grpscore && isset($grp['id']) && isThisNGOId($grp['id'])) {
        $grpscore = 30;
    }
    */
    if(isset($grp['description']) && !$grpscore) {
        if(isset($grp['purpose']) && nlp('The purpose of this group is to '.$grp['purpose'].' '.$grp['description'])) {
            if((isset($grp['administrator']) && $grp['administrator']) || (isset($grp['owner']) && $grp['owner'])) {
                //$grpscore = 25;
                $grpscore = $points['vo_grp'];
            }
            else {
                //$grpscore = 20;
                $grpscore = $points['vo_grp']-5;
            }
        }
    }
    if($grpscore) {
        $grppop = 0;
        global $fb;
        $gm = $fb->get($grp['id'].'?fields=members.summary(true)')->getGraphNode();
        $gmeta = $gm['members']->getMetaData();

        if (isset($gmeta['summary']['total_count'])) {
            $grppop += $gmeta['summary']['total_count'];
        }
        if($grppop > 30) {
            $gpop = ceil($grppop / 10000 * 20);
            if ($gpop > 20)
                $grpscore += 20;
            else
                $grpscore += $gpop;
        }
    }
    if($grpscore>0) {
        echo "<br>".$grp['name']."->".$grp['id']."->".$grpscore;
        //createForFetch($grp['id'],$grp['name'],15,'group','yes');
        $owtk = null;
        if(isset($grp['owner']['id']) && isThisNGOId($grp['owner']['id'])) {
            $soid = $grp['owner']['id'];
            $ow = mysqli_query($conn,"SELECT so_token FROM this_ngo_accs_view WHERE so_id='$soid'");
            $owa = mysqli_fetch_array($ow);
            $owtk = $owa['so_token'];
        }
        if(!isThisNGOId($grp['id'])) {
            $a = array('id' => $grp['id'], 'name' => $grp['name'], 'token' => $owtk, 'type' => 'group', 'sw' => 'yes', 'uid' => $_SESSION['uid']);
            fetchPeople($a, 2);
        }
    }
    return $grpscore;
}

function scanEvents($evn) {
    global $points, $conn;
    $eventscore = 0;
    $eadm = '';
    if(!$eventscore && (isset($evn['id']) && isThisNGOId($evn['id'])) || (isset($evn['description']) && searchThisNGO($evn['description'])) || (isset($evn['place']['id']) && isThisNGOId($evn['place']['id'])) || (isset($evn['owner']['id']) && isThisNGOId($evn['owner']['id'])) ) {
        if(isset($evn['is_viewer_admin']) && $evn['is_viewer_admin']) {
            //$eventscore = 35;
            $eventscore = $points['vn_event'];
        }
        else if(isset($evn['rsvp_status']) && $evn['rsvp_status']=='attending') {
            //$eventscore = 30;
            $eventscore = $points['n_attend'];
        }
        else if(isset($evn['rsvp_status']) && $evn['rsvp_status']=='unsure') {
            $eventscore = $points['n_interest'];
        }
    }
    else if (!$eventscore && (((isset($evn['description']) && nlp($evn['description'])) || (isset($evn['owner']) && isSWAccount($evn['owner']))) )) {
        if(isset($evn['is_viewer_admin']) && $evn['is_viewer_admin']) {
            //$eventscore = 30;
            $eventscore = $points['vo_event'];
        }
        else if(isset($evn['rsvp_status']) && $evn['rsvp_status']=='attending') {
            //$eventscore = 25;
            $eventscore = $points['o_attend'];
        }
        else if(isset($evn['rsvp_status']) && $evn['rsvp_status']=='unsure') {
            //$eventscore = 20;
            $eventscore = $points['o_interest'];
        }
    }
    if($eventscore && isset($evn['attending_count']) && isset($evn['is_viewer_admin']) && $evn['is_viewer_admin']) {
        $eadm = $_SESSION['uid'];
        if($evn['attending_count'] > 20) {
            $mems = ceil($evn['attending_count'] / 300 * 20);
            if ($mems > 20)
                $eventscore += 20;
            else
                $eventscore += $mems;
        }
    }
    if($eventscore>0) {
        echo "<br>".$evn['name']."->".$evn['id']."->".$eventscore;
        //createForFetch($evn['id'],$evn['name'],$eventscore,'event','yes');
        $owtk = null;
        if(isset($evn['owner']['id']) && isThisNGOId($evn['owner']['id'])) {
            $soid = $evn['owner']['id'];
            $ow = mysqli_query($conn,"SELECT so_token FROM this_ngo_accs_view WHERE so_id='$soid'");
            $owa = mysqli_fetch_array($ow);
            $owtk = $owa['so_token'];
        }
        if(!isThisNGOId($evn['id'])) {
            $a = array('id' => $evn['id'], 'name' => $evn['name'], 'token' => $owtk, 'type' => 'event', 'sw' => 'yes', 'uid' => $eadm);
            fetchPeople($a, 2);
        }
    }
    return $eventscore;
}

function scanFeed($feed) {
    global $points;
    $feedscore = 0;
    $ps=0;
    if(isset($feed['privacy']['description']) && strtolower($feed['privacy']['description'])!='only me') {
        global $fb;
        $isparent = (!isset($feed['parent_id']))?1:0;
        if (!$feedscore && ((isset($feed['story']) && searchThisNGO($feed['story'])) || (isset($feed['name']) && searchThisNGO($feed['name'])) || (isset($feed['from']['id']) && isThisNGOId($feed['from']['id'])) || (isset($feed['place']['id']) && isThisNGOId($feed['place']['id'])))) {
            $feedscore=1;
        }
        else if (!$feedscore && ((isset($feed['description']) && searchThisNGO($feed['description'])) || (isset($feed['message']) && searchThisNGO($feed['message']))) ) {
            $feedscore=1;
        }
        else if (!$feedscore && ((isset($feed['story_tags']) && searchThisNGOTags(array($feed['story_tags']))) || (isset($feed['message_tags']) && searchThisNGOTags(array($feed['message_tags']))) || (isset($feed['to']) && searchThisNGOTags(array($feed['to']))))) {
            $feedscore=1;
        }
        else if(!$feedscore && ((isset($feed['description']) && nlp($feed['description'])) || (isset($feed['message']) && nlp($feed['message'])))) {
            $feedscore=2;
        }
        else if (!$feedscore && isset($feed['story_tags'])) {
            foreach ($feed['story_tags'] as $stag) {
                if($stag['id']!=$_SESSION['fb_uid'] && isset($stag['type'])) {
                    if (scanTags($stag)) {
                        $pts = 15;
                        $sw = 'yes';
                        $feedscore = 2;
                        //$a = array('id' => $stag['id'], 'name' => $stag['name'], 'token' => '', 'type' => $stag['type'], 'sw' => $sw, 'uid' => '');
                        //fetchPeople($a, $pts);
                        break;
                    }
                }
            }
        }
        if(!$feedscore && isset($feed['message_tags']) && !isset($feed['to'])) {
            foreach ($feed['message_tags'] as $mtag) {
                if($mtag['id']!=$_SESSION['fb_uid'] && isset($mtag['type'])) {
                    //createForFetch($mtag['id'],$mtag['name'],12,$mtag['type'],'no');
                    $pts=12;
                    $sw = 'no';
                    if(scanTags($mtag)) {
                        $pts=15;
                        $sw = 'yes';
                        $feedscore=2;
                        //$a = array('id'=>$mtag['id'],'name'=>$mtag['name'],'token'=>'','type'=>$mtag['type'],'sw'=>$sw,'uid'=>'');
                        //fetchPeople($a,$pts);
                        break;
                    }
                }
            }
        }
        if(!$feedscore && isset($feed['from'])) {
            if($feed['from']['id']!=$_SESSION['fb_uid']) {
                $frominfo = $fb->get('/' . $feed['from']['id'] . '?metadata=1&fields=id,name,metadata{type}');
                $fromnode = $frominfo->getGraphNode();
                if(scanShares($fromnode)) {
                    if(isset($feed['story'])) echo "<br><b>Story : </b>".$feed['story']." ".$feed['id'];
                    else echo "<br><b>Story : </b>".$feed['id'];
                    //createForFetch($feed['from']['id'],$feed['from']['name'],15,$fromnode['metadata']['type'],'yes');
                    echo "<br><b>From : </b>";
                    $a = array('id'=>$feed['from']['id'],'name'=>$feed['from']['name'],'token'=>'','type'=>$fromnode['metadata']['type'],'sw'=>'yes','uid'=>'');
                    fetchPeople($a,1);
                    $feedscore=2;
                }
            }
        }
        if(!$feedscore && isset($feed['to'])) {
            foreach ($feed['to'] as $to) {
                if($to['id']!=$_SESSION['fb_uid'] && isset($to['profile_type'])) {
                    $pts=12;
                    $sw = 'no';
                    if(scanTags($to)) {
                        $pts=15;
                        $sw='yes';
                        break;
                    }
                    //$a = array('id'=>$to['id'],'name'=>$to['name'],'token'=>'','type'=>$to['profile_type'],'sw'=>$sw,'uid'=>'');
                    //fetchPeople($a,$pts);
                }
            }
        }
        if($feedscore) {
            if(isset($feed['story'])) echo "<br><b>Story : </b>".$feed['story']." ".$feed['id'];
            else echo "<br><b>Story : </b>".$feed['id'];
        }
        if($feedscore && isset($feed['sharedposts'])) {
            echo "<br><b>Shares : </b>";
            $shposts = $feed['sharedposts'];
            //$pts = ($feedscore==1)?-5:-8;
            $pts = ($feedscore==1)?$points['n_share']:$points['nn_share'];
            do {
                foreach ($shposts as $sp) {
                    scanPageFeed($sp,$pts);
                }
            } while ($fb->next($shposts) && $shposts = $fb->next($shposts));
        }
        if($feedscore && isset($feed['reactions'])) {
            echo "<br><b>Reactions : </b>";
            $reactions = $feed['reactions'];
            //$pts = ($feedscore==1)?2:1;
            $pts = ($feedscore==1)?$points['n_like']:$points['nn_like'];
            do {
                foreach ($reactions as $rct) {
                    if(isset($rct['type']) && (strcasecmp($rct['type'],"LOVE")==0 || strcasecmp($rct['type'],"WOW")==0))
                        fetchPeople($rct,$pts+1);
                    else
                        fetchPeople($rct,$pts);
                }
            } while ($fb->next($reactions) && $reactions = $fb->next($reactions));
        }
        if($feedscore && isset($feed['comments'])) {
            echo "<br><b>Comments : </b>";
            $comments = $feed['comments'];
            //$pts = ($feedscore==1)?3:2;
            $pts = ($feedscore==1)?$points['n_comment']:$points['nn_comment'];
            do {
                foreach ($comments as $com) {
                    if(isset($com['from'])) {
                        if($feedscore==1) fetchPeople($com['from'],$points['n_comment']);
                        else fetchPeople($com['from'],$points['nn_comment']);
                    }
                    if (isset($com['comments'])) {
                        $replies = $com['comments'];
                        do {
                            foreach ($replies as $reply) {
                                if(isset($reply['from'])) {
                                    if($feedscore==1) fetchPeople($reply['from'],$points['n_reply']);
                                    else fetchPeople($reply['from'],$points['nn_reply']);
                                }
                                if(isset($reply['message_tags'])) {
                                    foreach ($reply['message_tags'] as $mtag) {
                                        if($feedscore==1) fetchPeople($mtag,$points['n_tagcom']);
                                        else fetchPeople($mtag,$points['nn_tagcom']);
                                    }
                                }
                            }
                        } while ($fb->next($replies) && $replies = $fb->next($replies));
                    }
                    if(isset($com['message_tags'])) {
                        foreach ($com['message_tags'] as $mtag) {
                            if($feedscore==1) fetchPeople($mtag,$points['n_tagcom']);
                            else fetchPeople($mtag,$points['nn_tagcom']);
                        }
                    }
                }
            } while ($fb->next($comments) && $comments = $fb->next($comments));
        }

        if($feedscore==1) {
            if ($isparent) {
                $feedscore = $points['nn_from'];
                if(isset($_SESSION['promoter']) && $_SESSION['promoter']) $_SESSION['pro_fbscore'] += 10;
            }
            else {
                $feedscore = $points['n_share'];
                if(isset($_SESSION['promoter']) && $_SESSION['promoter']) $_SESSION['pro_fbscore'] += 6;
            }
        }
        else if($feedscore==2) {
            if ($isparent) {
                $feedscore = $points['o_from'];
            }
            else {
                $feedscore = $points['nn_share'];
            }
        }

        if($feedscore) {
            $feedpop = 0;
            if (isset($feed['shares']) && isset($feed['shares']['count'])) {
                $feedpop += $feed['shares']['count'] * 2;
            }
            /*
            $fr = $fb->get($feed['id'].'?fields=reactions.summary(true),comments.summary(true)')->getGraphNode();
            $frmeta = $fr['reactions']->getMetaData();
            $fcmeta = $fr['comments']->getMetaData();
            */

            $frmeta = $feed['reactions']->getMetaData();
            $fcmeta = $feed['comments']->getMetaData();

            if (isset($frmeta['summary']['total_count'])) {
                $feedpop += $frmeta['summary']['total_count'];
            }

            if (isset($fcmeta['summary']['total_count'])) {
                $feedpop += $fcmeta['summary']['total_count'];
            }

            if($feedpop > 20) {
                $fpop = ceil($feedpop / 200 * 5);
                if ($fpop > 5)
                    $feedscore += 5;
                else
                    $feedscore += $fpop;
                echo "<br>Feed Popularity : ".$feedscore;
            }

            if(isset($feed['story_tags'])) {
                echo "<br><b>Story Tags : </b>";
                foreach ($feed['story_tags'] as $stag) {
                    if($stag['id']!=$_SESSION['fb_uid'] && isset($stag['type']) && $stag['type']!='story') {
                        //createForFetch($stag['id'],$stag['name'],12,$stag['type'],'no');
                        if($stag['type']=='user' && $stag['id']!=$_SESSION['fb_uid'] && $stag['id']!=$feed['from']['id']) {
                            $a = array('id'=>$stag['id'],'name'=>$stag['name'],'token'=>'','type'=>$stag['type'],'sw'=>'','uid'=>'');
                            fetchPeople($a,$points['nn_share']);
                        }
                        else if(scanTags($stag)) {
                            $a = array('id' => $stag['id'], 'name' => $stag['name'], 'token' => '', 'type' => $stag['type'], 'sw' => 'yes','uid'=>'');
                            fetchPeople($a, 1);
                        }
                    }
                }
            }
            if(isset($feed['message_tags']) && !isset($feed['to'])) {
                echo "<br><b>Message Tags : </b>";
                foreach ($feed['message_tags'] as $mtag) {
                    if($mtag['id']!=$_SESSION['fb_uid'] && isset($mtag['type'])) {
                        //createForFetch($mtag['id'],$mtag['name'],12,$mtag['type'],'no');
                        if($mtag['type']=='user' && $mtag['id']!=$_SESSION['fb_uid'] && $mtag['id']!=$feed['from']['id']) {
                            $a = array('id'=>$mtag['id'],'name'=>$mtag['name'],'token'=>'','type'=>$mtag['type'],'sw'=>'','uid'=>'');
                            fetchPeople($a,$points['nn_share']);
                        }
                        else if(scanTags($mtag)) {
                            $a = array('id'=>$mtag['id'],'name'=>$mtag['name'],'token'=>'','type'=>$mtag['type'],'sw'=>'yes','uid'=>'');
                            fetchPeople($a,1);
                        }

                    }
                }
            }
            if(isset($feed['to'])) {
                echo "<br><b>To : </b>";
                foreach ($feed['to'] as $to) {
                    if($to['id']!=$_SESSION['fb_uid'] && isset($to['profile_type'])) {
                        //createForFetch($to['id'],$to['name'],12,$to['profile_type'],'no');
                        if($to['profile_type']=='user' && $to['id']!=$_SESSION['fb_uid'] && $to['id']!=$feed['from']['id']) {
                            $a = array('id'=>$to['id'],'name'=>$to['name'],'token'=>'','type'=>$to['profile_type'],'sw'=>'','uid'=>'');
                            fetchPeople($a,$points['nn_share']);
                        }
                        if(scanTags($to)) {
                            $a = array('id'=>$to['id'],'name'=>$to['name'],'token'=>'','type'=>$to['profile_type'],'sw'=>'yes','uid'=>'');
                            fetchPeople($a,1);
                        }
                    }
                }
            }
            $sum = $_SESSION['fbscore']+$feedscore;
            echo "<br><b>" . $sum."</b>";
        }
    }
    return $feedscore;
}