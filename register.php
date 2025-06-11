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


// POST로 전송된 로그인 정보 가져오기
$user_id = $_POST['id'];
$user_pw = $_POST['password'];
$email = $_POST['email'];
$user_name = $_POST['user_name'];
$user_gender = $_POST['gender'];
$user_birth = $_POST['birthdate'];
$phone_number = $_POST['phone'];

// 회원가입 정보를 데이터베이스에 삽입
$sql = "INSERT INTO user (user_id, user_pw, email , user_name, user_gender, user_birth, phone_number)
            VALUES ('$user_id', '$user_pw', '$email', '$user_name', '$user_gender', '$user_birth', '$phone_number')";

if ($conn->query($sql) === TRUE) {
    // 회원가입 성공
    echo "<script>alert('회원가입이 완료되었습니다');window.location.href='login.php';</script>";
} else {
    // 회원가입 실패
    echo "<script>alert('회원가입을 실패하였습니다');window.location.href='login.php';</script>" . $conn->error;
}

// 데이터베이스 연결 종료
$conn->close();

?>


