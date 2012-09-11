<?php
include(__DIR__ . '/../includes/__init__.php');

echo output_header();

$signed_request = parse_signed_request($_REQUEST['signed_request'], $config['AppSecret']);
$page_name = json_decode(curl_call('https://graph.facebook.com/'.$signed_request['page']['id']), true);
$user_name = json_decode(curl_call('https://graph.facebook.com/'.$signed_request['user_id']), true);

// Does the viewing user like the page?

if ($signed_request['page']['liked']) {
  $like_status = 'Yes';
} else {
  $like_status = 'No';
}
// is the viewing user an admin?
if ($signed_request['page']['admin']) {
  $admin_status = 'Yes';
} else {
  $admin_status = 'No';
}

// what age is the viewing user
if ($signed_request['user']['age']['min'] >= 21) {
    $age_range = 'User is over 21';
} else if ($signed_request['user']['age']['min'] <= 0 && $signed_request['user']['age']['min'] <= 12) {
    $age_range = 'User is not logged in, assume under 12';
} else if ($signed_request['user']['age']['min'] <= 12 && $signed_request['user']['age']['min'] <= 18) {
    $age_range = 'User is older than 12, but under 18';
} else if ($signed_request['user']['age']['min'] <= 18 && $signed_request['user']['age']['min'] <= 21) {
    $age_range = 'User is over 18, but under 21';
} else {
    $age_range = 'Unsure of age';
}

if ($signed_request['oauth_token']) {
    $token = $signed_request['oauth_token'];
} else {
    $token = 'No token supplied';
}

if ($signed_request['expires']){
    $token_expiry = $signed_request['expires'];
} else {
    $token_expiry = 'No expiry time provided';
}

if ($signed_request['user_id']){
    $user_id = $signed_request['user_id'];
} else {
    $user_id = 'No user id provided';
}

?>

<body>
<h1 class="center">Colm's Sandbox App</h1>
<div id="signed_request_container" class="page-tab container">
<?php
if (!$_REQUEST['signed_request']) {
	$warning = '<div class="page-tab span-9">';
	$warning .= '<p class="alert box"> <span class="bold"> ERROR: </span> No signed_request </p>';
	$warning .= '</div>';

	echo $warning;
}

?>
    <div class="page-tab span-4">
        <h3> The signed_request </h3>
        <table>
            <tr>
                <td>Encryption</td>
                <td><?php echo $signed_request['algorithm']; ?></td>
            </tr>
            <tr>
                <td>Issue Time</td>
                <td><?php echo $signed_request['issued_at'];?></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>
    <div class="page-tab span-5 last">
        <h3> The Page you're on </h3>
        <table>
            <tr>
                <td> FB ID </td>
                <td> <?php echo $signed_request['page']['id'];?> </td>
            </tr>
            <tr>
                <td> Page Name </td>
                <td> <?php echo $page_name['name']; ?> </td>
            </tr>
            <tr>
                <td> Do you like? </td>
                <td> <?php echo $like_status; ?> </td>
            </tr>
            <tr>
                <td> Are you an admin? </td>
                <td> <?php echo $admin_status; ?> </td>
            </tr>
        </table>
    </div>
    <div class="page-tab span-4">
        <h3> The User </h3>
            <table>
                <tr>
                    <td>Country</td>
                    <td><?php echo $signed_request['user']['country'];?> </td>
                </tr>
                <tr>
                    <td>Locale</td>
                    <td><?php echo $signed_request['user']['locale'];?> </td>
                </tr>
                <tr>
                    <td>Age</td>
                    <td><?php echo $age_range; ?></td>
                </tr>
            </table>
    </div>
    <div class="page-tab span-5">
        <h3> oAuth info </h3>
            <table>
                <tr>
                    <td>Token</td>
                    <td><?php echo $token;?> </td>
                </tr>
                <tr>
                    <td>Expiries</td>
                    <td><?php echo $token_expiry;?> </td>
                </tr>
                <tr>
                    <td>User ID</td>
                    <td><?php echo $user_id . ' (' . $user_name['name'] . ')'; ?></td>
                </tr>
            </table>
    </div>
<h4 class="italic"> Raw signed_request </h4>
<?php
echo('<pre>');
print_r($signed_request);
echo('</pre>');

?>
<br />
	<p> This code is available on <a href="<?php echo $config['github-url'];?>" target="_blank">Github</a></p>
</div>

</body>
