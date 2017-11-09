<?php

include_once 'fbinit.php';
use Facebook\FacebookRequest;

function nlp($str) {
    return true;
}

$fbtoken = $_SESSION['fb_access_token'];
$fbuid = $_SESSION['fb_uid'];
$fbname = $_SESSION['fb_uname'];
$_SESSION['fbscore'] = 0;

try {
    // Returns a `Facebook\FacebookResponse` object
    $fb->setDefaultAccessToken($fbtoken);
    $batch = array();
    $batch = [
        'u_about' => $fb->request('GET','/'.$fbuid.'?fields=about'),
        'u_education' => $fb->request('GET','/'.$fbuid.'?fields=education{concentration,school{id,name,category,about,mission,description,general_info,personal_info,category_list,company_overview,bio}}'),
        'u_work' => $fb->request('GET','/'.$fbuid.'?fields=work{employer{id,name,category,about,mission,description,general_info,personal_info,category_list,company_overview,bio},location,position}'),
        'u_accounts' => $fb->request('GET','/'.$fbuid.'?fields=accounts{id,name,category,about,mission,description,general_info,personal_info,category_list,company_overview,bio}'),
        'u_groups' => $fb->request('GET','/'.$fbuid.'?fields=groups{privacy,administrator,purpose,description,name,owner}'),
        'u_events' => $fb->request('GET','/'.$fbuid.'?fields=events{owner{id,name,category,about,mission,description,general_info,personal_info,category_list,company_overview,bio},description,name,place,rsvp_status,end_time,start_time,category,admins,type,is_viewer_admin}'),
        'u_feed' => $fb->request('GET','/'.$fbuid.'?fields=feed{description,message,from,name,caption,message_tags,source,status_type,story,story_tags,to,type,parent_id,place,privacy,likes.summary(true),shares}')
    ];
    $responses = $fb->sendBatchRequest($batch);

    foreach ($responses as $key => $response) {
        if ($response->isError()) {
            $e = $response->getThrownException();
            echo '<p>Error! Facebook SDK Said: ' . $e->getMessage() . "\n\n";
            echo '<p>Graph Said: ' . "\n\n";
            var_dump($e->getResponse());
        }
        else {
            $respbody = $response->getGraphNode();
            //echo '<br><br>'.$respbody.'<br><br>';
            if($key == 'u_about' && isset($respbody['about'])) {
                if(nlp($respbody['about'])) {
                    $_SESSION['fbscore'] += 10;
                    echo '<br>user about '.$_SESSION['fbscore'];
                }
            }
            if($key == 'u_education' && isset($respbody['education'])) {
                foreach ($respbody['education'] as $edu) {
                    $goteds=0;
                    if(isset($edu['school']['name']) && stripos($edu['school']['name'],'this ngo') && !$goteds) {
                        $_SESSION['fbscore'] += 15;
                        $goteds=1;
                        echo '<br>school name '.$_SESSION['fbscore'];
                    }
                    else if(isset($edu['school']['category']) && (strtolower($edu['school']['category'])=='non-governmental organization (ngo)' || strtolower($edu['school']['category'])=='nonprofit organization' || strtolower($edu['school']['category'])=='social service') && !$goteds) {
                        $_SESSION['fbscore'] += 10;
                        $goteds=1;
                        echo '<br>school category '.$_SESSION['fbscore'];
                    }
                    else if(isset($edu['school']['category_list']) && !$goteds) {
                        $edclflag=0;
                        foreach ($edu['school']['category_list'] as $edcat) {
                            if (isset($edcat['name']) && (strcasecmp('nonprofit organization', $edcat['name'])==0 || strcasecmp('social service', $edcat['name'])==0 || strcasecmp('', $edcat['name'])==0 || strcasecmp('non-governmental organization (ngo)', $edcat['name'])==0)) {
                                $edclflag=1;
                            }
                        }
                        if($edclflag) {
                            $_SESSION['fbscore'] += 10;
                            $goteds=1;
                            echo '<br>school category list '.$_SESSION['fbscore'];
                        }
                    }
                    if(!$goteds && ((isset($edu['school']['about']) && nlp($edu['school']['about'])) || (isset($edu['school']['mission']) && nlp($edu['school']['mission'])) || (isset($edu['school']['description']) && nlp($edu['school']['description'])) || (isset($edu['school']['general_info']) && nlp($edu['school']['general_info'])) || (isset($edu['school']['personal_info']) && nlp($edu['school']['personal_info'])) || (isset($edu['school']['company_overview']) && nlp($edu['school']['company_overview'])) || (isset($edu['school']['bio']) && nlp($edu['school']['bio'])) )) {
                        $_SESSION['fbscore'] += 5;
                        $goteds=1;
                        echo '<br>school details '.$_SESSION['fbscore'];
                    }
                    //if(isset($edu['concentration']) && !$goteds)
                    //    echo '<br><br>'.$edu['concentration'][0]['name'];
                }
            }
            if($key == 'u_work' && isset($respbody['work'])) {
                foreach ($respbody['work'] as $work) {
                    $gotwks=0;
                    if(isset($work['employer']['name']) && stripos($work['employer']['name'],'this ngo') && !$gotwks) {
                        $_SESSION['fbscore'] += 25;
                        $gotwks=1;
                        echo '<br>employer name '.$_SESSION['fbscore'];
                    }
                    else if(isset($work['employer']['category']) && (strtolower($work['employer']['category'])=='non-governmental organization (ngo)' || strtolower($work['employer']['category'])=='nonprofit organization' || strtolower($work['employer']['category'])=='social service') && !$gotwks) {
                        $_SESSION['fbscore'] += 20;
                        $gotwks=1;
                        echo '<br>employer category '.$_SESSION['fbscore'];
                    }
                    else if(isset($work['employer']['category_list']) && !$gotwks) {
                        $wclflag=0;
                        foreach ($work['employer']['category_list'] as $wrcat) {
                            if (isset($wrcat['name']) && (strcasecmp('nonprofit organization', $wrcat['name'])==0 || strcasecmp('social service', $wrcat['name'])==0 || strcasecmp('', $wrcat['name'])==0 || strcasecmp('non-governmental organization (ngo)', $wrcat['name'])==0)) {
                                $wclflag=1;
                            }
                        }
                        if($wclflag) {
                            $_SESSION['fbscore'] += 15;
                            $gotwks=1;
                            echo '<br>employer category list '.$_SESSION['fbscore'];
                        }
                    }
                    if( ((isset($work['employer']['about']) && nlp($work['employer']['about'])) || (isset($work['employer']['mission']) && nlp($work['employer']['mission'])) || (isset($work['employer']['description']) && nlp($work['employer']['description'])) || (isset($work['employer']['general_info']) && nlp($work['employer']['general_info'])) || (isset($work['employer']['personal_info']) && nlp($work['employer']['personal_info'])) || (isset($work['employer']['company_overview']) && nlp($work['employer']['company_overview'])) || (isset($work['employer']['bio']) && nlp($work['employer']['bio']))) && !$gotwks) {
                        $_SESSION['fbscore'] += 15;
                        $gotwks=1;
                        echo '<br>employer details '.$_SESSION['fbscore'];
                    }
                    else if((isset($work['position']) && $work['position']=="social worker") && !$gotwks) {
                        $_SESSION['fbscore'] += 20;
                        $gotwks=1;
                        echo '<br>work position '.$_SESSION['fbscore'];
                    }
                }
            }
            if($key == 'u_accounts' && isset($respbody['accounts'])) {
                foreach ($respbody['accounts'] as $acc) {
                    $gotacs=0;
                    if(isset($acc['name']) && stripos($acc['name'],'this ngo') && !$gotacs) {
                        $_SESSION['fbscore'] += 25;
                        $gotacs=1;
                        echo '<br>account name '.$_SESSION['fbscore'];
                    }
                    else if(isset($acc['category']) && (strtolower($acc['category'])=='non-governmental organization (ngo)' || strtolower($acc['category'])=='nonprofit organization' || strtolower($acc['category'])=='social service') && !$gotacs) {
                        $_SESSION['fbscore'] += 20;
                        $gotacs=1;
                        echo '<br>account category '.$_SESSION['fbscore'];
                    }
                    else if(isset($acc['category_list']) && !$gotacs) {
                        $aclflag=0;
                        foreach ($acc['category_list'] as $pgcat) {
                            if (strcasecmp('nonprofit organization', $pgcat['name'])==0 || strcasecmp('social service', $pgcat['name'])==0 || strcasecmp('', $pgcat['name'])==0 || strcasecmp('non-governmental organization (ngo)', $pgcat['name'])==0) {
                                $aclflag=1;
                            }
                        }
                        if($aclflag) {
                            $_SESSION['fbscore'] += 15;
                            $gotacs=1;
                            echo '<br>account category list '.$_SESSION['fbscore'];
                        }
                    }
                    if( ((isset($acc['about']) && nlp($acc['about'])) || (isset($acc['mission']) && nlp($acc['mission'])) || (isset($acc['description']) && nlp($acc['description'])) || (isset($acc['general_info']) && nlp($acc['general_info'])) || (isset($acc['personal_info']) && nlp($acc['personal_info'])) || (isset($acc['company_overview']) && nlp($acc['company_overview'])) || (isset($acc['bio']) && nlp($acc['bio']))) && !$gotacs) {
                        $_SESSION['fbscore'] += 15;
                        $gotacs=1;
                        echo '<br>account details '.$_SESSION['fbscore'];
                    }
                }
            }
            if($key == 'u_groups' && isset($respbody['groups'])) {
                foreach ($respbody['groups'] as $grp) {
                    $gotgrs=0;
                    if( (isset($grp['name']) && stripos($grp['name'],'this ngo')) && ((isset($grp['administrator']) && $grp['administrator']) || (isset($grp['owner']['name']) && strcasecmp($grp['owner']['name'],'this_ngo'))) && !$gotgrs ) {
                        $_SESSION['fbscore'] += 25;
                        $gotgrs=1;
                        echo '<br>group name & admin '.$_SESSION['fbscore'];
                    }
                    else if(isset($grp['name']) && stripos($grp['name'],'this ngo') && !$gotgrs) {
                        $_SESSION['fbscore'] += 20;
                        $gotgrs=1;
                        echo '<br>group name '.$_SESSION['fbscore'];
                    }
                    else if(isset($grp['description']) && !$gotgrs) {
                        if(isset($grp['purpose']) && nlp('The purpose of this group is to '.$grp['purpose'].' '.$grp['description'])) {
                            if((isset($grp['administrator']) && $grp['administrator']) || (isset($grp['owner']) && $grp['owner'])) {
                                $_SESSION['fbscore'] += 15;
                                $gotgrs=1;
                                echo '<br>group details & admin/owner '.$_SESSION['fbscore'];
                            }
                            else {
                                $_SESSION['fbscore'] += 10;
                                $gotgrs=1;
                                echo '<br>group details '.$_SESSION['fbscore'];
                            }
                        }
                    }
                }
            }
            if($key == 'u_events' && isset($respbody['events'])) {
                foreach ($respbody['events'] as $event) {
                    $gotevs=0;
                    if((isset($event['name']) && stripos($event['name'],'this ngo')) || (isset($event['description']) && stripos($event['description'],'this ngo')) || (isset($event['place']['name']) && stripos($event['place']['name'],'this ngo')) || (isset($event['owner']['name']) && strtolower($event['owner']['name'])=='this ngo') && !$gotevs) {
                        if(isset($event['is_viewer_admin']) && $event['is_viewer_admin']) {
                            $_SESSION['fbscore'] += 25;
                            $gotevs=1;
                            echo '<br>this ngo event & admin '.$_SESSION['fbscore'];
                        }
                        else if(isset($event['rsvp_status']) && $event['rsvp_status']=='attending') {
                            $_SESSION['fbscore'] += 20;
                            $gotevs=1;
                            echo '<br>this ngo event attending '.$_SESSION['fbscore'];
                        }
                        else if(isset($event['rsvp_status']) && $event['rsvp_status']=='unsure') {
                            $_SESSION['fbscore'] += 15;
                            $gotevs=1;
                            echo '<br>this ngo event interested '.$_SESSION['fbscore'];
                        }
                    }
                    else if(isset($event['description']) && nlp($event['description']) && !$gotevs) {
                        if(isset($event['is_viewer_admin']) && $event['is_viewer_admin']) {
                            $_SESSION['fbscore'] += 20;
                            $gotevs=1;
                            echo '<br>event description & admin '.$_SESSION['fbscore'];
                        }
                        else if(isset($event['rsvp_status']) && $event['rsvp_status']=='attending') {
                            $_SESSION['fbscore'] += 15;
                            $gotevs=1;
                            echo '<br>event description & attending '.$_SESSION['fbscore'];
                        }
                        else if(isset($event['rsvp_status']) && $event['rsvp_status']=='unsure') {
                            $_SESSION['fbscore'] += 10;
                            $gotevs=1;
                            echo '<br>event description & attending '.$_SESSION['fbscore'];
                        }
                    }
                    else if(isset($event['owner']['category']) && (strtolower($event['owner']['category'])=='non-governmental organization (ngo)' || strtolower($event['owner']['category'])=='nonprofit organization' || strtolower($event['owner']['category'])=='social service') && !$gotevs) {
                        if(isset($event['is_viewer_admin']) && $event['is_viewer_admin']) {
                            $_SESSION['fbscore'] += 20;
                            $gotevs=1;
                            echo '<br>event owner category & admin '.$_SESSION['fbscore'];
                        }
                        else if(isset($event['rsvp_status']) && $event['rsvp_status']=='attending') {
                            $_SESSION['fbscore'] += 15;
                            $gotevs=1;
                            echo '<br>event owner category & attending '.$_SESSION['fbscore'];
                        }
                        else if(isset($event['rsvp_status']) && $event['rsvp_status']=='unsure') {
                            $_SESSION['fbscore'] += 10;
                            $gotevs=1;
                            echo '<br>event owner category attending '.$_SESSION['fbscore'];
                        }
                    }
                    else if(isset($event['owner']['category_list']) && !$gotevs) {
                        $evclflag=0;
                        foreach ($event['owner']['category_list'] as $evcat) {
                            if (isset($evcat['name']) && (strcasecmp('nonprofit organization', $evcat['name'])==0 || strcasecmp('social service', $evcat['name'])==0 || strcasecmp('', $evcat['name'])==0 || strcasecmp('non-governmental organization (ngo)', $evcat['name'])==0)) {
                                $evclflag=1;
                            }
                        }
                        if($evclflag) {
                            if(isset($event['is_viewer_admin']) && $event['is_viewer_admin']) {
                                $_SESSION['fbscore'] += 20;
                                $gotevs=1;
                                echo '<br>event owner category list & admin '.$_SESSION['fbscore'];
                            }
                            else if(isset($event['rsvp_status']) && $event['rsvp_status']=='attending') {
                                $_SESSION['fbscore'] += 15;
                                $gotevs=1;
                                echo '<br>event owner category list & attending '.$_SESSION['fbscore'];
                            }
                            else if(isset($event['rsvp_status']) && $event['rsvp_status']=='unsure') {
                                $_SESSION['fbscore'] += 10;
                                $gotevs=1;
                                echo '<br>event owner category list attending '.$_SESSION['fbscore'];
                            }
                        }
                    }
                    if( ((isset($event['owner']['about']) && nlp($event['owner']['about'])) || (isset($event['owner']['mission']) && nlp($event['owner']['mission'])) || (isset($event['owner']['description']) && nlp($event['owner']['description'])) || (isset($event['owner']['general_info']) && nlp($event['owner']['general_info'])) || (isset($event['owner']['personal_info']) && nlp($event['owner']['personal_info'])) || (isset($event['owner']['company_overview']) && nlp($event['owner']['company_overview'])) || (isset($event['owner']['bio']) && nlp($event['owner']['bio']))) && !$gotevs) {
                        if(isset($event['is_viewer_admin']) && $event['is_viewer_admin']) {
                            $_SESSION['fbscore'] += 15;
                            $gotevs=1;
                            echo '<br>event owner details & admin '.$_SESSION['fbscore'];
                        }
                        else if(isset($event['rsvp_status']) && $event['rsvp_status']=='attending') {
                            $_SESSION['fbscore'] += 10;
                            $gotevs=1;
                            echo '<br>event owner details & attending '.$_SESSION['fbscore'];
                        }
                        else if(isset($event['rsvp_status']) && $event['rsvp_status']=='unsure') {
                            $_SESSION['fbscore'] += 5;
                            $gotevs=1;
                            echo '<br>event owner details attending '.$_SESSION['fbscore'];
                        }
                    }
                }
            }
            if($key == 'u_feed' && isset($respbody['feed'])) {
                foreach ($respbody['feed'] as $feed) {
                    $isparent = false;
                    $isparent = (!isset($feed['parent_id']))?1:0;
                    if(isset($feed['privacy']['description']) && strtolower($feed['privacy']['description'])!='only me') {
                        if ((isset($feed['story']) && stripos($feed['story'], 'this_ngo')) || (isset($feed['name']) && stripos($feed['name'], 'this_ngo')) || (isset($feed['from']) && stripos($feed['from'], 'this_ngo')) || (isset($feed['place']) && stripos($feed['place'], 'this_ngo'))) {
                            if ($isparent) {
                                $_SESSION['fbscore'] += 15;
                                echo '<br>this ngo post creator ' . $_SESSION['fbscore'];
                            }
                            else {
                                $_SESSION['fbscore'] += 10;
                                echo '<br>this ngo post ' . $_SESSION['fbscore'];
                            }
                        }
                        else if ( (isset($feed['description']) && stripos($feed['description'],'this_ngo')) || (isset($feed['message']) && stripos($feed['message'],'this_ngo')) ) {
                            if ($isparent) {
                                $_SESSION['fbscore'] += 15;
                                echo '<br>this ngo in desc post creator ' . $_SESSION['fbscore'];
                            }
                            else {
                                $_SESSION['fbscore'] += 10;
                                echo '<br>this ngo in desc post ' . $_SESSION['fbscore'];
                            }
                        }
                        else {
                            $gotscore = 0;
                            if(isset($feed['story_tags']) && !$gotscore) {
                                $stflag=0;
                                foreach ($feed['story_tags'] as $stag) {
                                    if (isset($stag['name']) && strcasecmp('this_ngo', $stag['name'])==0) {
                                        $stflag=1;
                                        break;
                                    }
                                }
                                if($stflag) {
                                    if ($isparent) {
                                        $_SESSION['fbscore'] += 15;
                                        echo '<br>this ngo in story post creator ' . $_SESSION['fbscore'];
                                    }
                                    else {
                                        $_SESSION['fbscore'] += 10;
                                        echo '<br>this ngo in story post ' . $_SESSION['fbscore'];
                                    }
                                    $gotscore=1;
                                }
                            }
                            if(isset($feed['message_tags']) && !$gotscore) {
                                $mtflag=0;
                                foreach ($feed['message_tags'] as $mtag) {
                                    if (isset($mtag['name']) && strcasecmp('this_ngo', $mtag['name'])==0) {
                                        $mtflag=1;
                                        break;
                                    }
                                }
                                if($mtflag) {
                                    if ($isparent) {
                                        $_SESSION['fbscore'] += 15;
                                        echo '<br>this ngo in message tags post creator ' . $_SESSION['fbscore'];
                                    }
                                    else {
                                        $_SESSION['fbscore'] += 10;
                                        echo '<br>this ngo in message tags post ' . $_SESSION['fbscore'];
                                    }
                                    $gotscore=1;
                                }
                            }
                            if(isset($feed['to']) && !$gotscore) {
                                $toflag=0;
                                foreach ($feed['to'] as $to) {
                                    if (isset($to['name']) && strcasecmp('this_ngo', $to['name'])==0) {
                                        $toflag=1;
                                        break;
                                    }
                                }
                                if($toflag) {
                                    if ($isparent) {
                                        $_SESSION['fbscore'] += 15;
                                        echo '<br>to this ngo post creator ' . $_SESSION['fbscore'];
                                    }
                                    else {
                                        $_SESSION['fbscore'] += 10;
                                        echo '<br>to this ngo post ' . $_SESSION['fbscore'];
                                    }
                                    $gotscore=1;
                                }
                            }/*
                            if ( ( (isset($feed['description']) && nlp($feed['description'])) || (isset($feed['message']) && nlp($feed['message'])) ) && !$gotscore ) {
                                if ($isparent) {
                                    $_SESSION['fbscore'] += 5;
                                    echo '<br>social work in desc post creator ' . $_SESSION['fbscore'];
                                }
                                else {
                                    $_SESSION['fbscore'] += 2;
                                    echo '<br>social work in desc post ' . $_SESSION['fbscore'];
                                }
                                $gotscore=1;
                            }*/
                            if (isset($feed['story_tags']) && !$gotscore) {
                                foreach ($feed['story_tags'] as $stag) {
                                    if($stag['id']!=$fbuid && isset($stag['type']) && $stag['type']!='user') {
                                        if($stag['type']=='page') {
                                            $pginfo = $fb->get('/'.$stag['id'].'?fields=id,name,category,about,mission,description,general_info,personal_info,category_list,company_overview,bio',$fbtoken);
                                            $pgnode = $pginfo->getGraphNode();
                                            if(isset($pgnode['category']) && (strtolower($pgnode['category'])=='non-governmental organization (ngo)' || strtolower($pgnode['category'])=='nonprofit organization' || strtolower($pgnode['category'])=='social service')) {
                                                if($isparent) {
                                                    $_SESSION['fbscore'] += 5;
                                                    echo '<br>story social work page category post creator ' . $stag['name'] . ' ' .$_SESSION['fbscore'];
                                                }
                                                else {
                                                    $_SESSION['fbscore'] += 2;
                                                    echo '<br>story social work page category post ' . $stag['name'] . ' ' . $_SESSION['fbscore'];
                                                }
                                                $gotscore=1;
                                                break;
                                            }
                                            else if(isset($pgnode['category_list']) && !$gotscore) {
                                                $pclflag=0;
                                                foreach ($pgnode['category_list'] as $pgcat) {
                                                    if (isset($pgcat['name']) && (strcasecmp('nonprofit organization', $pgcat['name'])==0 || strcasecmp('social service', $pgcat['name'])==0 || strcasecmp('', $pgcat['name'])==0 || strcasecmp('non-governmental organization (ngo)', $pgcat['name'])==0)) {
                                                        $pclflag=1;
                                                        break;
                                                    }
                                                }
                                                if($pclflag) {
                                                    if($isparent) {
                                                        $_SESSION['fbscore'] += 5;
                                                        echo '<br>story social work page category list post creator ' . $stag['name'] . ' '  . $_SESSION['fbscore'];
                                                    }
                                                    else {
                                                        $_SESSION['fbscore'] += 2;
                                                        echo '<br>story social work page category list post ' . $_SESSION['fbscore'];
                                                    }
                                                    $gotscore=1;
                                                    break;
                                                }
                                            }/*
                                            if( ((isset($pgnode['about']) && nlp($pgnode['about'])) || (isset($pgnode['mission']) && nlp($pgnode['mission'])) || (isset($pgnode['description']) && nlp($pgnode['description'])) || (isset($pgnode['general_info']) && nlp($pgnode['general_info'])) || (isset($pgnode['personal_info']) && nlp($pgnode['personal_info'])) || (isset($pgnode['company_overview']) && nlp($pgnode['company_overview'])) || (isset($pgnode['bio']) && nlp($pgnode['bio']))) && !$gotscore) {
                                                if($isparent) {
                                                    $_SESSION['fbscore'] += 5;
                                                    echo '<br>story social work page details post creator ' . $_SESSION['fbscore'];
                                                }
                                                else {
                                                    $_SESSION['fbscore'] += 2;
                                                    echo '<br>story social work page details post ' . $stag['name'] . ' '  . $_SESSION['fbscore'];
                                                }
                                                $gotscore=1;
                                                break;
                                            }*/
                                        }
                                        else if($stag['type']=='group') {
                                            $grinfo = $fb->get('/'.$stag['id'].'?fields=privacy,purpose,description,name,owner',$fbtoken);
                                            $grnode = $grinfo->getGraphNode();
                                            if(isset($grnode['name']) && (stripos($grnode['name'],'this ngo') && isset($grnode['owner']['name']) && strcasecmp($grnode['owner']['name'],'this_ngo')) ) {
                                                if($isparent) {
                                                    $_SESSION['fbscore'] += 5;
                                                    echo '<br>story this ngo group details post creator ' . $_SESSION['fbscore'];
                                                }
                                                else {
                                                    $_SESSION['fbscore'] += 2;
                                                    echo '<br>story this ngo group details post ' . $stag['name'] . ' '  . $_SESSION['fbscore'];
                                                }
                                                $gotscore=1;
                                                break;
                                            }
                                            else if(isset($grnode['description'])) {
                                                if(isset($grnode['purpose']) && nlp('The purpose of this group is to '.$grnode['purpose'].' '.$grnode['description'])) {
                                                    if($isparent) {
                                                        $_SESSION['fbscore'] += 5;
                                                        echo '<br>story social work group details post creator ' . $_SESSION['fbscore'];
                                                    }
                                                    else {
                                                        $_SESSION['fbscore'] += 2;
                                                        echo '<br>story social work group details post ' . $stag['name'] . ' '  . $_SESSION['fbscore'];
                                                    }
                                                    $gotscore=1;
                                                    break;
                                                }
                                            }
                                        }
                                        else if($stag['type']=='event') {
                                            $evinfo = $fb->get('/'.$stag['id'].'?fields=id,place,description,name,owner{id,name,category,category_list}',$fbtoken);
                                            $evnode = $evinfo->getGraphNode();
                                            if((isset($evnode['name']) && stripos($evnode['name'],'this ngo')) || (isset($evnode['description']) && stripos($evnode['description'],'this ngo')) || (isset($evnode['place']['name']) && stripos($evnode['place']['name'],'this ngo')) || (isset($evnode['owner']['name']) && strtolower($evnode['owner']['name'])=='this ngo')) {
                                                if($isparent) {
                                                    $_SESSION['fbscore'] += 5;
                                                    echo '<br>story this ngo event post creator ' . $_SESSION['fbscore'];
                                                }
                                                else {
                                                    $_SESSION['fbscore'] += 2;
                                                    echo '<br>story this ngo event post ' . $stag['name'] . ' '  . $_SESSION['fbscore'];
                                                }
                                                $gotscore=1;
                                                break;
                                            }
                                            else if(isset($evnode['description']) && nlp($evnode['description'])) {
                                                if($isparent) {
                                                    $_SESSION['fbscore'] += 5;
                                                    echo '<br>story this ngo event post creator ' . $_SESSION['fbscore'];
                                                }
                                                else {
                                                    $_SESSION['fbscore'] += 2;
                                                    echo '<br>story this ngo event post ' . $stag['name'] . ' '  . $_SESSION['fbscore'];
                                                }
                                                $gotscore=1;
                                                break;
                                            }
                                            else if(isset($evnode['owner']['category']) && (strtolower($evnode['owner']['category'])=='non-governmental organization (ngo)' || strtolower($evnode['owner']['category'])=='nonprofit organization' || strtolower($evnode['owner']['category'])=='social service')) {
                                                if($isparent) {
                                                    $_SESSION['fbscore'] += 5;
                                                    echo '<br>story social work event owner category post creator ' . $_SESSION['fbscore'];
                                                }
                                                else {
                                                    $_SESSION['fbscore'] += 2;
                                                    echo '<br>story social work event owner category post ' . $stag['name'] . ' '  . $_SESSION['fbscore'];
                                                }
                                                $gotscore=1;
                                                break;
                                            }
                                            else if(isset($evnode['owner']['category_list'])) {
                                                $evclflag=0;
                                                foreach ($evnode['owner']['category_list'] as $evcat) {
                                                    if (isset($evcat['name']) && strcasecmp('nonprofit organization', $evcat['name'])==0 || strcasecmp('social service', $evcat['name'])==0 || strcasecmp('', $evcat['name'])==0 || strcasecmp('non-governmental organization (ngo)', $evcat['name'])==0) {
                                                        $evclflag=1;
                                                        break;
                                                    }
                                                }
                                                if($evclflag) {
                                                    if($isparent) {
                                                        $_SESSION['fbscore'] += 5;
                                                        echo '<br>story social work event owner category list post creator ' . $_SESSION['fbscore'];
                                                    }
                                                    else {
                                                        $_SESSION['fbscore'] += 2;
                                                        echo '<br>story social work event owner category list post ' . $stag['name'] . ' '  . $_SESSION['fbscore'];
                                                    }
                                                    $gotscore=1;
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            if (isset($feed['message_tags']) && !$gotscore) {
                                foreach ($feed['message_tags'] as $mtag) {
                                    if($mtag['id']!=$fbuid && isset($mtag['type']) && $mtag['type']!='user') {
                                        if($mtag['type']=='page') {
                                            $pginfo = $fb->get('/'.$mtag['id'].'?fields=id,name,category,about,mission,description,general_info,personal_info,category_list,company_overview,bio',$fbtoken);
                                            $pgnode = $pginfo->getGraphNode();
                                            if(isset($pgnode['category']) && (strtolower($pgnode['category'])=='non-governmental organization (ngo)' || strtolower($pgnode['category'])=='nonprofit organization' || strtolower($pgnode['category'])=='social service')) {
                                                if($isparent) {
                                                    $_SESSION['fbscore'] += 5;
                                                    echo '<br>message tag social work page category post creator ' . $mtag['name'] . ' ' .$_SESSION['fbscore'];
                                                }
                                                else {
                                                    $_SESSION['fbscore'] += 2;
                                                    echo '<br>message tag social work page category post ' . $_SESSION['fbscore'];
                                                }
                                                $gotscore=1;
                                                break;
                                            }
                                            else if(isset($pgnode['category_list'])) {
                                                $pclflag=0;
                                                foreach ($pgnode['category_list'] as $pgcat) {
                                                    if (isset($pgcat['name']) && (strcasecmp('nonprofit organization', $pgcat['name'])==0 || strcasecmp('social service', $pgcat['name'])==0 || strcasecmp('', $pgcat['name'])==0 || strcasecmp('non-governmental organization (ngo)', $pgcat['name'])==0)) {
                                                        $pclflag=1;
                                                        break;
                                                    }
                                                }
                                                if($pclflag) {
                                                    if($isparent) {
                                                        $_SESSION['fbscore'] += 5;
                                                        echo '<br>message tag social work page category list post creator ' . $mtag['name'] . ' '  . $_SESSION['fbscore'];
                                                    }
                                                    else {
                                                        $_SESSION['fbscore'] += 2;
                                                        echo '<br>message tag social work page category list post ' . $_SESSION['fbscore'];
                                                    }
                                                    $gotscore=1;
                                                    break;
                                                }
                                            }
                                            if( ((isset($pgnode['about']) && nlp($pgnode['about'])) || (isset($pgnode['mission']) && nlp($pgnode['mission'])) || (isset($pgnode['description']) && nlp($pgnode['description'])) || (isset($pgnode['general_info']) && nlp($pgnode['general_info'])) || (isset($pgnode['personal_info']) && nlp($pgnode['personal_info'])) || (isset($pgnode['company_overview']) && nlp($pgnode['company_overview'])) || (isset($pgnode['bio']) && nlp($pgnode['bio']))) && !$gotscore) {
                                                if($isparent) {
                                                    $_SESSION['fbscore'] += 5;
                                                    echo '<br>message tag social work page details post creator ' . $_SESSION['fbscore'];
                                                }
                                                else {
                                                    $_SESSION['fbscore'] += 2;
                                                    echo '<br>message tag social work page details post ' . $mtag['name'] . ' '  . $_SESSION['fbscore'];
                                                }
                                                $gotscore=1;
                                                break;
                                            }
                                        }
                                        else if($mtag['type']=='group') {
                                            $grinfo = $fb->get('/'.$mtag['id'].'?fields=privacy,purpose,description,name,owner',$fbtoken);
                                            $grnode = $grinfo->getGraphNode();
                                            if(isset($grnode['name']) && stripos($grnode['name'],'this ngo') && (isset($grnode['owner']['name']) && strcasecmp($grnode['owner']['name'],'this_ngo')) ) {
                                                if($isparent) {
                                                    $_SESSION['fbscore'] += 5;
                                                    echo '<br>message tag this ngo group details post creator ' . $_SESSION['fbscore'];
                                                }
                                                else {
                                                    $_SESSION['fbscore'] += 2;
                                                    echo '<br>message tag this ngo group details post ' . $mtag['name'] . ' '  . $_SESSION['fbscore'];
                                                }
                                                $gotscore=1;
                                                break;
                                            }
                                            else if(isset($grnode['description'])) {
                                                if(isset($grnode['purpose']) && nlp('The purpose of this group is to '.$grnode['purpose'].' '.$grnode['description'])) {
                                                    if($isparent) {
                                                        $_SESSION['fbscore'] += 5;
                                                        echo '<br>message tag social work group details post creator ' . $_SESSION['fbscore'];
                                                    }
                                                    else {
                                                        $_SESSION['fbscore'] += 2;
                                                        echo '<br>message tag social work group details post ' . $mtag['name'] . ' '  . $_SESSION['fbscore'];
                                                    }
                                                    $gotscore=1;
                                                    break;
                                                }
                                            }
                                        }
                                        else if($mtag['type']=='event') {
                                            $evinfo = $fb->get('/'.$mtag['id'].'?fields=id,place,description,name,owner{id,name,category,category_list}',$fbtoken);
                                            $evnode = $evinfo->getGraphNode();
                                            if((isset($evnode['name']) && stripos($evnode['name'],'this ngo')) || (isset($evnode['description']) && stripos($evnode['description'],'this ngo')) || (isset($evnode['place']['name']) && stripos($evnode['place']['name'],'this ngo')) || (isset($evnode['owner']['name']) && strtolower($evnode['owner']['name'])=='this ngo')) {
                                                if($isparent) {
                                                    $_SESSION['fbscore'] += 5;
                                                    echo '<br>message tag this ngo event post creator ' . $_SESSION['fbscore'];
                                                }
                                                else {
                                                    $_SESSION['fbscore'] += 2;
                                                    echo '<br>message tag this ngo event post ' . $mtag['name'] . ' '  . $_SESSION['fbscore'];
                                                }
                                                $gotscore=1;
                                                break;
                                            }
                                            else if(isset($evnode['description']) && nlp($evnode['description'])) {
                                                if($isparent) {
                                                    $_SESSION['fbscore'] += 5;
                                                    echo '<br>message tag this ngo event post creator ' . $_SESSION['fbscore'];
                                                }
                                                else {
                                                    $_SESSION['fbscore'] += 2;
                                                    echo '<br>message tag this ngo event post ' . $mtag['name'] . ' '  . $_SESSION['fbscore'];
                                                }
                                                $gotscore=1;
                                                break;
                                            }
                                            else if(isset($evnode['owner']['category']) && (strtolower($evnode['owner']['category'])=='non-governmental organization (ngo)' || strtolower($evnode['owner']['category'])=='nonprofit organization' || strtolower($evnode['owner']['category'])=='social service')) {
                                                if($isparent) {
                                                    $_SESSION['fbscore'] += 5;
                                                    echo '<br>message tag social work event owner category post creator ' . $_SESSION['fbscore'];
                                                }
                                                else {
                                                    $_SESSION['fbscore'] += 2;
                                                    echo '<br>message tag social work event owner category post ' . $mtag['name'] . ' '  . $_SESSION['fbscore'];
                                                }
                                                $gotscore=1;
                                                break;
                                            }
                                            else if(isset($evnode['owner']['category_list'])) {
                                                $evclflag=0;
                                                foreach ($evnode['owner']['category_list'] as $evcat) {
                                                    if (isset($evcat['name']) && (strcasecmp('nonprofit organization', $evcat['name'])==0 || strcasecmp('social service', $evcat['name'])==0 || strcasecmp('', $evcat['name'])==0 || strcasecmp('non-governmental organization (ngo)', $evcat['name'])==0)) {
                                                        $evclflag=1;
                                                        break;
                                                    }
                                                }
                                                if($evclflag) {
                                                    if($isparent) {
                                                        $_SESSION['fbscore'] += 5;
                                                        echo '<br>message tag social work event owner category list post creator ' . $_SESSION['fbscore'];
                                                    }
                                                    else {
                                                        $_SESSION['fbscore'] += 2;
                                                        echo '<br>message tag social work event owner category list post ' . $mtag['name'] . ' '  . $_SESSION['fbscore'];
                                                    }
                                                    $gotscore=1;
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            if (isset($feed['from']) && !$gotscore) {
                                if($feed['from']['id']!=$fbuid) {
                                    $frominfo = $fb->get('/'.$feed['from']['id'].'?metadata=1&fields=id,name,metadata{type}',$fbtoken);
                                    $fromnode = $frominfo->getGraphNode();
                                    if(!isset($fromnode['error']) && isset($fromnode['metadata']['type']) && $fromnode['metadata']['type']!='user') {
                                        if($fromnode['metadata']['type']=='page') {
                                            $pginfo = $fb->get('/'.$feed['from']['id'].'?fields=id,name,category,about,mission,description,general_info,personal_info,category_list,company_overview,bio',$fbtoken);
                                            $pgnode = $pginfo->getGraphNode();
                                            if(isset($pgnode['category']) && (strtolower($pgnode['category'])=='non-governmental organization (ngo)' || strtolower($pgnode['category'])=='nonprofit organization' || strtolower($pgnode['category'])=='social service')) {
                                                if($isparent) {
                                                    $_SESSION['fbscore'] += 5;
                                                    echo '<br>from social work page category post creator ' . $feed['from']['name'] . ' ' .$_SESSION['fbscore'];
                                                }
                                                else {
                                                    $_SESSION['fbscore'] += 2;
                                                    echo '<br>from social work page category post ' . $_SESSION['fbscore'];
                                                }
                                                $gotscore=1;
                                            }
                                            else if(isset($pgnode['category_list'])) {
                                                $pclflag=0;
                                                foreach ($pgnode['category_list'] as $pgcat) {
                                                    if (isset($pgcat['name']) && (strcasecmp('nonprofit organization', $pgcat['name'])==0 || strcasecmp('social service', $pgcat['name'])==0 || strcasecmp('', $pgcat['name'])==0 || strcasecmp('non-governmental organization (ngo)', $pgcat['name'])==0)) {
                                                        $pclflag=1;
                                                        break;
                                                    }
                                                }
                                                if($pclflag) {
                                                    if($isparent) {
                                                        $_SESSION['fbscore'] += 5;
                                                        echo '<br>from social work page category list post creator ' . $feed['from']['name'] . ' '  . $_SESSION['fbscore'];
                                                    }
                                                    else {
                                                        $_SESSION['fbscore'] += 2;
                                                        echo '<br>from social work page category list post ' . $_SESSION['fbscore'];
                                                    }
                                                    $gotscore=1;
                                                }
                                            }
                                        }
                                        else if($fromnode['metadata']['type']=='group') {
                                            $grinfo = $fb->get('/'.$feed['from']['id'].'?fields=privacy,purpose,description,name,owner',$fbtoken);
                                            $grnode = $grinfo->getGraphNode();
                                            if(isset($grnode['name']) && stripos($grnode['name'],'this ngo') && (isset($grnode['owner']['name']) && strcasecmp($grnode['owner']['name'],'this_ngo')) ) {
                                                if($isparent) {
                                                    $_SESSION['fbscore'] += 5;
                                                    echo '<br>from this ngo group details post creator ' . $_SESSION['fbscore'];
                                                }
                                                else {
                                                    $_SESSION['fbscore'] += 2;
                                                    echo '<br>from this ngo group details post ' . $feed['from']['name'] . ' '  . $_SESSION['fbscore'];
                                                }
                                                $gotscore=1;
                                            }
                                            else if(isset($grnode['description'])) {
                                                if(isset($grnode['purpose']) && nlp('The purpose of this group is to '.$grnode['purpose'].' '.$grnode['description'])) {
                                                    if($isparent) {
                                                        $_SESSION['fbscore'] += 5;
                                                        echo '<br>from social work group details post creator ' . $_SESSION['fbscore'];
                                                    }
                                                    else {
                                                        $_SESSION['fbscore'] += 2;
                                                        echo '<br>from social work group details post ' . $feed['from']['name'] . ' '  . $_SESSION['fbscore'];
                                                    }
                                                    $gotscore=1;
                                                }
                                            }
                                        }
                                        else if($fromnode['metadata']['type']=='event') {
                                            $evinfo = $fb->get('/'.$feed['from']['id'].'?fields=id,place,description,name,owner{id,name,category,category_list}',$fbtoken);
                                            $evnode = $evinfo->getGraphNode();
                                            if((isset($evnode['name']) && stripos($evnode['name'],'this ngo')) || (isset($evnode['description']) && stripos($evnode['description'],'this ngo')) || (isset($evnode['place']['name']) && stripos($evnode['place']['name'],'this ngo')) || (isset($evnode['owner']['name']) && strtolower($evnode['owner']['name'])=='this ngo')) {
                                                if($isparent) {
                                                    $_SESSION['fbscore'] += 5;
                                                    echo '<br>from this ngo event post creator ' . $_SESSION['fbscore'];
                                                }
                                                else {
                                                    $_SESSION['fbscore'] += 2;
                                                    echo '<br>from this ngo event post ' . $feed['from']['name'] . ' '  . $_SESSION['fbscore'];
                                                }
                                                $gotscore=1;
                                            }
                                            else if(isset($evnode['description']) && nlp($evnode['description'])) {
                                                if($isparent) {
                                                    $_SESSION['fbscore'] += 5;
                                                    echo '<br>from this ngo event post creator ' . $_SESSION['fbscore'];
                                                }
                                                else {
                                                    $_SESSION['fbscore'] += 2;
                                                    echo '<br>from this ngo event post ' . $feed['from']['name'] . ' '  . $_SESSION['fbscore'];
                                                }
                                                $gotscore=1;
                                            }
                                            if(isset($evnode['owner']['category']) && (strtolower($evnode['owner']['category'])=='non-governmental organization (ngo)' || strtolower($evnode['owner']['category'])=='nonprofit organization' || strtolower($evnode['owner']['category'])=='social service') && !$gotscore) {
                                                if($isparent) {
                                                    $_SESSION['fbscore'] += 5;
                                                    echo '<br>from social work event owner category post creator ' . $_SESSION['fbscore'];
                                                }
                                                else {
                                                    $_SESSION['fbscore'] += 2;
                                                    echo '<br>from social work event owner category post ' . $feed['from']['name'] . ' '  . $_SESSION['fbscore'];
                                                }
                                                $gotscore=1;
                                            }
                                            else if(isset($evnode['owner']['category_list']) && !$gotscore) {
                                                $evclflag=0;
                                                foreach ($evnode['owner']['category_list'] as $evcat) {
                                                    if (isset($evcat['name']) && (strcasecmp('nonprofit organization', $evcat['name'])==0 || strcasecmp('social service', $evcat['name'])==0 || strcasecmp('', $evcat['name'])==0 || strcasecmp('non-governmental organization (ngo)', $evcat['name'])==0)) {
                                                        $evclflag=1;
                                                    }
                                                }
                                                if($evclflag) {
                                                    if($isparent) {
                                                        $_SESSION['fbscore'] += 5;
                                                        echo '<br>from social work event owner category list post creator ' . $_SESSION['fbscore'];
                                                    }
                                                    else {
                                                        $_SESSION['fbscore'] += 2;
                                                        echo '<br>from social work event owner category list post ' . $feed['from']['name'] . ' '  . $_SESSION['fbscore'];
                                                    }
                                                    $gotscore=1;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            if (isset($feed['to']) && !$gotscore) {
                                foreach ($feed['to'] as $feedto) {
                                    if($feedto['id']!=$fbuid) {
                                        $toinfo = $fb->get('/'.$feedto['id'].'?metadata=1&fields=id,name,metadata{type}',$fbtoken);
                                        $tonode = $toinfo->getGraphNode();
                                        if(!isset($tonode['error']) && isset($tonode['metadata']['type']) && $tonode['metadata']['type']!='user') {
                                            if($tonode['metadata']['type']=='page') {
                                                $pginfo = $fb->get('/'.$feedto['id'].'?fields=id,name,category,about,mission,description,general_info,personal_info,category_list,company_overview,bio',$fbtoken);
                                                $pgnode = $pginfo->getGraphNode();
                                                if(isset($pgnode['category']) && (strtolower($pgnode['category'])=='non-governmental organization (ngo)' || strtolower($pgnode['category'])=='nonprofit organization' || strtolower($pgnode['category'])=='social service')) {
                                                    if($isparent) {
                                                        $_SESSION['fbscore'] += 5;
                                                        echo '<br>to social work page category post creator ' . $feedto['name'] . ' ' .$_SESSION['fbscore'];
                                                    }
                                                    else {
                                                        $_SESSION['fbscore'] += 2;
                                                        echo '<br>to social work page category post ' . $_SESSION['fbscore'];
                                                    }
                                                    $gotscore=1;
                                                    break;
                                                }
                                                else if(isset($pgnode['category_list'])) {
                                                    $pclflag=0;
                                                    foreach ($pgnode['category_list'] as $pgcat) {
                                                        if (isset($pgcat['name']) && (strcasecmp('nonprofit organization', $pgcat['name'])==0 || strcasecmp('social service', $pgcat['name'])==0 || strcasecmp('', $pgcat['name'])==0 || strcasecmp('non-governmental organization (ngo)', $pgcat['name'])==0)) {
                                                            $pclflag=1;
                                                            break;
                                                        }
                                                    }
                                                    if($pclflag) {
                                                        if($isparent) {
                                                            $_SESSION['fbscore'] += 5;
                                                            echo '<br>to social work page category list post creator ' . $feedto['name'] . ' '  . $_SESSION['fbscore'];
                                                        }
                                                        else {
                                                            $_SESSION['fbscore'] += 2;
                                                            echo '<br>to social work page category list post ' . $_SESSION['fbscore'];
                                                        }
                                                        $gotscore=1;
                                                        break;
                                                    }
                                                }
                                            }
                                            else if($tonode['metadata']['type']=='group') {
                                                $grinfo = $fb->get('/'.$feedto['id'].'?fields=privacy,purpose,description,name,owner',$fbtoken);
                                                $grnode = $grinfo->getGraphNode();
                                                if(isset($grnode['name']) && stripos($grnode['name'],'this ngo') && (isset($grnode['owner']['name']) && strcasecmp($grnode['owner']['name'],'this_ngo')) ) {
                                                    if($isparent) {
                                                        $_SESSION['fbscore'] += 5;
                                                        echo '<br>to this ngo group details post creator ' . $_SESSION['fbscore'];
                                                    }
                                                    else {
                                                        $_SESSION['fbscore'] += 2;
                                                        echo '<br>to this ngo group details post ' . $feedto['name'] . ' '  . $_SESSION['fbscore'];
                                                    }
                                                    $gotscore=1;
                                                    break;
                                                }
                                                else if(isset($grnode['description'])) {
                                                    if(isset($grnode['purpose']) && nlp('The purpose of this group is to '.$grnode['purpose'].' '.$grnode['description'])) {
                                                        if($isparent) {
                                                            $_SESSION['fbscore'] += 5;
                                                            echo '<br>to social work group details post creator ' . $_SESSION['fbscore'];
                                                        }
                                                        else {
                                                            $_SESSION['fbscore'] += 2;
                                                            echo '<br>to social work group details post ' . $feedto['name'] . ' '  . $_SESSION['fbscore'];
                                                        }
                                                        $gotscore=1;
                                                        break;
                                                    }
                                                }
                                            }
                                            else if($tonode['metadata']['type']=='event') {
                                                $evinfo = $fb->get('/'.$feedto['id'].'?fields=id,place,description,name,owner{id,name,category,category_list}',$fbtoken);
                                                $evnode = $evinfo->getGraphNode();
                                                if((isset($evnode['name']) && stripos($evnode['name'],'this ngo')) || (isset($evnode['description']) && stripos($evnode['description'],'this ngo')) || (isset($evnode['place']['name']) && stripos($evnode['place']['name'],'this ngo')) || (isset($evnode['owner']['name']) && strtolower($evnode['owner']['name'])=='this ngo')) {
                                                    if($isparent) {
                                                        $_SESSION['fbscore'] += 5;
                                                        echo '<br>to this ngo event post creator ' . $_SESSION['fbscore'];
                                                    }
                                                    else {
                                                        $_SESSION['fbscore'] += 2;
                                                        echo '<br>to this ngo event post ' . $feedto['name'] . ' '  . $_SESSION['fbscore'];
                                                    }
                                                    $gotscore=1;
                                                    break;
                                                }
                                                else if(isset($evnode['description']) && nlp($evnode['description'])) {
                                                    if($isparent) {
                                                        $_SESSION['fbscore'] += 5;
                                                        echo '<br>to this ngo event post creator ' . $_SESSION['fbscore'];
                                                    }
                                                    else {
                                                        $_SESSION['fbscore'] += 2;
                                                        echo '<br>to this ngo event post ' . $feedto['name'] . ' '  . $_SESSION['fbscore'];
                                                    }
                                                    $gotscore=1;
                                                    break;
                                                }
                                                else if(isset($evnode['owner']['category']) && (strtolower($evnode['owner']['category'])=='non-governmental organization (ngo)' || strtolower($evnode['owner']['category'])=='nonprofit organization' || strtolower($evnode['owner']['category'])=='social service')) {
                                                    if($isparent) {
                                                        $_SESSION['fbscore'] += 5;
                                                        echo '<br>to social work event owner category post creator ' . $_SESSION['fbscore'];
                                                    }
                                                    else {
                                                        $_SESSION['fbscore'] += 2;
                                                        echo '<br>to social work event owner category post ' . $feedto['name'] . ' '  . $_SESSION['fbscore'];
                                                    }
                                                    $gotscore=1;
                                                    break;
                                                }
                                                else if(isset($evnode['owner']['category_list'])) {
                                                    $evclflag=0;
                                                    foreach ($evnode['owner']['category_list'] as $evcat) {
                                                        if (isset($evcat['name']) && (strcasecmp('nonprofit organization', $evcat['name'])==0 || strcasecmp('social service', $evcat['name'])==0 || strcasecmp('', $evcat['name'])==0 || strcasecmp('non-governmental organization (ngo)', $evcat['name'])==0)) {
                                                            $evclflag=1;
                                                        }
                                                    }
                                                    if($evclflag) {
                                                        if($isparent) {
                                                            $_SESSION['fbscore'] += 5;
                                                            echo '<br>to social work event owner category list post creator ' . $_SESSION['fbscore'];
                                                        }
                                                        else {
                                                            $_SESSION['fbscore'] += 2;
                                                            echo '<br>to social work event owner category list post ' . $feedto['name'] . ' '  . $_SESSION['fbscore'];
                                                        }
                                                        $gotscore=1;
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            
                        }

                    }
                }
            }
        }
    }
    echo '<br>'.$_SESSION['fbscore'];
}
catch(Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
}
catch(Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
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