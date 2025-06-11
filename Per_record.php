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
</main>
<div id="main-content">
    <div id="user-details">
        <h2>인사정보</h2>
        <!-- 리뷰: 사용자로부터 정보를 수집하는 폼입니다. POST 방식을 사용하는 것이 보안에 더 좋습니다. -->
        <form action="save_user.php" method="POST" enctype="multipart/form-data">
            <fieldset>
                <legend onclick="toggleVisibility('personal-information')">개인 정보</legend>
                <div id="personal-information" class="expand">
                    <div id="left-section">
                        <label for="profile-picture">사진:</label><br/>
                        <input
                                type="file"
                                id="profile-picture"
                                name="profile_picture"
                                accept="image/*"
                                onchange="previewImage(event)"
                        /><br/>
                        <img
                                id="profile-picture-preview"
                                src=""
                                alt="profile picture preview"
                        /><br/>
                    </div>
                    <div id="right-section">
                        <label for="employee-id">사번:</label><br/>
                        <input type="text" id="employee-id" name="employee_id" required/><br/>
                        <label for="name">이름:</label><br/>
                        <input type="text" id="name" name="name" required/><br/>
                        <label for="birth">생년월일:</label><br/>
                        <input type="date" id="birth" name="birth" required/><br/>
                        <label for="ssn" title="숫자만 입력하세요">주민번호:</label><br/>
                        <input
                                type="text"
                                id="ssn"
                                name="ssn"
                                title="숫자만 입력하세요"
                                maxlength="13"
                                required
                                placeholder="숫자만 입력하세요"
                        /><br/>
                        <div class="radio-container">
                            <input type="radio" id="male" name="gender" value="male" required/>
                            <label for="male">남성</label>
                            <input type="radio" id="female" name="gender" value="female" required/>
                            <label for="female">여성</label>
                        </div>
                        <div class="radio-container">
                            <input type="radio" id="personal-domestic" name="personal_nationality" value="domestic" required/>
                            <label for="personal-domestic">내국인</label>
                            <input type="radio" id="personal-foreign" name="personal_nationality" value="foreign" required/>
                            <label for="personal-foreign">외국인</label>
                        </div>
                    </div>
            </fieldset>
            <fieldset>
                <legend onclick="toggleVisibility('family-information')">가족관계</legend>
                <div id="family-information" class="collapse">
                    <div class="family-member-container" id="family-member-0">
                        <div class="family-member-info">
                            <input type="text" name="family_member_name[0]" placeholder="구성원 이름을 입력하세요."/><br/>
                            <input type="text" name="family_member_relationship[0]" placeholder="구성원 관계를 입력하세요."/><br/>
                            <input type="date" name="family_member_birth[0]"/><br/>
                        </div>
                        <div class="family-member-status">
                            <div class="radio-container">
                                <input type="radio" id="family_domestic-0" name="family_nationality[0]" value="domestic" required/>
                                <label for="family_domestic-0">내국인</label>
                                <input type="radio" id="family_foreign-0" name="family_nationality[0]" value="foreign" required/>
                                <label for="family_foreign-0">외국인</label>
                            </div>
                            <div class="radio-container">
                                <input type="radio" id="employed-0" name="employment_status[0]" value="employed" required/>
                                <label for="employed-0">직장 있음</label>
                                <input type="radio" id="unemployed-0" name="employment_status[0]" value="unemployed" required/>
                                <label for="unemployed-0">직장 없음</label>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="add-family-member">+</button>
                    <button type="button" id="delete-family-member">-</button>
                </div>
            </fieldset>

            <fieldset class="contact-info">
                <legend onclick="toggleVisibility('contact-information')">연락처 정보</legend>
                <div id="contact-information" class="collapse">
                    <label for="postal_code">우편번호:</label><br/>
                    <input type="text" id="postal_code" name="postal_code" required placeholder="우편번호"/><br/>
                    <input type="button" onclick="postelcodefind()" value="우편번호 찾기"><br>
                    <div class="address-container">
                        <div>
                            <label for="road_address">도로명주소:</label><br/>
                            <input type="text" id="road_address" name="road_address" required placeholder="도로명주소"/><br/>
                        </div>
                        <div>
                            <label for="jibun_address">지번주소:</label><br/>
                            <input type="text" id="jibun_address" name="jibun_address" placeholder="지번주소"/><br/>
                        </div>
                    </div>

                    <label for="detail_address">상세주소:</label><br/>
                    <input type="text" id="detail_address" name="detail_address" required placeholder="상세주소"/><br/>

                    <div class="number-container">
                        <div>
                            <label for="mobile-number">휴대폰번호:</label><br/>
                            <input type="text" id="mobile-number" name="mobile_number" required placeholder="숫자만 입력하세요"/><br/>
                        </div>
                        <div>
                            <label for="phone-number">전화번호:</label><br/>
                            <input type="text" id="phone-number" name="phone_number" placeholder="숫자만 입력하세요"/><br/>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend onclick="toggleVisibility('company-information')">회사 정보</legend>
                <div id="company-information" class="collapse">
                    <div style="flex: 1;">
                        <div style="display: flex;">
                            <div style="flex: 1;">
                                <label for="join-date">입사일자:</label><br/>
                                <input type="date" id="join-date" name="join_date" required/><br/>
                            </div>
                            <div style="flex: 1;" >
                                <label for="employee_rank">직급:</label><br/>
                                <select id="employee-rank" name="employee_rank" required>
                                    <option value="">선택해주세요</option>
                                    <option value="회장">회장</option>
                                    <option value="부회장">부회장</option>
                                    <option value="사장">사장</option>
                                    <option value="부사장">부사장</option>
                                    <option value="전무이사">전무이사</option>
                                    <option value="상무이사">상무이사</option>
                                    <option value="이사">이사</option>
                                    <option value="부장">부장</option>
                                    <option value="차장">차장</option>
                                    <option value="과장">과장</option>
                                    <option value="대리">대리</option>
                                    <option value="주임">주임</option>
                                    <option value="사원">사원</option>
                                    <option value="인턴">인턴</option>
                                </select><br/>
                            </div>
                            <div style="flex: 1;">
                                <label for="department">부서:</label><br/>
                                <select id="department" name="department" required>
                                    <option value="">선택해주세요</option>
                                    <option value="마케팅">마케팅</option>
                                    <option value="영업">영업</option>
                                    <option value="인사">인사</option>
                                    <option value="재무">재무</option>
                                    <option value="연구개발">연구개발</option>
                                    <option value="생산">생산</option>
                                    <option value="IT">IT</option>
                                </select><br/>
                            </div>
                        </div>
                        <!-- 리뷰: 이메일 입력 필드입니다. 'required' 속성을 사용하여 필수 입력을 나타냈습니다.
                        가능하면 인라인 스타일 대신 CSS 클래스를 사용하는 것이 좋습니다. -->
                        <label for="email">이메일</label><br/>
                        <input type="email" id="email" name="email" required style="width: 100%;"/><br/>
                    </div>
                </div>
            </fieldset>
            <input type="submit" value="저장"/>
        </form>
    </div>
</div>
<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script src="js/kakao_api.js"></script>
<script src="js/header_toggle.js"></script>
<script src="js/family_info.js"></script>
<script src="js/img_preview.js"></script>
<script src="js/record_exception.js"></script>
<script src="js/toggle_btn.js"></script>
</body>
</html>

