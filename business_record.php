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

// 사용자의 정보를 조회하기
$query = "SELECT user_name, department, employee_rank, user_birth, phone_number, email, profile_picture 
          FROM employee 
          WHERE user_id = ?";

$stmt = $db->prepare($query);
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

if (!$profile_picture_path || $profile_picture_path == "Unknown") {
    $profile_picture_url = "default/path/to/image.jpg";  // Default image path
} else {
    $project_root_path = 'C:\Users\asdf\IdeaProjects\projcct\emp_profile';
    $profile_picture_url = str_replace($project_root_path, '', $profile_picture_path);
}

// 업무 기록 추가, 수정 및 삭제
if (isset($_POST['submit_record'])) {
    $order_number = $_POST['order_number'];
    $company = $_POST['company'];
    $contact_person = $_POST['contact_person'];
    $content = $_POST['content'];
    $result = $_POST['result'];
    $detailed_notes = $_POST['detailed_notes'];

    if (isset($_POST['record_id'])) {
        // 업무 기록 수정
        $record_id = $_POST['record_id'];
        $update_query = "UPDATE business_record SET order_number = ?, company = ?, contact_person = ?, content = ?, result = ?, detailed_notes = ? WHERE record_id = ?";
        $stmt = $db->prepare($update_query);
        $stmt->bind_param('ssssssi', $order_number, $company, $contact_person, $content, $result, $detailed_notes, $record_id);
    } else {
        // 업무 기록 추가
        $insert_query = "INSERT INTO business_record (user_id, order_number, company, contact_person, content, result, detailed_notes) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($insert_query);
        $stmt->bind_param('sisssss', $user_id, $order_number, $company, $contact_person, $content, $result, $detailed_notes);
    }

    if ($stmt->execute()) {
        echo "<script>alert('업무 기록이 성공적으로 처리되었습니다.');</script>";
    } else {
        echo "<script>alert('오류: " . $stmt->error . "');</script>";
    }
}

if (isset($_POST['delete_record_id'])) {
    // 업무 기록 삭제
    $record_id_to_delete = $_POST['delete_record_id'];
    $delete_query = "DELETE FROM business_record WHERE record_id = ?";
    $stmt = $db->prepare($delete_query);
    $stmt->bind_param('i', $record_id_to_delete);

    if ($stmt->execute()) {
        echo "<script>alert('업무 기록이 삭제되었습니다.');</script>";
    } else {
        echo "<script>alert('오류: " . $stmt->error . "');</script>";
    }
}

// business_record 테이블에서 데이터 조회
$select_query = "SELECT * FROM business_record WHERE user_id = ?";
$select_stmt = $db->prepare($select_query);
$select_stmt->bind_param('s', $user_id);
$select_stmt->execute();
$records = $select_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>협업툴</title>
    <style>
        @import url(css/header_style.css);
        @import url(css/business_record.css);
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
<main>
    <div id="main-content">
        <!-- 업무 기록 추가/수정 폼 -->
        <div id="record-form-panel" class="panel">
            <h2>업무 기록 추가/수정</h2>
            <form action="" method="post" class="record-form">
                <label>순번: <input type="number" name="order_number"></label>
                <label>거래처(회사): <input type="text" name="company"></label>
                <label>담당자: <input type="text" name="contact_person"></label>
                <label>내용(한줄로 간단히): <textarea name="content"></textarea></label>
                <div>결과:
                    <label><input type="radio" name="result" value="완료"> 완료</label>
                    <label><input type="radio" name="result" value="미완료"> 미완료</label>
                    <label><input type="radio" name="result" value="보류"> 보류</label>
                    <label><input type="radio" name="result" value="이관"> 이관</label>
                </div>
                <label>세부사항: <textarea name="detailed_notes"></textarea></label>
                <input type="submit" name="submit_record" value="저장">
            </form>
        </div>

        <!-- 업무 기록 리스트 -->
        <div id="record-list-panel" class="panel">
            <h2>업무 기록</h2>
            <table>
                <thead>
                <tr>
                    <th>순번</th>
                    <th>거래처(회사)</th>
                    <th>담당자</th>
                    <th>내용</th>
                    <th>결과</th>
                    <th>세부사항</th>
                </tr>
                </thead>
                <tbody>
                <?php while($row = $records->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['order_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['company']); ?></td>
                        <td><?php echo htmlspecialchars($row['contact_person']); ?></td>
                        <td><?php echo htmlspecialchars($row['content']); ?></td>
                        <td><?php echo htmlspecialchars($row['result']); ?></td>
                        <td><?php echo htmlspecialchars($row['detailed_notes']); ?></td>
                        <td><button onclick="fillForm(<?php echo htmlspecialchars(json_encode($row)); ?>)">수정</button></td>
                        <td><button onclick="deleteRecord(<?php echo $row['record_id']; ?>)">삭제</button></td>
                        <td><button class="send-notification-button" data-record-id="<?php echo $row['record_id']; ?>" data-user-id="<?php echo $user_id; ?>">알림보내기</button></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
<script src="js/header_toggle.js"></script>
<script>
    function addForm() {
        const formContainer = document.getElementById('business-record');

        // 새로운 폼 요소 생성
        const newForm = document.createElement('div');
        newForm.className = "horizontal-form";
        newForm.innerHTML = `
         <form action="" method="post" class="horizontal-form">
            <label>순번: <input type="number" name="order_number"></label>
            <label>거래처(회사): <input type="text" name="company"></label>
            <label>담당자: <input type="text" name="contact_person"></label>
            <label>내용(한줄로 간단히): <textarea name="content"></textarea></label>
            <div>
                결과:
                <label><input type="radio" name="result" value="완료"> 완료</label>
                <label><input type="radio" name="result" value="미완료"> 미완료</label>
                <label><input type="radio" name="result" value="보류"> 보류</label>
                <label><input type="radio" name="result" value="이관"> 이관</label>
            </div>
            <label>세부사항: <textarea name="detailed_notes"></textarea></label>
            <input type="submit" value="저장">
            <button type="button" id="sendNotification">알림 보내기</button>
        </form>
    `;

        // 폼 컨테이너에 새로운 폼 추가
        formContainer.appendChild(newForm);
    }

    function removeForm() {
        const formContainer = document.getElementById('business-record');
        if(formContainer.children.length > 0) {
            formContainer.removeChild(formContainer.lastChild);
        }
    }

    //수정시 폼 채우기
    function fillForm(data) {
        document.querySelector('input[name="order_number"]').value = data.order_number;
        document.querySelector('input[name="company"]').value = data.company;
        document.querySelector('input[name="contact_person"]').value = data.contact_person;
        document.querySelector('textarea[name="content"]').value = data.content;
        // 결과에 대한 라디오 버튼 선택
        document.querySelectorAll('input[name="result"]').forEach((radio) => {
            if (radio.value === data.result) {
                radio.checked = true;
            }
        });
        document.querySelector('textarea[name="detailed_notes"]').value = data.detailed_notes;

        // record_id를 hidden input으로 추가
        const hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'record_id');
        hiddenInput.setAttribute('value', 'data.record_id'); // 여기서 data.record_id는 해당 레코드의 식별자입니다.
        document.querySelector('#business-record form').appendChild(hiddenInput);
    }

    //삭제
    function deleteRecord(recordId) {
        if (confirm('이 기록을 삭제하시겠습니까?')) {
            const form = document.createElement('form');
            form.method = 'post';
            form.action = ''; // 현재 페이지

            const recordIdField = document.createElement('input');
            recordIdField.type = 'hidden';
            recordIdField.name = 'delete_record_id';
            recordIdField.value = recordId;

            form.appendChild(recordIdField);
            document.body.appendChild(form);
            form.submit();
        }
    }
    // 각 버튼에 이벤트 리스너 추가
    document.querySelectorAll('.send-notification-button').forEach(button => {
        button.addEventListener('click', () => {
            const recordId = button.getAttribute('data-record-id');
            const userId = button.getAttribute('data-user-id'); // 사용자 ID를 어딘가에서 가져옵니다.
            sendNotification(recordId, userId);
        });
    });

    // 각 알람 보내기 버튼에 팝업창 열기 이벤트 추가
    document.querySelectorAll('.send-notification-button').forEach(button => {
        button.addEventListener('click', () => {
            // 선택된 recordId를 어딘가에 저장해야 합니다. 여기서는 예시로 직접 할당합니다.
            const selectedRecordId = button.getAttribute('data-record-id');
            document.getElementById('selectedRecordId').value = selectedRecordId;
            openUserModal();
        });
    });

    function openUserModal() {
        document.getElementById('userSelectionModal').style.display = 'block';
        fetchUsers(); // 사용자 목록을 가져오는 함수
    }

    function closeUserModal() {
        document.getElementById('userSelectionModal').style.display = 'none';
    }

    function fetchUsers() {
        fetch('get_employees.php')
            .then(response => response.json())
            .then(data => {
                const userList = document.getElementById('userList');
                userList.innerHTML = data.map(user => `<option value="${user.user_id}">${user.user_name}</option>`).join('');
            })
            .catch(error => console.error('Error:', error));
    }


    function sendNotificationToUser() {
        const selectedUserId = document.getElementById('userList').value;
        const selectedRecordId = document.getElementById('selectedRecordId').value;
        sendNotification(selectedRecordId, selectedUserId);
    }

    // OneSignal의 초기화와 사용자 ID(Player ID) 가져오기
    OneSignal.push(function() {
        OneSignal.init({
            appId: "a8f49881-dc3b-465d-89bc-ad01b4904bd8",
            notifyButton: {
                enable: true,
            },
            allowLocalhostAsSecureOrigin: true,
        });

        // 사용자 Player ID를 가져와서 서버에 전송하는 부분
        OneSignal.getUserId().then(function(playerId) {
            console.log("OneSignal User ID:", playerId);
            // AJAX 요청으로 playerId 값을 PHP 서버에 전송
            fetch('save_token.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `playerId=${playerId}`
            });
        });
    });

    // 알림 전송 함수
    function sendNotification(recordId, userId) {
        // AJAX 요청을 사용하여 서버에 알림 전송 요청
        fetch('send_push_notification.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `recordId=${recordId}&userId=${userId}`
        })
            .then(response => response.text())
            .then(data => {
                console.log(data); // 서버로부터의 응답 로그
            })
            .catch(error => console.error('Error:', error));
    }

    // 각 알람 보내기 버튼에 이벤트 리스너 추가
    document.querySelectorAll('.send-notification-button').forEach(button => {
        button.addEventListener('click', () => {
            const recordId = button.getAttribute('data-record-id');
            const userId = button.getAttribute('data-user-id');
            sendNotification(recordId, userId);
        });
    });
    // recordId를 저장할 hidden input 필드 추가
    const hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('id', 'selectedRecordId');
    document.body.appendChild(hiddenInput);
</script>
</body>
</html>