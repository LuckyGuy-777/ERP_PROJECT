<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>로그인</title>
    <style>
        @import url(login_style.css);

        /* 추가한 스타일 */
        .login-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .login-buttons {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 20px;
        }

        .g-signin2
        {
            margin-left: 450px;
            display: inline-block;
        }
        .naver-login {
            margin-left: 450px;
            display: inline-block;
        }

        /* 기타 스타일 */
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
    </style>
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <meta name="google-signin-client_id" content="">

    <script type="text/javascript" src="https://static.nid.naver.com/js/naverLogin_implicit-1.0.3.js" charset="utf-8"></script>
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
</head>
<body>
<div class="container">
    <img
            id="WH"
            src="IMG/WH logo.jpg"
            alt=""
            style="margin-top: 10px; margin-bottom: 10px"
    />
    <form action="login_process.php" method="post">
        <input
                type="text"
                id="ID"
                name="ID"
                placeholder="아이디"
                required
        />

        <input
                type="password"
                id="PW"
                name="PW"
                placeholder="비밀번호"
                required
        />

        <input type="submit" name="submit" value="로그인" />
    </form>
</div>
<div class="centered">
    <p>
        <a href="find_id.html" style="text-decoration: none">아이디 찾기</a>
        &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<a
                href="find_pw_id.php"
                style="text-decoration: none"
        >비밀번호 찾기</a
        >
        &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<a
                href="regist_form.php"
                style="text-decoration: none"
        >회원가입</a
        >
    </p>
</div>


<!-- 구글로그인 -->
<?php
// Google API 정보
$clientId = '';
$clientSecret = '';
$redirectUri = 'http://wh-erp.kro.kr/login.php';

// 세션 시작
session_start();

// Google API 라이브러리 포함
require_once 'vendor/autoload.php';

// Google API 클라이언트 생성
$client = new Google_Client();
$client->setClientId($clientId);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope('email');
$client->addScope('profile');

// 로그인 링크 생성
$authUrl = $client->createAuthUrl();

// 로그인 시도
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    // 사용자 정보 가져오기
    $service = new Google_Service_Oauth2($client);
    $userInfo = $service->userinfo->get();

    $email = $userInfo->getEmail();
    $name = $userInfo->getName();


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

    // 여기에 ALTER TABLE 쿼리를 추가합니다.
    $alter_query = "ALTER TABLE user MODIFY user_gender ENUM('male', 'female', 'other') DEFAULT 'other';";
    $conn->query("ALTER TABLE user MODIFY user_pw VARCHAR(255) DEFAULT 'default_password';");
    $conn->query("ALTER TABLE user MODIFY user_birth DATE DEFAULT '2000-01-01';");
    $conn->query("ALTER TABLE user MODIFY phone_number VARCHAR(20) DEFAULT '01012345678';");
    $conn->query($alter_query);

    // 사용자 정보 데이터베이스에서 조회
    $sql = "SELECT user_name FROM user WHERE user_id = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // 이미 존재하는 로그인 데이터인 경우 세션 처리
        session_start();
        $_SESSION['USER_ID'] = $email;  // <-- This line was changed

        // 메인 페이지로 리다이렉트
        header("Location: main.php");
        exit();
    } else {
        // 존재하지 않는 로그인 데이터인 경우 데이터 저장 후 메인 페이지로 이동
        $insertSql = "INSERT INTO user (user_id, user_name, email) VALUES ('$email','$name', '$email')";

        if ($conn->query($insertSql) === TRUE) {
            // 세션 시작
            session_start();

            // 세션 변수 설정
            $_SESSION['USER_ID'] = $email;  // <-- This line was changed

            // 메인 페이지로 리다이렉트
            header("Location: main.php");
            exit();
        } else {
            echo "회원가입 또는 로그인에 실패했습니다.";
        }
    }


} else {
    // 로그인 링크 출력
    echo "<a href='$authUrl', class='' ><img src='./img/googlelogin.png' title='구글로그인' style='width: 23.5%;height: 60px;margin-left: 38%'></a>";
}
?>

<!-- 카카오로그인 db 바꿔야뎀 -->

<a href="javascript:kakaoLogin();"><img src="https://www.gb.go.kr/Main/Images/ko/member/certi_kakao_login.png" style="width: auto;height: 60px;margin-left: 38%"></a>
<script src="https://developers.kakao.com/sdk/js/kakao.js"></script>
<script>
    window.Kakao.init("ccd535b22cc3d64204a3c866f84f2832");

    function kakaoLogin() {
        window.Kakao.Auth.login({
            scope: 'account_email,profile_nickname,gender',
            success: function (authObj) {
                console.log(authObj);
                window.Kakao.API.request({
                    url: '/v2/user/me',
                    success: res => {
                        const kakao_account = res.kakao_account;
                        console.log(kakao_account);

                        // 서버로 사용자 정보 전송
                        $.ajax({
                            url: 'save_kakao_user.php', // 서버 사이드 코드를 처리할 파일 경로
                            type: 'POST',
                            data: {
                                userID: kakao_account.email,
                                user_Name: kakao_account.profile.nickname,
                                user_gender: kakao_account.gender, // 수정된 부분
                                user_email: kakao_account.email // 이 부분에서 이메일 정보를 전송하도록 추가했습니다.
                            },
                            success: function(response) {
                                console.log(response);
                                window.location.href = 'main.php';
                            }

                        });

                    }
                });
            }
        });
    }

</script>

</body>
</html>