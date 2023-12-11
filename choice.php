<?php
// DB 연결 정보
$host = "db.ckfxooc4ide7.ap-northeast-2.rds.amazonaws.com"; // DB 서버 IP 주소
$username = "root"; // 사용자명
$password = "ITBANK"; // 비밀번호
$dbname = "vote"; // 데이터베이스 이름

// 사용자가 선택한 투표
$selectedVote = $_POST['vote'];

// 현재 날짜를 YYYYMMDD 형식으로 가져오기
$currentDate = date("Ymd");

// 사용자 아이디 가져오기
session_start();
$user_id = $_SESSION['user_id'];

// DB 연결
$conn = new mysqli($host, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("DB 연결 실패: " . $conn->connect_error);
}

try {
    // 중복 투표 여부 확인 로직 (가정)
    $duplicateCheckQuery = "SELECT COUNT(*) AS count FROM voteresult WHERE VtDt = '$currentDate' AND mb_id = '$user_id'";
    $duplicateCheckResult = $conn->query($duplicateCheckQuery);
    $duplicateCheckData = $duplicateCheckResult->fetch_assoc();

    if ($duplicateCheckData['count'] > 0) {
        // 이미 투표한 경우 중복 투표 불가능한 알림창 띄우기
        echo '<script>alert("이미 참여한 투표자입니다. 중복 투표는 불가능합니다."); window.location.href = "index.html";</script>';
    } else {
        // 중복이 아니라면 투표 정보 저장
        $query = "INSERT INTO voteresult (VtDt, VtRt, mb_id) VALUES ('$currentDate', '$selectedVote', '$user_id')";

        if ($conn->query($query) === TRUE) {
            echo '<script>alert("투표가 성공적으로 저장되었습니다."); window.location.href = "index.html";</script>';
        } else {
            echo '<script>alert("오류가 발생하였습니다."); window.location.href = "index.html";</script>';
        }
    }
} catch (mysqli_sql_exception $e) {
    echo "에러: " . $e->getMessage();
}

// 연결 종료
$conn->close();
?>
