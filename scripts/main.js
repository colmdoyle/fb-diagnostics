$(document).ready(function() {

    $('#publish_action_link').click(function() {
      var access_token_var = $('#oauth_token').text();
      $.post("https://graph.facebook.com/me/colms-sandbox:test",
        {
          access_token: access_token_var,
          testing_object: "http://colmd.fbdublin.com/colms-sandbox/objects/testing.php"
        },
        function(data) {
        console.log(data);
          $("#action_response").text(data);
        });
      });

});

  window.fbAsyncInit = function() {
    FB.init({
      appId      : '120999667956026', // App ID
      //channelUrl : '//WWW.YOUR_DOMAIN.COM/channel.html', // Channel File
      status     : true, // check login status
      cookie     : true, // enable cookies to allow the server to access the session
      xfbml      : true  // parse XFBML
    });

    // Additional initialization code here
  };

  // Load the SDK Asynchronously
  (function(d){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     ref.parentNode.insertBefore(js, ref);
   }(document));

      function postToFeed() {

        // calling the API ...
        var obj = {
          method: 'feed',
          link: 'https://developers.facebook.com/docs/reference/dialogs/',
          picture: 'http://fbrell.com/f8.jpg',
          name: 'Facebook Dialogs',
          caption: 'Reference Documentation',
          description: 'Using Dialogs to interact with users.'
        };

        function callback(response) {
          document.getElementById('msg').innerHTML = "Post ID: " + response['post_id'];
        }

        FB.ui(obj, callback);
      }

      function authApp() {
        var obj = {
method: 'oauth',
        redirect_uri: 'https://apps.facebook.com/colms-sandbox'
        };
      }

  function addToPage() {

        // calling the API ...
        var obj = {
          method: 'pagetab',
          redirect_uri: 'https://apps.facebook.com/colms-sandbox/',
        };

        FB.ui(obj);
      }

      function sendRequestToRecipients() {
        var user_ids = document.getElementsByName("user_ids")[0].value;
        FB.ui({method: 'apprequests',
          message: 'My Great Request',
          to: user_ids,
        }, requestCallback);
      }

      function sendRequestViaMultiFriendSelector() {
        FB.ui({method: 'apprequests',
          message: 'My Great Request'
        }, requestCallback);
      }

      function requestCallback(response) {
        // Handle callback here
      }
function sendDialog() {
      FB.ui({
          method: 'send',
          name: 'People Argue Just to Win',
          link: 'http://www.nytimes.com/2011/06/15/arts/people-argue-just-to-win-scholars-assert.html',
          });
}

