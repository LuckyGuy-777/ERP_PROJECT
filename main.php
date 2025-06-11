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
$query = "SELECT user_name, department, employee_rank, user_birth, phone_number, email, profile_picture, employee_id 
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
$employee_id = $row['employee_id'] ?? "Unknown";

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Firebase App (필수) -->
    <script src="https://www.gstatic.com/firebasejs/8.2.0/firebase-app.js"></script>
    <!-- Firebase Messaging -->
    <script src="https://www.gstatic.com/firebasejs/8.2.0/firebase-messaging.js"></script>
    <script>
        // Firebase 프로젝트 설정 초기화
        // For Firebase JS SDK v7.20.0 and later, measurementId is optional
        const firebaseConfig = {
            apiKey: "AIzaSyDacendbQyNnoUPkptAVmBdDVw0TZFLAZc",
            authDomain: "erp-project-396905.firebaseapp.com",
            projectId: "erp-project-396905",
            storageBucket: "erp-project-396905.appspot.com",
            messagingSenderId: "186994599842",
            appId: "1:186994599842:web:0166c5a4f228b9135f34fb",
            measurementId: "G-T6SSDVR3Y3"
        };
        firebase.initializeApp(firebaseConfig);
        // Firebase Messaging 객체 초기화
        const messaging = firebase.messaging();

        // 사용자에게 알림 권한 요청
        messaging.requestPermission().then(() => {
            return messaging.getToken(); // 사용자의 디바이스 토큰을 얻음
        }).then((token) => {
            console.log("FCM Token:", token);
            // AJAX 요청을 사용하여 서버에 토큰 전송
            fetch('save_token.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `token=${token}`
            });
        }).catch((error) => {
            console.error("토큰을 가져오는데 실패했습니다.", error);
        });
    </script>
    <!-- 서비스 워커 등록 -->
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/firebase-messaging-sw.js')
                .then(function(registration) {
                    console.log('Service Worker Registered', registration);
                })
                .catch(function(err) {
                    console.error('Service Worker Registration Failed', err);
                });
        }
    </script>
    <style>
        @import url(css/header_style.css);
        @import url(css/grid_style.css);
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
    <div class="grid-container">
        <div class="item1">
            <!-- 증명사진 출력 공간 -->
<!--            <div class="profile-picture">
                <img src="<?php /*echo htmlspecialchars($profile_picture_url); */?>" alt="Profile Picture" style="width:3cm; height:4cm;">
            </div>-->

            <p>이름: <?php echo htmlspecialchars($user_name); ?></p>
            <p>부서: <?php echo htmlspecialchars($department); ?></p>
            <p>직급: <?php echo htmlspecialchars($employee_rank); ?></p>
            <p>사번: <?php echo htmlspecialchars($employee_id); ?></p>

            <div class="buttons">
                <form action="attendance.php" method="post" class="attendance-form">
                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                    <input type="hidden" name="type" value="arrival">
                    <button type="submit" class="button-style">출근</button>
                </form>
                <form action="attendance.php" method="post" class="attendance-form">
                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                    <input type="hidden" name="type" value="departure">
                    <button type="submit" class="button-style">퇴근</button>
                </form>
                <button id="logout-button" class="button-style" onclick="location.href='logout.php'">로그아웃</button>
                <button id="manage-button" class="button-style" onclick="location.href='manager.php'">관리자</button>
                <button id="manage-button" class="button-style" onclick="location.href='Per_record.php'">인사카드 작성</button>
            </div>
        </div>
        <div class="item2">
            <h2>공지사항</h2>
            <?php
            // 공지사항을 조회하는 쿼리
            $query = "SELECT title, created_at FROM announce ORDER BY created_at DESC LIMIT 5";
            $result = $db->query($query);

            if ($result->num_rows > 0) {
                // 공지사항 데이터 출력
                while($announce = $result->fetch_assoc()) {
                    echo "<p><strong>" . htmlspecialchars($announce['title']) . "</strong> - ";
                    echo "<small>" . htmlspecialchars($announce['created_at']) . "</small></p>";
                }
            } else {
                echo "<p>현재 공지사항이 없습니다.</p>";
            }
            ?>
            <a href="announce.php">더보기</a>
        </div>
        <div class="item3">
            <div class="sec_cal">
                <div class="cal_nav">
                    <a href="javascript:;" class="nav-btn go-prev">prev</a>
                    <div class="year-month"></div>
                    <a href="javascript:;" class="nav-btn go-next">next</a>
                </div>
                <div class="cal_wrap">
                    <div class="days">
                        <div class="day">MON</div>
                        <div class="day">TUE</div>
                        <div class="day">WED</div>
                        <div class="day">THU</div>
                        <div class="day">FRI</div>
                        <div class="day">SAT</div>
                        <div class="day">SUN</div>
                    </div>
                    <div class="dates"></div>
                </div>
            </div>
        </div>
        <div class="item4" id="item4"> <!-- ID 추가 -->
            <!-- 여기에 할일 데이터가 표시될 것입니다 -->
        </div>
        <div class="item5"><a href="memo.html">메모</a></div>
    </div>
</main>
</body>
<script src="js/header_toggle.js"></script>
<script>
    //메인에서 할일 출력
    function fetchData() {
        // AJAX를 사용하여 데이터 목록 가져오기
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "list_todo_detail.php", true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // 데이터 목록 표시
                var item4 = document.getElementById("item4");
                item4.innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }

    window.onload = function () {
        fetchData();
    };

    // 사용자에게 알림 권한 요청(fcm)
    messaging.requestPermission().then(() => {
        return messaging.getToken(); // 사용자의 디바이스 토큰을 얻음
    }).then((token) => {
        console.log("FCM Token:", token);
        // AJAX 요청을 사용하여 서버에 토큰 전송
        fetch('save_token.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `token=${token}`
        });
    }).catch((error) => {
        console.error("토큰을 가져오는데 실패했습니다.", error);
    });
</script>
<script>
    $(document).ready(function() {
        calendarInit();
    });
    /*
        달력 렌더링 할 때 필요한 정보 목록

        현재 월(초기값 : 현재 시간)
        금월 마지막일 날짜와 요일
        전월 마지막일 날짜와 요일
    */

    function calendarInit() {

        // 날짜 정보 가져오기
        var date = new Date(); // 현재 날짜(로컬 기준) 가져오기
        var utc = date.getTime() + (date.getTimezoneOffset() * 60 * 1000); // uct 표준시 도출
        var kstGap = 9 * 60 * 60 * 1000; // 한국 kst 기준시간 더하기
        var today = new Date(utc + kstGap); // 한국 시간으로 date 객체 만들기(오늘)

        var thisMonth = new Date(today.getFullYear(), today.getMonth(), today.getDate());
        // 달력에서 표기하는 날짜 객체


        var currentYear = thisMonth.getFullYear(); // 달력에서 표기하는 연
        var currentMonth = thisMonth.getMonth(); // 달력에서 표기하는 월
        var currentDate = thisMonth.getDate(); // 달력에서 표기하는 일

        // kst 기준 현재시간
        // console.log(thisMonth);

        // 캘린더 렌더링
        renderCalender(thisMonth);

        function renderCalender(thisMonth) {

            // 렌더링을 위한 데이터 정리
            currentYear = thisMonth.getFullYear();
            currentMonth = thisMonth.getMonth();
            currentDate = thisMonth.getDate();

            // 이전 달의 마지막 날 날짜와 요일 구하기
            var startDay = new Date(currentYear, currentMonth, 0);
            var prevDate = startDay.getDate();
            var prevDay = startDay.getDay();

            // 이번 달의 마지막날 날짜와 요일 구하기
            var endDay = new Date(currentYear, currentMonth + 1, 0);
            var nextDate = endDay.getDate();
            var nextDay = endDay.getDay();

            // console.log(prevDate, prevDay, nextDate, nextDay);

            // 현재 월 표기
            $('.year-month').text(currentYear + '.' + (currentMonth + 1));

            // 렌더링 html 요소 생성
            calendar = document.querySelector('.dates')
            calendar.innerHTML = '';

            // 지난달
            for (var i = prevDate - prevDay + 1; i <= prevDate; i++) {
                calendar.innerHTML = calendar.innerHTML + '<div class="day prev disable">' + i + '</div>'
            }
            // 이번달
            for (var i = 1; i <= nextDate; i++) {
                calendar.innerHTML = calendar.innerHTML + '<div class="day current">' + i + '</div>'
            }
            // 다음달
            for (var i = 1; i <= (7 - nextDay == 7 ? 0 : 7 - nextDay); i++) {
                calendar.innerHTML = calendar.innerHTML + '<div class="day next disable">' + i + '</div>'
            }

            // 오늘 날짜 표기
            if (today.getMonth() == currentMonth) {
                todayDate = today.getDate();
                var currentMonthDate = document.querySelectorAll('.dates .current');
                currentMonthDate[todayDate -1].classList.add('today');
            }
        }

        // 이전달로 이동
        $('.go-prev').on('click', function() {
            thisMonth = new Date(currentYear, currentMonth - 1, 1);
            renderCalender(thisMonth);
        });

        // 다음달로 이동
        $('.go-next').on('click', function() {
            thisMonth = new Date(currentYear, currentMonth + 1, 1);
            renderCalender(thisMonth);
        });
    }
</script>
</html>