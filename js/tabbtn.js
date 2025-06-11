// 탭 열기 함수 정의
function openTab(evt, tabName) {
    // 모든 탭 내용 숨기기
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    // 모든 탭 링크에서 "active" 클래스 제거
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    // 선택한 탭 내용 보이게 설정
    document.getElementById(tabName).style.display = "block";
    // 선택한 탭 링크에 "active" 클래스 추가
    evt.currentTarget.className += " active";
}

// id가 "defaultOpen"인 요소를 찾아 클릭 이벤트 발생
document.getElementById("defaultOpen").click();