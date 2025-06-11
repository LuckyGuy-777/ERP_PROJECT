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

// POST 데이터로부터 선택된 cal_name과 ispublic 값을 가져옵니다.
$data = json_decode(file_get_contents("php://input"), true);
$calName = $data["calName"];
$ispublic = $data["ispublic"];

// 데이터베이스에서 events 테이블의 ispublic 값을 업데이트하는 쿼리 작성

$sql = "UPDATE events AS e
                INNER JOIN calendarlist AS c ON FIND_IN_SET(c.cal_id, e.cal_list)
                SET e.ispublic = $ispublic
                WHERE c.cal_name IN ('$calName') AND e.Todo_id > 0;";

// 쿼리 실행
if ($conn->query($sql) === true) {
    // 업데이트 성공 시, 성공 메시지를 JSON 형태로 반환
    $response = array("status" => "success", "message" => "ispublic updated successfully");
    echo json_encode($response);
} else {
    // 업데이트 실패 시, 실패 메시지를 JSON 형태로 반환
    $response = array("status" => "error", "message" => "Error updating ispublic: " . $conn->error);
    echo json_encode($response);
}

// 데이터베이스 연결 닫기
$conn->close();
?>
