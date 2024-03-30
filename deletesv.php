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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $conn = new \PDO($dsn, $credentials['username'], $credentials['password'], [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
            \PDO::MYSQL_ATTR_FOUND_ROWS => true,
        ]);

        // Lấy dữ liệu từ biểu mẫu
        $ho_ten = $_POST['ho_ten'];

        // Thực hiện truy vấn xóa sinh viên theo tên
        $sql = "DELETE FROM b2013518_qlsv WHERE ho_ten = :ho_ten";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':ho_ten', $ho_ten);
        $stmt->execute();

        // Kiểm tra xem có dòng dữ liệu nào bị xóa không
        $row_count = $stmt->rowCount();
        if ($row_count > 0) {
            // Thông báo thành công nếu có dữ liệu bị xóa
            echo "Sinh viên đã bị xóa thành công.";
        } else {
            // Thông báo nếu không có sinh viên nào được xóa
            echo "Không tìm thấy sinh viên có tên: " . $ho_ten;
        }

    } catch (PDOException $e) {
        // Thông báo lỗi nếu có lỗi
        echo "Lỗi khi xóa sinh viên: " . $e->getMessage();
    }
}

// Liên kết trở về trang index.php
echo '<a href="index.php">Trở về trang chính</a>';

// Form để nhập tên sinh viên cần xóa
echo '<h2>Xóa Sinh Viên</h2>';
echo '<form method="post">';
echo '<label for="ho_ten">Họ Tên Sinh Viên:</label>';
echo '<input type="text" name="ho_ten" required><br><br>';
echo '<input type="submit" value="Xóa">';
echo '</form>';
?>
