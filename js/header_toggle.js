document.addEventListener('DOMContentLoaded', function () {
    // DOM이 완전히 로드된 후 실행되는 이벤트 리스너

    // 메뉴 토글, 메뉴 컨텐츠, 닫기 버튼 요소 가져오기
    var menuToggle = document.querySelector('.menu-toggle');
    var menuContent = document.querySelector('.menu-content');
    var closeButton = document.querySelector('.closebtn');
    // 메뉴 토글 버튼 클릭 이벤트
    menuToggle.addEventListener('click', function () {
        // 메뉴 컨텐츠에 'active' 클래스 추가
        menuContent.classList.add('active');
    });
    // 닫기 버튼 클릭 이벤트
    closeButton.addEventListener('click', function () {
        // 메뉴 컨텐츠에서 'active' 클래스 제거
        menuContent.classList.remove('active');
    });
});