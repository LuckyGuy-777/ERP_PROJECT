<?php
// 데이터베이스 연결 설정
$servername = "localhost";
$username = "root";
$password = "abc123";
$dbname = "project";

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 오늘 할 일
$sql1 = "SELECT Todo_title, startDate, endDate, description
FROM events
WHERE DATE(startDate) <= CURDATE() AND DATE(endDate) >= CURDATE()";

$result = $conn->query($sql1);

echo "<h2>오늘 할 일</h2>";
displayResults($result);

$sql2 = "SELECT Todo_title, startDate, endDate, description
FROM events
WHERE YEAR(startDate) = YEAR(CURDATE()) AND YEARWEEK(CURDATE(),1) BETWEEN YEARWEEK(startDate,1) AND YEARWEEK(endDate,1)";

$result = $conn->query($sql2);

echo "<h2>이번주 할 일</h2>";
displayResults($result);

$sql3 = "SELECT Todo_title, startDate, endDate, description
FROM events
WHERE YEAR(startDate) = YEAR(CURDATE()) AND MONTH(CURDATE()) BETWEEN MONTH(startDate) AND MONTH(endDate)";

$result = $conn->query($sql3);

echo "<h2>이번달 할 일</h2>";
displayResults($result);

// 데이터베이스 연결 닫기
$conn->close();

function displayResults($result) {
    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Todo Title</th><th>Start Date</th><th>End Date</th><th>Description</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["Todo_title"] . "</td>";
            echo "<td>" . $row["startDate"] . "</td>";
            echo "<td>" . $row["endDate"] . "</td>";
            echo "<td>" . $row["description"] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>데이터가 없습니다.</p>";
    }
}
?>
