<?php
// DB 연결 정보
$servername = "localhost";
$username = "root";
$password = "abc123";
$dbname = "project";

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 데이터베이스 연결 확인
if ($conn->connect_error) {
    die("데이터베이스 연결 실패: " . $conn->connect_error);
}

// 캘린더 목록 조회 쿼리
$sql = "SELECT cal_id, cal_name FROM calendarlist";

// 쿼리 실행하여 결과 가져오기
$result = $conn->query($sql);

// 쿼리 결과가 있을 경우
if ($result->num_rows > 0) {
    // JSON 형태로 데이터 반환
    $calendars = array();
    while ($row = $result->fetch_assoc()) {
        $calendars[] = $row;
    }
    echo json_encode($calendars);
} else {
    echo "캘린더가 없습니다.";
}

// 데이터베이스 연결 종료
$conn->close();
?>
