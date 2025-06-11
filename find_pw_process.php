
<?php
// DB 연결 정보
$db_host = 'localhost';
$db_name = 'project';
$db_user = 'root';
$db_password = 'abc123';

// 데이터베이스 연결
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

// 연결 확인
if (!$conn) {
    die("연결 실패: " . mysqli_connect_error());
}

// PHPMailer 라이브러리를 불러옵니다.
require 'C:\Users\asdf\IdeaProjects\projcct\vendor\autoload.php';


    session_start();
    $user_ID =$_SESSION['USER_ID'];
    $email = $_SESSION['USER_EMAIL'];


    // 입력된 ID와 이메일이 데이터베이스에 있는지 확인합니다.
    $sql = "SELECT * FROM user WHERE user_id = '$user_ID' AND email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // 임시 비밀번호 생성 (13자리로 제한)
        $temp_password = generateRandomString(13);

        // 임시 비밀번호를 데이터베이스에 업데이트합니다.
        $update_sql = "UPDATE user SET user_pw = '$temp_password' WHERE user_id = '$user_ID'";
        $conn->query($update_sql);

        // PHPMailer를 초기화합니다.
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';; // SMTP 호스트
        $mail->Port = 587; // SMTP 포트 번호
        $mail->SMTPSecure = 'tls'; // 보안 방식
        $mail->SMTPAuth = true;
        $mail->Username = 'brillientchoi98@gmail.com'; // SMTP 사용자명
        $mail->Password = 'jbzyjdenoweefmwf'; // SMTP 비밀번호

        $mail->setFrom('brillientchoi98@gmail.com', 'WH_YOONSEOK'); // 발신자 이메일 주소와 이름
        $mail->addAddress($email, 'Client'); // 수신자 이메일 주소와 이름

        $mail->isHTML(true);
        $mail->Subject = 'Temporary Password'; // 이메일 제목
        $mail->Body = 'Your temporary password is: ' . $temp_password; // 이메일 내용

        // 이메일을 발송하고 발송 결과를 확인합니다.
        if ($mail->send()) {
            echo "<script>alert('임시 비밀번호를 이메일로 발송했습니다.');window.location.href='login.php';</script>";

        } else {

            echo "<script>alert('이메일 발송에 실패했습니다. 오류: ' . $mail->ErrorInfo);window.location.href='find_pw_id.html';</script>";

        }
    } else {
        echo "<script>alert('입력한 정보와 일치하는 사용자를 찾을 수 없습니다.');window.location.href='find_pw_id.html';</script>";

    }


// 랜덤한 문자열 생성 함수 (길이를 매개변수로 받도록 수정)
function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    $maxIndex = strlen($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $maxIndex)];
    }
    return $randomString;
}
?>





