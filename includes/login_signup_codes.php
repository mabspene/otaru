<?php
session_start();
include("../config/connect.php");

$req = filter_var($_POST['req'], FILTER_SANITIZE_SPECIAL_CHARS);

switch ($req) {
    // ============================= [ Login code ] =============================
    case 'login_code':
        $username = $_POST['un'];
        $password = $_POST['pd'];

        if (empty($username) && empty($password)) {
            echo "<p class='alertRed'>" . lang('enter_username_to_login') . "</p>";
        } elseif (empty($username)) {
            echo "<p class='alertRed'>" . lang('enter_username_to_login') . "</p>";
        } elseif (empty($password)) {
            echo "<p class='alertRed'>" . lang('enter_password_to_login') . "</p>";
        } else {
            $chekPwd = $conn->prepare("SELECT * FROM signup WHERE Username = :username OR Email = :email");
            $chekPwd->execute([
                'email' => $username,
                'username' => $username
            ]);

            $row = $chekPwd->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                echo "<p class='alertRed'>" . lang('un_email_not_exist') . "!</p>";
            } elseif (!password_verify($password, $row['Password'])) {
                echo "<p class='alertRed'>" . lang('password_incorrect') . "</p>";
            } else {
                $_SESSION['attempts'] = 0;
                include("GeT_login_WhileFetch.php");
                echo "Welcome...";
            }
        }

        break;

    // ============================= [ Signup code ] =============================
    case 'signup_code':
        $signup_id = (rand(0, 99999) . time()) + time();
        $signup_fullname = $_POST['fn'];
        $signup_username = $_POST['un'];
        $signup_email = $_POST['em'];
        $signup_password_var = $_POST['pd'];
        $signup_cpassword = $_POST['cpd'];
        $signup_genderVar = $_POST['gr'];

        // Password hashing
        $signup_password = password_hash($signup_password_var, PASSWORD_DEFAULT);

        if ($signup_genderVar == lang('male')) {
            $signup_gender = "Male";
            $userphoto = "user-male.png";
        } elseif ($signup_genderVar == lang('female')) {
            $signup_gender = "Female";
            $userphoto = "user-female.png";
        } else {
            $signup_gender = "Male";
            $userphoto = "user-male.png";
        }

        if (isset($_SESSION['language'])) {
            $signup_language = $_SESSION['language'];
        } else {
            $signup_language = "English";
        }

        $exist_username = $conn->prepare("SELECT * FROM signup WHERE Username = :signup_username");
        $exist_username->execute([
            'signup_username' => $signup_username
        ]);

        $exist_email = $conn->prepare("SELECT * FROM signup WHERE Email = :signup_email");
        $exist_email->execute([
            'signup_email' => $signup_email
        ]);

        if (empty($signup_fullname) || empty($signup_username) || empty($signup_email) || empty($signup_password) || empty($signup_cpassword)) {
            echo "<p class='alertRed'>" . lang('please_fill_required_fields') . "</p>";
        } elseif ($exist_username->rowCount() > 0) {
            echo "<p class='alertRed'>" . lang('user_already_exist') . "</p>";
        } elseif ($exist_email->rowCount() > 0) {
            echo "<p class='alertRed'>" . lang('email_already_exist') . "</p>";
        } elseif (strlen($signup_password_var) < 6) {
            echo "<p class='alertRed'>" . lang('password_short') . "</p>";
        } elseif ($signup_password_var != $signup_cpassword) {
            echo "<p class='alertRed'>" . lang('password_not_match_with_cpassword') . "</p>";
        } elseif (preg_match('/\s/', $signup_username) || !preg_match('/^[A-Za-z0-9_]+$/', $signup_username)) {
            echo "
            <ul class='alertRed' style='list-style:none;'>
                <li><b>" . lang('username_not_allowed') . ":</b></li>
                <li><span class='fa fa-times'></span> " . lang('signup_username_should_be_1') . ".</li>
                <li><span class='fa fa-times'></span> " . lang('signup_username_should_be_2') . ".</li>
                <li><span class='fa fa-times'></span> " . lang('signup_username_should_be_3') . ".</li>
                <li><span class='fa fa-times'></span> " . lang('signup_username_should_be_4') . ".</li>
                <li><span class='fa fa-times'></span> " . lang('signup_username_should_be_5') . ".</li>
            </ul>";
        } elseif (!filter_var($signup_email, FILTER_VALIDATE_EMAIL)) {
            echo "<p class='alertRed'>" . lang('invalid_email_address') . "</p>";
        } else {
            $signupsql = "INSERT INTO signup (id, Fullname, Username, Email, Password, Userphoto, gender, language)
            VALUES (:signup_id, :signup_fullname, :signup_username, :signup_email, :signup_password, :userphoto, :signup_gender, :signup_language)";
            $query = $conn->prepare($signupsql);
            $query->execute([
                'signup_id' => $signup_id,
                'signup_fullname' => $signup_fullname,
                'signup_username' => $signup_username,
                'signup_email' => $signup_email,
                'signup_password' => $signup_password,
                'userphoto' => $userphoto,
                'signup_gender' => $signup_gender,
                'signup_language' => $signup_language
            ]);

            // Login code after signup
            $loginsql = "SELECT * FROM signup WHERE (Username = :signup_username OR Email = :signup_email) AND Password = :signup_password";
            $query = $conn->prepare($loginsql);
            $query->execute([
                'signup_username' => $signup_username,
                'signup_email' => $signup_email,
                'signup_password' => $signup_password
            ]);

            include("GeT_login_WhileFetch.php");
            echo "Done..";
        }

        break;
}

$conn = null;
?>
