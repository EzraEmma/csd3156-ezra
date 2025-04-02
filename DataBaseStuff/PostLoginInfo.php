<?php
include "dbinfo.inc";
header("Access-Control-Allow-Origin: * " );
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/x-www-form-urlencoded");

$Username = $_POST['Username'] ?? null;     // inventoryID
$PW = $_POST['PW'] ?? null;                 //inventoryName

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
    $stmt = $connection->prepare("
        SELECT Account.AccountID FROM Account WHERE Account.Username = ? AND Account.Password = ?
    ");
    $_numberSold = 0;
    mysqli_stmt_bind_param($stmt, 'ss', $Username, $PW);
    $stmt->execute();
    $result = $stmt->get_result();
    if(mysqli_num_rows($result) == 0){
        echo json_encode([
            "success" => false,
            "message" => "failed login.",
        ]);
    }else{
        $query_data = mysqli_fetch_row($result);

        echo json_encode([
            "success" => true,
            "message" => "Login Successful.",
            "ID" => $query_data
        ]);
    }
 }
?>
