<?php
// HTTP 요청 메소드가 POST인 경우
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 새로운 SQLite 데이터베이스 연결 생성
    $db = new SQLite3('db.sqlite');

    // 요청 데이터에서 사용자 ID 가져오기
    $user_id = $_POST['user_id'];
    // chat_history 데이터를 검색하는 SELECT 문을 준비하고 실행
    $stmt = $db->prepare('SELECT human, ai FROM chat_history WHERE user_id = :user_id ORDER BY id ASC');
    $stmt->bindValue(':user_id', $user_id, SQLITE3_TEXT);
    $result = $stmt->execute();

    // 결과를 가져와 배열에 저장
    $chat_history = array();
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $chat_history[] = $row;
    }

    // 데이터베이스 연결 종료
    $db->close();

    // HTTP 응답 헤더를 설정하여 응답이 JSON임을 나타냄
    header('Content-Type: application/json');

    // 채팅 기록 배열을 JSON으로 변환하고 HTTP 응답 본문으로 전송
    echo json_encode($chat_history);
}
// HTTP 요청 메소드가 DELETE인 경우
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // 요청 본문에서 삭제할 사용자 ID 가져오기
    $user_id = $_GET['user'];

    // 새로운 SQLite 데이터베이스 연결 생성
    $db = new SQLite3('db.sqlite');

    // DELETE 문을 준비하고 실행하여 지정된 사용자 ID에 대한 채팅 기록 삭제
    $stmt = $db->prepare('DELETE FROM chat_history WHERE user_id = :user_id');
    $stmt->bindValue(':user_id', $user_id, SQLITE3_TEXT);
    $result = $stmt->execute();

    // 데이터베이스 연결 종료
    $db->close();

    // HTTP 응답 상태 코드를 설정하여 성공을 나타냄
    http_response_code(204); // No Content

}