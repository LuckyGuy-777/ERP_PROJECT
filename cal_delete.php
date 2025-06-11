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
        $stmt = $conn->prepare("DELETE FROM calendarlist WHERE cal_id = ? AND cal_name = ?");
        $stmt->bind_param("is", $idToDelete, $nameToDelete);

        // 쿼리 실행
        if ($stmt->execute()) {
            // 성공적으로 삭제되었을 때의 동작 (예: 메시지 출력 등)
            echo "할 일 '" . $nameToDelete . "'이 삭제되었습니다.";

            // 추가 쿼리를 실행하여 다른 테이블에서도 해당 cal_id를 사용한 데이터를 삭제합니다.
            $stmt_events = $conn->prepare("DELETE events
FROM events
JOIN (SELECT todo_id FROM events WHERE cal_list = ?) AS subquery
ON events.todo_id = subquery.todo_id;");
            $stmt_events->bind_param("i", $idToDelete);
            if ($stmt_events->execute()) {
                // 성공적으로 삭제되었을 때의 동작 (예: 메시지 출력 등)
                // 삭제된 할 일 이름 출력 쿼리
                $stmt_todo = $conn->prepare("SELECT Todo_title FROM events WHERE cal_list = ?");
                $stmt_todo->bind_param("i", $idToDelete);
                $stmt_todo->execute();
                $result_todo = $stmt_todo->get_result();
                while ($row_todo = $result_todo->fetch_assoc()) {
                    echo "삭제된 할 일: " . $row_todo['Todo_title'] . "<br>";
                }
                $stmt_todo->close();
            } else {
                // 삭제 실패시의 동작 (예: 에러 메시지 출력 등)
                echo "이벤트 삭제 실패: " . $stmt_events->error;
            }
            $stmt_events->close();
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
