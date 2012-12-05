<?php

// A function to process Facebook Signed Requests
// https://developers.facebook.com/docs/authentication/signed_request/

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

function output_standard_head(
                     $og_title,
                     $og_type,
                     $og_url,
                     $og_image,
                     $og_site_name,
                     $fb_app_id) {
  $output .= '<meta property="og:title"  content="' . $og_title . '" />';
  $output .= '<meta property="og:type"  content="' . $og_type . '" />';
  $output .= '<meta property="og:url"  content="' . $og_url . '" />';
  $output .= '<meta property="og:image"  content="' . $og_image . '" />';
  $output .= '<meta property="og:site_name"  content="' . $og_site_name . '" />';
  $output .= '<meta property="fb:app_id"  content="' . $fb_app_id . '" />';

  return $output;
}
function output_header () {
  $head = '<head>';
	// TODO - This is clowny. Make it better.
  $head .= output_standard_head('Colm\'s Sandbox', 'website', 'http://colmd.fbdublin.com/colms-sandbox/page-tab/index.php', 'https://fbcdn-photos-a.akamaihd.net/photos-ak-snc7/v85005/230/120999667956026/app_10_120999667956026_1011543027.gif', 'Colm\'s Sandbox', '120999667956026');
  $head .= '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>';
  $head .= '<script src=\'https://connect.facebook.net/en_US/all.js\'></script>';
  $head .= '<link href="https://colmd.fbdublin.com/colms-sandbox/css/bootstrap.min.css" rel="stylesheet" media="screen">';
  $head .= '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>';
  $head .= '<script src="https://colmd.fbdublin.com/colms-sandbox/js/bootstrap.min.js"></script>';
  $head .= '<script src="https://colmd.fbdublin.com/colms-sandbox/js/main.js"></script>';
  $head .= '<link rel="stylesheet" type="text/css" href="https://colmd.fbdublin.com/colms-sandbox/css/screen.css" />';

  $head .= '</head>';

  return $head;
}

function curl_call($url, $post = false) {

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_ENCODING , "gzip");
  curl_setopt($ch, CURLOPT_POST, $post);

  $payload = curl_exec($ch);

  curl_close($ch);

  return $payload;
}
