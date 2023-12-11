<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userid = $_POST["userid"];
    $userpw = $_POST["userpw"];
    $username = $_POST["username"];
    $jumin = $_POST["jumin"];
    

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
    $stmt = $conn->prepare("SELECT * FROM register WHERE mb_id=?");
    $stmt->bind_param("s", $userid);
    $stmt->execute();
    $check_id_result = $stmt->get_result();

    if (!$check_id_result) {
        error_log("오류: " . $conn->error);
        echo "오류입니다." // 오류임을 나타내는 응답
        $conn->close();
        exit;
    }

    if ($check_id_result->num_rows > 0) {
        echo "used"; // 이미 사용 중인 아이디임을 나타내는 응답
    } else {
        echo "available"; // 사용 가능한 아이디임을 나타내는 응답
    }

    // 중복되지 않은 키 값을 생성
    $otp = generateRandomString(); 

    // 회원 정보와 키 값을 DB에 저장
    $hashed_password = password_hash($userpw, PASSWORD_DEFAULT); // 비밀번호 해시화
    $stmt = $conn->prepare("INSERT INTO register (mb_id, mb_pw, name, SecNum, OTP) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $userid, $hashed_password, $username, $jumin, $otp);

    if ($stmt->execute()) {
        $_SESSION["otp"] = $otp; // 세션에 키 값 저장
        header("Location: register.php"); 
        exit;
    } else {
        error_log("오류: " . $conn->error);
    }

    $conn->close();
    exit;
}

// 난수 생성 함수
function generateRandomString() {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < 32; $i++) {
        $randomIndex = random_int(0, strlen($characters) - 1);
        $randomString .= $characters[$randomIndex];
    }
    return $randomString;
}
?>

<!DOCTYPE html>
<?php session_start(); ?>
<html lang="en-US">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>VoteYourChoice | 온라인투표서비스</title>
<link rel='stylesheet' href='css/woocommerce-layout.css' type='text/css' media='all'/>
<link rel='stylesheet' href='css/woocommerce-smallscreen.css' type='text/css' media='only screen and (max-width: 768px)'/>
<link rel='stylesheet' href='css/woocommerce.css' type='text/css' media='all'/>
<link rel='stylesheet' href='css/font-awesome.min.css' type='text/css' media='all'/>
<link rel='stylesheet' href='css/style.css' type='text/css' media='all'/>
<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Oswald:400,500,700%7CRoboto:400,500,700%7CHerr+Von+Muellerhoff:400,500,700%7CQuattrocento+Sans:400,500,700' type='text/css' media='all'/>
<link rel='stylesheet' href='css/easy-responsive-shortcodes.css' type='text/css' media='all'/>
<style>
    .centered-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 50vh;
    }

    .key-container {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    	width: 550px;
    	max-width: 90%;
}

    .key-container h1 {
        text-align: center;
    }

    .key-container p {
        text-align: center;
    }
    .warning-text {
        color: red;
        font-weight: bold;
        line-height: 1.2;
    }
</style>
</head>
<body class="home page page-template page-template-template-portfolio page-template-template-portfolio-php">
<div id="page">
    <header id="masthead" class="site-header"> 
        <div class="site-branding">
            <h1 class="site-title"><a href="index.html" rel="home">Vote Your Choice</a></h1>
            <h2 class="site-description">온라인 투표 서비스</h2>
        </div>
        <nav id="site-navigation" class="main-navigation">
        <button class="menu-toggle">Menu</button>
        <a class="skip-link screen-reader-text" href="#content">Skip to content</a>
        <div class="menu-menu-1-container">
            <ul id="menu-menu-1" class="menu">
                <li><a href="index.html">Home</a></li>
                <li><a href="login.html">Login</a></li>
                <li><a href="register.html">register</a></li>
                <li><a href="use.html">How to use</a></li>
                <li><a href="about.html">About</a></li>
            </ul>
        </div>
        </nav>
        </header>
    <div class="centered-container">
        <div class="key-container">
            <main id="content" class="site-content">
            <div class="center-content">
                <p><i class="fas fa-check-circle" style="color: blue;"></i> 회원 가입이 완료 되었습니다.</p>
                    <p><strong style="font-size: 24px;">키가 생성되었습니다.</strong></p>
                    <p id="randomStringDisplay">설정 키: <?php echo $_SESSION["otp"]; ?></p>
                    <p class="warning-text">-주의-</p>
		    <p class="warning-text">해당 키에 대한 접근 권한은 절대로 외부인에게 노출되어서는 안 되며, 이에 대한 엄중한 보안 조치가 필요합니다.</p>
		    <p class="warning-text">※ 키 재발급은 어려우므로 분실에 유의 부탁드립니다.</p>
		    <div style="text-align: center; margin-top: 20px;">
		    <a href="login.html" class="login-button" style="background-color: #007BFF; color: #FFFFFF; padding: 10px 20px; border-radius: 5px; text-decoration: none;">로그인</a>
		    </div>
                </div>
            </main>
        </div>
    </div>

    <footer id="colophon" class="site-footer">
        <div class="container">
            <div class="site-info">
                <h1 style="font-family: 'Herr Von Muellerhoff';color: #ccc;font-weight:300;text-align: center;margin-bottom:0;margin-top:0;line-height:1.4;font-size: 46px;">Vote Your choice</h1>
                Shared by <i class="fa fa-love"></i><a href="https://bootstrapthemes.co">yizo</a>
            </div>
        </div>
    </footer>


</div>
<script>
    window.onload = function() {
        if (window.performance.navigation.type == 1) { // 페이지가 새로고침되었을 경우
            window.location.href = "/index.html";
        }
    }
</script>

<script src='js/jquery.js'></script>
<script src='js/plugins.js'></script>
<script src='js/scripts.js'></script>
<script src='js/masonry.pkgd.min.js'></script>
</body>
</html>


