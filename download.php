<?php
$file = $_GET['file'];

if (isset($file)) {
    // 파일 경로 설정
    $filepath = "C:/Users/asdf/IdeaProjects/projcct/file/" . $file;
    // 파일이 존재하는지 확인
    if(file_exists($filepath)) {
        // 다운로드를 위한 헤더 설정
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        flush();  // 시스템 출력 버퍼를 비움
        readfile($filepath);
        exit;
    }
}
?>
