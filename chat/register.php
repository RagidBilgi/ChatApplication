<?php

// PHPMailer is a code library and used to send emails safely and easily via PHP code from a web server.
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // autoload Composer will make a list of classes that are contained in that file, and whenever one of those classes is needed, Composer will autoload the corresponding file.

// Variables
$error = '';
$success_message = '';


// if condition for user registration.
if(isset($_POST["register"]))
{
    // session_start creates a session or resumes the current one based on a session identifier passed via a GET or POST request, or passed via a cookie.
    session_start();

    if(isset($_SESSION['user_data']))
    {
        header('location:chatroom.php');
    }

    require_once('database/ChatUser.php'); // Accessing the database with table ChatUser.

    $user_object = new ChatUser; // Object for ChatUser.

    // Accessing variables from ChatUser.
    $user_object->setUserName($_POST['user_name']);

    $user_object->setUserEmail($_POST['user_email']);

	if($user_object->isEmailValid() === false){ // Checking the validity of the email adress.
		echo "Outsiders are not allowed";
		$error2 = "YOU ARE AN OUTSIDER!";
		return;
	}
    $user_object->setUserPassword($_POST['user_password']);

    $user_object->setUserProfile($user_object->make_avatar(strtoupper($_POST['user_name'][0])));

    $user_object->setUserStatus('Disabled');

    $user_object->setUserCreatedOn(date('Y-m-d H:i:s'));

    $user_object->setUserVerificationCode(md5(uniqid()));

    $user_data = $user_object->get_user_data_by_email();

    // Checking email occurrence.
    if(is_array($user_data) && count($user_data) > 0)
    {
        $error = 'This email is already registered in the app.';
    }
    else
    {
        if($user_object->save_data())
        {

            $mail = new PHPMailer(true);

            $mail->isSMTP();

            $mail->Host = 'SMTPHOST'; // #Change SMTP host to your host

            $mail->SMTPAuth = true;

            $mail->Username   = 'USERNAME';  // SMTP username
            $mail->Password   = 'PASSWORD#';

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            $mail->Port = 587;

            $mail->setFrom('FROMWHO', 'NAME');

            $mail->addAddress($user_object->getUserEmail());

            $mail->isHTML(true);

            $mail->Subject = 'Verifiy Your Email for Chat Application';

            $mail->Body = '
            <p>Thank you for registering your account!.</p>
                <p>This is a verification email, now please click the link below to verify your account.</p>
                <<p><a href="https://projecmpe492.click/verify.php?code='.$user_object->getUserVerificationCode().'">Click to Verify</a></p><p>Thank you...</p>            ';
            $mail->send();

            $success_message = 'Verification Email sent to ' . $user_object->getUserEmail() . ', so before login first verify your email';
        }
        else
        {
            $error = 'Something went wrong try again';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Chat Application | Registration</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="style.css"/>

    <link href="vendor-front/bootstrap/bootstrap.min.css" rel="stylesheet">

    <link href="vendor-front/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="vendor-front/parsley/parsley.css"/>

    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/material-design-icons/3.0.1/iconfont/material-icons.min.css'><link rel="stylesheet" href="./registerstyle.css">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script><script  src="./registerscript.js"></script>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor-front/jquery/jquery.min.js"></script>
    <script src="vendor-front/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor-front/jquery-easing/jquery.easing.min.js"></script>

    <script type="text/javascript" src="vendor-front/parsley/dist/parsley.min.js"></script>
</head>

<body>
        <?php
                if($error != '')
                {
                    echo '
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                      '.$error.'
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    ';
                }

                if($success_message != '')
                {
                    echo '
                    <div class="alert alert-success">
                    '.$success_message.'
                    </div>
                    ';
                }
                ?>

                <div class="navbar">
                  <table style="width:100%">
                  <tr>
                    <td>
                      <h6 class="navtitle">Web Chat Application | Registration</h6>
                    </td>
                  </tr>
                </table>
                </div>

                <div class="regpage">
                  <form method="post" id="register_form">
                  <table class="regtable">
                    <tr class="regrow">
                      <td>
                        <h6 class="Regtext">Register</h6>
                      </td>
                    </tr>
                    <tr>
                      <td>

                            <label class="texts" for="status">Enter your Name and Surname:</label><br>
                            <input class="inputtext" type="text" name="user_name" id="user_name" size="50" data-parsley-pattern="/^[a-zA-Z\s]+$/" required /><br><br>
                            <label class="texts" for="email">Enter your Email:</label><br>
                            <input class="inputtext" type="text" name="user_email" id="user_email" size="50" data-parsley-type="email" required /><br><br>
                            <label class="texts" for="password">Enter your Password:</label><br>
                            <input class="inputtext" type="password" name="user_password" id="user_password" size="50" data-parsley-minlength="6" data-parsley-maxlength="24" required /><br>
                            <input type="checkbox" onclick="showpass()"> Show Password<br><br>

                      </td>
                    </tr>
                    <tr align="center">
                      <td><input class="registerbutt" type="submit" name="register" value="Register"></td>
                    </tr>
                  </table>
                  </form>
                </div>
    </body>

</html>

<script>

$(document).ready(function(){

    $('#register_form').parsley();

});

</script>
