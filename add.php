<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $calendar = $_POST["calendar"];
    $startDate = $_POST["startDate"];
    $endDate = $_POST["endDate"];
    $members = $_POST["members"];
    $description = $_POST["description"];
    $priority = $_POST["priority"]; // 추가된 중요도 변수
    $result = $_POST["result"];

    // 파일 데이터 처리
    $file = $_FILES["file"];
    $fileName = $file["name"];
    $fileTmpName = $file["tmp_name"];
    $fileError = $file["error"];

    $ispublic = 1; // ispublic 값을 1로 고정

    if ($fileError === UPLOAD_ERR_OK) {
        // 파일이 업로드되었을 때 처리하는 코드
        $fileDestination = "C:/협업툴 화면/calendar/uploads/" . $fileName;
        move_uploaded_file($fileTmpName, $fileDestination);
    } else {
        // 파일 업로드 오류 처리
        die("파일 업로드에 실패했습니다.");
    }

    // 데이터 유효성 검사
    if (empty($title) || empty($calendar) || empty($startDate) || empty($endDate) || empty($members)) {
        die("필수 데이터를 모두 입력해주세요.");
    }

    // 일정 시작 날짜와 끝 날짜 비교 검사
    if (strtotime($endDate) < strtotime($startDate)) {
        die("끝나는 날짜는 시작 날짜보다 이후여야 합니다.");
    }

    // DB 연결 정보 (비밀번호는 외부 설정 등 고려)
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

    // Prepared Statements 사용하여 데이터 삽입
    $stmt = $conn->prepare("INSERT INTO events (Todo_title, cal_list, startDate, endDate, recipient, description, importance, classification_Code, work_File, ispublic) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $title, $calendar, $startDate, $endDate, $members, $description, $priority, $result, $fileName, $ispublic);

    if ($stmt->execute()) {
        // 쿼리 실행 성공 시 응답 메시지 출력
        echo "일정이 성공적으로 저장되었습니다.";
    } else {
        // 쿼리 실행 실패 시 에러 메시지 출력
        echo "Error: " . $stmt->error;
    }

    // 데이터베이스 연결 종료
    $stmt->close();
    $conn->close();
}
?>
