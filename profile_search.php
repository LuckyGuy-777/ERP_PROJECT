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

$profile_picture_path = $row['profile_picture'] ?? ''; // null일 경우 빈 문자열로 대체

$project_root_path = 'C:\Users\asdf\IdeaProjects\projcct\emp_profile';

// 프로필 사진 경로가 null이 아닐 때만 str_replace를 실행
if (!empty($profile_picture_path)) {
    $profile_picture_url = str_replace($project_root_path, '', $profile_picture_path);
} else {
    $profile_picture_url = ''; // 경로가 없을 경우 빈 문자열 할당
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>협업툴</title>
    <style>
        @import url(css/header_style.css);
        @import url(css/profile_search.css);
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
        <div id="scrollable-content">
            <div class="tab">
                <button id="tablinks" class="tablinks" onclick="goBack()">돌아가기</button>
                <button class="tablinks" onclick="openTab(event, 'Family')">가족정보</button>
                <button class="tablinks" id="defaultOpen" onclick="openTab(event, 'Employee')">직원정보</button>
            </div>
            <div id="Employee" class="tabcontent">
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $name = $_POST["name"];

                    $servername = "localhost";
                    $username = "root";
                    $password = "abc123";
                    $dbname = "project";

                    $conn = new mysqli($servername, $username, $password, $dbname);

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $sql = "SELECT * FROM employee WHERE user_name = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $name);
                    $stmt->execute();

                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        echo "<div class='grid-container'>";
                        echo "<div class='grid-item-left'>";
                        echo "<div class='user-image'>";
                        echo "<img src='/emp_profile/" . $row["profile_picture"] . "' alt='Profile Picture' width='300'><br>";
                        echo "</div>";
                        echo "</div>";
                        echo "<div class='grid-item-right'>";
                        echo "<div class='user-data'>";
                        echo "<h2>사원정보:</h2>";
                        echo "<table>";
                        echo "<tr><th>사번</th><td>" . $row["employee_id"] . "</td></tr>";
                        echo "<tr><th>이름</th><td>" . $row["user_name"] . "</td></tr>";
                        echo "<tr><th>생년월일</th><td>" . $row["user_birth"] . "</td></tr>";
                        echo "<tr><th>주민번호</th><td>" . $row["user_ssn"] . "</td></tr>";
                        echo "<tr><th>성별</th><td>" . $row["user_gender"] . "</td></tr>";
                        echo "<tr><th>내국인/외국인</th><td>" . $row["user_nationality"] . "</td></tr>";
                        echo "<tr><th>도로명주소</th><td>" . $row["road_address"] . "</td></tr>";
                        echo "<tr><th>지번주소</th><td>" . $row["lot_number_address"] . "</td></tr>";
                        echo "<tr><th>우편번호</th><td>" . $row["postal_code"] . "</td></tr>";
                        echo "<tr><th>휴대폰번호</th><td>" . $row["phone_number"] . "</td></tr>";
                        echo "<tr><th>전화번호</th><td>" . $row["home_phone_number"] . "</td></tr>";
                        echo "<tr><th>입사일자</th><td>" . $row["join_date"] . "</td></tr>";
                        echo "<tr><th>직급</th><td>" . $row["employee_rank"] . "</td></tr>";
                        echo "<tr><th>부서</th><td>" . $row["department"] . "</td></tr>";
                        echo "<tr><th>이메일</th><td>" . $row["email"] . "</td></tr>";
                        echo "</table>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    } else {
                        $_SESSION['message'] = "해당 사용자는 존재하지않습니다";
                        echo "<script type='text/javascript'>alert('" . $_SESSION['message'] . "');window.location.href = 'user_profile.php';</script>";
                        unset($_SESSION['message']);
                    }
                }
                ?>
            </div>
            <div id="Family" class="tabcontent">
                <?php
                if ($result->num_rows > 0) {
                    $sql = "SELECT * FROM emp_family WHERE employee_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $row["employee_id"]);
                    $stmt->execute();

                    $resultFamily = $stmt->get_result();
                    if ($resultFamily->num_rows > 0) {
                        echo "<div class='family-member-grid'>";
                        echo "<h2>가족정보:</h2>";
                        while ($rowFamily = $resultFamily->fetch_assoc()) {
                            echo "<div class='family-member'>";
                            echo "<table>";
                            echo "<tr><th>이름</th><td>" . $rowFamily["family_name"] . "</td></tr>";
                            echo "<tr><th>관계</th><td>" . $rowFamily["family_relationship"] . "</td></tr>";
                            echo "<tr><th>생년월일</th><td>" . $rowFamily["family_birth"] . "</td></tr>";
                            echo "<tr><th>직장유무</th><td>" . $rowFamily["family_work"] . "</td></tr>";
                            echo "<tr><th>내국인/외국인</th><td>" . $rowFamily["family_nationality"] . "</td></tr>";
                            echo "</table>";
                            echo "</div>";
                            echo "<hr>";
                        }
                        echo "</div>";
                    } else {
                        echo "<h2>가족정보가 없습니다. " . $row["employee_id"] . "</h2>";
                    }
                    $conn->close();
                }
                ?>
            </div>
        </div>
    </div>
</main>
<script src="js/header_toggle.js"></script>
<script src="js/tabbtn.js"></script>
<script>
    function goBack() {
        window.location.href = 'user_profile.php';
    }
</script>
</body>
</html>
