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
include(__DIR__ . '/includes/__init__.php');
?>

<!DOCTYPE html>
<html lang="en">

<?php
echo output_header($config['RootUrl']);
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
<!-- !NavBar 	-->
<div class="navbar navbar-static-top">
  <div class="navbar-inner">
    <div class="container">
 
      <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
 
      <!-- Be sure to leave the brand out there if you want it shown -->
      <a class="brand" href="#"><?php echo $config['AppName']; ?></a>
 
      <!-- Everything you want hidden at 940px or less, place within here -->
      <div class="nav-collapse collapse">
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
              		<i class="icon-picture"></i> Canvas
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
    </div>
  </div>
</div>
      <div class="container-fluid">
					<div class="row-fluid">
						<div class="span12" id="explorer">
							<h3> Graph API Explorer </h3>
							<div class="row-fluid">
								<div class="span12 controls controls-row control-group" id="explorer-form">
									<form class="form-inline">
										<div class="btn-group span1">
											<button class="btn dropdown-toggle" data-toggle="dropdown" id="explorer-http-menu" type="button">
												<span id="explorer-http-active">GET</span>
												<span class="caret"></span>
											</button>
											<ul class="dropdown-menu" id="explorer-dropdown">
												<li>
													<a href="#" id="explorer-dropdown-get">GET</a>
												</li>
												<li>
													<a href="#" id="explorer-dropdown-post">POST</a>
												</li>
												<li>
													<a href="#" id="explorer-dropdown-delete">DELETE</a>
												</li>
											</ul>
										</div>
										<input class="span10" type="text" placeholder="/" id="explorer-input">
										<button class="btn btn-primary span1" id="explorer-submit">Submit</button>
									</form>
								</div>
							</div>
						</div>
					</div>
					<hr />
					<div class="row-fluid" id="explorer-response-row">
						<div class="span12" id="explorer-response-span">
							<pre id="explorer-response" class="prettyprint">
							</pre>
						</div>
					</div>
				</div>

</body>
</html>


