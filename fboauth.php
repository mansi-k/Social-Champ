<?php

include_once 'fbinit.php';

$helper = $fb->getRedirectLoginHelper();

$permissions = ['user_birthday','user_religion_politics','user_relationships','user_relationship_details','user_hometown',
    'user_location','user_likes','user_education_history','user_work_history','user_website','user_events','user_photos',
    'user_videos','user_friends','user_about_me','user_status','user_games_activity','user_tagged_places','user_posts',
    'rsvp_event','email','read_insights','publish_actions','read_audience_network_insights','read_custom_friendlists',
    'user_actions.books','user_actions.music','user_actions.video','user_actions.news','user_actions.fitness',
    'user_managed_groups','manage_pages','pages_manage_cta','pages_manage_instant_articles','pages_show_list','publish_pages',
    'read_page_mailboxes','ads_management','ads_read','business_management','pages_messaging',
    'public_profile']; // Optional permissions
$loginUrl = $helper->getLoginUrl('http://localhost:8081/sci3/fbtoken.php', $permissions);

echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';

?>