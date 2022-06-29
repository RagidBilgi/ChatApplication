<?php

// session_start creates a session or resumes the current one based on a session identifier passed via a GET or POST request, or passed via a cookie.
session_start();

// isset determine if a variable is declared and is different than null.
if(isset($_POST['action']) && $_POST['action'] == 'leave') // $_POST collect form data after submitting an HTML form.
{
	require('database/ChatUser.php'); // Accessing the database with table ChatUser.

	$user_object = new ChatUser; // Object for ChatUser.

	$timestamp = date('Y-m-d h:i:s');

	// Accessing variables from ChatUser.
	$user_object->setUserId($_POST['user_id']);
	
	$user_object->setUserLoginStatus('Logout');

	$user_object->setTimestamp($timestamp);

	$user_object->setUserLogout('Logout');

	$user_object->setUserToken($_SESSION['user_data'][$_POST['user_id']]['token']);

	//$user_object->save_data();

	// if condition for updating the user login data.
	if($user_object->update_user_login_data())
	{
		unset($_SESSION['user_data']);

		session_destroy();

		echo json_encode(['status'=>1]);
	}
}

// if condition for fetching the chat in real-time.
if(isset($_POST["action"]) && $_POST["action"] == 'fetch_chat')
{
	require 'database/PrivateChat.php'; // Accessing the database with table PrivateChat.

	$private_chat_object = new PrivateChat; // Object for PrivateChat.

	// Accessing variables from PrivateChat.
	$private_chat_object->setFromUserId($_POST["to_user_id"]);

	$private_chat_object->setToUserId($_POST["from_user_id"]);

	$private_chat_object->change_chat_status();

	echo json_encode($private_chat_object->get_all_chat_data());
}
?>