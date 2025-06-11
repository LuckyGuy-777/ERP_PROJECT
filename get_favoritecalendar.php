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
$sql = "SELECT fav_cal_id, fav_cal_name FROM favorite_calendar_list";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // 결과 출력
    while ($row = $result->fetch_assoc()) {
        $fav_cal_id = $row["fav_cal_id"];
        $fav_cal_name = $row["fav_cal_name"];
        $isChecked = true; // 항상 체크되게 할 변수를 추가하고, true로 설정합니다.

        echo "<div style='display: flex; justify-content: space-between; align-items: center;'>";
        echo "<div style='display: flex; align-items: center;'>";
        echo "<input type='checkbox' name='name[]' value='" . $fav_cal_name . "' " . ($isChecked ? "checked" : "") . ">";
        echo "<span>" . $fav_cal_name . "</span>";
        echo "<input type='hidden' name='id[]' value='" . $fav_cal_id . "'>"; // id를 hidden input으로 전달합니다.
        echo "</div>";
        echo "<span style='cursor: pointer;' onclick='fav_deleteData(\"" . $fav_cal_id . "\", \"" . $fav_cal_name . "\")'>&times;</span>";
        echo "</div>";
    }
} else {
    echo "<li>데이터가 없습니다.</li>";
}

// 데이터베이스 연결 닫기
$conn->close();
?>
