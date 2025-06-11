<?php
// 데이터베이스 연결 설정
$servername = "localhost";
$username = "root";
$password = "abc123";
$dbname = "project";

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 데이터 목록 가져오는 쿼리 작성
$sql = "SELECT cal_name FROM calendarlist";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // 결과 출력
    while ($row = $result->fetch_assoc()) {
        echo "<li>" . $row["cal_name"] . "</li>";
    }
} else {
    // 데이터가 없을 경우 출력
    echo "<li>데이터가 없습니다.</li>";
}

// 데이터베이스 연결 닫기
$conn->close();
?>
