const sidebar = document.getElementById("sidebar");
const resizeSidebarButton = document.getElementById("resize-sidebar-button");

let isResizing = false;
let initialX = 0;
let sidebarWidth = 150;

resizeSidebarButton.addEventListener("mousedown", (e) => {
    isResizing = true;
    initialX = e.clientX;
});

document.addEventListener("mousemove", (e) => {
    if (!isResizing) return;

    const offset = e.clientX - initialX;
    sidebarWidth += offset;
    initialX = e.clientX;

    if (sidebarWidth < 100) sidebarWidth = 100;
    if (sidebarWidth > 400) sidebarWidth = 400;

    sidebar.style.width = `${sidebarWidth}px`;
});

document.addEventListener("mouseup", () => {
    isResizing = false;
});

// 사용자 정보 팝업과 사용자 이름 요소를 가져옵니다.
const userInfoPopup = document.querySelector(".user-info-popup");
const userNameElement = document.querySelector("a[href='user_profile.php']");

// 사용자 이름을 클릭할 때 팝업을 표시하거나 숨깁니다.
userNameElement.addEventListener("click", (e) => {
    e.preventDefault();
    if (userInfoPopup.style.display === "none" || !userInfoPopup.style.display) {
        userInfoPopup.style.display = "block";
    } else {
        userInfoPopup.style.display = "none";
    }
});

// 팝업 외부를 클릭하면 팝업을 숨깁니다.
document.addEventListener("click", (e) => {
    if (!userInfoPopup.contains(e.target) && !userNameElement.contains(e.target)) {
        userInfoPopup.style.display = "none";
    }
});