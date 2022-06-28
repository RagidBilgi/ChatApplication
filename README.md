# Web Chat Application Documentation

## Welcome to Web Chat App

Web Chat Application is an open-source application developed to be used within closed communities and families who value their privacy. In this documentation, we will help you with setting up the application and launch it on your Server or Local Machine. We will also share with you the required changes so the application can work as intended. In order to set up the application, you need basic PHP and MySQL knowledge to edit the configs and make sure everything is secured. 

## Installing the Application

To install the application, all you have to do is clone or download the source code from GitHub, and copy it to your preferred directory on your sever. Web Chat Application requires Apache or Nginx to run. Please edit your config and select the path to /chat/index.php. 

## Creating The Database

Using the following script, you can create the database automatically. Please change **$servername, $username, and $password** to your details. 

<details><summary>Show Script Code</summary>
<p>

```
<?php

	# This file is made to initiate your database and tables by running it only once

	# Connect to your server
	$servername = "localhost"; #your MySQL server name
	$username = "root"; #your username
	$password = "password"; #your password
	
	$link = mysqli_connect($servername, $username);
	if (!$link) {
		die("Connection failed: " . mysqli_connect_error());
	}


	# Create a database called MyChat which stores all tables for the application
	$sql = 'CREATE DATABASE MyChat';
	if (mysqli_query($link, $sql)) {
		echo "Database MyChat created successfully\n";
	} else {
		echo 'Error creating database: ' . mysqli_error() . "\n";
	}


	$link = mysqli_connect($servername, $username, "", 'MyChat');
	# Create the tables for the application

	# Create table for User Profile
	$sql_table_userProfile = "CREATE TABLE user_profile (
		id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		first_name VARCHAR(30) NOT NULL,
		last_name VARCHAR(30) NOT NULL,
		email VARCHAR(50),
		reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
		)";
		
		if (mysqli_query($link, $sql_table_userProfile)) {
		  echo "Table userProfile created successfully\n";
		} else {
		  echo "Error creating table: " . mysqli_error($link);
		}

	# Create table for Private Messages 
	$sql_private_message = "CREATE TABLE private_message (
		id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		sender_Id VARCHAR(30) NOT NULL,
		receiver_Id VARCHAR(30) NOT NULL,
		sent_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
		)";
		
		if (mysqli_query($link, $sql_private_message)) {
			echo "Table privateMessage created successfully\n";
		} else {
			echo "Error creating table: " . mysqli_error($link);
		}

	# Create table for User Status 
	$sql_user_status = "CREATE TABLE user_status (
		id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		userId VARCHAR(30) NOT NULL,
		user_login TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		user_logout TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
		)";
		
		if (mysqli_query($link, $sql_user_status)) {
			echo "Table userStatus created successfully\n";
		} else {
			echo "Error creating table: " . mysqli_error($link);
		}
	
?>
```

</p>
</details>


## Database Connection Details and AES Decryption Key

To increase the security of the application in case of a breach from the client side, the database connection details (username, password, database name) and the AES Decryption Key have been stored in another folder. We advise you to NOT make the folder ‘database’ accessible through the internet and keep it on the server only.  It is recommended to create a new user specific to the application and give read permission to /database folder to this user only to increase the security of the application. However, this is a completely optional step. 

Using **/database/Database_connection.php**, you can write your MySQL details to give the application access to the database. For the AES Decryption, please change the 256-bit key to a new key, DO NOT use the default key as it is available publicly to everyone and you can put your data at risk.

## Running the Server and Changing Port

The application runs on **55** port by default. If you are planning to use another port, please change the port number in **/chat/bin/server.php at line 38** to the port you want. To run the server, you need to run the following command in your terminal “php server.php” from the correct directory. If you are not running on localhost, you need to change the IP and Port to your current server in **/chat/chatroom.php at line 213** and in **/chat/privatechat.php at line 180**.

The default version uses SSL Encryption for enhanced security. To use it, you need a valid SSL Certificate. If you don’t have one, you can get a certificate for free from Let’s Encrypt. Once SSL is added to your domain, please add the following line in your Apache config: **“ProxyPass /wss2/ ws://yourdomain:port/”** to enable SSL Encryption in the application.

If you are planning to use HTTP, ignore the previous step and keep the config as it is. In the application, please change the connection type from ‘WSS’ to ‘WS’ in **/chat/chatroom.php at line 213** and in **/chat/privatechat.php at line 180**.

## Accepted Domains

Since the application is intended to be used within closed groups, you do not want to give access to everyone who comes across your application. Therefore, you can select one or multiple domains to be accepted. The default settings accept “@ragidhallak.com” domain only, you can add the preferred list of domains in **chat/database/ChatUser.php at line 194**. 

## Email Verification

To be able to access the application, you need to create an account. This can be done through the register page. However, in order to get a verification email, you need to add your own email settings. 

This application uses the PHPMailer library to send emails with SMTP, from the host of your choice. You can add your SMTP settings from Gmail, Outlook, or Zoho in **chat/database/register.php starting at line 66**. From there, you can also customize your message, and don’t forget to adjust the path to your Server/LocalMachine at line 88.

## File Sharing Addon

To improve the user experience, you can add file sharing functionality to your application by using an open source third-party application called Kjeela. Kjeela can be installed inside the folder of chat application or as subdomain, we recommend the latter to avoid server config issues. For installation steps, please refer to [Kjeela on GitHub.](https://github.com/kleeja-official/kleeja)

Adding File Sharing is a completely optional step and not required for the Chat Application to work. Once Kjeela is installed, please edit the link on **/chat/chatroom.php at line X** and **/chat/privatechat.php at line X** with the correct link to the service. 
