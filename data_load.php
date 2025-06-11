<?php
//db 연결정보
$servername = "localhost";
$username = "root";
$password = "abc123";
$dbname = "project";
// MySQL 서버에 연결
$conn = new mysqli($servername, $username, $password, $dbname);
// 연결 확인
if (!$conn) {
    // 연결 실패 시 오류 메시지 출력 후 종료
    die("데이터베이스 연결 실패: " . mysqli_connect_error());
}
// POST로 전달된 할 일 ID 가져오기
$todo_id = $_POST['todo_id'];
// 이벤트 테이블에서 해당 ID에 해당하는 데이터를 조회하는 쿼리 작성
$query = "SELECT Todo_title, cal_list, startDate, endDate, description, work_File FROM events WHERE Todo_id = $todo_id";
// 쿼리 실행
$result = mysqli_query($conn, $query);
// 쿼리 실행 결과 확인
if (!$result) {
    // 쿼리 실행 실패 시 오류 메시지 출력 후 종료
    die("쿼리 실행 실패: " . mysqli_error($conn));
}

$eventData = array();
while ($row = mysqli_fetch_assoc($result)) {
    $cal_list = explode(",", $row['cal_list']); // 쉼표로 구분된 cal_list 값을 배열로 분할

    $cal_names = array();
    foreach ($cal_list as $cal_id) {
        $cal_query = "SELECT cal_name FROM calendarlist WHERE cal_id = $cal_id";
        $cal_result = mysqli_query($conn, $cal_query);
        $cal_row = mysqli_fetch_assoc($cal_result);
        if ($cal_row) {
            $cal_names[] = $cal_row['cal_name']; // 각 cal_id에 해당하는 cal_name을 가져와 배열에 저장
        }
    }

    $eventData = array(
        'Todo_title' => $row['Todo_title'],
        'cal_list' => implode(", ", $cal_names), // cal_names 배열을 다시 문자열로 결합
        'startDate' => $row['startDate'],
        'endDate' => $row['endDate'],
        'description' => $row['description'],
        'work_File' => $row['work_File']
    );
}
// JSON 형식으로 출력
header('Content-Type: application/json');
echo json_encode($eventData);
// 데이터베이스 연결 닫기
mysqli_close($conn);
?>
