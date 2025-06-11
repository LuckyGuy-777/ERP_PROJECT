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
    if (!$permissions['announce_create']) {
        echo "<script>alert('관리자 권한이 없습니다.');</script>"; // 권한이 없으면 알림 메시지 출력
        header("Location: announce.php"); // announce.php로 리다이렉트
    }
    // 여기에 추가적인 권한 확인 로직을 추가할 수 있습니다.
} else {
    echo "<script>alert('권한을 확인할 수 없습니다.');</script>"; // 권한 정보가 없으면 알림 메시지 출력
    header("Location: announce.php"); // announce.php로 리다이렉트
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
<?php

// 리뷰: 세션을 시작합니다. 로그인 여부나 사용자 정보 등을 세션에서 관리합니다.
if (!isset($_SESSION['USER_ID'])) {
    header("Location: login.php");
    exit();
}
// 리뷰: 데이터베이스 연결을 위한 설정입니다. 실제 서비스에서는 이 정보를 별도의 파일이나 환경 변수에서 관리하는 것이 보안상 좋습니다.
$servername = "localhost";
$username = "root";
$password = "abc123";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("데이터베이스 연결 실패: " . $conn->connect_error);
}

$user_id = $_SESSION['USER_ID'];
// 리뷰: SQL 쿼리를 준비하고, 파라미터를 바인딩하여 SQL 인젝션을 방지합니다.$sql);
$sql = "SELECT user_name FROM employee WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $author = $row['user_name'];
} else {
    echo "No user found with ID: $user_id";
    exit();
}

// 리뷰: 사용자로부터 전송된 데이터를 변수에 저장합니다.
// 입력 데이터는 데이터베이스에 저장하기 전에 추가적인 검증과 정제가 필요할 수 있습니다.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $message = nl2br(trim($_POST['message']));
    $font = trim($_POST['font']);
    $size = trim($_POST['size']);

    $file = $_FILES['file'];
    $filename = $file['name'];
    $filetmp = $file['tmp_name'];

    // 리뷰: 파일 업로드를 위한 경로를 설정합니다. 서버 설정에 따라 실제 경로가 다를 수 있습니다
    $uploadDir = "C:/Users/asdf/IdeaProjects/projcct/file/";
    $uploadPath = $uploadDir . basename($filename);

    if (!empty($filename)) {
        if (move_uploaded_file($filetmp, $uploadPath)) {
            echo "The file " . basename($filename) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // 리뷰: SQL 쿼리를 준비하고, 파라미터를 바인딩하여 SQL 인젝션을 방지합니다.
    $stmt = $conn->prepare("INSERT INTO announce (user_id, title, message, font, size, user_name, filename, created_at) 
VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");

    $stmt->bind_param("sssssss", $user_id, $title, $message, $font, $size, $author, $filename);
    $stmt->execute();
    $stmt->close();

    $conn->close();

    // announce.php로 이동
    header("Location: announce.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>공지작성</title>
    <style>
        @import url(css/header_style.css);
        @import url(css/announce.css);
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
        <!--리뷰: 사용자가 공지사항을 작성하고 파일을 업로드할 수 있는 폼입니다. 'enctype' 속성은 파일 업로드를 위해 필요합니다.-->
        <div id="notice-form">
            <form method="post" enctype="multipart/form-data">
                <label for="title">공지사항 제목:</label>
                <input type="text" id="title" name="title" required placeholder="제목을 입력하세요"/>
                <label for="message">내용:</label>
                <textarea id="message" name="message" rows="4" cols="50" required placeholder="내용을 입력하세요"></textarea>
                <div class="font-grid-container">
                    <div>
                        <label for="font">폰트:</label>
                        <select id="font" name="font">
                            <option value="Arial">Arial</option>
                            <option value="Verdana">Verdana</option>
                            <option value="Courier New">Courier New</option>
                            <option value="Georgia">Georgia</option>
                            <option value="Times New Roman">Times New Roman</option>
                            <option value="Comic Sans MS">Comic Sans MS</option>
                            <option value="Lucida Sans Unicode">Lucida Sans Unicode</option>
                            <option value="Tahoma">Tahoma</option>
                            <option value="Trebuchet MS">Trebuchet MS</option>
                        </select>
                    </div>
                    <div>
                        <label for="size">글자 크기:</label>
                        <select id="size" name="size">
                            <option value="12">12</option>
                            <option value="14">14</option>
                            <option value="16">16</option>
                            <option value="18">18</option>
                            <option value="20">20</option>
                        </select>
                    </div>
                </div>
                <label for="file">파일 업로드</label>
                <input type="file" id="file" name="file"/>
                <input type="submit" value="등록"/>
            </form>
        </div>
    </div>
</main>
<script src="js/header_toggle.js"></script>
<script src="js/announce_font,size.js"></script>
</body>
</html>
