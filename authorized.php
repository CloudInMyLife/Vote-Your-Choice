<?php
session_start();

function request_api($user_id, $entered_otp) {
    $api_url = "http://192.168.100.36:8000/check_otp?user_id=" . urlencode($user_id) . "&otp=" . urlencode($entered_otp);
    $response = file_get_contents($api_url);
    $api_response = json_decode($response, true);

    if (isset($api_response['success']) && $api_response['success'] == 'true') {
        return true;
    } else {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['OTP'])) {
        $entered_otp = $_POST['OTP'];
        $user_id = $_SESSION['user_id']; // 세션에서 사용자 ID를 가져옴 

        $api_response = request_api($user_id, $entered_otp);

        // 인증 결과를 세션 변수에 저장
        $_SESSION['authentication_success'] = $api_response;

        // 성공한 경우에는 auth_success.html 페이지로 리다이렉트
        if ($api_response) {
            header("Location: auth_success.html");
            exit;
        } else {
            // 실패한 경우에는 auth_fail.html 페이지로 리다이렉트
            header("Location: auth_fail.html");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
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
#OTP::-webkit-inner-spin-button, 
#OTP::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

#OTP {
  -moz-appearance: textfield;
}
.signup-form {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background-color: #f9f9f9;
    border-radius: 5px;
    padding: 20px;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    width: 300px;
    margin: 0 auto; /* 가운데로 배치 */
    margin-bottom: 15px; /* 여백 추가 */
}

    .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

    .submit-button {
        width: 100%;
        background-color: #007bff;
        color: #fff;
        padding: 10px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        transition: background-color 0.3s ease-in-out;
    }

    .submit-button:hover {
        background-color: #0056b3;
    }
        .body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .h1 {
            margin-top: 50px;
        }
        .message {
            font-size: 18px;
            font-weight: bold;
            color: <?php echo isset($_SESSION['authentication_success']) && $_SESSION['authentication_success'] == true ? 'green' : 'red'; ?>;
            margin-top: 20px;
        }

</style>

</head>
<body class="home page page-template page-template-template-portfolio page-template-template-portfolio-php">
<div id="page">
    <header id="masthead" class="site-header">
        <div class="site-branding">
            <h1 class="site-title"><a href="index.html" rel="home">Vote Your Choice</a></h1>
            <h2 class="site-description">온라인 투표 서비스 </h2>
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
    <div class="container">
    <div class="signup-form">
        <h2 style="font-family: 'Your Preferred Font'; font-size: 24px; color: #333; text-align: center;">2차인증</h2>
        <form action="authorized.php" method="post" autocomplete="off">
              <div class="form-group">
                  <label for="OTP">OTP</label>
                  <input type="number" id="OTP" name="OTP" required maxlength="6" inputmode="numeric">
              </div>
          
            <button type="submit" class="submit-button">인증하기</button>
        </form>
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
    
    <script src='js/jquery.js'></script>
    <script src='js/plugins.js'></script>
    <script src='js/scripts.js'></script>
    <script src='js/masonry.pkgd.min.js'></script>
    <?php if (isset($_SESSION['authentication_success'])) {
        if ($_SESSION['authentication_success'] === false) { ?>
            <div class="centered-container">
                <div class="key-container">
                    <main id="content" class="site-content">
                        <div class="center-content">
                            <span class="warning-icon">&#9888;</span>
                            <a href="login.html">로그인 페이지로 이동</a>
                        </div>
                    </main>
                </div>
            </div>
        <?php }
    } ?>
</body>
</html>

