<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>회원가입 폼</title>
    <style>
        @import url(css/regist_form.css);
        /* 배경 및 글꼴 스타일 */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4; /* 전체 배경색 */
            background-image: url(img/loginbg.png);/* 배경 이미지 */
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body>
<div class="container"> <!-- 회원가입 제목 -->
    <h2>회원가입</h2> <!-- 회원가입 폼 -->
    <form method="post" action="register.php">
        <label for="id">아이디:</label>
        <input type="text" name="id" id="id" required /><!-- 아이디 입력란 -->

        <label for="password">비밀번호:</label>
        <input type="password" name="password" id="password" required /><!-- 비밀번호 입력란 -->

        <label for="email">이메일:</label>
        <input type="email" name="email" id="email" required /><!-- 이메일 입력란 -->

        <label for="user_name">성명:</label>
        <input type="text" name="user_name" id="user_name" required /> <!-- 성명 입력란 -->

        <label>성별:</label>
        <select name="gender" required>   <!-- 성별 선택 셀렉트 박스 -->
            <option value="male">남성</option>
            <option value="female">여성</option>
            <option value="other">기타</option>
        </select>

        <label for="birthdate">생년월일:</label> <!-- 생년월일 입력란 -->
        <input type="date" name="birthdate" id="birthdate" required />
        <label for="phone">핸드폰:</label><!-- 핸드폰 입력란 -->
        <input type="text" name="phone" id="phone" required />

        <input type="submit" value="가입하기" /><!-- 회원가입 제출 버튼 -->
    </form>
</div>
</body>

</html>