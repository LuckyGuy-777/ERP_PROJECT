
if (getCookie("id") == "") {
    uuid = uuidv4()
    document.cookie = "id=" + uuid
    document.getElementById("id").value = uuid
} else {
    document.getElementById("id").value = getCookie("id");
}
// 사용자 ID 표시를 위한 세션 요소 선택
const idSession = get(".id_session");
const USER_ID = document.getElementById("id").value;
idSession.textContent = USER_ID

// Chat history 불러오기 및 렌더링
getHistory()

// 채팅 입력폼, 입력 필드, 채팅창, 전송 버튼 선택
const msgerForm = get(".msger-inputarea");
const msgerInput = get(".msger-input");
const msgerChat = get(".msger-chat");
const msgerSendBtn = get(".msger-send-btn");


// ChatGPT와 사용자의 프로필 이미지 및 이름 설정
const BOT_IMG = ".img/chatgpt.svg";
const PERSON_IMG = "https://api.dicebear.com/5.x/micah/svg?seed=" + document.getElementById("id").value
const BOT_NAME = "ChatGPT";
const PERSON_NAME = "You";


// Chat history 삭제를 위한 함수
// Function to delete chat history records for a user ID using the API
function deleteChatHistory(userId) {
    if (!confirm("Are you sure? Your Session and History will delete for good.")) {
        return false
    }
    // API를 통해 사용자 ID에 해당하는 채팅 기록 삭제
    fetch('/api.php?user=' + USER_ID, {
        method: 'DELETE',
        headers: {'Content-Type': 'application/json'}
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error deleting chat history: ' + response.statusText);
            }
            deleteAllCookies()// 모든 쿠키 삭제 및 페이지 리로드
            location.reload(); // Reload the page to update the chat history table
        })
        .catch(error => console.error(error));
}

// 삭제 버튼 클릭 이벤트 리스너 등록
// Event listener for the Delete button click
const deleteButton = document.querySelector('#delete-button');
// 메시지 전송 이벤트 리스너 등록
deleteButton.addEventListener('click', event => {
    event.preventDefault();
    deleteChatHistory(USER_ID);
});

msgerForm.addEventListener("submit", event => {
    event.preventDefault();
    // 입력된 메시지 텍스트 가져오기
    const msgText = msgerInput.value;
    if (!msgText) return;

    // 사용자가 입력한 메시지를 채팅창에 추가
    appendMessage(PERSON_NAME, PERSON_IMG, "right", msgText);
    msgerInput.value = "";
    // 서버로 메시지 전송
    sendMsg(msgText)
});
// Chat history 불러와서 렌더링
function getHistory() {
    var formData = new FormData();
    formData.append('user_id', USER_ID);
    fetch('/api.php', {method: 'POST', body: formData})
        .then(response => response.json())
        .then(chatHistory => {
            for (const row of chatHistory) {
                // 채팅 기록을 채팅창에 추가
                appendMessage(PERSON_NAME, PERSON_IMG, "right", row.human);
                appendMessage(BOT_NAME, BOT_IMG, "left", row.ai, "");
            }
        })
        .catch(error => console.error(error));
}
// 채팅창에 메시지 추가
function appendMessage(name, img, side, text, id) {
    //   Simple solution for small apps
    const msgHTML = `
    <div class="msg ${side}-msg">
      <div class="msg-img" style="background-image: url(${img})"></div>
      <div class="msg-bubble">
        <div class="msg-info">
          <div class="msg-info-name">${name}</div>
          <div class="msg-info-time">${formatDate(new Date())}</div>
        </div>

        <div class="msg-text" id=${id}>${text}</div>
      </div>
    </div>
  `;

    msgerChat.insertAdjacentHTML("beforeend", msgHTML);
    msgerChat.scrollTop += 500;
}
// 메시지 전송 함수
function sendMsg(msg) {
    msgerSendBtn.disabled = true
    var formData = new FormData();
    formData.append('msg', msg);
    formData.append('user_id', USER_ID);
    fetch('/send-gpt-message.php', {method: 'POST', body: formData})
        .then(response => response.json())
        .then(data => {
            let uuid = uuidv4()
            const eventSource = new EventSource(`/event-stream.php?chat_history_id=${data.id}&id=${encodeURIComponent(USER_ID)}`);
            appendMessage(BOT_NAME, BOT_IMG, "left", "", uuid);
            const div = document.getElementById(uuid);

            eventSource.onmessage = function (e) {
                if (e.data == "[DONE]") {
                    msgerSendBtn.disabled = false
                    eventSource.close();
                } else {
                    let txt = JSON.parse(e.data).choices[0].delta.content
                    if (txt !== undefined) {
                        div.innerHTML += txt.replace(/(?:\r\n|\r|\n)/g, '<br>');
                    }
                }
            };
            eventSource.onerror = function (e) {
                msgerSendBtn.disabled = false
                console.log(e);
                eventSource.close();
            };
        })
        .catch(error => console.error(error));


}

// 유틸리티 함수들
function get(selector, root = document) {
    return root.querySelector(selector);
}
//날짜 객체를 받아와서 시간 형식으로 포맷팅하는 함수
function formatDate(date) {
    const h = "0" + date.getHours();
    const m = "0" + date.getMinutes();

    return `${h.slice(-2)}:${m.slice(-2)}`;
}
//* 주어진 범위 내에서 랜덤한 정수를 생성하는 함수
function random(min, max) {
    return Math.floor(Math.random() * (max - min) + min);
}

// * 쿠키 이름을 받아와서 해당 쿠키의 값을 반환하는 함수
function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
// * UUID v4를 생성하는 함수
function uuidv4() {
    return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
        (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    );
}
// * 모든 쿠키를 삭제하는 함수
function deleteAllCookies() {
    const cookies = document.cookie.split(";");

    for (let i = 0; i < cookies.length; i++) {
        const cookie = cookies[i];
        const eqPos = cookie.indexOf("=");
        const name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
    }
}