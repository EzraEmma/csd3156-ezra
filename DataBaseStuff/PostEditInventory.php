<?php
/**
 * @file    PostEditInventory.php
 * @author  Emma Natalie Soh
 * @par     Email: 2202191\@sit.singaporetech.edu.sg
 * @par     Course: CSD3156 Mobile and Cloud Computing
 * @par     Project: Cloud Computing Project
 *
 * @brief   This file defines a way to drop a entry in the inventory table.
 */

include "dbinfo.inc";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Content-Type: application/x-www-form-urlencoded");

$InventoryID = $_POST['ID'];                    // inventoryID
$Name = $_POST['Name'] ?? null;                 //inventoryName
$Desc = $_POST['Desc'] ?? null;                 //Description
$Price = $_POST['Price'] ?? null;               //Price
$ImagePath = $_POST['ImagePath'] ?? null;       //ImagePath
$Quant = $_POST['Quant'] ?? null;               //Quantity

// set up the connection
$connection = mysqli_connect(hostname: DB_SERVER, username: DB_USERNAME, password: DB_PASSWORD);
if (mysqli_connect_errno()) {
   echo "Failed to connect to MySQL: " . mysqli_onnecterror();
}
$database = mysqli_select_db(mysql: $connection, database: DB_DATABASE);

function TableExists($tableName, $connection, $dbName)
{
   $t = mysqli_real_escape_string($connection, $tableName);
   $d = mysqli_real_escape_string($connection, $dbName);

   $checktable = mysqli_query(
      $connection,
      "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'"
   );

   if (mysqli_num_rows($checktable) > 0) return true;

   return false;
}

// Prepare the SQL query using placeholders
$query = "UPDATE Inventory SET `Name`=?, `Description`=?, Price=?, `Image`=?, NumberInStock=? WHERE InventoryID=?";

$_name = $Name;
$_description = $Desc;
$_price = $Price;
$_imagePath = $ImagePath;
$_numberInStock = $Quant;
$_inventoryID = $InventoryID;

if ($stmt = mysqli_prepare($connection, $query)) {
   // Bind the parameters to the placeholders
   mysqli_stmt_bind_param(
      $stmt,
      'ssisii',
      $_name,
      $_description,
      $_price,
      $_imagePath,
      $_numberInStock,
      $_inventoryID
   );

   if (mysqli_stmt_execute($stmt)) {
      echo json_encode([
         "success" => true,
         "message" => "Edited inventory successfully.",
      ]);
   } else {
      echo json_encode([
         "success" => false,
         "message" => "Inventory not edited.",
      ]);
   }
}

?>