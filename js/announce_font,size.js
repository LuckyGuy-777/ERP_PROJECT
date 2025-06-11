// 필요한 DOM 요소들을 가져오기
const titleInput = document.getElementById("title");
const messageInput = document.getElementById("message");
const fontSelect = document.getElementById("font");
const sizeSelect = document.getElementById("size");

// 프리뷰 업데이트 함수 정의
function updatePreview() {
    // 제목과 메시지 입력 요소에 선택된 폰트 및 크기 적용
    titleInput.style.fontFamily = `'${fontSelect.value}'`;
    messageInput.style.fontFamily = `'${fontSelect.value}'`;
    titleInput.style.fontSize = `${sizeSelect.value}px`;
    messageInput.style.fontSize = `${sizeSelect.value}px`;
}
// 입력 요소에 이벤트 리스너 추가
titleInput.addEventListener("input", updatePreview);
messageInput.addEventListener("input", updatePreview);
fontSelect.addEventListener("change", updatePreview);
sizeSelect.addEventListener("change", updatePreview);

// 페이지 로딩 시 프리뷰 업데이트
window.addEventListener("DOMContentLoaded", (event) => {
    updatePreview();
});