<?php
// 세션 시작
// 웹 페이지 간의 데이터를 저장하거나 전달하는 데 사용되는 세션을 시작
session_start();
//리뷰: 세션을 시작합니다. 이를 통해 웹 페이지 간의 데이터를 저장하거나 전달할 수 있습니다.
//리뷰: 세션을 시작하여 사용자 세션 데이터에 접근할 수 있습니다.

// 데이터베이스 연결 정보 설정
$servername = "localhost";
$username = "root";
$password = "abc123";
$dbname = "project";

$user_id = $_SESSION['USER_ID'];

// Create connection
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
// 데이터베이스 연결 시도
try {
//리뷰: 데이터베이스 연결을 시도하는 부분입니다. 연결 시 오류가 발생하면 오류 메시지를 출력합니다.
//리뷰: 데이터베이스 연결을 시도합니다. 연결 중 오류가 발생하면 예외 처리를 통해 오류 메시지를 출력합니다.
    // MySQLi 객체를 사용하여 데이터베이스에 연결. 연결 실패 시 오류 메시지 출력
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8");
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage());
}

// 파일 업로드 경로 설정
// 프로필 사진을 저장할 서버 내의 디렉토리 경로. 경로 설정 시 절대 경로 사용
$upload_dir =
//리뷰: 파일 업로드를 위한 디렉터리 경로를 설정합니다.
//리뷰: 파일을 업로드할 디렉터리를 설정합니다. 절대 경로를 사용하여 파일 위치를 명확하게 합니다.
 'C:/Users/asdf/IdeaProjects/projcct/emp_profile/'; 
$uploaded_file = $upload_dir . basename($_FILES['profile_picture']['name']);

// 파일 업로드 오류 검사
// 파일 업로드 중 발생한 오류를 확인. 오류가 있을 경우 오류 메시지를 출력
if ($_FILES['profile_picture']['error'] !== UPLOAD_ERR_OK) {
//리뷰: 파일 업로드 중 오류가 있는지 확인합니다.
//리뷰: 파일 업로드 중에 오류가 발생했는지 확인합니다. 오류가 발생하면 오류 코드와 함께 메시지를 출력합니다.
    die("File upload failed with error code " . $_FILES['profile_picture']['error']);
}

// 이미 존재하는 파일 이름 검사
// 업로드하려는 파일의 이름이 서버에 이미 존재하는지 확인. 이미 존재할 경우 파일 이름을 변경
if (file_exists($uploaded_file)) {
    echo "이미 존재하는 파일입니다.";
    exit;
}

// 파일 크기 제한 확인
// 업로드 파일의 크기를 확인. 5MB(5000000바이트)보다 큰 경우 업로드를 중단하고 오류 메시지 출력
if ($_FILES['profile_picture']['size'] > 5000000) {
    echo "파일의 크기가 너무 큽니다.";
    exit;
}

// 이미지 파일 형식 확인
// 업로드된 파일의 확장자를 가져와 소문자로 변환. 이후 이미지 파일 형식을 검증하는데 사용
$imageFileType = strtolower(pathinfo($uploaded_file, PATHINFO_EXTENSION));
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
    echo "JPG, JPEG, PNG & GIF 파일만 업로드 가능합니다.";
    exit;
}

if (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploaded_file)) {
    echo "저장에 실패하였습니다.";
    exit;
}

$input_name = $_POST['name'];
$input_email = $_POST['email'];  // 이메일 값을 가져옵니다.

$sql = "SELECT user_id FROM user WHERE user_name='$input_name' AND email='$input_email'";  // 이메일로 사용자를 찾습니다.

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_id = $row['user_id'];
} else {
    die("No matching user found");
}

$profile_picture = basename($uploaded_file);
$stmt = $conn->prepare("INSERT INTO employee (user_id, profile_picture, employee_id, user_name, user_birth, user_ssn, user_gender, user_nationality, total_address, postal_code, phone_number, home_phone_number, join_date, employee_rank, department, email, road_address, lot_number_address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$employee_id = $_POST['employee_id'];
$user_name = $_POST['name'];
$user_birth = $_POST['birth'];
$user_ssn = $_POST['ssn'];
$user_gender = $_POST['gender'];
$user_nationality = $_POST['personal_nationality'];
$total_address = $_POST['postal_code'] . ', ' . $_POST['road_address'] . ', ' . $_POST['jibun_address'] . ', ' . $_POST['detail_address'];
$road_address = $_POST['road_address'] . ', ' . $_POST['detail_address'];
$lot_number_address = $_POST['jibun_address'] . ', ' . $_POST['detail_address'];
$postal_code = $_POST['postal_code'];
$phone_number = $_POST['mobile_number'];
$home_phone_number = $_POST['phone_number'];
$join_date = $_POST['join_date'];
$employee_rank = $_POST['employee_rank'];
$department = $_POST['department'];
$email = $_POST['email'];

$stmt->bind_param(
//리뷰: SQL 쿼리에 파라미터를 바인딩합니다. 이 방법은 SQL 인젝션을 방지하는 데 도움이 됩니다.
//리뷰: SQL 문의 파라미터에 변수를 바인딩합니다. 이 방법은 SQL 인젝션 공격을 방지하는 데 효과적입니다.
"ssssssssssssssssss",
    $user_id,
    $profile_picture,
    $employee_id,
    $user_name,
    $user_birth,
    $user_ssn,
    $user_gender,
    $user_nationality,
    $total_address,
    $postal_code,
    $phone_number,
    $home_phone_number,
    $join_date,
    $employee_rank,
    $department,
    $email,
    $road_address,
    $lot_number_address);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//리뷰: 전송된 이메일 주소의 형식이 올바른지 검증합니다.
//리뷰: 이메일 주소의 유효성을 검사합니다. FILTER_VALIDATE_EMAIL 필터를 사용하여 유효한 이메일 형식인지 확인합니다.
    die("Invalid email format");
}

$stmt->execute();

$_SESSION['USER_IMG'] = $profile_picture;

$user_id = $stmt->insert_id;

$stmt->close();

$family_member_stmt = $conn->prepare("INSERT INTO emp_family (employee_id, family_name, family_relationship, family_birth, family_nationality, family_work) VALUES (?, ?, ?, ?, ?, ?)");

$family_member_stmt->bind_param("ssssss", $employee_id, $family_member_name, $family_member_relationship, $family_member_birth, $family_member_nationality, $family_member_work);

$family_member_count = count($_POST["family_member_name"]);

for ($i = 0; $i < $family_member_count; $i++) {
    $family_member_name = $_POST["family_member_name"][$i];
    $family_member_relationship = $_POST["family_member_relationship"][$i];
    $family_member_birth = $_POST["family_member_birth"][$i];
    $family_member_nationality = $_POST["family_nationality"][$i];
    $family_member_work = $_POST["employment_status"][$i];

    if ($family_member_stmt->execute() === false) {
        die('Error occurred: ' . $family_member_stmt->error);
    }
}

$family_member_stmt->close();

$conn->close();

echo "<script type='text/javascript'>alert('저장되었습니다.');window.location.href = 'Per_record.php';</script>";
//리뷰: 사용자 정보 저장이 성공적으로 완료된 후, 사용자에게 알림을 표시하고 'Per_record.php' 페이지로 리다이렉트합니다.
//리뷰: 데이터가 성공적으로 저장된 후에 사용자에게 알림을 보내고 다른 페이지로 리디렉션합니다.
exit();
?>
