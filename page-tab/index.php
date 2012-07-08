<?php
include(__DIR__ . '/../includes/__init__.php');

echo output_header();

$signed_request = parse_signed_request($_REQUEST['signed_request'], $config['AppSecret']);
$page_name = json_decode(curl_call('https://graph.facebook.com/'.$signed_request['page']['id']), true);

?>

<body class="page-tab">
<h1 class="center">Colm's Sandbox App</h1>
<div id="signed_request_container">
<h2>Information from the signed_request</h2>
</div>
<?php
echo '<p>The signed_request was encrypted using <span class="bold">' . $signed_request['algorithm'].'</span> ';
echo 'and was issued at <span class="bold">'. $signed_request['issued_at'].'</span>';
echo ' which is better known as '.date('l jS \of F Y h:i:s A', $signed_request['issued_at']).' UTC</p>';
if ($signed_request['oauth_token']) {
  echo '<p> We have an oAuth token for you, which is <br/>';
  echo '<span class="bold">' . $signed_request['oauth_token'] . '</span><p>';
} else {
  echo '<p>Much to our dismay, The signed_request doesn\'t contain an oAuth token</p>';
}
$like_admin = '<p>It looks like you <span class="bold">';
if ($signed_request['page']['liked']) {
  $like_admin .= 'do like ';
} else {
  $like_admin .= 'don\'t like ';
}

$like_admin .= '</span> this page and you <span class="bold">';

if ($signed_request['page']['admin']) {
  $like_admin .= 'are ';
} else {
  $like_admin .= 'aren\'t ';
}

$like_admin .= '</span> an admin</p>';

echo $like_admin;
echo '<p>The page you\'re currently viewing has an ID of <span class="bold">'. $signed_request['page']['id'].'</span>';
echo ' which according to the Graph API is called <span class="bold">'.$page_name['name'] .'</span></p>';



echo('<pre>');
print_r($signed_request);
echo('</pre>');

?>

</body>
