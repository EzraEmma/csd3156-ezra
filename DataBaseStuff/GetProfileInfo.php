<?php
include "dbinfo.inc";
header("Access-Control-Allow-Origin: * " );
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");

$ID = isset($_GET['ID']) ? $_GET['ID'] : null;

$connection = mysqli_connect(hostname: DB_SERVER, username: DB_USERNAME, password: DB_PASSWORD);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " .mysqli_onnecterror();
 }
 $database = mysqli_select_db(mysql: $connection, database: DB_DATABASE);


function TableExists($tableName, $connection, $dbName) {
    $t = mysqli_real_escape_string($connection, $tableName);
    $d = mysqli_real_escape_string($connection, $dbName);

    $checktable = mysqli_query($connection,
            "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

    if(mysqli_num_rows($checktable) > 0) return true;

    return false;
 }
 if(TableExists("Account",$connection,DB_DATABASE)){

    $sql = "SELECT
    Account.Username,
    Account.ProfileImage,
     FROM Account  WHERE  Account.AccountID = ? ";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $ID);
    $stmt->execute();
    $result = $stmt->get_result();

    $response = [];

    while($query_data = mysqli_fetch_row($result)) {
        $data = [

                    "Name" => $query_data[0],
                    "PFP" => $query_data[1],
        ];
        $response[] = $data;

    }
    echo json_encode($response);
 }
?>