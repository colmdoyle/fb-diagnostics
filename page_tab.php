<?php

include('master_include.php');

echo output_header();

$signed_request = parse_signed_request($_REQUEST['signed_request'], $config['AppSecret']);
$page_name = json_decode(curl_call('https://graph.facebook.com/'.$signed_request['page']['id']), true);
echo '<p>Here\'s what we can tell about you, based on the signed_request from Facebook</p>';

echo '<p>The signed_request was encrypted using <strong>' . $signed_request['algorithm'].'</strong> ';
echo 'and was issued at <strong>'. $signed_request['issued_at'].'</strong>';
echo ' which is better known as '.date('l jS \of F Y h:i:s A', $signed_request['issued_at']).' UTC</p>';
if ($signed_request['oauth_token']) {
  echo '<p> We have an oAuth token for you, which is <br/>';
  echo '<strong>' . $signed_request['oauth_token'] . '</strong><p>';
} else {
  echo '<p>Much to our dismay, The signed_request doesn\'t contain an oAuth token</p>';
}
$like_admin = '<p>It looks like you <strong>';
if ($signed_request['page']['liked']) {
  $like_admin .= 'do like ';
} else {
  $like_admin .= 'don\'t like ';
}

$like_admin .= '</strong> this page and you <strong>';

if ($signed_request['page']['admin']) {
  $like_admin .= 'are ';
} else {
  $like_admin .= 'aren\'t ';
}

$like_admin .= '</strong> an admin</p>';

echo $like_admin;
echo '<p>The page you\'re currently viewing has an ID of <strong>'. $signed_request['page']['id'].'</strong>';
echo ' which according to the Graph API is called <strong>'.$page_name['name'] .'</strong></p>';



echo('<pre>');
print_r($signed_request);
echo('</pre>');
