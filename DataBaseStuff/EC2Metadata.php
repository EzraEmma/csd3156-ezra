<?php
include "dbinfo.inc";
header("Access-Control-Allow-Origin:" . DB_ACCESS_ALLOW_ORIGIN);
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");

$instance_id = file_get_contents(
    DB_ACCESS_ALLOW_ORIGIN . "/latest/meta-data/instance-id"
);
$public_ipv4 = file_get_contents(
    DB_ACCESS_ALLOW_ORIGIN . "/latest/meta-data/public-ipv4"
);
$private_ipv4 = file_get_contents(
    DB_ACCESS_ALLOW_ORIGIN . "/latest/meta-data/local-ipv4"
);
$hostname = file_get_contents(
    DB_ACCESS_ALLOW_ORIGIN . "/latest/meta-data/hostname"
);

// Return the current timestamp as a JSON response
$response = [
    "instance_id" => $instance_id,
    "public_ipv4" => $public_ipv4,
    "private_ipv4" => $private_ipv4,
    "hostname" => $hostname
];

echo json_encode($response);
?>