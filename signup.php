<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
if(isset($_SESSION['Username'])){
    header("location: home");
}
$getLang = '';
if (isset($_GET['lang'])) {
    $getLang = trim(filter_var(htmlspecialchars($_GET['lang'], ENT_QUOTES, 'UTF-8'), FILTER_SANITIZE_STRING));
    if (!empty($getLang)) {
        $_SESSION['language'] = $getLang;
    }
}
// ========================= config the languages ================================
error_reporting(E_NOTICE ^ E_ALL);
if (is_file('home.php')){
    $path = "";
}elseif (is_file('../home.php')){
    $path =  "../";
}elseif (is_file('../../home.php')){
    $path =  "../../";
}
include_once $path."langs/set_lang.php";
?>
<html dir="<?php echo lang('html_dir'); ?>">
<head>
    <title><?php echo lang('create_new_account'); ?> | YOUTZONE</title>
    <meta charset="UTF-8">
    <meta name="description" content="youtzone is a social network platform helps you meet new friends and stay connected with your family and with who you are interested anytime anywhere.">
    <meta name="keywords" content="homepage,main,login,social network,social media,youtzone,meet,free platform">
    <meta name="author" content="youtzone">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "includes/head_imports_main.php";?>
</head>
<body class="login_signup_body">
    <!--============[ Nav bar ]============-->
    <div class="login_signup_navbar"><img src="logo.png" width="50" height="50">
        <a href="index" class="login_signup_navbarLinks">YOUTZONE</a>
        <a href="aboutus.php" class="login_signup_navbarLinks">ABOUT US</a>
        
        <div style="float: <?php echo lang('float2'); ?>;">
            <a href="login" class="login_signup_btn1"><?php echo lang('login'); ?></a>
            <a href="signup" class="login_signup_btn2"><?php echo lang('signup'); ?></a>
        </div>
    </div>
    <!--============[ main contains ]============-->
    <div align="center">
        <div class="login_signup_box2" style="text-align:<?php echo lang('textAlign'); ?>"><img src="logo.png" width="80" height="80">
        <!--============[ sign up sec ]============-->
            <h4 align="center"><?php echo lang('create_new_account'); ?></h4>
            <p><input type="text" name="signup_fullname" class="login_signup_textfield" id="fn" placeholder="<?php echo lang('fullname'); ?>"/></p>
            <p><input type="text" name="signup_username" class="login_signup_textfield" id="un" placeholder="<?php echo lang('username'); ?>"/></p>
            <p><input type="email" name="signup_email" class="login_signup_textfield" id="em" placeholder="<?php echo lang('email'); ?>"/></p>
            <p><input type="password" name="signup_password" class="login_signup_textfield" id="pd" placeholder="<?php echo lang('password'); ?>"/></p>
            <p><input type="password" name="signup_cpassword" class="login_signup_textfield" id="cpd" placeholder="<?php echo lang('confirm_password'); ?>"/></p>
            <p> 
            <select class="login_signup_textfield" name="gender" id="gr">
              <option selected><?php echo lang('male'); ?></option>
              <option><?php echo lang('female'); ?></option>
            </select>
            </p>
           
            <button type="submit" class="login_signup_btn2" id="signupFunCode"><?php echo lang('create_account'); ?></button>
            <p id="login_wait" style="margin: 0px;"></p>
        </div>
        <!--============[ login sec ]============-->
        <div style="background: #fff; border-radius: 3px; width: 420px; padding: 15px; margin: 15px;color: #7b7b7b;" align="center">
            <?php echo lang('already_have_an_account'); ?> <a href="login"><?php echo lang('login_now'); ?></a>.<hr style="margin: 8px;">
                <a href="?lang=english">English</a> &bull; <a href="?lang=العربية">العربية</a>
        </div>
    </div>

    <script type="text/javascript">
        function signupUser() {
            var fullname = document.getElementById("fn").value;
            var username = document.getElementById("un").value;
            var emailAdd = document.getElementById("em").value;
            var password = document.getElementById("pd").value;
            var cpassword = document.getElementById("cpd").value;
            var gender = document.getElementById("gr").value;
            $.ajax({
                type: 'POST',
                url: 'includes/login_signup_codes.php',
                data: {'req':'signup_code','fn':fullname,'un':username,'em':emailAdd,'pd':password,'cpd':cpassword,'gr':gender},
                beforeSend: function() {
                    $('.login_signup_btn2').hide();
                    $('#login_wait').html("<b><?php echo lang('creating_your_account'); ?></b>");
                },
                success: function(data) {
                    $('#login_wait').html(data);
                    if (data == "Done..") {
                        $('#login_wait').html("<p class='alertGreen'><?php echo lang('done'); ?>..</p>");
                        setTimeout(' window.location.href = "home"; ',2000);
                    } else {
                        $('.login_signup_btn2').show();
                    }
                },
                error: function(err) {
                    alert(err);
                }
            });
        }
        
        $('#signupFunCode').click(function() {
            signupUser();
        });

        $(".login_signup_textfield").keypress(function (e) {
            if (e.keyCode == 13) {
                signupUser();
            }
        });
    </script>
</body>
</html>
