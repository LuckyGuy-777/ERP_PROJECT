<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 데이터베이스 연결 설정 (같은 설정을 사용합니다.)
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

    // POST로 전달된 삭제 데이터 처리
    if (isset($_POST['deleteId']) && isset($_POST['deleteName'])) {
        $idToDelete = $_POST['deleteId'];
        $nameToDelete = $_POST['deleteName'];

        // SQL Injection 방지를 위해 쿼리를 준비합니다.
        $stmt = $conn->prepare("DELETE FROM favorite_calendar_list WHERE fav_cal_id = ? AND fav_cal_name = ?");
        $stmt->bind_param("is", $idToDelete, $nameToDelete);

        // 쿼리 실행
        if ($stmt->execute()) {
            // 성공적으로 삭제되었을 때의 동작 (예: 메시지 출력 등)
            echo  $nameToDelete . "가 삭제되었습니다.";
        } else {
            // 삭제 실패시의 동작 (예: 에러 메시지 출력 등)
            echo "삭제 실패: " . $stmt->error;
        }

        $stmt->close();
    }

    // 데이터베이스 연결 닫기
    $conn->close();
}
?>