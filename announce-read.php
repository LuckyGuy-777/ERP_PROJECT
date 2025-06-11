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
        <?php
        // 데이터베이스 연결 설정
        $servername = "localhost";
        $username = "root";
        $password = "abc123";
        $dbname = "project";
        // 데이터베이스 연결
        $conn = new mysqli($servername, $username, $password, $dbname);
        // 연결 실패 시 에러 출력
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        // 게시글 ID 확인
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            // 게시글 정보 조회 쿼리 준비
            $stmt = $conn->prepare("SELECT user_name, title, created_at, message, filename, font, size FROM announce WHERE id = ?");
            $stmt->bind_param("i", $id); // "i" indicates the variable type is integer
            // 쿼리 실행
            $stmt->execute();
            // 결과 바인딩
            $stmt->bind_result($user_name, $title, $created_at, $message, $filename, $font, $size);
            while ($stmt->fetch()) {
            }
            // 결과 루프에서 아무 작업도 수행하지 않음
            // fetch() 함수는 한 번만 호출되기 때문에 정보를 변수에 할당하기만 하면 됨
            $stmt->close();// 사용한 statement 닫기
        }
        $conn->close();// 데이터베이스 연결 닫기
        ?>

        <div class="announce-read">
            <div class="announce-item">
                <span class="announce-label">이름:</span>
                <span class="announce-content"><?php echo $user_name; ?></span>
            </div>

            <div class="announce-item">
                <span class="announce-label">작성 시간:</span>
                <span class="announce-content"><?php echo $created_at; ?></span>
            </div>

            <div class="announce-item">
                <span class="announce-label">제목:</span>
                <h2 class="announce-content"><?php echo $title; ?></h2>
            </div>

            <div class="announce-item">
                <span class="announce-label">내용:</span>
                <p class="announce-content"
                   style="font-family:<?php echo $font; ?>;
                           font-size:<?php echo $size; ?>px;">
                    <?php echo nl2br($message); ?></p>
            </div>

            <div class="announce-item">
                <span class="announce-label">첨부 파일:</span>
                <span class="announce-content">
                    <?php
                    if($filename) {
                        echo $filename; ?>
                        <a href="download.php?file=<?php echo urlencode($filename); ?>" style="margin-left: 10px;">
                             <button type="button" class="announce-button-style">Download</button></a>
                        <?php
                            } else {
                        echo "첨부파일이 없습니다.";
                            }
                        ?>
                </span>
            </div>
        </div>
        <div class="announce-button">
            <button class="announce-button-style" type="button" onclick="location.href='announce.php'">목록</button>
        </div>
    </div>
</main>
<script src="js/header_toggle.js"></script>
</body>
</html>