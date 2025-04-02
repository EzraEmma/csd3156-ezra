<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");

// Return the current timestamp as a JSON response
$response = ["timestamp" => date("Y-m-d H:i:s")];

echo json_encode($response);
?>