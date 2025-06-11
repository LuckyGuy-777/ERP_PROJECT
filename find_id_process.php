<?php
// DB 연결 정보
$db_host = 'localhost';
$db_name = 'project';
$db_user = 'root';
$db_password = 'abc123';

// DB 연결
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

// 연결 확인
if ($conn->connect_error) {
    die("연결 실패: " . $conn->connect_error);
}
// 사용자로부터 전송된 이메일과 사용자명
$email = $_POST['email'];
$user_name = $_POST['user_name'];

// 데이터베이스에서 사용자 ID 검색
$sql = "SELECT user_id FROM user WHERE email='$email' AND user_name='$user_name'";
$result = mysqli_query($conn, $sql);
// 검색 결과 행 가져오기
$row = mysqli_fetch_row($result);
// 검색된 결과에 따른 처리
if (!$row) {
    // 검색된 결과가 없을 경우 에러 메시지 출력 및 메인 페이지로 이동
    echo "<script>alert('없는 ID입니다');window.location.href='main.php';</script>";
} else {
    // 검색된 결과가 있을 경우 ID를 알림 메시지에 포함하여 출력하고 로그인 페이지로 이동
    echo "<script>alert('회원님의 ID는 " . $row[0] . " 입니다.');window.location.href='login.php';</script>";
}

// 데이터베이스 연결 종료
$conn->close();
?>
