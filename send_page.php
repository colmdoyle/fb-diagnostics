<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <body>
    <script src="http://connect.facebook.net/en_US/all.js"></script>
    <div id="fb-root"></div>
    <script>
      // assume we are already logged in
      FB.init({appId: '123050457758183', xfbml: true, cookie: true});

      FB.ui({
          method: 'send',
          name: 'People Argue Just to Win',
          link: 'http://www.nytimes.com/2011/06/15/arts/people-argue-just-to-win-scholars-assert.html',
          });
     </script>
  </body>
</html>
