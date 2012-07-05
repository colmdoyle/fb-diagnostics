<?php

$fb_ref = $_REQUEST['fb_source'];

?>

<html>
 <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# video: http://ogp.me/ns/video#">
     <meta property="fb:app_id"                     content="326246980797748" />
  <meta property="og:type"                       content="hello_world_alex:thing" />
  <meta property="og:url"                        content="http://colmd.fbdublin.com/colms-sandbox/objects/testing.php" />
  <meta property="og:title"                      content="Open Graph" />
  <meta property="og:image"                      content="https://s-static.ak.fbcdn.net/images/devsite/attachment_blank.png" />
</head>
<body>
<p>Hello intrepid visitor.</p>
<p> You appear to have come from <?php echo $fb_ref;?>
</body>

</html>
