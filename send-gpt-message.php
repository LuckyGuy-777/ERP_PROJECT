<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['user_id'];
    $msg = $_POST['msg'];


    // 새로운 SQLite 데이터베이스 연결 생성
    $db = new SQLite3('db.sqlite');
    // INSERT문 준비
    $stmt = $db->prepare('INSERT INTO main.chat_history (user_id, human) VALUES (:user_id, :human)');

    // 매개변수를 바인딩하고 각 데이터행에 대해 코드를 실행
    $row = ['user_id' => $id, 'human' => $msg];

    $stmt->bindValue(':user_id', $row['user_id']);
    $stmt->bindValue(':human', $row['human']);
    $stmt->execute();


    //
    // 데이터베이스 연결 종료
    // HTTP 응답헤더를 JSON으로 설정
    header('Content-Type: application/json');

    // 데이터
    $data = [
        "id" => $db->lastInsertRowID()
    ];

    // 채팅 기록 배열을 JSON으로 변환하고 HTTP 응답을, 본문으로 전송
    echo json_encode($data);

    $db->close();
}
