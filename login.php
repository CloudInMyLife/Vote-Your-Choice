<?php
session_start();

// 데이터베이스 연결 설정
$host = "192.168.100.34";
$username = "root";
$password = "ITBANK";
$dbname = "vote";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = '';

// 사용자 입력 받기
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // DB에서 사용자 정보 가져오기
    $query = $conn->prepare("SELECT * FROM register WHERE mb_id = ?");
    $query->bind_param('s', $username);
    $query->execute();
    $result = $query->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // 비밀번호 일치 여부 확인
        if (password_verify($password, $user['mb_pw'])) {
            // 세션에 사용자 정보 저장
            $_SESSION['user_id'] = $user['mb_id'];
            header("Location: authorized.php");
            exit();
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "ID/PassWord가 존재하지 않습니다.";
    }

    $conn->close();
}
?>

<!-- 모달창 HTML 부분 -->
<div id="errorModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 300px; background-color: #fefefe; padding: 20px; border: 1px solid #888; z-index: 1000;">
    <span id="errorModalClose" style="color: #aaa; float: right; font-size: 28px; font-weight: bold;">&times;</span>
    <p><?php echo $error_message; ?></p>
    <button id="errorModalButton" style="padding: 10px 20px;">확인</button>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var errorModal = document.getElementById('errorModal');
    var span = document.getElementById('errorModalClose');
    var button = document.getElementById('errorModalButton');
    
    if ('<?php echo $error_message; ?>' != '') {
        errorModal.style.display = "block";
    }

    span.onclick = function() {
        errorModal.style.display = "none";
        window.location.href = 'login.html';
    }

    button.onclick = function() {
        errorModal.style.display = "none";
        window.location.href = 'login.html';
    }

    window.onclick = function(event) {
        if (event.target == errorModal) {
            errorModal.style.display = "none";
            window.location.href = 'login.html';
        }
    }
});
</script>
