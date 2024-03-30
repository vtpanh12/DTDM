<?php
declare(strict_types=1);
use Platformsh\ConfigReader\Config; //Khai bao su dung lop Config de lay thong tin tu bien moi truong
require __DIR__.'/vendor/autoload.php'; //Yeu cau he thong tu nap goi thu vien
$config = new Platformsh\ConfigReader\Config(); // Tạo doi tuong thuoc lop Config
if (!$config->isValidPlatform()) { // Kiem tra tinh hop le cua moi truong thuc thi ung dung
die("Not in a Platform.sh Environment.");
}
// Ten quan he da thiet lap o buoc truoc 'database' la ten cua co so du lieu duoc cap co ung dung
$credentials = $config-> credentials('database'); // Lay thong tin ket noi vao co so du lieu database va dua vao mang
// Tạo chuoi dsn chua thong tin ket noi vao csdl tu mang credentials
$dsn = sprintf('mysql:host=%s;port=%d;dbname=%s', $credentials['host'], $credentials['port'], $credentials['path']);
echo "$dsn";
try {
	$conn = new \PDO($dsn, $credentials['username'], $credentials['password'], [
		\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
		\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => TRUE,
		\PDO::MYSQL_ATTR_FOUND_ROWS => TRUE,
	]);
// Drop table (if it exists)
	$sql = "DROP TABLE IF EXISTS People";
	$conn->query($sql);
// Creating a table.
	$sql = "CREATE TABLE People (
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(30) NOT NULL,
	city VARCHAR(30) NOT NULL
	)";
	$conn->query($sql);

// Insert data.
	$sql = "INSERT INTO People (name, city) VALUES
		('Neil Armstrong', 'Moon'),
		('Buzz Aldrin', 'Glen Ridge'),
		('Sally Ride', 'La Jolla');";
	$conn->query($sql);
// Query table
	$sql = "SELECT * FROM People";
	$result = $conn->query($sql);
	$result->setFetchMode(\PDO::FETCH_OBJ);
//Print data
if ($result) {
  print <<<TABLE
  <table>
    <thead>
      <tr>
        <th>Name</th>
        <th>City</th>
      </tr>
    </thead>
    <tbody>
  TABLE;

  foreach ($result as $record) {
    printf("<tr><td>%s</td><td>%s</td></tr>\n", $record->name, $record->city);
  }

  print "</tbody>\n</table>\n";
}

// Drop table. (Optional, if you want to clean up after running the script)
//$sql = "DROP TABLE People";
//$conn->query($sql);
} catch (\Exception $e) {
print $e->getMessage();
}
?>

