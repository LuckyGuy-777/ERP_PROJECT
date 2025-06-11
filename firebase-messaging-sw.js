// Firebase SDK를 로드합니다.
importScripts('https://www.gstatic.com/firebasejs/8.2.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.2.0/firebase-messaging.js');

// Firebase 프로젝트 설정을 초기화합니다.
firebase.initializeApp({
    apiKey: "AIzaSyDacendbQyNnoUPkptAVmBdDVw0TZFLAZc",
    authDomain: "erp-project-396905.firebaseapp.com",
    projectId: "erp-project-396905",
    storageBucket: "erp-project-396905.appspot.com",
    messagingSenderId: "1:186994599842:web:a24a207a33da21585f34fb",
    appId: "G-DS0CLRHZ6C"
});


// Firebase Messaging 인스턴스를 초기화합니다.
const messaging = firebase.messaging();
