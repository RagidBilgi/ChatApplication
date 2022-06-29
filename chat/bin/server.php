<?php

/*
 Error Reporting
*/

// error_reporting function sets the error_reporting directive at runtime.
error_reporting(E_ALL); // E_ALL: Report all errors.


// ini_set function sets the value of a configuration option.
ini_set('ignore_repeated_errors', FALSE); // Ignoring repeated error messages.
ini_set('display_errors', FALSE); // Determine whether errors should be printed to the screen or not.
ini_set('log_errors', TRUE); // Tells whether script error messages should be logged to the server's error log or error_log. This option is thus server-specific.
ini_set('error_log', 'N:\XAMPP\htdocs\chat\bin\error.log'); // A record of critical errors that are encountered by the application, operating system or server while in operation.



/*
 Running the Server
*/

// Importing the Ratchet library and our class Chat.
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use MyApp\Chat;

    require dirname(__DIR__) . '/vendor/autoload.php'; // autoload Composer will make a list of classes that are contained in that file, and whenever one of those classes is needed, Composer will autoload the corresponding file.

    // The base of the application as it handles the direct communication and transport with clients.
    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new Chat()
            )
        ),
        55 // local port number.
    );

    $server->run(); // Run the server.
?>