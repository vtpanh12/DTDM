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

// Xử lý việc thêm sinh viên mới
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $conn = new \PDO($dsn, $credentials['username'], $credentials['password'], [
\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => TRUE,
\PDO::MYSQL_ATTR_FOUND_ROWS => TRUE,
]);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Lấy dữ liệu từ biểu mẫu
        $ho_ten = $_POST['ho_ten'];
        $nam_sinh = $_POST['nam_sinh'];
        $email = $_POST['email'];

        // Thực hiện truy vấn thêm sinh viên mới
        $sql = "INSERT INTO b2013518_qlsv (ho_ten, nam_sinh, email) VALUES (:ho_ten, :nam_sinh, :email)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':ho_ten', $ho_ten);
        $stmt->bindParam(':nam_sinh', $nam_sinh);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Thông báo thành công
        echo "Sinh viên đã được thêm thành công.";

    } catch (PDOException $e) {
        // Thông báo lỗi nếu có lỗi
        echo "Lỗi khi thêm sinh viên: " . $e->getMessage();
    }
}

// Liên kết trở về trang index.php
echo '<br><a href="index.php">Trở về trang chính</a>';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Thêm Sinh Viên</title>
</head>
<body>
    <h2>Thêm Sinh Viên Mới</h2>
    <form method="post">
        <label for="ho_ten">Họ Tên:</label>
        <input type="text" name="ho_ten" required><br><br>

        <label for="nam_sinh">Năm Sinh:</label>
        <input type="number" name="nam_sinh" required><br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br><br>

        <input type="submit" value="Thêm Sinh Viên">
    </form>
</body>
</html>

