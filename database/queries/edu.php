<?php
require_once '../connect_to_db.php';

$state = filter_input(INPUT_GET, 'state', FILTER_VALIDATE_INT);

$sql_query = "SELECT COUNT(`id`), `haridus`, `valimisnimekiri` FROM `kandidaadid` GROUP BY `haridus`, `valimisnimekiri`  
ORDER BY `kandidaadid`.`valimisnimekiri` ASC";

$database_response = mysqli_query($connect, $sql_query);

$candidates_in_listing = [];
while ( $row = mysqli_fetch_array($database_response, MYSQLI_ASSOC)) {

    $candidates_in_listing[] = $row;
}

$json = '{
"cols": [
        {"id":"","label":"Valimisnimekiri","pattern":"","type":"string"},
        {"id":"","label":"Põhiharidus","pattern":"","type":"number"},
        {"id":"","label":"Keskharidus","pattern":"","type":"number"},
        {"id":"","label":"Kõrgharidus","pattern":"","type":"number"}
      ],
  "rows": [';

$json_rows = ['{"c":[{"v":"Eesti Keskerakond"},{"v":0},{"v":17},{"v":108}]}',
    '{"c":[{"v":"Eesti Konervatiivne Rahvaerakond"},{"v":1},{"v":51},{"v":73}]}',
    '{"c":[{"v":"Eesti Reformierakond"},{"v":0},{"v":17},{"v":108}]}',
    '{"c":[{"v":"Eesti Vabaerakond"},{"v":7},{"v":41},{"v":83}]}'];

$json.= join(",", $json_rows);

$json .= ']
}';

echo $json;