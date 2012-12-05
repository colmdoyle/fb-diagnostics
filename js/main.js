$(document).ready(function () {
	if ($("[rel=popover]").length) {
		$("[rel=popover]").popover();
	}
	
	$('#add_page_tab').click(function(){
		FB.ui({method: 'pagetab', redirect_uri: 'https://colmd.fbdublin.com/colms-sandbox/page-tab/'});
	});
	
	$('#feed_dialog').click(function(){
		// TODO - Add Response Handler
		FB.ui({
		method: 'feed', 
		redirect_uri: 'https://colmd.fbdublin.com/colms-sandbox/page-tab/', 
		link:'https://github.com/colmdoyle/colms-sandbox', 
		picture: 'https://colmd.fbdublin.com/colms-sandbox/img/320x320.png',
		name: 'Colm\'s Sandbox',
		caption: 'It\'s a great app altogether',
		description: 'Source code for a Facebook App that can be used to test various APIs & features of Facebook Apps.'
		});
	});

});

