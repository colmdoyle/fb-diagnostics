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


$(document).ready(function () {
	if ($("[rel=popover]").length) {
		$("[rel=popover]").popover();
	}
	
	$('#add_page_tab').click(function(){
		FB.ui({method: 'pagetab', redirect_uri: 'https://colmd.fbdublin.com/colms-sandbox/page-tab/'});
	});
	
	$('#send_dialog').click(function(){
		FB.ui({method: 'send', link: 'https://www.github.com/colmdoyle/colms-sandbox'}, 
		function(response) {
			$('#alert-container').html('<div class="alert alert-success">'+ response.post_id + '<a class="close" data-dismiss="alert" href="#">&times;</a></div>');
		});
	});

	$('#oauth_dialog').click(function(){
		FB.ui({method: 'oauth'}, 
		function(response) {
			$('#alert-container').html('<div class="alert alert-success">'+ response + '<a class="close" data-dismiss="alert" href="#">&times;</a></div>');
		});
	});
	
	$('#requests_mfs_dialog').click(function(){
		FB.ui({method: 'apprequests', message: 'You should see this test app'}, 
		function(response) {
			console.log(response);
			$('#alert-container').html('<div class="alert alert-success"><p><strong>Request ID: </strong>'+ response.request +'</p><p><strong>Recipients: </strong>' + response.to + '</p><a class="close" data-dismiss="alert" href="#">&times;</a></div>');
		});
	});
	
	$('#feed_dialog').click(function(){
		FB.ui({
		method: 'feed', 
		redirect_uri: 'https://colmd.fbdublin.com/colms-sandbox/page-tab/', 
		link:'https://github.com/colmdoyle/colms-sandbox', 
		picture: 'https://colmd.fbdublin.com/colms-sandbox/img/320x320.png',
		name: 'Colm\'s Sandbox',
		caption: 'It\'s a great app altogether',
		description: 'Source code for a Facebook App that can be used to test various APIs & features of Facebook Apps.'
		}, 
		function(response) {
			$('#alert-container').html('<div class="alert alert-success">'+ response.post_id + '<a class="close" data-dismiss="alert" href="#">&times;</a></div>');
		});
	});
	
	$('#publish_action').click(function(){
		FB.api('/me/colms-sandbox:test', 'post', {testing_object: 'http://colmd.fbdublin.com/colms-sandbox/objects/testing.php'},
		function(response){
			if (response.error) {
				$('#alert-container').html('<div class="alert alert-error">'+ response.error + '<a class="close" data-dismiss="alert" href="#">&times;</a></div>');
			} else if (response.id) {
				$('#alert-container').html('<div class="alert alert-success">'+ response.id + '<a class="close" data-dismiss="alert" href="#">&times;</a></div>');
			} else {
				$('#alert-container').html('<div class="alert alert-error"> An unknown error has occurred, check your console. <a class="close" data-dismiss="alert" href="#">&times;</a></div>');
				console.log(response);
			}
		});
	});
	
	$('#publish_submit').click(function(){
		var message_val = $('#message_box_modal').val();
		$('#myModal').modal('hide')
		FB.api('/me/feed', 'post', {message: message_val},
		function(response){
			if (response.error) {
				$('#alert-container').html('<div class="alert alert-error">'+ response.error + '<a class="close" data-dismiss="alert" href="#">&times;</a></div>');
				console.log(response);
			} else if (response.id) {
				$('#alert-container').html('<div class="alert alert-success">'+ response.id + '<a class="close" data-dismiss="alert" href="#">&times;</a></div>');
				$('#message_box_modal').val('');
			} else {
				$('#alert-container').html('<div class="alert alert-error"> An unknown error has occurred, check your console. <a class="close" data-dismiss="alert" href="#">&times;</a></div>');
				console.log(response);
			}
		});
	});
});

