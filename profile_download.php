<?php
// 세션 시작
session_start();
// Composer로 설치한 라이브러리 사용
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// 파일 이름에 사용할 수 없는 문자를 제거하는 함수
function sanitizeFileName($name) {
    // Windows에서 사용 불가능한 문자 제거
    return preg_replace('/[\/:*?"<>|]/', '', $name);
}

// POST 요청인 경우
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 사용자 이름 받아오기
    $name = $_POST["Name"];
    // 데이터베이스 연결 설정
    $servername = "localhost";
    $username = "root";
    $password = "abc123";
    $dbname = "project";

    // 데이터베이스 연결
    $conn = new mysqli($servername, $username, $password, $dbname);

    // 연결 확인
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // 사용자 정보를 조회하는 쿼리 작성
    $sql = "SELECT * FROM employee WHERE user_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    // 결과 가져오기
    $result = $stmt->get_result();
    // 스프레드시트 생성
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    // 결과가 있는 경우
    if ($result->num_rows > 0) {
        // 행 카운트 초기화
        $rowCount = 1;
        // 각 행마다 데이터 출력
        while($row = $result->fetch_assoc()) {
            $sheet->fromArray(array('사번', $row['employee_id']), NULL, 'A' . $rowCount++);
            $sheet->fromArray(array('이름', $row['user_name']), NULL, 'A' . $rowCount++);
            $sheet->fromArray(array('생년월일', $row['user_birth']), NULL, 'A' . $rowCount++);
            $sheet->fromArray(array('주민번호', $row['user_ssn']), NULL, 'A' . $rowCount++);
            $sheet->fromArray(array('성별', $row['user_gender']), NULL, 'A' . $rowCount++);
            $sheet->fromArray(array('내/외국인', $row['user_nationality']), NULL, 'A' . $rowCount++);
            $sheet->fromArray(array('도로명주소', $row['road_address']), NULL, 'A' . $rowCount++);
            $sheet->fromArray(array('지번주소', $row['lot_number_address']), NULL, 'A' . $rowCount++);
            $sheet->fromArray(array('우편번호', $row['postal_code']), NULL, 'A' . $rowCount++);
            $sheet->fromArray(array('휴대폰번호', $row['phone_number']), NULL, 'A' . $rowCount++);
            $sheet->fromArray(array('전화번호', $row['home_phone_number']), NULL, 'A' . $rowCount++);
            $sheet->fromArray(array('입사일자', $row['join_date']), NULL, 'A' . $rowCount++);
            $sheet->fromArray(array('직급', $row['employee_rank']), NULL, 'A' . $rowCount++);
            $sheet->fromArray(array('부서', $row['department']), NULL, 'A' . $rowCount++);
            $sheet->fromArray(array('이메일', $row['email']), NULL, 'A' . $rowCount++);

            // 가족 구성원 조회
            $sql = "SELECT * FROM emp_family WHERE employee_id = ?";
            $family_stmt = $conn->prepare($sql);
            $family_stmt->bind_param("s", $row["employee_id"]); // 's' for string
            $family_stmt->execute();

            $resultFamily = $family_stmt->get_result();
            // 가족 정보가 있는 경우
            if ($resultFamily->num_rows > 0) {
                // output data of each row
                while($rowFamily = $resultFamily->fetch_assoc()) {
                    $sheet->fromArray(array('이름(가족)', $rowFamily['family_name']), NULL, 'A' . $rowCount++);
                    $sheet->fromArray(array('관계', $rowFamily['family_relationship']), NULL, 'A' . $rowCount++);
                    $sheet->fromArray(array('생년월일', $rowFamily['family_birth']), NULL, 'A' . $rowCount++);
                    $sheet->fromArray(array('직장유무', $rowFamily['family_work']), NULL, 'A' . $rowCount++);
                    $sheet->fromArray(array('국적', $rowFamily['family_nationality']), NULL, 'A' . $rowCount++);
                }
            }
            // 엑셀 파일 저장
            $writer = new Xlsx($spreadsheet);
            // 사용자 이름을 파일 이름으로 사용
            $fileName = sanitizeFileName($row['user_name']) . ".xlsx";
            $writer->save($fileName);
            // 다운로드 설정 및 파일 전송
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            readfile($fileName);
            // 엑셀 파일 삭제
            unlink($fileName);
        }
    } else {
        // 결과가 없는 경우
        echo "<script>alert('검색된 사용자가 없습니다.'); window.history.go(-1);</script>";
    }
    // 데이터베이스 연결 닫기
    $conn->close();
}
?>
