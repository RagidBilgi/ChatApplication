<?php

// session_start creates a session or resumes the current one based on a session identifier passed via a GET or POST request, or passed via a cookie.
session_start();

// isset determine if a variable is declared and is different than null.
if(!isset($_SESSION['user_data']))
{
    header('location:index.php'); // Accessing the index.php
}

require('database/ChatUser.php'); // Accessing the database with table ChatUser.

$user_object = new ChatUser; // Object for ChatUser.

$user_id = '';

// foreach loop that runs over all the user_data to get the value of each user id.
foreach($_SESSION['user_data'] as $key => $value)
{
    $user_id = $value['id'];
}

// Accessing variables from ChatUser.
$user_object->setUserId($user_id);

$user_data = $user_object->get_user_data_by_id();

$message = '';


// if condition for accessing and editing the user profile data.
if(isset($_POST['edit'])) // $_POST collect form data after submitting an HTML form.
{
    $user_profile = $_POST['hidden_user_profile'];

    if($_FILES['user_profile']['name'] != '')
    {
        $user_profile = $user_object->upload_image($_FILES['user_profile']); // Uploading image.
        $_SESSION['user_data'][$user_id]['profile'] = $user_profile;
    }

    // Accessing variables from ChatUser.
    $user_object->setUserName($_POST['user_name']);

    $user_object->setUserEmail($_POST['user_email']);

    $user_object->setUserPassword($_POST['user_password']);

    $user_object->setUserProfile($user_profile);

    $user_object->setUserId($user_id);

    if($user_object->update_data())
    {
        $message = '<div class="alert alert-success">Profile Details Updated</div>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Chat Application | Profile</title>
	<!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="style.css"/>
    <link href="vendor-front/bootstrap/bootstrap.min.css" rel="stylesheet">

    <link href="vendor-front/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="vendor-front/parsley/parsley.css"/>


    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/material-design-icons/3.0.1/iconfont/material-icons.min.css'><link rel="stylesheet" href="./profilestyle.css">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script><script  src="./profilescript.js"></script>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor-front/jquery/jquery.min.js"></script>
    <script src="vendor-front/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor-front/jquery-easing/jquery.easing.min.js"></script>

    <script type="text/javascript" src="vendor-front/parsley/dist/parsley.min.js"></script>
</head>
<body>
	<?php echo $message; ?>
  <div class="navbar">
    <table style="width:100%">
    <tr>
      <td>
        <h6 class="navtitle">Web Chat Application | Profile</h6>
      </td>
    </tr>
  </table>
  </div>

  <form method="post" id="profile_form" enctype="multipart/form-data">

  <div class="profilepage">

    <table class="profiletable">
      <tr class="profilerow">
        <td class="pfptext" align="left">Your Profile</td>
        <td class="pfptext" align="right"><a href="chatroom.php">Go to Chat</td>
      </tr>

      <tr>
        <td colspan="2">



            <label class="texts" for="name">Name</label><br>
            <input class="inputtext" type="text" name="user_name" id="user_name" size="75" data-parsley-pattern="/^[a-zA-Z\s]+$/" required value="<?php echo $user_data['user_name']; ?>" /><br><br>

            <label class="texts" for="email">Email</label><br>
            <input class="inputtext" type="text" name="user_email" id="user_email" size="75" data-parsley-type="email" required readonly value="<?php echo $user_data['user_email']; ?>" /><br><br>

            <label class="texts" for="pwd">Password</label><br>
            <input class="inputtext" type="password" name="user_password" id="user_password" size="75" data-parsley-minlength="6" data-parsley-maxlength="24" required value="<?php echo $user_data['user_password']; ?>" /><br>

            <input type="checkbox" onclick="showpass()"> Show Password<br><br>

            <label class="texts" for="pfp">Profile picture</label><br>
            <input type="file" name="user_profile" id="user_profile" /><br>

            <img src="<?php echo $user_data['user_profile']; ?>" class="img-fluid img-thumbnail mt-3" width="100" /><br><br>

            <input type="hidden" name="hidden_user_profile" value="<?php echo $user_data['user_profile']; ?>" /><br>



        </td>
      </tr>
      <tr align="center">
        <td colspan="2"><input class="editbutt" type="submit" name="edit" value="Edit"></td>
      </tr>
    </table>

  </div>

  </form>

</body>
</html>

<script>

$(document).ready(function(){

    $('#profile_form').parsley();

    $('#user_profile').change(function(){
        var extension = $('#user_profile').val().split('.').pop().toLowerCase();
        if(extension != '')
        {
            if(jQuery.inArray(extension, ['gif','png','jpg','jpeg']) == -1)
            {
                alert("Invalid Image File");
                $('#user_profile').val('');
                return false;
            }
        }
    });

});

</script>
