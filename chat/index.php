<?php

/*
 Error Reporting
*/

// ini_set function sets the value of a configuration option.
ini_set('display_errors', 1); // Determine whether errors should be printed to the screen or not.
ini_set('display_startup_errors', 1); // Directive which is used to find errors during PHP's startup sequence.
error_reporting(E_ALL); // E_ALL: Report all errors.

// session_start creates a session or resumes the current one based on a session identifier passed via a GET or POST request, or passed via a cookie.
session_start();

// Variable.
$error = '';


// isset determine if a variable is declared and is different than null.
if(isset($_SESSION['user_data'])) // $_SESSION is an associative array that contains all session variables.
{
    header('location:chatroom.php'); // Accessing the chatroom.php
}


// if condition for user login.
if(isset($_POST['login']))
{
    require_once('database/ChatUser.php'); // Accessing the database with table ChatUser.

    $timestamp = date('Y-m-d h:i:s');

    $user_object = new ChatUser; // Object for ChatUser.

    // Accessing variables from ChatUser.
    $user_object->setUserEmail($_POST['user_email']);

    $user_data = $user_object->get_user_data_by_email();


    // if conditions for user login process.
    if(is_array($user_data) && count($user_data) > 0)
    {
        if($user_data['user_status'] == 'Enable')
        {
			$password_result = password_verify($_POST['user_password'], $user_data['user_password']); // Checking if the password correct or not.

            if($password_result === true) // If yes, login
            {

                $user_object->setUserId($user_data['user_id']);

                $user_object->setUserLoginStatus('Login');

		$user_object->setTimestamp($timestamp);

		$user_object->setUserLogout('Login');

                $user_token = md5(uniqid());

                $user_object->setUserToken($user_token);

		$user_object->save_status_data();

                if($user_object->update_user_login_data()) // Updateing the user login data after the successful login.
                {
                    $_SESSION['user_data'][$user_data['user_id']] = [
                        'id'    =>  $user_data['user_id'],
                        'name'  =>  $user_data['user_name'],
                        'profile'   =>  $user_data['user_profile'],
                       'token' =>  $user_token
                    ];

                    header('location:chatroom.php');
                }
            }
            else
            {
                $error = 'Password is invalid.'; // Error message in case of wrong password.
            }
        }
        else
        {
            $error = 'Please double check your email.'; // Error message in case of wrong email.
        }
    }
    else
    {
        $error = 'Email is wrong or invalid'; // Error message in case of wrong email.
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

    <title>Chat Application | Server Raiders </title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="style.css">

    <link href="vendor-front/bootstrap/bootstrap.min.css" rel="stylesheet">

    <link href="vendor-front/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="vendor-front/parsley/parsley.css"/>

    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css'>
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/material-design-icons/3.0.1/iconfont/material-icons.min.css'><link rel="stylesheet" href="./indexstyle.css">
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script><script  src="./indexscript.js"></script>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor-front/jquery/jquery.min.js"></script>
    <script src="vendor-front/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor-front/jquery-easing/jquery.easing.min.js"></script>

    <script type="text/javascript" src="vendor-front/parsley/dist/parsley.min.js"></script>
</head>

<body>
        <?php
               if(isset($_SESSION['success_message']))
               {
                    echo '
                    <div class="alert alert-success">
                    '.$_SESSION["success_message"] .'
                    </div>
                    ';
                    unset($_SESSION['success_message']);
               }

               if($error != '')
               {
                    echo '
                    <div class="alert alert-danger">
                    '.$error.'
                    </div>
                    ';
               }
               ?>

               <form method="post" id="login_form">
               <div class="navbar">
                 <table style="width:100%">
                 <tr>
                   <td>
                     <h6 class="navtitle">Web Chat Application</h6>
                   </td>


                   <td>
                     <input class="registerbtn" type="submit" name="register" value="Register" onClick="window.location = 'register.php'" />

                     <input class="loginbtn" type="submit" name="login" id="login" value="Login">

                     <input class="loginbtn" type="submit" name="Documentation" value="Documentation" onClick="window.open('https://github.com/RagidBilgi/ChatApplication');">
                   </td>
                 </tr>
               </table>
               </div>

               <div class="mainindex">
                       <table class="registerlogin">
                         <tr class="edit-header">
                           <td colspan="2">
                               <h6 class="welcome-title">Welcome back!</h6>
                           </td>
                         </tr>
                         <tr>
                           <td colspan="2">


                               <label class="texts" for="emailmain">Enter your email:</label><br>
                               <input class="inputtext" type="text" name="user_email" id="user_email" size="40" data-parsley-type="email" required /><br><br>
                               <label class="texts" for="pwdmain">Enter your password:</label><br>
                               <input class="inputtext" type="password" name="user_password" id="user_password" size="40" required /><br>
                               <input class="texts" type="checkbox" onclick="showpass()"> Show Password<br><br>



                           </td>
                         </tr>
                         <tr class="lgnregisbtn">
                           <td align="center">
                             <input class="registerbutt" type="submit" name="register" value="Register" onClick="window.location = 'register.php'" />
                          </td>

                          <td>
                             <input class="loginbutt" type="submit" name="login" id="login" value="Login">
                          </td>
                         </tr>
                       </table>
               </div>
               </form>
    </body>

</html>

<script>

$(document).ready(function(){

    $('#login_form').parsley();

});

</script>
