<?php
// 네이버 로그인 콜백 처리 스크립트 (naver_login_callback.php) - 아직 미구현

$client_id = '';   //네이버개발자센터에서 받은 ClientID 입력
$client_secret = ''; //네이버개발자센터에서 받은 Client Secret 입력

$code = $_GET["code"];
$state = $_GET["state"];
// 콜백 URL 설정 (현재 Callback URL 입력)
$redirectURI = urlencode("http://localhost:63342/%ED%8C%8C%EC%9D%BC3/project/naverloginCB.php"); // 현재 Callback Url 입력
// 네이버 토큰 발급 요청을 위한 URL 구성
$url = "https://nid.naver.com/oauth2.0/token?grant_type=authorization_code&client_id=".$client_id."&client_secret=".$client_secret."&redirect_uri=".$redirectURI."&code=".$code."&state=".$state;
$is_post = false;

$ch = curl_init();// cURL 초기화
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, $is_post);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// 헤더 설정
$headers = array();
// cURL 실행 및 응답 받기
$response = curl_exec ($ch);
// HTTP 상태 코드 확인
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "status_code:".$status_code;
// cURL 세션 종료
curl_close ($ch);

// 토큰 발급 성공 시 응답 출력
if($status_code == 200) {
    echo $response;
} else {
    // 토큰 발급 실패 시 에러 내용 출력
    echo "Error 내용:".$response;
}
?>