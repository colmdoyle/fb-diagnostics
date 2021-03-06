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
  prettyPrint();
  
  var num_of_post_params = 0;

	if ($("[rel=popover]").length) {
		$("[rel=popover]").popover();
	}

	$('#add_page_tab').click(function(){
		FB.ui({method: 'pagetab', redirect_uri: 'https://colmd.fbdublin.com/fb-diagnostics/page-tab/'});
	});

	$('#send_dialog').click(function(){
		FB.ui({method: 'send', link: 'https://www.github.com/colmdoyle/fb-diagnostics'},
		function(response) {
			$('#alert-container').html('<div class="alert alert-success">'+ response.post_id + '<a class="close" data-dismiss="alert" href="#">&times;</a></div>');
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
		redirect_uri: 'https://colmd.fbdublin.com/fb-diagnostics/page-tab/',
		link:'https://github.com/colmdoyle/fb-diagnostics',
		picture: 'https://colmd.fbdublin.com/fb-diagnostics/img/320x320.png',
		name: 'Diagnostics App',
		caption: 'It\'s a great app altogether',
		description: 'Source code for a Facebook App that can be used to test various APIs & features of Facebook Apps.'
		},
		function(response) {
			$('#alert-container').html('<div class="alert alert-success">'+ response.post_id + '<a class="close" data-dismiss="alert" href="#">&times;</a></div>');
		});
	});

	$('#publish_action').click(function(){
		FB.api('/me/diagnostics-app:test', 'post', {testing_object: 'http://colmd.fbdublin.com/fb-diagnostics/objects/testing.php'},
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
	
	$('#oauth_submit').click(function(){
		var scope = '';
		var perms = $('#oauth_scope_form').find('input:checked');
		var perms_length = perms.length - 1;
		$(perms).each(function(index) {
			scope += $(this).val();
			if (index < perms_length) {
				scope += ',';
			}
		});
		FB.login(function(response){
			$('#oauth-modal').modal('hide');
			$('#oauth_scope_form').find('input:checked').removeAttr('checked');
			var oauth_html_response = '<ul class="unstyled">';
			$.each(response.authResponse, function(key, valueObj) {
				oauth_html_response += '<li class="break-all"><strong>'+key+'</strong> : '+valueObj+'</li>';
			});
			oauth_html_response += '</ul>'
			$('#alert-container').html('<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#">&times;</a><h4>Response Object</h4>'+ oauth_html_response + '</div>');
			console.log(response);
		}, {scope: scope});
	});
	
	$('#clear_checkbox').click(function() {
		$('#oauth_scope_form').find('input:checked').removeAttr('checked');
	});
	
	$('#explorer-dropdown li a').click(function() {
		if ($(this).html() == 'POST') {
			$('#explorer').append('<div class="row-fluid"><div id="post-field-row" class="span11 offset1 controls"><a id="field-add" href="#"> Add a field </a></div></div>');
			$('#field-add').on('click', function(event){
				num_of_post_params++;
				$('#post-field-row').append('<div class="controls-row param-pair" id="param-pair-'+num_of_post_params+'"><input class="span2 post-field" type="text" placeholder="name" name="name"><input class="span2 value post-field" type="text" placeholder="value" name="value"></div>');
			});
		} else {
			$('#post-field-row').remove();
		}
		$('#explorer-http-active').text($(this).html());
	});
	
	$('#explorer-input').focus(function() {
		$('#explorer-form').removeClass('error');
	})
	
	$('#explorer-submit').on('click', function(e) {
			
		var http_method = $('#explorer-http-active').html();
		var path = $('#explorer-input').val();
		
		var param_pair_total = $(".param-pair").length;
		var post_params = '{';
		
		$(".param-pair").each(function(index) {
			name = $(this).children(":nth-child(1)").val();
			value = $(this).children(":nth-child(2)").val();
			
			post_params += '"'+name+'":"'+value+'"';
			
			if (index === param_pair_total - 1) {
			 post_params += '';
			} else {
			 post_params += ',';	
			}
			
		});
		post_params += '}';

		params_object = $.parseJSON(post_params);
		
		if (path) {
	    $('#explorer-response').addClass('center-text');
			$('#explorer-response').html('<i class="icon-spinner icon-spin icon-2x loading-indicator"></i>');	
		
			if (path.charAt(0) != '/') {
			  path = '/' + path;
			}
			
			if (post_params.length > 3) {
			  FB.api(path, http_method, params_object,
			  function(response){
			    console.log(response);
    	    $('#explorer-response').removeClass('center-text');
			    $('#explorer-response').text(JSON.stringify(response, null, '\t'));
			    $('#explorer-response').addClass('lang-js');
			    prettyPrint();
			  });
			} else {
			  FB.api(path, http_method,
			  function(response){
			    console.log(response);
    	    $('#explorer-response').removeClass('center-text');
			    $('#explorer-response').text(JSON.stringify(response, null, '\t'));
			    $('#explorer-response').addClass('lang-js');
			    prettyPrint();
			  });
			}
		} else {
			$('#explorer-form').addClass('error');
		} 
		
		e.preventDefault();
	});

});

