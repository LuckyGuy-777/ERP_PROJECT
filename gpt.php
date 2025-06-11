<!DOCTYPE html>
<html lang="en">
<head>
    <!-- 문서의 문자 인코딩을 UTF-8로 설정합니다. -->
    <meta charset="UTF-8">
    <!-- 문서의 제목을 "Chat GPT"로 설정합니다. -->
    <title>Chat GPT</title>
    <!-- 문자 인코딩을 다시 설정합니다. (중복된 부분으로 보입니다. 아마도 실수) -->
    <meta charset="UTF-8">
    <!-- 반응형 디자인을 위한 뷰포트 설정입니다. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Internet Explorer의 호환성 모드를 설정합니다. -->
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- 스타일링을 위한 외부 스타일 시트 파일을 연결합니다. (gpt_style.css) -->
    <link rel="stylesheet" href="css/gpt_style.css"><!-- 이 파일의 작동에 필요한 스타일 폴더가 연결되어있음-->
</head>
<body>
<!-- 주석 처리된 "History feature" 부분
<div class="sidebar">
  <p class="tablink sidebar-header">Create Chat</h2>
    <button class="tablink" onclick="openTab(event, 'tab1')">Lorem ipsum</button>
    <button class="tablink" onclick="openTab(event, 'tab2')">Dolor sit amet</button>
</div>
-->
<!-- 메인 채팅 섹션 -->
<section class="msger">
    <!-- 채팅 헤더 -->
    <header class="msger-header">
        <!-- 제목 및 사용자 ID 표시 -->
        <div class="msger-header-title">
            <i class="fas fa-comment-alt"></i> ChatGPT
            &nbsp;| ID: <input type="text" id="id" hidden> <span class="id_session"></span><!-- 현재 유저 아이디를 표시-->
        </div>
        <!-- 옵션, 채팅 히스토리 삭제 버튼 포함 -->
        <div class="msger-header-options">
            <button id="delete-button">Delete History</button><!-- 채팅 히스토리 삭제 버튼-->
        </div>
    </header>
    <!-- 메인 채팅 영역 -->
    <main class="msger-chat">
    </main>
    <!-- 사용자 입력 폼 -->
    <form class="msger-inputarea">
        <!-- 사용자 메시지를 입력하는 입력란 -->
        <input class="msger-input" placeholder="Enter your message..." require>
        <!-- 메시지를 ChatGPT에 전송하는 버튼 -->
        <button type="submit" class="msger-send-btn">Send</button><!-- 질문 입력후, 지피티에게 보내기-->
    </form>
</section>
<!-- Font Awesome 아이콘 라이브러리를 포함합니다. -->
<script src='https://use.fontawesome.com/releases/v5.0.13/js/all.js'></script>
<!-- 이 파일의 작동에 필요한 자바스크립트 파일을 연결합니다. (gpt-script.js) -->
<script src="js/gpt-script.js"></script> <!-- 이 파일의 작동에 필요한 자바스크립트 폴더가 연결되어있음-->
<!-- 주석 처리된 "History feature" 부분
<script>
function openTab(evt, tabName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablink");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(tabName).style.display = "block";
  evt.currentTarget.className += " active";
}
</script>
-->
</body>
</html>
