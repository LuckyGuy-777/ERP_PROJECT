<?php
// PHP 설정 및 세션 시작
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$user_id = $_SESSION['USER_ID'];    // 세션에서 사용자 ID 가져오기

if ($_SERVER["REQUEST_METHOD"] == "POST") { // POST 요청이면 실행
    // POST 데이터에서 사용자 정보 가져오기
    $user_birth = $_POST['user_birth'];
    $phone_number = $_POST['phone_number'];
    // 데이터베이스 연결 정보 설정
    $host = 'localhost';
    $db_username = 'root';
    $db_password = 'abc123';
    $db_name = 'project';
    // MySQL 데이터베이스 연결
    $connection = new mysqli($host, $db_username, $db_password, $db_name);
    // 연결 실패 시 오류 메시지 출력
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // 사용자 성별 및 쿼리 설정
    $user_gender = $_POST['user_gender'];
    $query = "UPDATE user SET user_gender=?, user_birth=?, phone_number=? WHERE user_id=?";

    // 준비된 문 실행을 위한 바인딩 및 실행
    $stmt = $connection->prepare($query);
    $stmt->bind_param('ssss', $user_gender, $user_birth, $phone_number, $user_id);
    // 쿼리 실행 성공 시 성공 메시지 출력
    if ($stmt->execute()) {  // 주석 해제된 부분
        echo "<script>alert('Information saved successfully!'); window.location.href = 'main.php';</script>";
    } else {
        // 오류 발생 시 오류 메시지 출력
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    // 문 닫기 및 데이터베이스 연결 닫기
    $stmt->close();
    $connection->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Additional Information</title>
</head>
<body>
<h2>Enter Additional Information</h2>
<form action="additional_info.php" method="POST">
    <!-- Add a hidden input for the userID -->
    <input type="hidden" name="userID" value="<?php echo $_SESSION['USER_ID']; ?>">


    <!-- Modify the name attribute to user_pw -->



    <label for="user_gender">Gender:</label>
    <select id="user_gender" name="user_gender" required>
        <option value="male">Male</option>
        <option value="female">Female</option>
        <option value="other">Other</option>
    </select><br>
    <label for="user_birth">Birth Date:</label>
    <input type="date" id="user_birth" name="user_birth" required><br>

    <label for="phone_number">Phone Number:</label>
    <input type="text" id="phone_number" name="phone_number" required><br>

    <input type="submit" value="Submit">
</form>
</body>
</html>

