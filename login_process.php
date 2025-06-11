<?php
// 세션 시작
session_start();

// 데이터베이스 연결 설정
$db_host = "localhost";
$db_user = "root";
$db_password = "abc123";
$db_name = "project";

// 데이터베이스 연결
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . mysqli_connect_error());
}


// POST로 전송된 로그인 정보 가져오기
$user_ID = $_POST['ID'];
$user_PW = $_POST['PW'];

// 입력된 사용자 정보를 데이터베이스와 비교
$sql = "SELECT * FROM user WHERE user_id='$user_ID' AND user_pw='$user_PW'";
$result = $conn->query($sql);

// 결과 확인
if ($result->num_rows == 1) {
    // 로그인 성공
    $_SESSION['USER_ID'] = $user_ID;
    /* $_SESSION['USER_PW'] = $user_PW;*/
    echo "<script>alert('로그인 성공.');window.location.href='main.php';</script>";
} else {
    // 로그인 실패
    echo "<script>alert('아이디나 비밀번호를 확인해주세요.');window.location.href='login.php';</script>";
}

// 데이터베이스 연결 종료
$conn->close();
?>