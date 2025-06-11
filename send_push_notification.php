<?php
// 데이터베이스 연결
$db = new mysqli('localhost', 'root', 'abc123', 'project');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// POST 요청으로부터 사용자 ID를 받음
$userId = $_POST['userId'];

// 데이터베이스에서 사용자의 디바이스 토큰을 조회
$query = "SELECT device_token FROM user WHERE user_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param('s', $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
    $userDeviceToken = $row['device_token'];

    // FCM 서버 키,개인의 키로 수정
    $serverKey = 'AAAAK4m_J6I:APA91bEs4W2XfjNja_Zpy2nILhOPIbair7zwOOw2AAc41HxDk
    f78MPDs8fbibnk4Pa4KdyfZBMpvh9soN_pNf4BY9fQH9ZN5JSyL1DNY2ypQQY6YC2ezYAYJJLtE7eR9h33nmCz-504o';

    // 알림 데이터 준비
    $notification = [
        'to' => $userDeviceToken,
        'notification' => [
            'title' => '새로운 업무 기록',
            'body' => "업무 기록 ID {$userId}에 대한 새로운 알림이 있습니다."
        ]
    ];

    // cURL을 사용하여 FCM 서버에 요청 전송
    $ch = curl_init('https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: key=' . $serverKey,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notification));

    $result = curl_exec($ch);
    curl_close($ch);

    // 결과 출력
    echo $result;
} else {
    echo "No device token found for user ID: {$userId}";
}
?>
