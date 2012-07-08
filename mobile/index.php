<?php

include_once('../includes/__init__.php');

$login_button = '<fb:login-button scope="user_likes" show-faces="true" width="200" max-rows="1"></fb:login-button>';

$head = '<head>';
$head .= output_standard_head('Colm\'s Sandbox', 'website', 'http://colmd.fbdublin.com/colms-sandbox/index.php', 'https://fbcdn-photos-a.akamaihd.net/photos-ak-snc7/v85005/230/120999667956026/app_10_120999667956026_1011543027.gif', 'Colm\'s Sandbox', $config['AppId']);
$head .= '<script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>';
$head .= '<script src="../scripts/main.js"></script>';
$head .= '<script src=\'https://connect.facebook.net/en_US/all.js\'></script>';
$head .= '<link rel="stylesheet" type="text/css" href="../css/screen.css" />';
$head .= '<link rel="stylesheet" type="text/css" href="../css/custom.css" />';
$head .= '<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no"/>';

$head .= '</head>';


echo $head;
echo $login_button;
