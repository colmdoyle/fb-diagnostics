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
