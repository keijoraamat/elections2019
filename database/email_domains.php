<?php
require_once '../connect_to_db.php';

function get_email_domains($sqlQuery, $connect)
{
    $sqlResponse = mysqli_query($connect, $sqlQuery);

    $email_domains = [];
    $emails = 0;
    while ($queryElement = mysqli_fetch_array($sqlResponse, MYSQLI_ASSOC)) {
        preg_match("/[^@]+$/", $queryElement["email"], $regexResult);
        $domain = strtolower($regexResult[0]);
        $domain = str_replace(' ', '', $domain);
        $email_domains[$domain] += 1;
        $emails += 1;
    }

    $email_domains = array_flip($email_domains);
    return $email_domains;
};


