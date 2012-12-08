<?php
include(__DIR__ . '/../includes/__init__.php');

echo output_header();

$signed_request = parse_signed_request($_REQUEST['signed_request'], $config['AppSecret']);
$page_name = json_decode(curl_call('https://graph.facebook.com/'.$signed_request['page']['id']), true);
$user_name = json_decode(curl_call('https://graph.facebook.com/'.$signed_request['user_id']), true);

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

if ($signed_request['app_data']) {
	$app_data = htmlspecialchars($signed_request['app_data']);
} else {
	$app_data = 'No app_data supplied';
}

if ($signed_request['oauth_token']) {
    $token = $signed_request['oauth_token'];
} else {
    $token = 'No token supplied';
}

if ($signed_request['expires']){
    $token_expiry = $signed_request['expires'];
    if (is_int($signed_request['expires'])) {
	    $token_expiry_human = '(' .date("M j Y, Hi e", $signed_request['expires']). ')';
    }
} else {
    $token_expiry = 'No expiry time provided';
    $token_expiry_human = '';
}

if ($signed_request['user_id']){
    $user_id = $signed_request['user_id'];
} else {
    $user_id = 'No user id provided';
}

?>

<body>
<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    // init the FB JS SDK
    FB.init({
      appId      : '<?php echo $config['AppId'];?>', // App ID from the App Dashboard
      channelUrl : '//COLMD.FBDUBLIN.COM/channel.html', // Channel File for x-domain communication
      status     : true, // check the login status upon init?
      cookie     : true, // set sessions cookies to allow your server to access the session?
      xfbml      : true  // parse XFBML tags on this page?
    });

    FB.Canvas.setAutoGrow();
  };

  // Load the SDK's source Asynchronously
  // Note that the debug version is being actively developed and might
  // contain some type checks that are overly strict.
  // Please report such bugs using the bugs tool.
  (function(d, debug){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all" + (debug ? "/debug" : "") + ".js";
     ref.parentNode.insertBefore(js, ref);
   }(document, /*debug*/ false));
</script>
<div class="container-fluid" id="content">
<div class="navbar">
  <div class="navbar-inner">
    <a class="brand" href="#"><?php echo $config['AppName']; ?></a>
    <ul class="nav">
	    <li class="dropdown">
	      <a class="dropdown-toggle" data-toggle="dropdown" href="#">
	        Dialogs
	        <span class="caret"></span>
	      </a>
	      <ul class="dropdown-menu">
	        <li>
	        	<a tabindex="-1" href="#" id="add_page_tab">
	        		<i class="icon-plus"></i> Add Page Tab
	        	</a>
	        </li>
	        <li>
	        	<a tabindex="-1" href="#" id="feed_dialog">
	        		<i class="icon-comment"></i> Feed
	        	</a>
	        </li>
	        <li>
	        	<a tabindex="-1" href="#" id="send_dialog">
	        		<i class="icon-envelope"></i> Send
	        	</a>
	        </li>
	        <li>
	        	<a tabindex="-1" href="#" id="oauth_dialog">
	        		<i class="icon-lock"></i> OAuth
	        	</a>
	        </li>
	        <li>
	        	<a tabindex="-1" href="#" id="requests_mfs_dialog">
	        		<i class="icon-globe"></i> Requests MultiFriend
	        	</a>
	        </li>
	      </ul>
	    </li>
	    <li class="dropdown">
	    	<a class="dropdown-toggle" data-toggle="dropdown" href="#">
		      Publishing
		      <span class="caret"></span>
		    </a>
	      <ul class="dropdown-menu">
	        <li>
	        	<a tabindex="-1" href="#myModal" data-toggle="modal" id="feed_publish">
	        		<i class="icon-comment"></i> Post to Wall
	        	</a>
	        </li>
	        <li>
	        	<a tabindex="-1" href="#" id="publish_action">
	        		<i class="icon-plus-sign"></i> Publish an Action
	        	</a>
	        </li>
	      </ul>
	    </li>
	    <li class="dropdown">
	    	<a class="dropdown-toggle" data-toggle="dropdown" href="#">
		      View On
		      <span class="caret"></span>
		    </a>
	      <ul class="dropdown-menu">
	        <li>
	        	<a tabindex="-1" href="<?php echo $config['CanvasUrl']; ?>" target="_blank">
	        		<i class="icon-th-large"></i> Canvas
	        	</a>
	        </li>
	        <li>
	        	<a tabindex="-1" href="<?php echo $config['PageTabUrl']; ?>" target="_blank">
	        		<i class="icon-flag"></i> Page Tab
	        	</a>
	        </li>
	      </ul>
	    </li>
    </ul>
  </div>
</div>
<div id="alert-container"></div>
<?php
if (!$_REQUEST['signed_request']) {
?>
<div class="alert alert-error">
  <button type="button" class="close" data-dismiss="alert">×</button>
  <h4>Warning!</h4>
  There was no signed_request in the POST.
</div>
<?php
}
?>
	<div class="row-fluid">
	    <div class="span6">
	        <h3> The signed_request </h3>
	        <table class="table table-bordered table-striped">
	            <tr>
	                <td>
	                	<span
	                		rel="popover"
	                		data-title="algorithm"
	                		data-content="A JSON string containing the mechanism used to sign the request"
	                		data-trigger="hover">
	                		Encryption
	                	</span>
	                </td>
	                <td><?php echo $signed_request['algorithm']; ?></td>
	            </tr>
	            <tr>
	                <td>
	                	<span
	                		rel="popover"
	                		data-title="issued_at"
	                		data-content="A JSON number containing the Unix timestamp when the request was signed"
	                		data-trigger="hover">
	                		Issue Time
	                	</span>
	                </td>
	                <td><?php echo $signed_request['issued_at'];?></td>
	            </tr>
	        </table>
	    </div>
	    <div class="span6">
	        <h3> The User </h3>
	            <table class="table table-bordered table-striped">
	                <tr>
	                    <td>
	   	                	<span
		   	                	rel="popover"
			   	                data-title="['user']['country']"
			   	                data-content="A string representing the country of the viewing user"
				   	              data-trigger="hover">
					   	            	Country
	   	                	</span>
	                    </td>
	                    <td><?php echo $signed_request['user']['country'];?> </td>
	                </tr>
	                <tr>
	                    <td>
 	   	                	<span
		   	                	rel="popover"
			   	                data-title="['user']['locale']"
			   	                data-content="A string representing the locale of the viewing user"
				   	              data-trigger="hover">
					   	              Locale
 	   	                	</span>
 	   	                </td>
	                    <td><?php echo $signed_request['user']['locale'];?> </td>
	                </tr>
	                <tr>
	                    <td>
	                    	<span
	                    		rel="popover"
	                    		data-title="['user']['age']"
	                    		data-content="The age object provides an unspecific age range that the user fits into, allowing apps to determine whether the user can be shown alcohol content for example, without identifying their age specifically."
	                    		data-trigger="hover">
	                    			Age
	                    	</span>
	                    </td>
	                    <td><?php echo $age_range; ?></td>
	                </tr>
	            </table>
	    </div>
	</div>
	<div class="row-fluid">
	    <div class="span12">
	        <h3> OAuth info </h3>
	            <table class="table table-bordered table-striped">
	                <tr>
	                    <td>
 	   	                	<span
		   	                	rel="popover"
			   	                data-title="oauth_token"
			   	                data-content="A JSON string that can be used when making requests to the Graph API. This is also known as a user access token."
				   	              data-trigger="hover">
					   	              Token
 	   	                	</span>
 	   	                </td>
											<td>
												<a href="<?php echo $config['fb-debug'].$token; ?>" target="_blank">
												<?php echo $token;?></a>
											</td>
	                </tr>
	                <tr>
	                    <td>
	                    	<span
	                    		rel="popover"
	                    		data-title="expires"
	                    		data-content="A JSON number containing the Unix timestamp when the oauth_token expires."
	                    		data-trigger="hover">
	                    		Expires
	                    	</span>
	                    </td>
	                    <td><?php echo $token_expiry .' '. $token_expiry_human;?> </td>
	                </tr>
	                <tr>
	                    <td>
	                    	<span
	                    		rel="popover"
	                    		data-title="user_id"
	                    		data-content="A JSON string containing the User ID of the current user."
	                    		data-trigger="hover">
	                    			User ID
	                    	</span>
	                    </td>
											<td>
												<a href="<?php echo $config['graph-explorer'].$user_id; ?>" target="_blank">
													<?php echo $user_id; ?></a>
											<?php echo ' (' . $user_name['name'] . ')'; ?>
											</td>
	                </tr>
	            </table>
	    </div>
	</div>
	<h4 class="italic"> Raw signed_request </h4>
	<?php
	echo('<pre><code>');
	print_r($signed_request);
	echo('</code></pre>');

	?>
 	<hr />
	<div class="row-fluid">
		<div class="span6">
			<ul class="nav nav-pills">
				<li><a href="<?php echo $config['github-url'];?>" target="_blank">Github</a></li>
				<li><a href="<?php echo $config['github-url'];?>/issues" target="_blank">Report Bugs</a></li>
				<li><a href="https://colmd.fbdublin.com/colms-sandbox/privacy.php">Privacy Policy</a></li>
			</ul>
		</div>
		<div class="span6">
			<p class="muted pull-right">Copyright &copy; 2012 Colm Doyle, Licensed under the <a href="http://www.apache.org/licenses/LICENSE-2.0">Apache License, Version 2.0.</a></p>
		</div>
	</div>
</div>

</body>
