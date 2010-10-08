<?php

include('../php-sdk/src/facebook.php');
include('config.php');

$facebook = new Facebook(array(
                'appId'  => $appid,
                'secret' => $appsecret,
                'cookie' => true,));
                

$session = $facebook->getSession();
$fbme = null;  
if ($session) {  
    try {  
        $fbme = $facebook->api('/me');  
    } catch (FacebookApiException $e) {
		error_log($e);
    }  
}        



if (!$fbme) {
 $loginUrl = $facebook->getLoginUrl(array('canvas' => 1,
                                          'fbconnect' => 0,
                                          'req_perms' => 'publish_stream,user_checkins,friends_checkins,email,user_status,user_likes,read_stream',
                                          'next' => $canvas_base_url . 'index.php',
                                          'cancel_url' => $canvas_base_url
                                         ));
     echo "<script type='text/javascript'>top.location.href = '$loginUrl';</script>";
} else {

  // $fbme is valid i.e. user can access our app
$user_id = $fbme[id];


// Get user's checkins
$checkins 	= $facebook->api('/me/checkins');

// Get user's likes
$likes		= $facebook->api('/me/likes'); 


//Echo User's Name
echo('<p>Oh hi '.$fbme[name].'</p>');


//Echo User's UID
echo('<p>Your user ID is '.$user_id.'</p>');


//Echo User's last 'like'
echo('<p>The last thing you liked was '.$likes['data']['0']['name'].'</p>');


// Verify if a user has checked in & if they have, display details.

if(empty($checkins['data'])) //Is checkin data array empty?
{

echo('<p>You\'ve never used Facebook Places');

}
else
{
echo('<p>The last place you checked in at was '.$checkins['data']['0']['place']['name'].' which was at '.$checkins['data']['0']['created_time'].'</p>');
}

$message = '';


$wallpost = array(
'access_token' => $facebook->getAccessToken(),
'message' => $message, // this should be an empty string
);

try
{
$resp = $facebook->api('/me/feed', 'POST', $wallpost);
return $resp;
}
catch(Exception $e)
{
$error = $e->getResult();
echo('oh noes!'.$error.' '.$resp);

return false;
}



}      


?>