
<?php
include('config.php');

?>
<div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=<?php echo $appid; ?>&amp;xfbml=1"></script><fb:login-button show-faces="true" width="200" max-rows="1" perms="email, publish_stream, publish_actions"></fb:login-button>
<?php

function parse_signed_request($signed_request, $secret) {
  list($encoded_sig, $payload) = explode('.', $signed_request, 2);

  // decode the data
  $sig = base64_url_decode($encoded_sig);
  $data = json_decode(base64_url_decode($payload), true);

  if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
    error_log('Unknown algorithm. Expected HMAC-SHA256');
    return null;
  }

  // check sig
  $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
  if ($sig !== $expected_sig) {
    error_log('Bad Signed JSON signature!');
    return null;
  }

  return $data;
}

function base64_url_decode($input) {
  return base64_decode(strtr($input, '-_', '+/'));
}

$fb_sr = parse_signed_request($_REQUEST['signed_request'], $appsecret);

if(!$fb_sr['page']['liked'])
{
  echo('<img src="yuno.jpg" alt="y u no fan?"/>');
}
else
{
  echo 'you are a fan';
echo("<pre>\n");
print_r(parse_signed_request($_REQUEST['signed_request'],$appsecret));
echo("</pre>\n");
echo(file_get_contents("https://graph.facebook.com/me/permissions?access_token=". $fb_sr['oauth_token']));
}

//echo("<pre>\n");
//print_r($_REQUEST['signed_request']);
//echo("</pre>\n");

//echo("<pre>\n");
//print_r(parse_signed_request($_REQUEST['signed_request'],$appsecret));
//echo("</pre>\n");
?>
