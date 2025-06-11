<!--PHP 스크립트 시작-->
<?php
// 모든 오류를 화면에 표시하도록 설정
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 세션 시작. 웹 페이지 간의 데이터를 저장하거나 전달하는 데 사용
session_start();

// 세션에서 사용자 ID 값을 가져옴
$user_id = $_SESSION['USER_ID'];  // 세션에서 사용자 ID를 가져옵니다.

// 데이터베이스 연결
$db = // MySQL 데이터베이스에 연결. 호스트, 사용자 이름, 비밀번호, 데이터베이스 이름을 인자로 제공

    new mysqli('localhost', 'root', 'abc123', 'project');
// 데이터베이스 연결 오류 처리
if($db->connect_error) {
    die("데이터베이스 연결 실패: " . $db->connect_error);
}

// 사용자의 권한을 데이터베이스에서 가져옴
$permission_query = "SELECT * FROM user_permissions WHERE user_id = ?";
$permission_stmt = $db->prepare($permission_query);
$permission_stmt->bind_param('s', $user_id);

error_log("About to fetch permissions for User ID: " . $user_id); // 로그 추가

if ($permission_stmt->execute()) {
    error_log("Query executed successfully."); // 로그 추가
} else {
    error_log("Query execution failed: " . $db->error); // 로그 추가
}

$permission_result = $permission_stmt->get_result();
$permissions = $permission_result->fetch_assoc();

if ($permissions) {
    error_log("Fetched permissions: " . json_encode($permissions)); // 로그 추가
} else {
    error_log("No permissions found for User ID: " . $user_id); // 로그 추가
}


//echo "Permissions Array: <pre>";
//var_dump($permissions);
//echo "</pre><br>";        db권한체크

// 권한 확인 로직
if ($permissions) {
    if (!$permissions['user_profile']) {
        echo "<script>alert('관리자 권한이 없습니다.');</script>"; // 권한이 없으면 알림 메시지 출력
        header("Location: main.php"); //main.php로 리다이렉트
    }
    // 여기에 추가적인 권한 확인 로직을 추가할 수 있습니다.
} else {
    echo "<script>alert('권한을 확인할 수 없습니다.');</script>"; // 권한 정보가 없으면 알림 메시지 출력
    header("Location: main.php"); //main.php로 리다이렉트
}

// 사용자의 프로필 사진 경로, 이름, 사번을 조회
$query = "SELECT u.user_name, e.department, e.employee_rank, e.user_birth, e.phone_number, e.email 
          FROM user u
          JOIN employee e ON u.user_id = e.user_id
          WHERE u.user_id = ?";

$stmt = $db->prepare($query);
// SQL 쿼리의 파라미터에 사용자 ID 값을 바인딩
$stmt->bind_param('s', $user_id);

$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// 조회된 정보를 변수에 저장
$user_name = $row['user_name'];
$department = $row['department'];
$employee_rank = $row['employee_rank'];
$user_birth = $row['user_birth'];
$phone_number = $row['phone_number'];
$email = $row['email'];
$employee_info = [
    'user_name' => $row['user_name'],
    'department' => $row['department'],
    'employee_rank' => $row['employee_rank'],
    'user_birth' => $row['user_birth'],
    'phone_number' => $row['phone_number'],
    'email' => $row['email']
];



// 사용자의 프로필 사진 경로를 조회
$query = "SELECT profile_picture FROM employee WHERE user_id = ?";
$stmt = $db->prepare($query);
// SQL 쿼리의 파라미터에 사용자 ID 값을 바인딩
$stmt->bind_param('s', $user_id);

$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$profile_picture_path = $row['profile_picture'];  // 프로필 사진의 로컬 파일 시스템 경로를 가져옵니다.

// 프로필 사진 경로 처리
$project_root_path = 'C:\Users\asdf\IdeaProjects\projcct\emp_profile';
if ($profile_picture_path) {
    $profile_picture_url = str_replace($project_root_path, '', $profile_picture_path);
} else {
    $profile_picture_url = '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>프로필</title>
    <style>
        @import url(css/header_style.css);
        @import url(css/user_profile.css);
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
        <h2>Search User</h2>
        <p>검색할 사용자의 이름을 입력하세요</p>
        <form method="POST" action="profile_search.php">
            <input type="text" name="name" placeholder="Enter name">
            <input type="submit" value="검색">
        </form>
        <h2>Download User Profile</h2>
        <p>다운로드할 사용자의 이름을 입력하세요</p>
        <form method="POST" action="profile_download.php">
            <input type="text" id="download-name" name="Name" required placeholder="Enter name">
            <input type="submit" value="다운로드">
        </form>
        <h2>Update User Profile</h2>
        <p>수정할 사용자의 이름을 입력하세요</p>
        <form method="POST" action="profile_update.php">
            <input type="text" id="update-name" name="Name" required placeholder="Enter name">
            <input type="submit" value="수정">
        </form>
    </div>
</main>
<script src="js/header_toggle.js"></script>
</body>
</html>
