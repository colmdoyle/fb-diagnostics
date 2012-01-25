<?php

//Hello worldo


include('../../php-sdk/src/facebook.php');
include('config.php');


$facebook = new Facebook(array(
                'appId'  => $appid,
                'secret' => $appsecret,
                'cookie' => true,));
                

$attending = $facebook -> api('/212562888754853/attending');

echo '<pre>';
print_r(count($attending['data']));
echo '</pre>';

?>
