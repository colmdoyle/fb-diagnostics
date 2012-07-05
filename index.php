<?php

include('master_include.php');

$signed_request = $_REQUEST['signed_request'];
$parsed_request = parse_signed_request($signed_request, $config['AppSecret']);


$signed_request_array = '<ul>';
foreach($parsed_request as $key => $value)
{
if($key == 'user') {
$signed_request_array .= '<li> user </li>';
$signed_request_array .= '<ul>';
    foreach($value as $sub_key => $sub_array) {

  $signed_request_array .= '<li>';
      $signed_request_array .= $sub_key . ' = ' . $sub_array;
  $signed_request_array .= '</li>';
   }
$signed_request_array .= '</ul>';
  } else {
  $signed_request_array .= '<li>';
  $signed_request_array .= $key . ' = <span id="'.$key.'" >' . $value . "</span>";
  }
  $signed_request_array .= '</li>';
}
$signed_request_array .= '</ul>';
$head = '<head>';
$head .= output_standard_head('Colm\'s Sandbox', 'website', 'http://colmd.fbdublin.com/colms-sandbox/index.php', 'https://fbcdn-photos-a.akamaihd.net/photos-ak-snc7/v85005/230/120999667956026/app_10_120999667956026_1011543027.gif', 'Colm\'s Sandbox', $config['AppId']);
$head .= '<script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>';
$head .= '<script src="scripts/main.js"></script>';
$head .= '<script src=\'https://connect.facebook.net/en_US/all.js\'></script>';
$head .= '<link rel="stylesheet" type="text/css" href="css/screen.css" />';
$head .= '<link rel="stylesheet" type="text/css" href="css/custom.css" />';
$head .= '</head>';

$fb_init .= '<div id=\'fb-root\'></div>';

$welcome_header .= '<h1> Welcome to Colm\'s Sandbox </h1>';
$welcome_header .= '<h2> Home of mediocre code since 2010 </h2>';

$signed_request_div .= '<div id="signed_request">';
$signed_request_div .= '<h3> Signed Request </h3>';
$signed_request_div .= $signed_request_array;
$signed_request_div .= '</div>';

$publish_action_div .= '<div id="publish_action">';
$publish_action_div .= '<a href="#" id="publish_action_link">';
$publish_action_div .= 'Publish Test Action';
$publish_action_div .= '</a>';
$publish_action_div .= '<div id="action_response"></div>';
$publish_action_div .= '</div>';

$feed_dialog .= '<p><input type="button" onclick=\'postToFeed(); return false;\' value="Post to Feed" /></p>';
$oauth_dialog .= '<p><input type="button" onclick=\'authApp(); return false;\' value="Auth App" /></p>';
$add_page_tab_dialog .= '<p><input type="button" onclick=\'addToPage(); return false;\' value="Add To Page"></p>';
$friends_dialog .= '';
$pay_dialog .= '';
$requests_dialog .= '<p><input type="button" onclick="sendRequestToRecipients(); return false;" value="Send Request to Users Directly" /><input type="text" value="User ID" name="user_ids" /></p><p><input type="button"  onclick="sendRequestViaMultiFriendSelector(); return false;" value="Send Request to Many Users with MFS"/></p>';
$send_dialog .= '<p><a onclick=\'sendDialog(); return false;\'>Send Dialog</a></p>';

$dialogs_div .= '<h3> Dialogs </h3> ';
$dialogs_div .= $feed_dialog;
$dialogs_div .= $oauth_dialog;
$dialogs_div .= $add_page_tab_dialog;
$dialogs_div .= $friends_dialog;
$dialogs_div .= $pay_dialog;
$dialogs_div .= $requests_dialog;
$dialogs_div .= $send_dialog;

$login_button = '<fb:login-button show-faces="true" width="200" max-rows="1"></fb:login-button>';

$social_plugins_div .= '<h3> Social Plugins </h3>';
$social_plugins_div .= $login_button;

$body .= $welcome_header;
$body .= $signed_request_div;
$body .= $publish_action_div;
$body .= $dialogs_div;
$body .= $social_plugins_div;

echo $head;
echo $body;
