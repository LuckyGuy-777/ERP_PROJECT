<?php
// 데이터베이스 연결
$servername = "localhost";
$username = "root";
$password = "abc123";
$dbname = "project";

// 데이터베이스에 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if (!$conn) {
    die("데이터베이스 연결 실패: " . mysqli_connect_error());
}

// POST로 전달된 date 값을 가져옵니다.
$date = $_POST['date'];

// 데이터베이스에서 Todo_id와 Todo_title 값을 조회하는 쿼리를 작성합니다.
$query = "SELECT Todo_id, Todo_title FROM events WHERE '$date' BETWEEN startdate AND enddate AND ispublic = 1";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("쿼리 실행 실패: " . mysqli_error($conn));
}

// 쿼리 결과에서 Todo_id와 Todo_title 값을 가져와서 배열에 저장합니다.
$titles = array();
while ($row = mysqli_fetch_assoc($result)) {
    $titles[] = array(
        'Todo_id' => $row['Todo_id'],
        'Todo_title' => $row['Todo_title']
    );
}

// JSON 형식으로 반환
header('Content-Type: application/json');
echo json_encode($titles);

// 데이터베이스 연결 종료
mysqli_close($conn);
?>
