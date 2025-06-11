const mobileNumberInput = document.getElementById("mobile-number");
const postalCodeInput = document.getElementById("postal-code");
const phoneNumberInput = document.getElementById("phone-number");
const ssnInput = document.getElementById("ssn");

// 주민등록번호 입력 시 숫자 이외의 문자 제거하고 길이 제한 이벤트 리스너 등록
ssnInput.addEventListener("input", function () {
    this.value = this.value.replace(/[^0-9]/g, "").substr(0, 13);
});
// 휴대폰 번호 입력 시 숫자 이외의 문자 제거하고 길이 제한 이벤트 리스너 등록
mobileNumberInput.addEventListener("input", function () {
    this.value = this.value.replace(/[^0-9]/g, "").substr(0, 11);
});
// 우편번호 입력 시 숫자 이외의 문자 제거하고 길이 제한 이벤트 리스너 등록
postalCodeInput.addEventListener("input", function () {
    this.value = this.value.replace(/[^0-9]/g, "").substr(0, 5);
});
// 전화번호 입력 시 숫자 이외의 문자 제거하고 길이 제한 이벤트 리스너 등록
phoneNumberInput.addEventListener("input", function () {
    this.value = this.value.replace(/[^0-9]/g, "").substr(0, 10);
});