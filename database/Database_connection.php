<?php
/*
 A class for making a connection with the database.
*/

class Database_connection
{
	function connect()
	{
		// PDO provides a data-access abstraction layer, which means that, regardless of which database you're using, you use the same functions to issue queries and fetch data.
		$connect = new PDO("mysql:host=localhost; dbname=DBNAME", "USERNAME", "PASSWORD"); #add your details

		return $connect;
	}
}
?>