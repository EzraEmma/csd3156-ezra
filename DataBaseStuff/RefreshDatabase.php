<!--
 * @file    RefreshDatabase.php
 * @author  Goh Jun Lin Wayne
 * @par     Email: 2200628\@sit.singaporetech.edu.sg
 * @par     Course: CSD3156 Mobile and Cloud Computing
 * @par     Project: Cloud Computing Project
 *
 * @brief   This file creates a php page to create and populate a database
 *
 * This database will be displayed in a format so it is easily viewed
-->
<?php include "dbinfo.inc"; ?>
<html>

<body>
   <h1>RefreshDatabsePage</h1>
   <?php
   $connection = mysqli_connect(hostname: DB_SERVER, username: DB_USERNAME, password: DB_PASSWORD);
   if (mysqli_connect_errno()) {
      echo "Failed to connect to MySQL: " . mysqli_onnecterror();
   }
   $database = mysqli_select_db(mysql: $connection, database: DB_DATABASE);

   if (isset($_POST['loadTables'])) {
      VerifyTables(connection: $connection, dbName: DB_DATABASE);
      // Redirect to avoid resubmission on page refresh
      header("Location: " . $_SERVER['PHP_SELF']);
      exit; // Stop further script execution
   }
   if (isset($_POST['dropTables'])) {
      DropTables(connection: $connection, dbName: DB_DATABASE);
      // Redirect to avoid resubmission on page refresh
      header("Location: " . $_SERVER['PHP_SELF']);
      exit; // Stop further script execution
   }
   if (isset($_POST['loadData'])) {
      LoadData(connection: $connection, dbName: DB_DATABASE);
      // Redirect to avoid resubmission on page refresh
      header("Location: " . $_SERVER['PHP_SELF']);
      exit; // Stop further script execution
   }

   if (isset($_POST['testConfirm'])) {
      testConfirm(connection: $connection, dbName: DB_DATABASE);
      // Redirect to avoid resubmission on page refresh
      header("Location: " . $_SERVER['PHP_SELF']);
      exit; // Stop further script execution
   }
   ?>

   <form method="post">
      <input type="submit" name="loadTables" value="Must Load Table uggggh" />

      <input type="submit" name="dropTables" value="Must Drop Tables Ahhhhh" />

      <input type="submit" name="loadData" value="Load Data Yay" />

      <input type="submit" name="testConfirm" value="test Data Yeahhh" />
   </form>

   </form>

   <!-- Display Account table data -->
   <h1>Account Table</h1>
   <table border="1" cellpadding="2" cellspacing="2">
      <td>
         AccountID
      </td>
      <td>
         ProfileImage
      </td>
      <td>
         UserName
      </td>
      <td>
         PassWord
      </td>

      <?php
      if (TableExists("Account", $connection, DB_DATABASE)) {
         $result = mysqli_query(mysql: $connection, query: "SELECT * From Account");
         while ($query_data = mysqli_fetch_row($result)) {

            $imageData = $query_data[1];


            echo "<tr>";
            //account id
            echo "<td>", $query_data[0], "</td>";
            //profile image
            if ($imageData == null) {
               echo "<td> NULL </td>";
            } else {
               echo "<td><img src='" . $imageData . "' alt='Image' width='100' height='100'></td>";
            }
            //username
            echo "<td>", $query_data[2], "</td>";
            //password
            echo "<td>", $query_data[3], "</td>";
            echo "</tr>";
         }

         mysqli_free_result($result);
      } else {
         echo "<h1>";
         echo "no table";
         echo "</h1>";
      }
      ?>
   </table>
   <!-- Display Inventory table data -->
   <h1>Inventory Table</h1>
   <table border="1" cellpadding="2" cellspacing="2">
      <td>
         InventoryID
      </td>
      <td>
         Name
      </td>
      <td>
         Description
      </td>
      <td>
         Price
      </td>
      <td>
         Image
      </td>
      <td>
         Number in stock
      </td>
      <td>
         Seller ID
      </td>
      <td>
         Number Sold
      </td>

      <?php
      if (TableExists("Inventory", $connection, DB_DATABASE)) {
         $result = mysqli_query(mysql: $connection, query: "SELECT * From Inventory");
         while ($query_data = mysqli_fetch_row($result)) {

            $imageData = $query_data[4];

            echo "<tr>";
            echo "<td>", $query_data[0], "</td>",
               "<td>", $query_data[1], "</td>",
               "<td>", $query_data[2], "</td>",
               "<td>", $query_data[3], "</td>";
            if ($imageData == null) {
               echo "<td>NULL</td>";
            } else {
               echo "<td><img src='" . $imageData . "' alt='Image' width='100' height='100'></td>";
            }
            echo "<td>", $query_data[5], "</td>",
               "<td>", $query_data[6], "</td>",
               "<td>", $query_data[7], "</td>";
            echo "</tr>";
         }

         mysqli_free_result($result);
      } else {
         echo "<h1>";
         echo "no table";
         echo "</h1>";
      }

      ?>
   </table>
   <!-- Display Orders table data -->
   <h1>Orders Table</h1>
   <table border="1" cellpadding="2" cellspacing="2">
      <td>
         OrderID
      </td>
      <td>
         CustomerID
      </td>
      <td>
         SellerID
      </td>
      <td>
         InventoryID
      </td>
      <td>
         Quantity
      </td>
      <td>
         OrderGroupID
      </td>
      <td>
         Timestamp
      </td>
      <td>
         OrderConfirmed
      </td>
      <?php
      if (TableExists("Orders", $connection, DB_DATABASE)) {
         $result = mysqli_query(mysql: $connection, query: "SELECT * From Orders");
         while ($query_data = mysqli_fetch_row($result)) {
            echo "<tr>";
            echo "<td>", $query_data[0], "</td>",
               "<td>", $query_data[1], "</td>",
               "<td>", $query_data[2], "</td>",
               "<td>", $query_data[3], "</td>",
               "<td>", $query_data[4], "</td>",
               "<td>", $query_data[5], "</td>",
               "<td>", $query_data[6], "</td>",
               "<td>", $query_data[7], "</td>";
            echo "</tr>";
         }

         mysqli_free_result($result);
      } else {
         echo "<h1>";
         echo "no table";
         echo "</h1>";
      }
      ?>
   </table>
   <h1>Order Groups Table</h1>
   <table border="1" cellpadding="2" cellspacing="2">
      <?php
      if (TableExists("Orders", $connection, DB_DATABASE) && TableExists("Inventory", $connection, DB_DATABASE)) {
         $result = mysqli_query(mysql: $connection, query: "SELECT Orders.OrderConfirmed,Orders.OrderGroupID,Orders.Quantity,Orders.CustomerID,Inventory.Name,Account.UserName,Inventory.Price
                From Orders INNER JOIN Inventory ON Orders.InventoryID = Inventory.InventoryID INNER JOIN Account ON Inventory.SellerID = Account.AccountID ORDER BY Orders.CustomerID ASC;");

         $previousGroup = null;
         // Loop through the result set and display the order and inventory details
         while ($row = mysqli_fetch_assoc($result)) {
            // Check if the order belongs to a new order group
            if ($previousGroup != $row['OrderGroupID']) {
               // New group, display group info
               if ($previousGroup !== null) {
                  echo "</tr>"; // Close the previous group
                  echo "</table>";
               }
               echo "<p> customerID :";
               echo $row['CustomerID'];
               echo "<p>";
               echo "<p> OrderGroup :";
               echo $row['OrderGroupID'];
               echo "<p>";
               echo "<table border= '1' cellpadding='2' cellspacing='2' style='margin-bottom: 20px;'>";
               echo "<tr>";
               echo "<tr>";
               echo "<td>";
               echo "Inventory Name";
               echo "</td>";
               echo "<td>";
               echo "Quantity";
               echo "</td>";
               echo "<td>";
               echo "Price";
               echo "</td>";
               echo "<td>";
               echo "SellerName";
               echo "</td>";
               echo "<td>";
               echo "Order Confirmation Status";
               echo "</td>";
               $previousGroup = $row['OrderGroupID'];
            }

            echo "<tr>";
            echo "<td>", $row['Name'], "</td>";
            echo "<td>", $row['Quantity'], "</td>";
            echo "<td>", $row['Price'], "</td>";
            echo "<td>", $row['UserName'], "</td>";
            echo "<td>", $row['OrderConfirmed'], "</td>";
            echo "</tr>";
         }
      }
      ?>

   </table>
   <!-- Clean up. -->
   <?php
   mysqli_close($connection);

   ?>

</body>

</html>


<?php
/**
 * Verifies if the required tables exist and creates them if they do not.
 *
 * This function checks the existence of three essential tables (`Account`, `Inventory`,
 * and `Orders`) in the database. If any of these tables do not exist, the function
 * triggers the creation of the missing tables using the respective creation functions.
 * It also sets the global MySQL variable `max_allowed_packet` to 64MB to handle larger queries.
 *
 * @param mysqli $_connection The database connection object.
 * @param string $dbName The name of the database to check the tables in.
 *
 * @return void This function does not return any value. It performs table existence checks
 *              and creates missing tables.
 */
function VerifyTables($connection, $dbName)
{
   //check whether account table exists
   //if doesnt exist then create
   if (!TableExists(tableName: "Account", connection: $connection, dbName: $dbName)) {
      CreateAccountTable($connection);
   }
   //check whether inventory table exists
   if (!TableExists(tableName: "Inventory", connection: $connection, dbName: $dbName)) {
      CreateInventoryTable($connection);
   }
   //check whether inventry table exists
   if (!TableExists(tableName: "Orders", connection: $connection, dbName: $dbName)) {
      CreateOrdersTable($connection);
   }
   $connection->query("SET GLOBAL max_allowed_packet=64*1024*1024");
}
/**
 * Checks if a table exists in the specified database.
 *
 * This function queries the `information_schema` to check if a given table exists
 * in the provided database. It uses `SELECT` to check for the table's existence
 * and returns a boolean value based on the result.
 *
 * @param string $tableName The name of the table to check.
 * @param mysqli $_connection The database connection object.
 * @param string $dbName The name of the database to check for the table in.
 *
 * @return bool Returns `true` if the table exists, `false` otherwise.
 */
function TableExists($tableName, $connection, $dbName)
{
   $t = mysqli_real_escape_string($connection, $tableName);
   $d = mysqli_real_escape_string($connection, $dbName);

   $checktable = mysqli_query(
      $connection,
      "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'"
   );

   if (mysqli_num_rows($checktable) > 0)
      return true;

   return false;
}
/**
 * Creates the `Account` table in the database.
 *
 * This function creates a table named `Account` with the following columns:
 * - `AccountID`: Primary key, auto-increment integer.
 * - `ProfileImage`: Stores the profile image as a VARCHAR.
 * - `Username`: A `VARCHAR(20)` field for the username.
 * - `Password`: A `VARCHAR(20)` field for the user's password.
 *
 * If the table creation fails, an error message is displayed.
 *
 * @param mysqli $_connection The database connection object.
 *
 * @return void This function does not return any value. It creates the `Account` table
 *              in the database.
 */
function CreateAccountTable($connection)
{
   $query = "
             CREATE TABLE Account (
                AccountID INT(11) PRIMARY KEY AUTO_INCREMENT ,
                ProfileImage VARCHAR(400),
                Username VARCHAR(20) NOT NULL,
                Password VARCHAR(20) NOT NULL
             )
          ";

   if (!mysqli_query($connection, $query))
      echo ("<p>Error creating table.</p>");
}
/**
 * Creates the `Inventory` table in the database.
 *
 * This function creates a table named `Inventory` with the following columns:
 * - `InventoryID`: Primary key, auto-increment integer.
 * - `Name`: The name of the inventory item (`VARCHAR(255)`).
 * - `Description`: A `TEXT` field for the item description.
 * - `Price`: The price of the item (integer).
 * - `Image`: A VARCHAR to store the link to the image of the inventory item.
 * - `NumberInStock`: The number of items available in stock.
 * - `SellerID`: A foreign key referencing the `Account` table's `AccountID`.
 * - `NumberSold`: The number of items sold.
 *
 * If the table creation fails, an error message is displayed.
 *
 * @param mysqli $_connection The database connection object.
 *
 * @return void This function does not return any value. It creates the `Inventory` table
 *              in the database.
 */
function CreateInventoryTable($connection)
{
   $query = "
            CREATE TABLE Inventory (
               InventoryID INT(11) PRIMARY KEY AUTO_INCREMENT ,
               Name VARCHAR(255) NOT NULL,
               Description TEXT,
               Price INT NOT NULL,
               Image VARCHAR(400),
               NumberInStock INT NOT NULL,
               SellerID INT(11) NOT NULL,
               NumberSold INT NOT NULL,
               FOREIGN KEY (SellerID) REFERENCES Account(AccountID)
            )
         ";

   if (!mysqli_query($connection, $query))
      echo ("<p>Error creating table.</p>");
}
/**
 * Creates the `Orders` table in the database.
 *
 * This function creates a table named `Orders` with the following columns:
 * - `OrderID`: Primary key, auto-increment integer.
 * - `CustomerID`: A foreign key referencing the `Account` table's `AccountID`.
 * - `SellerID`: A foreign key referencing the `Account` table's `AccountID`.
 * - `InventoryID`: A foreign key referencing the `Inventory` table's `InventoryID`.
 * - `Quantity`: The quantity of items ordered.
 * - `OrderGroupID`: A unique identifier for the order group.
 * - `Timestamp`: A timestamp for when the order was created.
 *
 * If the table creation fails, an error message is displayed.
 *
 * @param mysqli $_connection The database connection object.
 *
 * @return void This function does not return any value. It creates the `Orders` table
 *              in the database.

*/
function CreateOrdersTable($connection)
{
   $query = "
            CREATE TABLE Orders (
               OrderID INT(11) PRIMARY KEY AUTO_INCREMENT ,
               CustomerID INT(11) NOT NULL,
               SellerID INT(11) NOT NULL,
               InventoryID INT(11) NOT NULL,
               Quantity INT(10) NOT NULL,
               OrderGroupID INT(11) NOT NULL,
               Timestamp TIMESTAMP NOT NULL,
               OrderConfirmed BOOL NOT NULL,
               FOREIGN KEY (CustomerID) REFERENCES Account(AccountID),
               FOREIGN KEY (SellerID) REFERENCES Account(AccountID),
               FOREIGN KEY (InventoryID) REFERENCES Inventory(InventoryID)
            )
         ";

   if (!mysqli_query($connection, $query))
      echo ("<p>Error creating table.</p>");
}
/**
 * Drops specified tables from the database.
 *
 * This function removes the tables `Orders`, `Inventory`, and `Account` from the database,
 * if they exist. It uses the `DROP TABLE IF EXISTS` SQL command to ensure that no error
 * occurs if the tables do not exist. This function is useful for database resets or
 * cleaning up the schema.
 *
 * @param mysqli $_connection The database connection object.
 * @param string $dbName The name of the database (not directly used in the function
 *                       but included for future flexibility or modifications).
 *
 * @return void This function does not return any value. It performs the operation
 *              to drop the specified tables.
 */
function DropTables($connection, $dbName): void
{
   mysqli_query($connection, "DROP TABLE IF EXISTS Orders;");
   mysqli_query($connection, "DROP TABLE IF EXISTS Inventory;");
   mysqli_query($connection, "DROP TABLE IF EXISTS Account;");
}
/**
 * Loads sample account and inventory data into the database.
 *
 * This function populates the database with initial data by creating user accounts
 * and adding inventory items, specifically sofas. It also creates orders for various
 * inventory items and associates them with customers and sellers.
 * The data is loaded using predefined profile images and inventory descriptions.
 *
 * The function performs the following tasks:
 * 1. Loads sample account data with images and user information.
 * 2. Loads sofa inventory data, including names, descriptions, prices, stock, and images.
 * 3. Creates orders for different inventory items and stores them in the Orders table.
 *
 * @param mysqli $_connection The database connection object.
 * @param string $dbName The name of the database being used (not utilized in the function but included as a parameter).
 *
 * @return void This function does not return any value. It performs multiple insert operations to populate the database.
 */
function LoadData($connection, $dbName): void
{

   $profileImagelinks = array(
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/profile/profile00.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/profile/profile01.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/profile/profile02.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/profile/profile03.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/profile/profile04.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/profile/profile05.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/profile/profile06.jpg"
   );

   LoadAccount($connection, $profileImagelinks[0], "Emma Natalie Soh", "password");
   LoadAccount($connection, $profileImagelinks[6], "sponge Inventory", "password");
   LoadAccount($connection, $profileImagelinks[1], "Goh Jun Lin, Wayne", "password");
   LoadAccount($connection, $profileImagelinks[2], "Guo Chen", "password");
   LoadAccount($connection, $profileImagelinks[3], "Kenzie Lim", "password");
   LoadAccount($connection, $profileImagelinks[4], "Lee Cjeng, Jacob", "password");
   LoadAccount($connection, $profileImagelinks[5], "Trina Lim", "password");

   $sofaImageFileLoc = "sofa/";
   $inventoryText = "inventory";
   $SofaName = "sofa";
   $additionalFormatting = "0";

   $sofaImageLink = array(
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/sofa/sofa00.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/sofa/sofa01.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/sofa/sofa02.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/sofa/sofa03.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/sofa/sofa04.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/sofa/sofa05.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/sofa/sofa06.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/sofa/sofa07.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/sofa/sofa08.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/sofa/sofa09.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/sofa/sofa10.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/sofa/sofa11.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/sofa/sofa12.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/sofa/sofa13.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/sofa/sofa14.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/sofa/sofa15.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/sofa/sofa16.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/sofa/sofa17.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/sofa/sofa18.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/sofa/sofa19.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/sofa/sofa20.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/sofa/sofa21.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/sofa/sofa22.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/sofa/sofa23.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/sofa/sofa24.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/sofa/sofa25.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/sofa/sofa26.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/sofa/sofa27.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/sofa/sofa28.jpg",
      "https://raw.githubusercontent.com/EzraEmma/csd3156-ezra/refs/heads/dev/DataBaseStuff/sofa/sofa29.jpg"
   );

   $sofaDesc = array(
      "A modern blue velvet sofa with a sleek silhouette, ideal for contemporary living rooms.",
      "Minimalist gray fabric sofa with clean lines, perfect for a sophisticated and neutral décor.",
      "Compact mustard-yellow loveseat with a tufted back, great for small apartments or reading nooks.",
      "Cozy white and beige sofa with colorful cushions, adding warmth and comfort to any space.",
      "Vibrant yellow mid-century sofa with wooden legs, bringing energy and style to modern interiors.",
      "Industrial-style black leather sofa with metal legs, ideal for loft spaces and contemporary homes.",
      "Classic brown fabric sofa with plush cushions, offering a timeless and inviting appeal.",
      "Elegant blue velvet sofa with a tufted back, perfect for a luxurious living space.",
      "Simple and modern white sofa, a versatile choice for minimalist and Scandinavian interiors.",
      "Unique green modular floor sofa, great for casual lounging and creative spaces.",
      "Chic pink accent chair with a rounded back, adding a touch of elegance to any corner.",
      "Stylish blue and yellow sectional sofa, offering both comfort and modern aesthetics.",
      "Futuristic gray curved sofa, making a statement in contemporary interiors.",
      "Warm-toned brown and red cushioned sofa, creating a cozy and inviting atmosphere.",
      "Vintage-inspired green velvet sofa with rolled arms, exuding classic elegance.",
      "Sunlit cream-colored sofa with a sleek design, perfect for a bright and airy living room.",
      "Bold orange leather sofa with a minimalist frame, ideal for a modern and stylish home.",
      "Light gray fabric sofa with a contemporary touch, fitting seamlessly into any décor.",
      "Spacious L-shaped sofa in soft gray, offering both comfort and functionality for families.",
      "Luxurious golden draped sofa setting, ideal for elegant and dramatic interiors.",
      "Classic blue sofa with a tufted back and rolled arms, perfect for a refined look.",
      "Rich brown leather Chesterfield sofa, a timeless piece for sophisticated spaces.",
      "Mid-century modern caramel leather sofa with wooden legs, combining retro and contemporary charm.",
      "Deep green velvet sofa with a streamlined design, bringing a touch of luxury to any room.",
      "Elegant dark gray sectional sofa with plush seating, ideal for modern homes.",
      "Cozy beige sofa set in a stylish living room, creating a warm and inviting feel.",
      "Natural wood and fabric sofa with a bohemian vibe, perfect for relaxed interiors.",
      "Dark navy-blue velvet sofa with gold accents, adding a luxurious touch to any setting.",
      "Soft white fabric sofa with a cozy throw, great for a minimalist and serene look.",
      "Neutral beige sofa with modern cushions, a versatile choice for any home style."
   );

   $sofaNames = array(
      "Coastal Blue Velvet Sofa",
      "Urban Gray Minimalist Sofa",
      "Sunbeam Mustard Loveseat",
      "Harmony Two-Tone Cushion Sofa",
      "Zest Yellow Mid-Century Sofa",
      "Metro Black Leather Lounge",
      "Amber Classic Fabric Sofa",
      "Royal Blue Tufted Sofa",
      "Nordic White Modern Sofa",
      "Olive Plush Floor Lounger",
      "Blush Pink Accent Chair",
      "Skyline Sectional Sofa",
      "Galaxy Gray Statement Sofa",
      "Rustic Red & Brown Sofa",
      "Emerald Roll-Arm Velvet Sofa",
      "Cream Sunlit Modern Sofa",
      "Tangerine Leather Statement Sofa",
      "Cloud Gray Contemporary Sofa",
      "Family Comfort L-Shaped Sofa",
      "Golden Luxe Draped Sofa",
      "Vintage Blue Tufted Sofa",
      "Regal Chesterfield Leather Sofa",
      "Cognac Mid-Century Sofa",
      "Forest Green Velvet Lounge",
      "Slate Gray Sectional Sofa",
      "Warm Beige Living Set",
      "Boho Natural Frame Sofa",
      "Navy Luxe Velvet Sofa",
      "Ivory Minimalist Sofa",
      "Neutral Chic Cushion Sofa"
   );


   for ($i = 0; $i < 30; ++$i) {
      if ($i == 10) {
         $additionalFormatting = "";
      }
      LoadInventory($connection, $sofaNames[$i], $sofaDesc[$i], 10.00, $sofaImageLink[$i], 10, 0, 2);
   }

   //LoadInventory($connection,"inventory1","description",10.00,"test_image.jpg",10,0,2);
   //LoadInventory($connection,"inventory2","description",10.00,"test_image.jpg",10,0,2);
   //LoadAccount($connection,"test_image.jpg","customer2","password");


   AddOrders($connection, 2, 1, 1, 1, 1, '2025-03-30 12:00:00');
   AddOrders($connection, 2, 1, 3, 1, 1, '2025-03-30 12:00:00');
   AddOrders($connection, 3, 1, 1, 1, 2, '2025-03-30 12:01:00');
   AddOrders($connection, 3, 1, 2, 1, 2, '2025-03-30 12:01:00');
   AddOrders($connection, 4, 1, 4, 1, 3, '2025-03-30 12:02:00');
   AddOrders($connection, 4, 1, 5, 1, 3, '2025-03-30 12:02:00');
   AddOrders($connection, 3, 1, 4, 1, 4, '2025-03-30 12:03:00');
   AddOrders($connection, 3, 1, 5, 1, 4, '2025-03-30 12:03:00');
   AddOrders($connection, 4, 1, 10, 1, 5, '2025-03-30 12:04:00');
   AddOrders($connection, 4, 1, 15, 1, 5, '2025-03-30 12:04:00');

   LoadOrders($connection, 4, 1, 15,10,6, '2025-03-30 12:04:00', false);
}

function testConfirm($connection, $dbName) : void{
   ConfirmOrderGroup($connection,6);
}
/**
 * Loads the account into database
 *
 * This function takes in the database connection, profile image path,
 * user name, and password as parameters and Adds the account data.
 *
 * @param mysqli $connection The database connection object.
 * @param string $_profileImagePath The file path to the user's profile image.
 * @param string $_userName The username for the account.
 * @param string $_password The password associated with the account.
 *
 * @return void This function does not return any value.
 */
function LoadAccount($connection, $_profileImagePath, $_userName, $_password): void
{

   $query = "INSERT INTO Account (ProfileImage,Username,`Password`) VALUES ( ?,?,?);";

   if ($stmt = mysqli_prepare($connection, $query)) {
      // Bind the parameters to the placeholders
      mysqli_stmt_bind_param($stmt, 'sss', $_profileImagePath, $_userName, $_password);

      // Execute the statement
      if (mysqli_stmt_execute($stmt)) {
         echo "<p>Data added successfully!</p>";
      } else {
         echo "<p>Error executing the query: " . mysqli_error($connection) . "</p>";
      }

      // Close the prepared statement
      mysqli_stmt_close($stmt);
   }
}
/**
 * Loads inventory data into the database.
 *
 * This function takes in inventory item details such as name, description, price, image path,
 * number in stock, number sold, and seller ID. It checks if the image exists and, if so, stores
 * the image in the database along with the rest of the inventory information. If no image is provided,
 * it inserts the inventory data without an image.
 *
 * @param mysqli $_connection The database connection object.
 * @param string $_name The name of the inventory item.
 * @param string $_description The description of the inventory item.
 * @param float $_price The price of the inventory item.
 * @param string $_imagePath The file path to the inventory item's image.
 * @param int $_numberInStock The number of items in stock.
 * @param int $_numberSold The number of items sold.
 * @param int $_sellerID The ID of the seller associated with the inventory item.
 *
 * @return void This function does not return any value. It directly inserts data into the database.
 */
function LoadInventory($_connection, $_name, $_description, $_price, $_imagePath, $_numberInStock, $_numberSold, $_sellerID)
{

   // Prepare the SQL query using placeholders
   $query = "INSERT INTO Inventory (`Name`, `Description`, Price, `Image`, NumberInStock, SellerID, NumberSold)
      VALUES ( ?, ?, ?, ?, ?, ?, ?)";

   if ($stmt = mysqli_prepare($_connection, $query)) {
      // Bind the parameters to the placeholders
      mysqli_stmt_bind_param($stmt, 'ssdssis', $_name, $_description, $_price, $_imagePath, $_numberInStock, $_sellerID, $_numberSold);

      // Execute the statement
      if (mysqli_stmt_execute($stmt)) {
         echo "<p>Data added successfully!</p>";
      } else {
         echo "<p>Error executing the query: " . mysqli_error($_connection) . "</p>";
      }

      // Close the prepared statement
      mysqli_stmt_close($stmt);
   }
}

/**
 * Inserts a new order into the Orders table.
 *
 * This function takes in details about a customer's order, including customer ID, seller ID,
 * inventory ID, quantity, order group ID, and timestamp, then inserts this information into
 * the Orders table in the database.
 *
 * @param mysqli $_connection The database connection object.
 * @param int $_customerID The ID of the customer making the order.
 * @param int $_sellerID The ID of the seller associated with the order.
 * @param int $_inventoryID The ID of the inventory item being ordered.
 * @param int $_quantity The quantity of the item being ordered.
 * @param int $_orderGroupID The group ID to associate orders within a group.
 * @param bool $_orderConfirmed The boolean if the order is confirmed
 * @param string $_timeStamp The timestamp when the order was placed.
 *
 * @return void This function does not return any value. It performs an insert operation.
 */
function LoadOrders($_connection, $_customerID, $_sellerID, $_inventoryID, $_quantity, $_orderGroupID, $_timeStamp, $_orderConfirmed)
{
   $query = "INSERT INTO Orders ( `CustomerID`, `SellerID`, InventoryID, `Quantity`,OrderGroupID ,`Timestamp`,`OrderConfirmed`)
      VALUES ( ?, ?, ?, ?, ?,?,?)";

   if ($stmt = mysqli_prepare($_connection, $query)) {
      // Bind the parameters to the placeholders
      mysqli_stmt_bind_param($stmt, 'iiiiisi', $_customerID, $_sellerID, $_inventoryID, $_quantity, $_orderGroupID, $_timeStamp, $_orderConfirmed);

      // Execute the statement
      if (mysqli_stmt_execute($stmt)) {
         echo "<p>Data added successfully!</p>";
      } else {
         echo "<p>Error executing the query: " . mysqli_error($_connection) . "</p>";
      }

      // Close the prepared statement
      mysqli_stmt_close($stmt);
   }
}
/**
 * Checks if the required quantity of an item is available in stock.
 *
 * This function queries the Inventory table to retrieve the current stock quantity
 * for a specific inventory item and compares it with the requested quantity.
 * It returns true if enough stock is available, otherwise false.
 *
 * @param mysqli $_connection The database connection object.
 * @param int $_inventoryID The ID of the inventory item to check stock for.
 * @param int $_quantity The quantity requested by the customer.
 *
 * @return bool Returns true if the requested quantity is available, false otherwise.
 */
function CheckStock($_connection, $_inventoryID, $_quantity)
{
   $query = "SELECT NumberInStock FROM Inventory WHERE InventoryID = ?";
   if ($stmt = mysqli_prepare($_connection, $query)) {
      mysqli_stmt_bind_param($stmt, 'i', $_inventoryID);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_bind_result($stmt, $availableQuantity);
      mysqli_stmt_fetch($stmt);
      mysqli_stmt_close($stmt);
      if ($availableQuantity < $_quantity) {
         return false;
      }
      return true;
   }
   echo "error checking inventory";
   return false;
}
/**
 * Updates the inventory and sold quantity when an item is sold.
 *
 * This function decreases the inventory stock and increases the number of items sold
 * in the Inventory table based on the quantity of items sold.
 *
 * @param mysqli $_connection The database connection object.
 * @param int $_inventoryID The ID of the inventory item being sold.
 * @param int $_quantity The quantity of the item being sold.
 *
 * @return void This function does not return any value. It updates the inventory data.
 */
function SoldInventory($_connection, $_inventoryID, $_quantity): void
{
   ChangeStock($_connection, $_inventoryID, -$_quantity);
   ChangeInSold($_connection, $_inventoryID, $_quantity);
}
/**
 * Updates the stock quantity of an inventory item.
 *
 * This function adjusts the number of items in stock by a specified change amount
 * (positive or negative) for a given inventory item in the Inventory table.
 *
 * @param mysqli $_connection The database connection object.
 * @param int $_inventoryID The ID of the inventory item to update.
 * @param int $_changeInStock The change in stock quantity (can be positive or negative).
 *
 * @return void This function does not return any value. It updates the inventory stock.
 */
function ChangeStock($_connection, $_inventoryID, $_changeInStock): void
{
   $query = "UPDATE Inventory SET NumberInStock = NumberInStock + ? WHERE InventoryID = ?";
   if ($updateStmt = mysqli_prepare($_connection, $query)) {
      mysqli_stmt_bind_param($updateStmt, 'ii', $_changeInStock, $_inventoryID);
      if (mysqli_stmt_execute($updateStmt)) {
         echo "Inventory updated successfully.";
      } else {
         echo "Error updating inventory.";
      }
      mysqli_stmt_close($updateStmt);
   }
}
/**
 * Updates the number of items sold for a specific inventory item.
 *
 * This function increments the number of items sold in the Inventory table for a
 * given inventory item by a specified quantity.
 *
 * @param mysqli $_connection The database connection object.
 * @param int $_inventoryID The ID of the inventory item to update.
 * @param int $_changeInSold The change in the number of items sold (should be positive).
 *
 * @return void This function does not return any value. It updates the sold quantity.
 */
function ChangeInSold($_connection, $_inventoryID, $_changeInSold): void
{
   $query = "UPDATE Inventory SET NumberSold = NumberSold + ? WHERE InventoryID = ?";
   if ($updateStmt = mysqli_prepare($_connection, $query)) {
      mysqli_stmt_bind_param($updateStmt, 'ii', $_changeInSold, $_inventoryID);
      if (mysqli_stmt_execute($updateStmt)) {
         echo "Inventory updated successfully.";
      } else {
         echo "Error updating inventory.";
      }
      mysqli_stmt_close($updateStmt);
   }
}

/**
 * Adds a new order if there is sufficient stock, and updates inventory.
 *
 * This function first checks if there is enough stock available for the requested item.
 * If there is enough stock, it loads the order into the database and then updates the
 * inventory by adjusting the stock and the number of items sold.
 *
 * @param mysqli $_connection The database connection object.
 * @param int $_customerID The ID of the customer placing the order.
 * @param int $_sellerID The ID of the seller for the order.
 * @param int $_inventoryID The ID of the inventory item being ordered.
 * @param int $_quantity The quantity of the item being ordered.
 * @param int $_orderGroupID The ID of the order group for grouping related orders.
 * @param string $_timeStamp The timestamp of when the order is placed.
 *
 * @return void This function does not return any value. It performs order processing and inventory updates.
 */
function AddOrders($_connection, $_customerID, $_sellerID, $_inventoryID, $_quantity, $_orderGroupID, $_timeStamp)
{
   if (CheckStock($_connection, $_inventoryID, $_quantity)) {
      LoadOrders($_connection, $_customerID, $_sellerID, $_inventoryID, $_quantity, $_orderGroupID, $_timeStamp, true);
      SoldInventory($_connection, $_inventoryID, $_quantity);
   } else {
      echo "not enough stock";
   }
}

function ConfirmOrderGroup($_connection ,$_orderGroup)
{
   $query = "SELECT
   Inventory.InventoryID,
   Inventory.NumberInStock,
   Orders.Quantity,
   Orders.OrderGroupID,
   Orders.OrderConfirmed
   FROM Inventory
   INNER JOIN Orders ON Orders.InventoryID = Inventory.InventoryID
   WHERE Orders.OrderGroupID = ?";

   $stmt = $_connection->prepare($query);
   $stmt->bind_param("i", $_orderGroup);
   $stmt->execute();
   $result = $stmt->get_result();
   //check if all number in stock < quantity
   while ($query_data = mysqli_fetch_row($result)) {
      if($query_data[1] < $query_data[2])
      {
         return;
      }
   }
   mysqli_data_seek($result, 0);  // Reset result set pointer for the second loop

   //update data
   while ($query_data = mysqli_fetch_row($result)) {
      SoldInventory($_connection, $query_data[0], $query_data[2]);
      $query = "UPDATE Orders SET OrderConfirmed = true WHERE Orders.OrderGroupID = ?";
      $stmt = $_connection->prepare($query);
      $stmt->bind_param("i", $_orderGroup);
      $stmt->execute();
   }

}
?>