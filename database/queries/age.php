<?php
require_once '../connect_to_db.php';
const ELECTIONDAY = '03.03.2019';
$electionDate = new DateTime(ELECTIONDAY);
$state = filter_input(INPUT_GET, 'state', FILTER_VALIDATE_INT);
$sortArgument = filter_input(INPUT_GET, 'sortBy', FILTER_VALIDATE_INT);
$sortBy = "`valimisnimekiri`";
$label = "Valimisnimekiri";

if ($sortArgument == 1) {
    $sortBy = "`haridus`";
    $label = "Haridustase";
}
$sql_query = "SELECT `sunniaeg`," . $sortBy . "as 'sortBy' FROM `kandidaadid`";


$database_response = mysqli_query($connect, $sql_query);

$candidates_in_listing = [];
while ( $row = mysqli_fetch_array($database_response, MYSQLI_ASSOC)) {
    $candidates_in_listing[] = $row;
}

$json = '{
"cols": [
        {"id":"","label":"'.$label.'","pattern":"","type":"string"},
        {"id":"","label":"mediaan vanus","pattern":"","type":"number"},
        {"id":"","label":"keskmine vanus","pattern":"","type":"number"},
        {"id":"","label":"kandidaate kokku","pattern":"","type":"number"}
      ],
  "rows": [';

$json_rows = [];

function calculateAgeOnElectionDay ($birthDateString, $electionDate)
{
    $birthDate = new DateTime($birthDateString);
    $difference = $birthDate->diff($electionDate);
    $ageOnElection = $difference->y;
    return $ageOnElection;
}

function calculateMedianAge ($ages) {
    $amount = count($ages);
    if ($amount == 0) return 0;

    $middle_index = floor($amount / 2);
    sort($ages, SORT_NUMERIC);
    $medianAge = $ages[$middle_index]; // assume an odd # of items

    // Handle the even case by averaging the middle 2 items
    if ($amount % 2 == 0)
        $medianAge = ($medianAge + $ages[$middle_index - 1]) / 2;

    return $medianAge;
}

function calculateMeanAge ($ages) {
    $amount = count($ages);
    $sumOfAges = array_sum($ages);
    return floor($sumOfAges/$amount);
}

// sort candidates by $sortBy into assocArray ->
// calculate age on election day per candidate

if (!empty($candidates_in_listing)) {
    $sorted_list = [];
    $counting_argument = [];
    $ages = [];
    # Put ages into arrays according to sortBy value
    foreach ($candidates_in_listing as $key => $value) {
        $counting_argument = $value['sortBy'];
        $age = calculateAgeOnElectionDay($value['sunniaeg'], $electionDate);
        if (empty($sorted_list[$counting_argument])) {
            $sorted_list[$counting_argument] = $ages;
        }
        array_push($sorted_list[$counting_argument], $age);
    }

    foreach ($sorted_list as $item => $value){
        #echo $item;
        $medianAge = calculateMedianAge($value);
        #echo " ".$medianAge;
        $meanAge = calculateMeanAge($value);
        #echo " ".$meanAge;
        $candidatesAmount = count($value);
        #echo " ".$candidatesAmount;
        #echo "<br>";
        $json_rows[] = '{"c":[{"v":"' . $item . '"},{"v":' . $medianAge . '},{"v":'.$meanAge.'},{"v":'.$candidatesAmount.'}]}';
    }
//    echo "mediaan vanus: ".$medianAge." kesmine vanus: ".$meanAge;
    #$json_rows[] = '{"c":[{"v":"' . $value['name'] . '","f":null},{"v":' . $value['value'] . ',"f":null}]}';
}

$json.= join(",", $json_rows);

$json .= ']
}';

echo $json;
