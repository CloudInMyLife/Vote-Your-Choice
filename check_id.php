<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userid = $_POST["userid"];

    // DB 연결 설정
    $servername = "db.ckfxooc4ide7.ap-northeast-2.rds.amazonaws.com";
    $db_username = "vote";
    $db_password = "Vote_site2";
    $dbname = "VoteDB";

    $conn = new mysqli($servername, $db_username, $db_password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 아이디 중복 확인
    $check_id_query = "SELECT * FROM register WHERE mb_id='$userid'";
    $check_id_result = $conn->query($check_id_query);

    if ($check_id_result === false) {
        echo "오류: " . $conn->error;
        $conn->close();
        exit;
    }

    if ($check_id_result->num_rows > 0) {
        echo "used"; // 이미 사용 중인 아이디임을 나타내는 응답
    } else {
        echo "available"; // 사용 가능한 아이디임을 나타내는 응답
    }

    $conn->close();
    exit;
}
?>