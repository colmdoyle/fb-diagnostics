<?php

include('master_include.php');

$signed_request = $_REQUEST['signed_request'];
$parsed_request = parse_signed_request($signed_request, $config['AppSecret']);

$html_signed_request = <h3>Signed Request</h3>;
$html_signed_request .= <pre>{$signed_request}</pre>;

$html_parsed_request = <h3>Parsed Signed Request</h3>;
$request_info_table = <tbody />;

foreach($parsed_request as $title => $value) {
  $request_info_table->appendChild(
    <tr>
      <td>
        {$title}
      </td>
      <td>
        {$value}
      </td>
    </tr>
  );
}

$html_parsed_request .=
  <table>
    {$request_info_table}
  </table>;


$html = $html_signed_request;
$html .= $html_parsed_request;

echo $html;
