<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
if(isset($_SESSION['Username'])){
    header("location: home");
}
$getLang = '';
if (isset($_GET['lang'])) {
    $getLang = trim(filter_var(htmlspecialchars($_GET['lang'], ENT_QUOTES, 'UTF-8'), FILTER_SANITIZE_SPECIAL_CHARS));
}
if (!empty($getLang)) {
    $_SESSION['language'] = $getLang;
}
// ========================= config the languages ================================
error_reporting(E_NOTICE ^ E_ALL);
if (is_file('home.php')){
    $path = "";
} elseif (is_file('../home.php')){
    $path =  "../";
} elseif (is_file('../../home.php')){
    $path =  "../../";
}
include_once $path."langs/set_lang.php";
?>
<html dir="<?php echo lang('html_dir'); ?>">
<head>
    <title><?php echo lang('welcome'); ?> | YOUTZONE</title>
    <meta charset="UTF-8">
    <meta name="description" content="youtzone is a social network platform helps you meet new friends and stay connected with your family and with who you are interested anytime anywhere.">
    <meta name="keywords" content="homepage,main,login,social network,social media,youtzone,meet,free platform">
    <meta name="author" content="youtzone">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "includes/head_imports_main.php";?>
</head>
<body class="login_signup_body">
    <!--============[ Nav bar ]============-->
    <div class="login_signup_navbar">
        <img src="logo.png" width="50" height="50">
        <a href="index" class="login_signup_navbarLinks">YOUTZONE</a>
        <a href="aboutus.php" class="login_signup_navbarLinks">ABOUT US</a>
        <div style="float: <?php echo lang('float2'); ?>;">
            <a href="login.php" class="login_signup_btn1"><?php echo lang('login'); ?></a>
            <a href="signup.php" class="login_signup_btn2"><?php echo lang('signup'); ?></a>
        </div>
    </div>
    <!--============[ main contains ]============-->
    <div class="login_signup_box">
        <h3 align="center"><?php echo lang('welcome_to'); ?> YOUTZONE</h3>
        <p align="center" style="color: #999; margin-bottom: 25px;"><?php echo lang('wallstant_main_string'); ?>.</p>
        <img src="logo.png" width="80" height="80">
        <div style="display: flex;">
            <div style="width: 100%;">
                <br><h4><?php echo lang('login_now'); ?></h4>
                <p><input type="text" name="login_username" id="un" class="login_signup_textfield" placeholder="<?php echo lang('email_or_username'); ?>"/></p>
                <p><input type="password" name="login_password" id="pd" class="login_signup_textfield" placeholder="<?php echo lang('password'); ?>"/></p>
                <button type="submit" class="login_signup_btn1" id="loginFunCode"><?php echo lang('login'); ?></button>
                <p id="login_wait" style="margin: 0px;"></p>
            </div>
            <div style="width: 100%;text-align: center;">
                <img src="imgs/main_icons/pc_main.png" alt="Wallstant" style="width: 300px;" />
            </div>
        </div>
    </div>
    <div style="background: #fff; border-radius: 3px; max-width: 800px; padding: 15px; margin:auto;margin-top: 15px;color: #7b7b7b;" align="center">
        <?php echo lang('dont_have_an_account'); ?> <a href="signup"><?php echo lang('signup'); ?></a> <?php echo lang('for_free'); ?>.<hr style="margin: 8px;">
        <a href="?lang=english">English</a> &bull; <a href="?lang=العربية">العربية</a>
    </div>

    <script type="text/javascript">
        function loginUser() {
            var username = document.getElementById("un").value;
            var password = document.getElementById("pd").value;
            $.ajax({
                type: 'POST',
                url: 'includes/login_signup_codes.php',
                data: {'req':'login_code','un':username,'pd':password},
                beforeSend: function() {
                    $('.login_signup_btn1').hide();
                    $('#login_wait').html("<?php echo lang('loading'); ?>...");
                },
                success: function(data) {
                    $('#login_wait').html(data);
                    if (data == "Welcome...") {
                        $('#login_wait').html("<p class='alertGreen'><?php echo lang('welcome'); ?>..</p>");
                        setTimeout(' window.location.href = "home"; ',2000);
                    } else {
                        $('.login_signup_btn1').show();
                    }
                },
                error: function(err) {
                    alert(err);
                }
            });
        }
        
        $('#loginFunCode').click(function() {
            loginUser();
        });
        
        $(".login_signup_textfield").keypress(function (e) {
            if (e.keyCode == 13) {
                loginUser();
            }
        });
    </script>
</body>
</html>
