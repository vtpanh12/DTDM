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
        $id = $_POST['id'];
        $ho_ten = $_POST['ho_ten'];
        $nam_sinh = $_POST['nam_sinh'];
        $email = $_POST['email'];

        // Thực hiện truy vấn cập nhật thông tin sinh viên
        $sql = "UPDATE B2013518_qlsv SET ho_ten = :ho_ten, nam_sinh = :nam_sinh, email = :email WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':ho_ten', $ho_ten);
        $stmt->bindParam(':nam_sinh', $nam_sinh);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Thông báo thành công
        echo "Thông tin sinh viên đã được cập nhật thành công.";

    } catch (PDOException $e) {
        // Thông báo lỗi nếu có lỗi
        echo "Lỗi khi cập nhật thông tin sinh viên: " . $e->getMessage();
    }
}

// Liên kết trở về trang index.php
echo '<a href="index.php">Trở về trang chính</a>';

// Form để nhập thông tin cập nhật
echo '<h2>Cập Nhật Thông Tin Sinh Viên</h2>';
echo '<form method="post">';
echo '<label for="id">ID Sinh Viên:</label>';
echo '<input type="text" name="id" required><br><br>';
echo '<label for="ho_ten">Họ Tên:</label>';
echo '<input type="text" name="ho_ten" required><br><br>';
echo '<label for="nam_sinh">Năm Sinh:</label>';
echo '<input type="number" name="nam_sinh" required><br><br>';
echo '<label for="email">Email:</label>';
echo '<input type="email" name="email" required><br><br>';
echo '<input type="submit" value="Cập Nhật">';
echo '</form>';
?>


