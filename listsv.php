<?php
// Kết nối đến cơ sở dữ liệu
declare(strict_types=1);
use Platformsh\ConfigReader\Config;
require __DIR__.'/vendor/autoload.php';

$config = new Platformsh\ConfigReader\Config();
if (!$config->isValidPlatform()) {
   die("Not in a Platform.sh Environment.");
}

$credentials = $config->credentials('database');

$dsn = sprintf('mysql:host=%s;port=%d;dbname=%s', $credentials['host'], $credentials['port'], $credentials['path']);

// Tạo một mảng để lưu danh sách sinh viên
$students = [];

try {
    $conn = new \PDO($dsn, $credentials['username'], $credentials['password'], [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        \PDO::MYSQL_ATTR_FOUND_ROWS => true,
    ]);

    // Truy vấn danh sách sinh viên từ cơ sở dữ liệu
    $sql = "SELECT * FROM b2013518_qlsv";
    $result = $conn->query($sql);

    // Lấy dữ liệu và lưu vào mảng students
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $students[] = $row;
    }

} catch (PDOException $e) {
    // Xử lý lỗi nếu có lỗi kết nối hoặc truy vấn
    die("Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage());
}

// Liên kết trở về trang index.php
echo '<a href="index.php">Trở về trang chính</a>';

// Hiển thị danh sách sinh viên
echo '<h2>Danh Sách Sinh Viên</h2>';
echo '<table border="1">';
echo '<tr><th>ID</th><th>Họ Tên</th><th>Năm Sinh</th><th>Email</th></tr>';
foreach ($students as $student) {
    echo '<tr>';
    echo '<td>' . $student['id'] . '</td>';
    echo '<td>' . $student['ho_ten'] . '</td>';
    echo '<td>' . $student['nam_sinh'] . '</td>';
    echo '<td>' . $student['email'] . '</td>';
    echo '</tr>';
}
echo '</table>';
?>

