<?php
require_once '../connect_to_db.php';
require_once '../email_domains.php';
$state = filter_input(INPUT_GET, 'state', FILTER_VALIDATE_INT);

if ($state == 1) {
    $sqlQuery = "SELECT email FROM `kandidaadid` where email != 'Email puudub'";
} elseif ($state == 0) {
    $sqlQuery = "SELECT email FROM `kandidaadid` where email != 'Email puudub' and erakond !='Puudub'";
} else {
    $sqlQuery = "SELECT email FROM `kandidaadid` where email != 'Email puudub' and erakond !='Puudub'";
}
$emails = get_email_domains($sqlQuery, $connect);

$json = '{
"cols": [
        {"id":"","label":"Emaili domeen","pattern":"","type":"string"},
        {"id":"","label":"emaili","pattern":"","type":"number"}
      ],
  "rows": [';
$jason_rows = [];

foreach ($emails as $key => $value) {
    $jason_rows[] = '{"c":[{"v":"' . $value . '"},{"v":' . $key . '}]}';
}

$json.= join(",", $jason_rows);
$json .= ']
}';

echo $json;
