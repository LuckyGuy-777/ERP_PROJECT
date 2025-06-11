<!--PHP 스크립트 시작-->
<?php
// 모든 오류를 화면에 표시하도록 설정
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 세션 시작. 웹 페이지 간의 데이터를 저장하거나 전달하는 데 사용
session_start();
// 세션에서 사용자 ID 값을 가져옴
$user_id = $_SESSION['USER_ID'];
$email= $_SESSION['USER_ID'];


// 데이터베이스 연결
$db = new mysqli('localhost', 'root', 'abc123', 'project');
// 데이터베이스 연결 오류 처리
if($db->connect_error) {
    die("데이터베이스 연결 실패: " . $db->connect_error);
}

// 사용자의 정보를 조회
$query = "SELECT user_name, department, employee_rank, user_birth, phone_number, email, profile_picture 
          FROM employee 
          WHERE user_id = ?";

$stmt = $db->prepare($query);
// SQL 쿼리의 파라미터에 사용자 ID 값을 바인딩
$stmt->bind_param('s', $user_id);

$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();


// 사용자의 비밀번호가 설정되어 있는지 확인하는 쿼리
$query_password = "SELECT user_pw FROM user WHERE user_id = ?";
$stmt_password = $db->prepare($query_password);
$stmt_password->bind_param('s', $user_id);
$stmt_password->execute();
$result_password = $stmt_password->get_result();
$row_password = $result_password->fetch_assoc();

// 조회된 정보를 변수에 저장
$user_name = $row['user_name'] ?? "Unknown";
$department = $row['department'] ?? "Unknown";
$employee_rank = $row['employee_rank'] ?? "Unknown";
$user_birth = $row['user_birth'] ?? "Unknown";
$phone_number = $row['phone_number'] ?? "Unknown";
$email = $row['email'] ?? "Unknown";
$profile_picture_path = $row['profile_picture'] ?? "Unknown";
// 프로필 이미지 경로 확인 및 기본 이미지 지정
if (!$profile_picture_path || $profile_picture_path == "Unknown") {
    $profile_picture_url = "default/path/to/image.jpg";  // Default image path
} else {
    $project_root_path = 'C:\Users\asdf\IdeaProjects\projcct\emp_profile';
    $profile_picture_url = str_replace($project_root_path, '', $profile_picture_path);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>협업툴</title>
    <style>
        @import url(css/header_style.css);
    </style>
</head>
<body>
<header>
    <nav id="top-nav">
        <div class="logo">
            <a href="main.php"><img src="img/WH.png" alt="Company Logo"></a>
        </div>
        <ul class="nav-links">
            <li><a href="announce.php">공지사항</a></li>
            <li><a href="day.html">내프로젝트</a></li>
            <li><a href="business_record.php">업무기록</a></li>
            <li><a href="day.html">캘린더</a></li>
            <li><a href="user_profile.php">인사정보 보기</a></li>
        </ul>
        <!-- 토글 메뉴 버튼 -->
        <!--<div class="menu-toggle">MENU</div>-->

        <!-- 토글 메뉴 내용 -->
        <!--        <div class="menu-content">
                    <a href="javascript:void(0)" class="closebtn">&times; Close</a>
                </div>-->
    </nav>
</header>
<main>
    <div id="main-content">
        <!--메인화면 그리드 레이아웃-->
        <div id="main_grid_container">
            <div class="content" id="con"> </div>
        </div>
    </div>
</main>
<script src="js/header_toggle.js">
    </script>
<script>
    function fetchData() {
        // AJAX를 사용하여 데이터 목록 가져오기
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "list_todo_detail.php", true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // 데이터 목록 표시
                var main_grid_container = document.getElementById("con");
                main_grid_container.innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }

    window.onload = function () {
        fetchData();
    };
</script>
</body>
</html>
