<?php

include('master_include.php');

$signed_request = parse_signed_request($_REQUEST['signed_request'], $config['AppSecret']);
echo('<pre>');
print_r($signed_request);
echo('</pre>');
