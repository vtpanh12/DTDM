<?php
declare(strict_types=1);
use Platformsh\ConfigReader\Config; //Khai bao su dung lop Config de lay thong tin tu bien moi truong
require __DIR__.'/vendor/autoload.php'; //Yeu cau he thong tu nap goi thu vien
$config = new Platformsh\ConfigReader\Config(); // Tao doi tuong thuoc lop Config
if (!$config->isValidPlatform()) { // Kiem tra tinh hop le cua moi truong thuc thi ung dung
die("Not in a Platform.sh Environment.");
}
// Ten quan he da thiet lap o buoc truoc 'database' la ten cua co so du lieu duoc cap co ung dung
$credentials = $config->credentials('database'); // Lay thong tin ket noi vao co so du lieu database va dua vao mang
// Tao chuoi dsn chua thong tin ket noi vao csdl tu mang credentials
$dsn = sprintf('mysql:host=%s;port=%d;dbname=%s', $credentials['host'], $credentials['port'], $credentials['path']);
echo "$dsn";

try {
$conn = new \PDO($dsn, $credentials['username'], $credentials['password'], [
\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => TRUE,
\PDO::MYSQL_ATTR_FOUND_ROWS => TRUE,
]);
// Drop table
//$sql3 = "DROP TABLE People";
$sql = "DROP TABLE IF EXISTS b2013518_qlsv";
// Creating a table.
$sql = "CREATE TABLE b2013518_qlsv (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
ho_ten VARCHAR(50) NOT NULL,
nam_sinh INT NOT NULL,
email VARCHAR(30) NOT NULL
)";
echo '<br>';
echo '<a href="index.php">Trở về trang chính</a>';
echo '<br>';
$conn->query($sql);
print("/n Tao bang b2013518_qlsv thanh cong !!!");
} catch (\Exception $e) {
print $e->getMessage();
}
?>
