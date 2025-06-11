<?php
// 데이터베이스 연결 정보
$servername = "localhost";
$username = "root";
$password = "abc123";
$dbname = "project";

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);
// 연결 확인
if (!$conn) {
    die("데이터베이스 연결 실패: " . mysqli_connect_error());
}
// 클라이언트로부터 받은 JSON 데이터 디코딩
$data = json_decode(file_get_contents('php://input'), true);
$title = $data['title'];
// Todo_title에 해당하는 Todo_id 조회
$query = "SELECT Todo_id FROM events WHERE Todo_title = '$title'";
$result = mysqli_query($conn, $query);

// 쿼리 실행 결과 확인
if (!$result) {
    die("쿼리 실행 실패: " . mysqli_error($conn));
}
// 조회 결과에서 Todo_id 추출
$row = mysqli_fetch_assoc($result);
$id = $row['Todo_id'];
// JSON 형식으로 응답
header('Content-Type: application/json');
echo json_encode(array('id' => $id));
// 데이터베이스 연결 닫기
mysqli_close($conn);
?>
