<?php
//오류 표시 설정
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// 세션시작
session_start();

// 데이터베이스 연결
$db = new mysqli('localhost', 'root', 'abc123', 'project');

// 데이터베이스 연결 확인
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
//데이터베이스 연결 성공
echo "Database connected successfully.\n";

// 세션 확인
if (!isset($_SESSION['USER_ID'])) {
    die("Session USER_ID not set.\n");
}
$user_id = $_SESSION['USER_ID'];
echo "Session USER_ID: " . $user_id . "\n";

// POST 요청 확인
if (!isset($_POST['token'])) {
    die("No token received in POST request.\n");
}
$token = $_POST['token'];
echo "Token received: " . $token . "\n";

// 데이터베이스에 토큰 저장
$stmt = $db->prepare("UPDATE user SET device_token = ? WHERE user_id = ?");
$stmt->bind_param('ss', $token, $user_id);
// DB에 토큰저장할떄 오류발생 시 반응
if (!$stmt->execute()) {
    echo "Error during token saving: " . $stmt->error . "\n";
} else {
    echo "Token saved successfully.\n";
}
?>

