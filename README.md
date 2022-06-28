# ChatApplication

Welcome to Web Chat App! 

Web Chat Application is an open-source application developed to be used within closed communities and families who value their privacy. In this documentation, we will help you with setting up the application and launch it on your Server or Local Machine. We will also share with you the required changes so the application can work as intended. In order to set up the application, you need basic PHP and MySQL knowledge to edit the configs and make sure everything is secured. 

Installing the Application

To install the application, all you have to do is download the source code from HERE, and copy it to your preferred directory. Web Chat Application requires Apache or Nginx to run. Please edit your config and select the path to /chat/index.php. 

Creating The Database

##Waiting for Anas’s script to add details here##

Database Connection Details and AES Decryption Key

To increase the security of the application in case of a breach from the client side, the database connection details (username, password, database name) and the AES Decryption Key have been stored in another folder. We advise you to NOT make the folder ‘database’ accessible through the internet and keep it on the server only.  It is recommended to create a new user specific to the application and give read permission to /database folder to this user only to increase the security of the application. However, this is a completely optional step. 

Using /database/Database_connection.php, you can write your MySQL details to give the application access to the database. For the AES Decryption, please change the 256-bit key to a new key, DO NOT use the default key as it is available publicly to everyone and you can put your data at risk.

Running the Server and Changing Port

The application runs on 55 port by default. If you are planning to use another port, please change the port number in /chat/bin/server.php at line 38 to the port you want. To run the server, you need to run the following command in your terminal “php server.php” from the correct directory. If you are not running on localhost, you need to change the IP and Port to your current server in /chat/chatroom.php at line 213 and in /chat/privatechat.php at line 180.

The default version uses SSL Encryption for enhanced security. To use it, you need a valid SSL Certificate. If you don’t have one, you can get a certificate for free from Let’s Encrypt. Once SSL is added to your domain, please add the following line in your Apache config: “ProxyPass /wss2/ ws://yourdomain:port/” to enable SSL Encryption in the application.

If you are planning to use HTTP, ignore the previous step and keep the config as it is. In the application, please change the connection type from ‘WSS’ to ‘WS’ in /chat/chatroom.php at line 213 and in /chat/privatechat.php at line 180.


Accepted Domains

Since the application is intended to be used within closed groups, you do not want to give access to everyone who comes across your application. Therefore, you can select one or multiple domains to be accepted. The default settings accept “@ragidhallak.com” domain only, you can add the preferred list of domains in chat/database/ChatUser.php at line 194. 

Email Verification: 

To be able to access the application, you need to create an account. This can be done through the register page. However, in order to get a verification email, you need to add your own email settings. 

This application uses the PHPMailer library to send emails with SMTP, from the host of your choice. You can add your SMTP settings from Gmail, Outlook, or Zoho in chat/database/register.php starting at line 66. From there, you can also customize your message, and don’t forget to adjust the path to your Server/LocalMachine at line 88.

File Sharing Addon:

To improve the user experience, you can add file sharing functionality to your application by using an open source third-party application called Kjeela. Kjeela can be installed inside the folder of chat application or as subdomain, we recommend the latter to avoid server config issues. For installation steps, please refer to Kjeela on GitHub. 

Adding File Sharing is a completely optional step and required for the Chat Application to work. Once Kjeela is installed, please edit the link on /chat/chatroom.php at line X and /chat/privatechat.php at line X with the correct link to the service. 




Superuser Settings [DELETED FEATURE DUE TO SECURITY REASONS]

In case you want to view the logs of the database and monitor when users are online, offline, or who is talking with who, you can enable the Superuser feature. To do that, remove the HTML comment in /chat/chatroom.php at line 104 and 107 (<!-- and -->) to enable the SuperUser feature. If you enable it, every user will be able to view it so they know the admin is looking over the logs. You can change the password from superuser_login.php, the default password is “admin”. 

Update: We decided to remove this feature due to security reasons. 
