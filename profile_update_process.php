<?php
session_start();
$db = new mysqli('localhost', 'root', 'abc123', 'project');

if($db->connect_error) {
    die("데이터베이스 연결 실패: " . $db->connect_error);
}

$user_id = $_SESSION['USER_ID'];

// 기본 값을 빈 문자열로 설정하고, $_POST가 존재할 경우 값을 할당합니다.
$name = $_POST['name'] ?? '';
$department = $_POST['department'] ?? '';
$employee_rank = $_POST['employee_rank'] ?? '';
$birth = $_POST['birth'] ?? '';
$phone_number = $_POST['phone_number'] ?? '';
$email = $_POST['email'] ?? '';
$road_address = $_POST['road_address'] ?? '';
$jibun_address = $_POST['jibun_address'] ?? '';
$postal_code = $_POST['postal_code'] ?? '';
$ssn = $_POST['ssn'] ?? '';
$gender = $_POST['gender'] ?? '';
$personal_nationality = $_POST['personal_nationality'] ?? '';

// 파일 업로드 처리
$profile_picture = '';
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
        $profile_picture = $target_file;
    }
}

$query = "UPDATE employee 
          SET user_name=?, department=?, employee_rank=?, user_birth=?, 
              phone_number=?, email=?, road_address=?, lot_number_address=?, 
              postal_code=?, user_ssn=?, user_gender=?, 
              user_nationality=?, profile_picture=?
          WHERE user_id=?";
$stmt = $db->prepare($query);
$stmt->bind_param('ssssssssssssss', $name, $department, $employee_rank, $birth,
    $phone_number, $email, $road_address, $jibun_address, $postal_code,
    $ssn, $gender, $personal_nationality, $profile_picture, $user_id);

if (!$stmt->execute()) {
    echo "사용자 및 직원 정보 업데이트 오류: " . $stmt->error;
}
$stmt->close();

// 가족 정보 처리
$family_names = $_POST['family_member_name'] ?? [];
$family_births = $_POST['family_member_birth'] ?? [];
$family_relationships = $_POST['family_member_relationship'] ?? [];
$family_nationalities = $_POST['family_nationality'] ?? [];
$family_works = $_POST['employment_status'] ?? [];

$delete_query = "DELETE FROM emp_family WHERE employee_id=?";
$delete_stmt = $db->prepare($delete_query);
$delete_stmt->bind_param('s', $user_id);
$delete_stmt->execute();
$delete_stmt->close();

$insert_query = "INSERT INTO emp_family (employee_id, family_name, family_birth, family_relationship, family_nationality, family_work) VALUES (?, ?, ?, ?, ?, ?)";
$insert_stmt = $db->prepare($insert_query);
foreach ($family_names as $i => $name) {
    $insert_stmt->bind_param('ssssss', $user_id, $name, $family_births[$i], $family_relationships[$i], $family_nationalities[$i], $family_works[$i]);
    $insert_stmt->execute();
}
$insert_stmt->close();

if ($stmt->affected_rows > 0) {
    echo "정보가 성공적으로 수정되었습니다.";
} else {
    echo "정보 수정에 실패했습니다.";
}

$db->close();
?>
