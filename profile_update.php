<!--기존의 직원정보가 니외야 하는데 안나옴-->
<?php
// 세션 시작. 웹 페이지 간의 데이터를 저장하거나 전달하는 데 사용
session_start();
// 세션에서 사용자 ID 값을 가져옴
$user_id = $_SESSION['USER_ID'];  // 세션에서 사용자 ID를 가져옵니다.
$name = $_POST["Name"];

$db = new mysqli('localhost', 'root', 'abc123', 'project');
if ($db->connect_error) {
    die("데이터베이스 연결 실패: " . $db->connect_error);
}

// Fetch the user's current data from the database
$result = $db->query("SELECT * FROM user JOIN employee ON user.user_id = employee.user_id WHERE user.user_id = '$user_id'");
$user_data = $result->fetch_assoc();

// Fetch the user's family data
$family_result = $db->query("SELECT * FROM emp_family WHERE employee_id = '{$user_data['employee_id']}'");
$family_data = $family_result->fetch_all(MYSQLI_ASSOC);
?>
<!--PHP 스크립트 시작-->
<?php
// 모든 오류를 화면에 표시하도록 설정
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


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
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>인사카드</title>
    <style>
        @import url(css/header_style.css);
        @import url(css/per_record.css);

        .collapse {
            display: none;
        }

        .expand {
            display: block;
        }
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
        <div id="user-details">
            <h2>인사정보</h2>
            <!-- 프로필 사진 영역 -->
            <div class="profile-picture-section">
                <h3>프로필 사진</h3>
                <!-- 현재 프로필 사진을 표시 -->
                <img id="current-profile-picture" src="<?php echo $profile_picture_url; ?>" alt="프로필 사진" width="150" height="150">
                <br>
                <!-- 이미지 파일 선택 입력 필드 -->
                <input type="file" name="profile_picture" id="profile-picture-input" accept="image/*">
                <!-- 이미지 미리보기 영역 -->
                <img id="profile-picture-preview" src="#" alt="프로필 사진 미리보기" style="display: none;" width="150" height="150">
            </div>

            <form action="profile_update_process.php" method="POST" enctype="multipart/form-data">
                <h1>사번:</h1>
                <input type="text" name="user_name" value="<?php echo $user_data['employee_id']; ?>">
                <h1>이름:</h1>
                <input type="text" name="user_name" value="<?php echo $user_data['user_name']; ?>">
                <h1>생년월일:</h1>
                <input type="date" name="user_birth" value="<?php echo $user_data['user_birth']; ?>">
                <h1>주민번호:</h1>
                <input type="text" name="user_name" value="<?php echo $user_data['user_ssn']; ?>">
                <h1>성별:</h1>
                <input type="text" name="user_gender" value="<?php echo $user_data['user_gender']; ?>">
                <h1>국적:</h1>
                <input type="text" name="user_nationality" value="<?php echo $user_data['user_nationality']; ?>">
                <h1>부서:</h1>
                <input type="text" name="department" value="<?php echo $user_data['department']; ?>">
                <h1>직급:</h1>
                <input type="text" name="employee_rank" value="<?php echo $user_data['employee_rank']; ?>">
                <h1>이메일:</h1>
                <input type="email" name="email" value="<?php echo $user_data['email']; ?>">
                <h1>전화번호:</h1>
                <input type="text" name="phone_number" value="<?php echo $user_data['phone_number']; ?>">
                <h1>집 전화번호:</h1>
                <input type="text" name="home_phone_number" value="<?php echo $user_data['home_phone_number']; ?>">
                <h1>주소:</h1>
                <textarea name="total_address"><?php echo $user_data['total_address']; ?></textarea>
                <h1>도로명 주소:</h1>
                <textarea name="road_address"><?php echo $user_data['road_address']; ?></textarea>
                <h1>우편 번호:</h1>
                <input type="text" name="postal_code" value="<?php echo $user_data['postal_code']; ?>">

                <h2>가족정보</h2>
                <form id="family-form" action="profile_update_process.php" method="POST" enctype="multipart/form-data">
                    <div id="family-members">
                        <?php foreach ($family_data as $family_member) { ?>
                            <div class="family-member">
                                <h3>가족 구성원:</h3>
                                <label>이름: <input type="text" name="family_member_name[]" value="<?php echo $family_member['family_name']; ?>"></label>
                                <label>생년월일: <input type="date" name="family_member_birth[]" value="<?php echo $family_member['family_birth']; ?>"></label>
                                <label>관계: <input type="text" name="family_member_relationship[]" value="<?php echo $family_member['family_relationship']; ?>"></label>
                                <label>국적: <input type="text" name="family_nationality[]" value="<?php echo $family_member['family_nationality']; ?>"></label>
                                <label>직업 상태: <input type="text" name="employment_status[]" value="<?php echo $family_member['family_work']; ?>"></label>
                                <button type="button" onclick="removeFamilyMember(this)">제거</button>
                            </div>
                        <?php } ?>
                    </div>
                    <button type="button" onclick="addFamilyMember()">가족 구성원 추가</button><br>

                    <input type="submit" value="수정하기"/>
                </form>

        </div>
    </div>
</main>
<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script src="js/kakao_api.js"></script>
<script src="js/header_toggle.js"></script>
<script src="js/family_info.js"></script>
<script src="js/img_preview.js"></script>
<script src="js/record_exception.js"></script>
<script src="js/toggle_btn.js"></script>
<script>
    //회원정보 수정에서 가족추가
    function addFamilyMember() {
        const familyMembersDiv = document.getElementById('family-members');
        const div = document.createElement('div');
        div.className = 'family-member';

        div.innerHTML = `
        <h3>가족 구성원:</h3>
        <label>이름: <input type="text" name="family_member_name[]"></label>
        <label>생년월일: <input type="date" name="family_member_birth[]"></label>
        <label>관계: <input type="text" name="family_member_relationship[]"></label>
        <label>국적: <input type="text" name="family_nationality[]"></label>
        <label>직업 상태: <input type="text" name="employment_status[]"></label>
        <button type="button" onclick="removeFamilyMember(this)">제거</button>
    `;

        familyMembersDiv.appendChild(div);
    }

    function removeFamilyMember(button) {
        const familyMembersDiv = document.getElementById('family-members');
        familyMembersDiv.removeChild(button.parentElement);
    }

    //프로필사진
    document.getElementById('profile-picture-input').addEventListener('change', function(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                const previewImage = document.getElementById('profile-picture-preview');
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
                document.getElementById('current-profile-picture').style.display = 'none';
            }

            reader.readAsDataURL(input.files[0]); // 이미지 파일을 Base64 형식으로 읽어옵니다.
        }
    });
</script>
</body>
</html>

