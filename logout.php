<?php
// 세션 시작 및 모든 세션 데이터 삭제
session_start();
session_destroy();
// 로그아웃 메시지를 띄우고 로그인 페이지로 이동
echo '<script>alert("로그아웃 되었습니다."); location.href="login.php";</script>';
exit;
?>
