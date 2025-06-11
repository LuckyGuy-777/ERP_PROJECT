<?php
// 오류 표시 설정
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// 세션 시작
session_start();

// 현재 사용자의 세션에서 ID 가져오기
$user_id = $_SESSION['USER_ID'];
// 데이터베이스 연결
$conn = new mysqli('localhost', 'root', 'abc123', 'project');
if ($conn->connect_error) {
    die("데이터베이스 연결 실패: " . $conn->connect_error);// 데이터베이스 연결 오류 처리
}
$db = new mysqli('localhost', 'root', 'abc123', 'project');
if($db->connect_error) {
    die("데이터베이스 연결 실패: " . $db->connect_error);
}
// 사용자 정보 조회 쿼리
$query = "SELECT u.user_name, e.department, e.employee_rank, e.user_birth, e.phone_number, e.email 
          FROM user u
          JOIN employee e ON u.user_id = e.user_id
          WHERE u.user_id = ?";

$stmt = $db->prepare($query);
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

// 공지사항 목록 가져오기
$query = "SELECT * FROM announce ORDER BY created_at DESC";
$result = $conn->query($query);
// 프로필 사진 조회 쿼리
$query = "SELECT profile_picture FROM employee WHERE user_id = ?";
$stmt = $db->prepare($query);
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

// 공지사항 검색 및 페이징 설정
$items_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $items_per_page;

$search_type = isset($_GET['search_type']) ? $_GET['search_type'] : "title";
$search_term = isset($_GET['search_term']) ? $_GET['search_term'] : "";

if (!empty($search_term)) {
    if ($search_type == "title") {
        $sql = "SELECT * FROM announce WHERE title LIKE ? ORDER BY created_at DESC LIMIT $start, $items_per_page";
    } elseif ($search_type == "user_name") {
        $sql = "SELECT * FROM announce WHERE user_name LIKE ? ORDER BY created_at DESC LIMIT $start, $items_per_page";
    }
    $stmt = $conn->prepare($sql);
    $search_term = "%" . $search_term . "%";
    $stmt->bind_param('s', $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM announce ORDER BY created_at DESC LIMIT $start, $items_per_page";
    $result = $conn->query($sql);
}
// 공지사항 데이터 배열에 저장
$notices = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notices[] = $row;
    }
}
// 전체 공지사항 페이지 수 계산
$sql = "SELECT COUNT(*) as total FROM announce";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_pages = ceil($row['total'] / $items_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>협업툴</title>
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
    <div class="announcement-container">
        <div class="announcement-header">
            공지사항
        </div>
        <div class="search-create-container">
            <a href="announce-create.php" class="create-notice-button">공지 작성하기</a>
            <div class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                    <?php if ($i == $page) : ?>
                        <a href="?page=<?= $i ?>" class="active"><?= $i ?></a>
                    <?php else : ?>
                        <a href="?page=<?= $i ?>"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
            <div class="search-section">
                <form action="" method="GET">
                    <select name="search_type">
                        <option value="title">제목</option>
                        <option value="user_name">작성자</option>
                    </select>
                    <input type="text" name="search_term" placeholder="검색...">
                    <button type="submit">검색</button>
                </form>
            </div>
        </div>
        <div class="announcement-list">
            <?php foreach ($notices as $notice) : ?>
                <div class="announcement-item">
                    <h2 class="announcement-title"><a href="announce-read.php?id=<?= $notice['id'] ?>"><?= $notice['title'] ?></a></h2>
                    <p class="announcement-meta">작성자: <?= $notice['user_name'] ?> | 작성일: <?= $notice['created_at'] ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>
    <script src="js/header_toggle.js"></script>
</body>
</html>
