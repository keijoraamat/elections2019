<?php
require_once '../connect_to_db.php';

$state = filter_input(INPUT_GET, 'state', FILTER_VALIDATE_INT);

$sql_query = "SELECT `valimisnimekiri`, COUNT(`id`) as 'kandidaate', `haridus`  FROM `kandidaadid` GROUP BY `haridus`, `valimisnimekiri`  
ORDER BY `kandidaadid`.`valimisnimekiri` ASC";

$database_response = mysqli_query($connect, $sql_query);

$candidates_in_listing = [];
while ( $row = mysqli_fetch_array($database_response, MYSQLI_ASSOC)) {
    $candidates_in_listing[] = $row;
}
function getEducationLevel ($value) {
    if (strlen($value) == 14) {
        return 0;
    }
    elseif (strlen($value) > 17 ) {
        return 2;
    }
    elseif ((strlen($value) < 13) && (substr($value, 0, 1) == 'P')) {
        return 1;
    }
    elseif ((strlen($value) < 13) && (substr($value, 0, 1) == 'K')) {
        return 3;
    }
}
function getCandidatesAmount ($value) {
    return (int)$value;
}
$listings = [];
foreach ($candidates_in_listing as $key => $value) {
    $educationLevel = 0;
    $candidatesAmount = 0;
    $education_counter = [0, 0, 0, 0];
    $listing = "";
    foreach ($value as $k => $v) {
        if ($k == "haridus") {
            $educationLevel = getEducationLevel($v);
        }
        if ($k == "valimisnimekiri") {
            $listing = $v;
        }
        if ($k == "kandidaate") {
            $candidatesAmount = getCandidatesAmount($v);
        }
    }

    if (empty($listings[$listing])) {
        $listings[$listing]=$education_counter;
    }
    $listings[$listing][$educationLevel] = $candidatesAmount;

}
#print_r($listings);
$json = '{
"cols": [
        {"id":"","label":"Valimisnimekiri","pattern":"","type":"string"},
        {"id":"","label":"Põhihariduseta","pattern":"","type":"number"},
        {"id":"","label":"Põhiharidusega","pattern":"","type":"number"},
        {"id":"","label":"Kesk- ja/või keskeriharidusga","pattern":"","type":"number"},
        {"id":"","label":"Kõrgharidusega","pattern":"","type":"number"}
      ],
  "rows": [';

$json_rows = [];
if (!empty($listings)) {
    foreach ($listings as $key => $value) {
        $json_rows[] = '{"c":[{"v":"'.$key.'"},{"v":'.$value[0].'},{"v":'.$value[1].'},{"v":'.$value[2].'},{"v":'.$value[3].'}]}';
    }
}

$json.= join(",", $json_rows);

$json .= ']
}';

echo $json;