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

// user_pw, user_birth, phone_number 필드의 기본값 설정
$conn->query("ALTER TABLE user MODIFY user_pw VARCHAR(255) DEFAULT 'default_password';");
$conn->query("ALTER TABLE user MODIFY user_birth DATE DEFAULT '2000-01-01';");
$conn->query("ALTER TABLE user MODIFY phone_number VARCHAR(20) DEFAULT '01012345678';");

//POST 요청 확인
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userID = $_POST['userID']; // 아이디 정보
    $userName = $_POST['user_Name'];// 이름 정보
    $userGender = $_POST['user_gender']; // 성별 정보
    $userEmail = $_POST['user_email'];  // 이메일 정보

    // 사용자 정보 데이터베이스에서 조회
    $sql = "SELECT user_name FROM user WHERE user_id = '$userID'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // 이미 존재하는 로그인 데이터인 경우 세션 처리
        $user = $result->fetch_assoc();

        // 세션 시작
        session_start();

        // 세션 변수 설정
        $_SESSION['USER_ID'] = $userName;
        $_SESSION['USER_ID'] = $userID;

        exit(); // 중요: 리다이렉트 후에 스크립트 실행을 중단합니다.
    } else {
        // 존재하지 않는 로그인 데이터인 경우 데이터 저장
        $insertSql = "INSERT INTO user (user_id, user_name, user_gender, email) VALUES ('$userID', '$userName', '$userGender', '$userEmail')";

        if ($conn->query($insertSql) === TRUE) {
            // 세션 시작
            session_start();

            // 세션 변수 설정
            $_SESSION['USER_ID'] = $userName;
        } else {
            echo "오류: " . $insertSql . "<br>" . $conn->error;
        }
    }
    $conn->close();
}
?>
