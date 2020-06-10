<?php
require_once '../connect_to_db.php';
$state = filter_input(INPUT_GET, 'state', FILTER_VALIDATE_INT);

if ($state == 0) {
    $sql_query = "SELECT COUNT('erakond') as value, erakond as name FROM kandidaadid where erakond != 'Puudub' group by erakond";
} elseif ( $state == 1) {
    $sql_query = "SELECT COUNT('erakond') as value, erakond as name FROM kandidaadid group by valimisnimekiri";
} else {
    $sql_query = "SELECT COUNT('erakond') as value, erakond as name FROM kandidaadid where erakond != 'Puudub' group by erakond";
}


$database_response = mysqli_query($connect, $sql_query);

$candidates_in_listing = [];
while ( $row = mysqli_fetch_array($database_response, MYSQLI_ASSOC)) {
    $candidates_in_listing[] = $row;
}

$json = '{
"cols": [
        {"id":"","label":"Partei","pattern":"","type":"string"},
        {"id":"","label":"kandidaate","pattern":"","type":"number"}
      ],
  "rows": [';

$json_rows = [];
if (!empty($candidates_in_listing)) {
    foreach ($candidates_in_listing as $key => $value) {
        $json_rows[] = '{"c":[{"v":"' . $value['name'] . '","f":null},{"v":' . $value['value'] . ',"f":null}]}';
    }
}

$json.= join(",", $json_rows);

$json .= ']
}';

echo $json;