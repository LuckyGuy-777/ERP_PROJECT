<?php
// 데이터베이스 연결 설정
$db = new mysqli('localhost', 'root', 'abc123', 'project');

// 데이터베이스 연결 오류 처리
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// employee 테이블에서 모든 직원 조회
$query = "SELECT user_id, user_name FROM employee";
$result = $db->query($query);

$employees = [];
while ($row = $result->fetch_assoc()) {
    $employees[] = $row;
}

// JSON 형식으로 결과 반환
header('Content-Type: application/json');
echo json_encode($employees);

// 데이터베이스 연결 종료
$db->close();
?>
