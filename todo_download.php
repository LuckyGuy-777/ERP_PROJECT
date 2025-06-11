<?php
//파일 이름이 설정되어 있는지 확인
if (isset($_GET['filename'])) {
    $filename = $_GET['filename'];
    $filePath = 'C:/Users/asdf/IdeaProjects/projcct/uploads' . $filename;
    // 파일이 존재하는지 확인
    if (file_exists($filePath)) {
        //파일 다운로드를 위한 헤더 설정
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));

        //파일을 읽어 출력
        readfile($filePath);
        exit;
    } else {
        //파일이 존재하지 않을경우 메시지 출력
        echo "File not found.";
    }
} else {
    //유효하지 않은 파일 이름일 경우 메시지 출력
    echo "Invalid filename.";
}
?>
