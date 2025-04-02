<?php
/**
 * @file    EC2Metadata.php
 * @author  Emma Natalie Soh
 * @par     Email: 2202191\@sit.singaporetech.edu.sg
 * @par     Course: CSD3156 Mobile and Cloud Computing
 * @par     Project: Cloud Computing Project
 *
 * @brief   This file defines a way to obtain the metadata of the EC2 PHP
 *          instance.
 */

include "EC2MetadataInclude.php";
// the security group will restrict access anyway
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");

// Return the current timestamp as a JSON response
$response = [
    "instance_id" => EC2_INSTANCE_ID,
    "private_ipv4" => EC2_PRIVATE_IP,
    "public_ipv4" => EC2_PUBLIC_IP,
    "instance_type" => EC2_INSTANCE_TYPE,
    "availability_zone" => EC2_AVAILABILITY_ZONE
];

echo json_encode($response);
?>