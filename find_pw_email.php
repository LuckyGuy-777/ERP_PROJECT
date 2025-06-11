<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>비밀번호 찾기</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-wrapper {
            max-width: 400px;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            color: #555;
            margin-bottom: 8px;
        }

        input[type="text"] {
            box-sizing: border-box;
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 16px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        p {
            text-align: center;
            margin-top: 10px;
            color: #888;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="form-wrapper">
        <h1>비밀번호 찾기_이메일</h1>
        <!-- 이메일 확인을 위한 폼 -->
        <form action="" method="post">
            <label for="email">이메일을 입력하세요:</label>
            <input type="text" id="email" name="email" placeholder="가입할때 입력하신 이메일을 입력해주세요" required><br>
            <input type="submit" name="submit" value="확인">
        </form>
        <?php
        session_start();
        // 폼이 제출되었을 때 실행되는 코드
        if (isset($_POST['submit'])) {
            $input_email = $_POST['email'];

            // Check if the entered username exists in the database
            // You should use prepared statements to prevent SQL injection

            // Example using mysqli (you can use PDO or other libraries as well)
            // DB 연결 정보
            $db_host = 'localhost';
            $db_name = 'project';
            $db_user = 'root';
            $db_password = 'abc123';

            // 데이터베이스 연결
            $conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

            // 연결 확인
            if (!$conn) {
                die("연결 실패: " . mysqli_connect_error());
            }
            // 입력된 이메일이 데이터베이스에 존재하는지 확인
            $sql = "SELECT email FROM user WHERE email = '$input_email'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $_SESSION['USER_EMAIL']=$input_email;
                // 이메일이 데이터베이스에 존재하는 경우 비밀번호 재설정 프로세스로 이동
                header("Location: find_pw_process.php");
            } else {
                // 이메일이 데이터베이스에 존재하지 않는 경우 에러 메시지 출력
                echo "<p style='color: red;'>이메일이  일치하지 않습니다.</p>";
            }

            // 데이터베이스 연결 닫기
            $conn->close();
        }
        ?>
    </div>
</div>
</body>
</html>
