/**
 * 프로필 사진 미리보기를 위한 함수
 * @param {Event} event - input[type="file"]의 change 이벤트
 */
function previewImage(event) {
    // 미리보기를 표시할 이미지 요소 선택
    const preview = document.getElementById("profile-picture-preview");
    const file = event.target.files[0];  // 선택한 파일 가져오기
    const reader = new FileReader();// FileReader 객체 생성
    // 파일을 읽기가 끝났을 때의 이벤트 처리
    reader.onloadend = function () {
        preview.src = reader.result;// 읽은 내용을 이미지의 소스로 설정하여 미리보기 업데이트
    };
    // 선택한 파일이 존재하는 경우에만 읽기 수행
    if (file) {
        reader.readAsDataURL(file);
    } else {
        // 선택한 파일이 없으면 미리보기 이미지 초기화
        preview.src = "";
    }
}