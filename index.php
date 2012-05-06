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
$head .= '<link rel="stylesheet" type="text/css" href="css/screen.css" />';
$head .= '</head>';

$signed_request_div .= '<div id="signed_request">';
$signed_request_div .= $signed_request_array;
$signed_request_div .= '</div>';

$publish_action_div .= '<div id="publish_action">';
$publish_action_div .= '<a href="#" id="publish_action_link">';
$publish_action_div .= 'Publish Test Action';
$publish_action_div .= '</a>';
$publish_action_div .= '<div id="action_response"></div>';
$publish_action_div .= '</div>';



$body .= $signed_request_div;
$body .= $publish_action_div;

echo $head;
echo $body;
