<?php
/*
Copyright 2012 Colm Doyle

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/

include(__DIR__ . '/../includes/__init__.php');

echo output_header($config['RootUrl']);

$signed_request = parse_signed_request($_REQUEST['signed_request'], $config['AppSecret']);
$page_name = json_decode(curl_call('https://graph.facebook.com/'.$signed_request['page']['id']), true);

// Does the viewing user like the page?

if ($signed_request['page']['liked']) {
  $like_status = '<span class="label label-success">Yes</span>';
} else {
  $like_status = '<span class="label label-important">No</span>';
}
// is the viewing user an admin?
if ($signed_request['page']['admin']) {
  $admin_status = '<span class="label label-success">Yes</span>';
} else {
  $admin_status = '<span class="label label-important">No</span>';
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

if ($signed_request['app_data']) {
	$app_data = htmlspecialchars($signed_request['app_data']);
} else {
	$app_data = 'No app_data supplied';
}

if ($signed_request['oauth_token']) {
    $token = $signed_request['oauth_token'];
    $token_field = '<a href="'. $config['fb-debug'].$token .'" target="_blank" class="break-all">'.$token.'</a>';
} else {
    $token_field = 'No token supplied';
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
    $user_name = json_decode(curl_call('https://graph.facebook.com/'.$signed_request['user_id']), true);
    $user_field = '<a href="' . $config['graph-explorer'] . $user_id .'" target="_blank">'. $user_id . '</a> (' . $user_name['name'] . ')';
} else {
    $user_field = 'No user ID provided';
}

?>

<body>
<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    // init the FB JS SDK
    FB.init({
      appId      : '<?php echo $config['AppId'];?>', // App ID from the App Dashboard
      channelUrl : '<?php echo $config['ChannelUrl']; ?>', // Channel File for x-domain communication
      status     : true, // check the login status upon init?
      cookie     : true, // set sessions cookies to allow your server to access the session?
      xfbml      : true  // parse XFBML tags on this page?
    });
    
    FB.Canvas.setAutoGrow();
    FB.getLoginStatus(function(response) {
	    if (response.status === 'connected') {
	    	// TODO Add Deauth button
	     } else if (response.status === 'not_authorized') {
	     	// TODO Add Auth button
	     } else {
	     	// TODO Add Auth button ?
	      // the user isn't logged in to Facebook.
	     }
    });
    
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
<!-- TODO - Make this more dynamic -->
if(self == top) {
	window.location.replace("http://www.facebook.com/colmstestpage/app_120999667956026");
}
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
	        	<a tabindex="-1" href="#oauth-modal" data-toggle="modal" id="oauth_dialog">
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
	            <tr>
	                <td>
	                	<span 
	                		rel="popover" 
	                		data-title="app_data"
	                		data-content="A JSON string containing the content of the app_data query string parameter which may be passed if the app is being loaded within a Page Tab" 
	                		data-trigger="hover">
	                		App Data
	                	</span>
	                </td>
									<td><?php echo $app_data; ?></td>
	            </tr>
	        </table>
	    </div>
	    <div class="span6">
	        <h3> The Page you're on </h3>
	        <table class="table table-bordered table-striped">
	            <tr>
	                <td>
	                	<span
	                		rel="popover"
	                		data-title="['page']['id']"
		                	data-content="A JSON string containing the id of the page that loaded the app"
			                data-trigger="hover">
			                FB ID
			               </span>
			            </td>
									<td>
									<a 
										href="<?php echo $config['graph-explorer'].$signed_request['page']['id']?>"
										target="_blank">
											<?php echo $signed_request['page']['id'];?>
										</a>
									</td>
	            </tr>
	            <tr>
	                <td> 	                	
	                	<span 
	                		rel="popover" 
	                		data-title="['page']['name']"
	                		data-content="A JSON string containing the name of the page that loaded the app" 
	                		data-trigger="hover">
		                		Page Name 
	                	</span>
	                </td>
	                <td> <?php echo $page_name['name']; ?> </td>
	            </tr>
	            <tr>
	                <td> 
	                	<span 
	                		rel="popover" 
	                		data-title="['page']['liked']"
	                		data-content="A JSON boolean of whether the current user likes the page that loaded the app" 
	                		data-trigger="hover">
		                		Do you like?
	                	</span>
	                </td>
	                <td> <?php echo $like_status; ?> </td>
	            </tr>
	            <tr>
	                <td>
 	                	<span 
	                		rel="popover" 
	                		data-title="['page']['admin']"
	                		data-content="A JSON boolean of whether the current user admins the page that loaded the app" 
	                		data-trigger="hover">
		                		Are you an admin?
 	                	</span>
 	                </td>
	                <td> <?php echo $admin_status; ?> </td>
	            </tr>
	        </table>
	    </div>
	</div>
	<div class="row-fluid">
	    <div class="span6">
	        <h3> The User </h3>
	            <table class="table table-bordered table-striped">
	                <tr>
	                    <td>
	   	                	<span 
		   	                	rel="popover" 
			   	                data-title="['user']['country']"
			   	                data-content="A JSON string representing the country of the viewing user" 
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
			   	                data-content="A JSON string representing the locale of the viewing user" 
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
	    <div class="span6">
	        <h3> oAuth info </h3>
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
												<?php echo $token_field; ?>
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
												<?php echo $user_field; ?>
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
				<li><a data-toggle="modal" href="<?php echo $config['RootUrl']; ?>privacy.php" data-target="#privacyModal">Privacy Policy</a></li>
			</ul>
		</div>
		<div class="span6">
			<p class="muted pull-right">Copyright &copy; 2012 Colm Doyle, Licensed under the <a href="http://www.apache.org/licenses/LICENSE-2.0" target="_blank">Apache License, Version 2.0.</a></p>
		</div>
	</div>
</div>
<!-- 
	Feed Modal 
	===========================
-->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 id="myModalLabel">Post to Facebook</h3>
  </div>
  <div class="modal-body">
  	<p> Enter the message you want to post in the field below </p>
		<form class="form-inline">
		  <input type="text" id="message_box_modal" placeholder="What's on your mind?">
		</form>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <button class="btn btn-primary" id="publish_submit">Post to Facebook</button>
  </div>
</div>
<!-- 
	OAuth Modal 
	==================
-->
<div id="oauth-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="oauth-modalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 id="oauth-modalLabel">Choose Permissions</h3>
  </div>
  <div class="modal-body">
		<ul class="nav nav-pills">
			<li class="active"><a href="#user-data" data-toggle="tab">User Data Permissions</a></li>
			<li><a href="#friend-data" data-toggle="tab">Friends Data Permissions</a></li>
			<li><a href="#extended-data" data-toggle="tab">Extended Permissions</a></li>
		</ul>
		<div class="tab-content" id="oauth_scope_form">
		  <div class="tab-pane active" id="user-data">
			  <form class="form container-fluid">
			  	<div class="row-fluid">
				    <label class="checkbox inline span4">
				      <input type="checkbox" id="email" value="email"> email
				    </label>
				    <label class="checkbox inline span4">
				      <input type="checkbox" id="publish_actions" value="publish_actions"> publish_actions
				    </label>
				    <label class="checkbox inline span4">
				      <input type="checkbox" id="user_about_me" value="user_about_me"> user_about_me
				    </label>
			  	</div>
			  	<div class="row-fluid">
				    <label class="checkbox inline span4">
				      <input type="checkbox" id="user_actions.music" value="user_actions.music"> user_actions.music
				    </label>
				    <label class="checkbox inline span4">
				      <input type="checkbox" id="user_actions.news" value="user_actions.news"> user_actions.news
				    </label>
				    <label class="checkbox inline span4">
				      <input type="checkbox" id="user_actions.video" value="user_actions.video"> user_actions.video
				    </label>
			  	</div>
			  	<div class="row-fluid">
				    <label class="checkbox inline span4">
				      <input type="checkbox" id="user_activities" value="user_activities"> user_activities
				    </label>
				    <label class="checkbox inline span4">
				      <input type="checkbox" id="user_birthday" value="user_birthday"> user_birthday
				    </label>
				    <label class="checkbox inline span4">
				      <input type="checkbox" id="user_education_history" value="user_education_history"> user_education_history
				    </label>
			  	</div>
			  	<div class="row-fluid">
				    <label class="checkbox inline span4">
				      <input type="checkbox" id="user_events" value="user_events"> user_events
				    </label>
				    <label class="checkbox inline span4">
				      <input type="checkbox" id="user_games_activity" value="user_games_activity"> user_games_activity
				    </label>
				    <label class="checkbox inline span4">
				      <input type="checkbox" id="user_groups" value="user_groups"> user_groups
				    </label>
			  	</div>
			  	<div class="row-fluid">
				    <label class="checkbox inline span4">
				      <input type="checkbox" id="user_hometown" value="user_hometown"> user_hometown
				    </label>
				    <label class="checkbox inline span4">
				      <input type="checkbox" id="user_interests" value="user_interests"> user_interests
				    </label>
				    <label class="checkbox inline span4">
				      <input type="checkbox" id="user_likes" value="user_likes"> user_likes
				    </label>
			  	</div>
			  	<div class="row-fluid">
				    <label class="checkbox inline span4">
				      <input type="checkbox" id="user_location" value="user_location"> user_location
				    </label>
				    <label class="checkbox inline span4">
				      <input type="checkbox" id="user_notes" value="user_notes"> user_notes
				    </label>
				    <label class="checkbox inline span4">
				      <input type="checkbox" id="user_photos" value="user_photos"> user_photos
				    </label>
			  	</div>
			  	<div class="row-fluid">
				    <label class="checkbox inline span4">
				      <input type="checkbox" id="user_questions" value="user_questions"> user_questions
				    </label>
				    <label class="checkbox inline span4">
				      <input type="checkbox" id="user_relationship_details" value="user_relationship_details"> user_relationship_details
				    </label>
				    <label class="checkbox inline span4">
				      <input type="checkbox" id="user_relationships" value="user_relationships"> user_relationships
				    </label>
			  	</div>
			  	<div class="row-fluid">
				    <label class="checkbox inline span4">
				      <input type="checkbox" id="user_religion_politics" value="user_religion_politics"> user_religion_politics
				    </label>
				    <label class="checkbox inline span4">
				      <input type="checkbox" id="user_status" value="user_status"> user_status
				    </label>
				    <label class="checkbox inline span4">
				      <input type="checkbox" id="user_subscriptions" value="user_subscriptions"> user_subscriptions
				    </label>
			  	</div>
			  	<div class="row-fluid">
				    <label class="checkbox inline span4">
				      <input type="checkbox" id="user_videos" value="user_videos"> user_videos
				    </label>
				    <label class="checkbox inline span4">
				      <input type="checkbox" id="user_website" value="user_website"> user_website
				    </label>
				    <label class="checkbox inline span4">
				      <input type="checkbox" id="user_work_history" value="user_work_history"> user_work_history
				    </label>
			  	</div>
			  </form>
		  </div>
		  <div class="tab-pane" id="friend-data">
			  <form class="form container-fluid">
			  	<div class="row-fluid">
				  <label class="checkbox inline span4">
				    <input type="checkbox" id="friends_about_me" value="friends_about_me"> friends_about_me
				  </label>
				  <label class="checkbox inline span4">
				    <input type="checkbox" id="friends_actions.music" value="friends_actions.music"> friends_actions.music
				  </label>
				  <label class="checkbox inline span4">
				    <input type="checkbox" id="friends_actions.news" value="friends_actions.news"> friends_actions.news
				  </label>
			  	</div>
			  	<div class="row-fluid">
				  <label class="checkbox inline span4">
				    <input type="checkbox" id="friends_actions.video" value="friends_actions.video"> friends_actions.video
				  </label>
				  <label class="checkbox inline span4">
				    <input type="checkbox" id="friends_activities" value="friends_activities"> friends_activities
				  </label>
				  <label class="checkbox inline span4">
				    <input type="checkbox" id="friends_birthday" value="friends_birthday"> friends_birthday
				  </label>
			  	</div>
			  	<div class="row-fluid">
				  <label class="checkbox inline span4">
				    <input type="checkbox" id="friends_education_history" value="friends_education_history"> friends_education_history
				  </label>
				  <label class="checkbox inline span4">
				    <input type="checkbox" id="friends_events" value="friends_events"> friends_events
				  </label>
				  <label class="checkbox inline span4">
				    <input type="checkbox" id="friends_games_activity" value="friends_games_activity"> friends_games_activity
				  </label>
			  	</div>
			  	<div class="row-fluid">
				  <label class="checkbox inline span4">
				    <input type="checkbox" id="friends_groups" value="friends_groups"> friends_groups
				  </label>
				  <label class="checkbox inline span4">
				    <input type="checkbox" id="friends_hometown" value="friends_hometown"> friends_hometown
				  </label>
				  <label class="checkbox inline span4">
				    <input type="checkbox" id="friends_interests" value="friends_interests"> friends_interests
				  </label>
			  	</div>
			  	<div class="row-fluid">
				  <label class="checkbox inline span4">
				    <input type="checkbox" id="friends_likes" value="friends_likes"> friends_likes
				  </label>
				  <label class="checkbox inline span4">
				    <input type="checkbox" id="friends_location" value="friends_location"> friends_location
				  </label>
				  <label class="checkbox inline span4">
				    <input type="checkbox" id="friends_notes" value="friends_notes"> friends_notes
				  </label>
			  	</div>
			  	<div class="row-fluid">
				  <label class="checkbox inline span4">
				    <input type="checkbox" id="friends_photos" value="friends_photos"> friends_photos
				  </label>
				  <label class="checkbox inline span4">
				    <input type="checkbox" id="friends_questions" value="friends_questions"> friends_questions
				  </label>
				  <label class="checkbox inline span4">
				    <input type="checkbox" id="friends_relationship_details" value="friends_relationship_details"> friends_relationship_details
				  </label>
			  	</div>
			  	<div class="row-fluid">
				  <label class="checkbox inline span4">
				    <input type="checkbox" id="friends_relationships" value="friends_relationships"> friends_relationships
				  </label>
				  <label class="checkbox inline span4">
				    <input type="checkbox" id="friends_religion_politics" value="friends_religion_politics"> friends_religion_politics
				  </label>
				  <label class="checkbox inline span4">
				    <input type="checkbox" id="friends_status" value="friends_status"> friends_status
				  </label>
			  	</div>
			  	<div class="row-fluid">
				  <label class="checkbox inline span4">
				    <input type="checkbox" id="friends_subscriptions" value="friends_subscriptions"> friends_subscriptions
				  </label>
				  <label class="checkbox inline span4">
				    <input type="checkbox" id="friends_videos" value="friends_videos"> friends_videos
				  </label>
				  <label class="checkbox inline span4">
				    <input type="checkbox" id="friends_website" value="friends_website"> friends_website
				  </label>
			  	</div>
			  	<div class="row-fluid">
				  <label class="checkbox inline span4">
				    <input type="checkbox" id="friends_work_history" value="friends_work_history"> friends_work_history
				  </label>
			  	</div>
			  </form>			  
		  </div>
		  <div class="tab-pane" id="extended-data">
			  <form class="form container-fluid">
			  	<div class="row-fluid">
				    <label class="checkbox inline span4">
				      <input type="checkbox" id="ads_management" value="ads_management"> ads_management
				    </label>
				    <label class="checkbox inline span4">
				      <input type="checkbox" id="create_event" value="create_event"> create_event
				    </label>
				    <label class="checkbox inline span4">
				      <input type="checkbox" id="create_note" value="create_note"> create_note
				    </label>
			  	</div>
			  	<div class="row-fluid">
					  <label class="checkbox inline span4">
					    <input type="checkbox" id="export_stream" value="export_stream"> export_stream
					  </label>
					  <label class="checkbox inline span4">
					    <input type="checkbox" id="friends_online_presence" value="friends_online_presence"> friends_online_presence
					  </label>
					  <label class="checkbox inline span4">
					    <input type="checkbox" id="manage_friendlists" value="manage_friendlists"> manage_friendlists
					  </label>
			  	</div>
			  	<div class="row-fluid">
					  <label class="checkbox inline span4">
					    <input type="checkbox" id="manage_notifications" value="manage_notifications"> manage_notifications
					  </label>
					  <label class="checkbox inline span4">
					    <input type="checkbox" id="manage_pages" value="manage_pages"> manage_pages
					  </label>
					  <label class="checkbox inline span4">
					    <input type="checkbox" id="offline_access" value="offline_access"> offline_access
					  </label>
			  	</div>
			  	<div class="row-fluid">
					  <label class="checkbox inline span4">
					    <input type="checkbox" id="photo_upload" value="photo_upload"> photo_upload
					  </label>
					  <label class="checkbox inline span4">
					    <input type="checkbox" id="publish_checkins" value="publish_checkins"> publish_checkins
					  </label>
					  <label class="checkbox inline span4">
					    <input type="checkbox" id="publish_stream" value="publish_stream"> publish_stream
					  </label>
			  	</div>
			  	<div class="row-fluid">
					  <label class="checkbox inline span4">
					    <input type="checkbox" id="read_friendlists" value="read_friendlists"> read_friendlists
					  </label>
					  <label class="checkbox inline span4">
					    <input type="checkbox" id="read_insights" value="read_insights"> read_insights
					  </label>
					  <label class="checkbox inline span4">
					    <input type="checkbox" id="read_mailbox" value="read_mailbox"> read_mailbox
					  </label>
			  	</div>
			  	<div class="row-fluid">
					  <label class="checkbox inline span4">
					    <input type="checkbox" id="read_page_mailboxes" value="read_page_mailboxes"> read_page_mailboxes
					  </label>
					  <label class="checkbox inline span4">
					    <input type="checkbox" id="read_requests" value="read_requests"> read_requests
					  </label>
					  <label class="checkbox inline span4">
					    <input type="checkbox" id="read_stream" value="read_stream"> read_stream
					  </label>
			  	</div>
			  	<div class="row-fluid">
					  <label class="checkbox inline span4">
					    <input type="checkbox" id="rsvp_event" value="rsvp_event"> rsvp_event
					  </label>
					  <label class="checkbox inline span4">
					    <input type="checkbox" id="share_item" value="share_item"> share_item
					  </label>
					  <label class="checkbox inline span4">
					    <input type="checkbox" id="sms" value="sms"> sms
					  </label>
			  	</div>
			  	<div class="row-fluid">
					  <label class="checkbox inline span4">
					    <input type="checkbox" id="status_update" value="status_update"> status_update
					  </label>
					  <label class="checkbox inline span4">
					    <input type="checkbox" id="user_online_presence" value="user_online_presence"> user_online_presence
					  </label>
					  <label class="checkbox inline span4">
					    <input type="checkbox" id="video_upload" value="video_upload"> video_upload
					  </label>
			  	</div>
			  	<div class="row-fluid">
					  <label class="checkbox inline span4">
					    <input type="checkbox" id="xmpp_login" value="xmpp_login"> xmpp_login
					  </label>
			  	</div>
			  </form>	  
		  </div>
		</div>
  </div>
  <div class="modal-footer">
    <button class="btn btn-primary" id="oauth_submit">Get Access Token</button>
    <button class="btn" id="clear_checkbox">Clear</button>
    <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
  </div>
</div>
<!-- 
	Privacy Modal 
	========================
-->
<div id="privacyModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="privacyModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 id="privacyModalLbael">Privacy</h3>
  </div>
  <div class="modal-body">
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>
</body>
