<!--PHP 스크립트 시작-->
<?php
// 모든 오류를 화면에 표시하도록 설정
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 에러를 파일로 로깅
ini_set('log_errors', 1);
ini_set('error_log', 'C:/Users/asdf/IdeaProjects/projcct/php-error.log'); // 에러 로그 경로 설정

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

// 사용자의 권한을 데이터베이스에서 가져옴
$permission_query = "SELECT * FROM user_permissions WHERE user_id = ?";
$permission_stmt = $db->prepare($permission_query);
$permission_stmt->bind_param('s', $user_id);

// 로그에 권한 조회 시작을 추가
error_log("About to fetch permissions for User ID: " . $user_id); // 로그 추가

// 권한 조회 쿼리 실행
if ($permission_stmt->execute()) {
    // 로그에 쿼리 성공을 추가
    error_log("Query executed successfully."); // 로그 추가
} else {
    // 로그에 쿼리 실패 및 에러 메시지를 추가
    error_log("Query execution failed: " . $db->error); // 로그 추가
}
// 권한 결과 가져오기

$permission_result = $permission_stmt->get_result();
$permissions = $permission_result->fetch_assoc();

// 권한이 존재하면 로그에 권한 정보 추가
if ($permissions) {
    error_log("Fetched permissions: " . json_encode($permissions)); // 로그 추가
} else {
    // 권한이 없으면 로그에 메시지 추가
    error_log("No permissions found for User ID: " . $user_id); // 로그 추가
}

//echo "Permissions Array: <pre>";
//var_dump($permissions);
//echo "</pre><br>";        db권한체크

// 권한 확인 로직
if ($permissions) {
    if (!$permissions['manager']) {
        echo "<script>alert('관리자 권한이 없습니다.');</script>"; // 권한이 없으면 알림 메시지 출력
        header("Location: main.php"); //main.php로 리다이렉트
    }
    // 여기에 추가적인 권한 확인 로직을 추가할 수 있습니다.
} else {
    echo "<script>alert('권한을 확인할 수 없습니다.');</script>"; // 권한 정보가 없으면 알림 메시지 출력
    header("Location: main.php"); //main.php로 리다이렉트
}

// 사용자의 정보를 조회
$query = "SELECT user_name, department, employee_rank, user_birth, phone_number, email, profile_picture 
          FROM employee 
          WHERE user_id = ?";

$stmt = $db->prepare($query);
// SQL 쿼리의 파라미터에 사용자 ID 값을 바인딩
$stmt->bind_param('s', $user_id);
// 사용자의 정보를 조회하여 변수에 저장
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

// 프로필 사진 경로가 없거나 "Unknown"인 경우 기본 이미지 경로 설정
if (!$profile_picture_path || $profile_picture_path == "Unknown") {
    $profile_picture_url = "default/path/to/image.jpg";  // Default image path
} else {
    // 프로젝트 루트 경로를 기준으로 상대 경로 설정
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
        /*메인페이지는 뭐없어서 분할안함*/
        @import url(css/header_style.css);
        @import url(css/manager.css);
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
        <!--mysql에서 1이면 권한 있고 0이면 없는거-->
        <div id="manage-content">
            <form action="manager.php" method="post">
                <input type="text" name="search_user" placeholder="사용자 이름으로 검색">
                <input type="submit" value="검색">
            </form>

            <?php   // 사용자가 검색어를 입력한 경우
            if (isset($_POST['search_user'])) {
                // 검색어와 일치하는 사용자 정보를 조회하는 쿼리 작성
                $searched_user = $_POST['search_user'];
                $query = "SELECT employee_id, user_name, department, employee_rank FROM employee WHERE user_name LIKE ?";
                // 쿼리를 실행하기 위해 준비
                $stmt = $db->prepare($query);
                // 검색어에 와일드카드를 추가하여 부분 일치하는 사용자 이름을 찾음
                $search_term = "%" . $searched_user . "%";
                $stmt->bind_param('s', $search_term);
                $stmt->execute();
                // 쿼리 결과를 얻어옴
                $result = $stmt->get_result();
                // 조회된 사용자 정보를 반복하여 출력
                while ($row = $result->fetch_assoc()) {
                    echo "이름: " . $row['user_name'] . ", 사번: " . $row['employee_id'] . ", 부서: " . $row['department'] . ", 직급: " . $row['employee_rank'];
                    ?>
                    <form action="manager.php" method="post">
                        <input type="hidden" name="user_id" value="<?php echo $row['employee_id']; ?>">
                        <label><input type="checkbox" name="permissions[]" value="announce-create"> 공지작성</label>
                        <label><input type="checkbox" name="permissions[]" value="per_reord"> 인사카드 작성</label>
                        <label><input type="checkbox" name="permissions[]" value="user_profile"> 인사정보</label>
                        <label><input type="checkbox" name="permissions[]" value="manager"> 관리자</label>
                        <label><input type="checkbox" name="permissions[]" value="record_attendance"> 근태관리</label>
                        <input type="submit" name="set_permission" value="권한 설정">
                    </form>
                    <?php
                }
            }
            // 사용자가 권한을 설정하는 POST 요청을 처리
            if (isset($_POST['set_permission']) && isset($_POST['user_id']) && isset($_POST['permissions'])) {
                $user_id = $_POST['user_id'];   // 사용자로부터 전달된 사용자 ID와 권한 목록을 변수에 할당
                $permissions = $_POST['permissions'];


                if (isset($_POST['set_permission']) && isset($_POST['user_id']) && isset($_POST['permissions'])) {
                    // 사용자로부터 전달된 사용자 ID와 권한 목록을 변수에 할당
                    $user_id = $_POST['user_id'];
                    $permissions = $_POST['permissions'];
                    // 각 권한이 설정되어 있는지에 따라 1 또는 0으로 변환
                    //테이블에서 1이면 권한존재,0이면 권한없음
                    $announce_create = in_array("announce-create", $permissions) ? 1 : 0;
                    $per_record = in_array("per_reord", $permissions) ? 1 : 0;
                    $user_profile = in_array("user_profile", $permissions) ? 1 : 0;
                    $manager = in_array("manager", $permissions) ? 1 : 0;

                    // 해당 사용자의 권한 정보가 있는지 확인
                    $check_query = "SELECT id FROM user_permissions WHERE employee_id = ?";
                    $check_stmt = $db->prepare($check_query);
                    $check_stmt->bind_param('s', $user_id);
                    $check_stmt->execute();
                    $check_result = $check_stmt->get_result();

                    // 사용자의 권한 정보가 이미 존재하는 경우
                    if ($check_result->num_rows > 0) {
                        // 권한 업데이트 쿼리 작성 및 실행
                        $update_query = "UPDATE user_permissions SET announce_create = ?, per_record = ?, user_profile = ?, manager = ? WHERE employee_id = ?";
                        $update_stmt = $db->prepare($update_query);
                        $update_stmt->bind_param('iiisi', $announce_create, $per_record, $user_profile, $manager, $user_id);
                        $update_stmt->execute();
                    } else {
                        // 사용자의 권한 정보가 없는 경우
                        // 새로운 레코드를 추가하는 쿼리 작성 및 실행
                        $insert_query = "INSERT INTO user_permissions (employee_id, announce_create, per_record, user_profile, manager) VALUES (?, ?, ?, ?, ?)";
                        $insert_stmt = $db->prepare($insert_query);
                        $insert_stmt->bind_param('siiii', $user_id, $announce_create, $per_record, $user_profile, $manager);
                        $insert_stmt->execute();
                    }
                }
            }
            ?>
        </div>
    </div>
</main>
</div>
<script src="js/header_toggle.js"></script>
</body>
</html>
