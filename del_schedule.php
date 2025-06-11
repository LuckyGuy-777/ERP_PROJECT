<?php
$servername = "localhost";
$username = "root";
$password = "abc123";
$dbname = "project";
// MySQL 서버에 연결
$conn = new mysqli($servername, $username, $password, $dbname);

if (!$conn) {
    // 연결 실패 시 오류 메시지 출력 후 종료
    die("데이터베이스 연결 실패: " . mysqli_connect_error());
}
// POST로 전달된 할 일 ID 가져오기
$todo_id = $_POST['todo_id'];
// 이벤트 테이블에서 해당 ID에 해당하는 데이터 삭제하는 쿼리 작성
$query = "DELETE FROM events WHERE Todo_id = $todo_id";
// 쿼리 실행
$result = mysqli_query($conn, $query);

// 쿼리 실행 결과 확인
if (!$result) {
    // 쿼리 실행 실패 시 오류 메시지 출력 후 종료
    die("쿼리 실행 실패: " . mysqli_error($conn));
}
// 삭제 성공 메시지 출력
echo "이벤트가 성공적으로 삭제되었습니다.";
// 데이터베이스 연결 닫기
mysqli_close($conn);
?>
