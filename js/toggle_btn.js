function toggleVisibility(id) {
    // 주어진 ID를 가진 요소 가져오기
    const element = document.getElementById(id);
    // 요소가 'collapse' 클래스를 포함하고 있는지 확인
    if (element.classList.contains("collapse")) {
        // 'collapse' 클래스가 있으면 제거하고 'expand' 클래스 추가
        element.classList.remove("collapse");
        element.classList.add("expand");
    } else {
        // 'expand' 클래스가 있으면 제거하고 'collapse' 클래스 추가
        element.classList.remove("expand");
        element.classList.add("collapse");
    }
}