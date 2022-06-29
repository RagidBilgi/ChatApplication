<?php 
/*
 A class for accessing and storing data into the database table responsible for the general chat room.
*/

class ChatRooms
{
	// Variables.
	private $chat_id; // The id number of each message (Primary key).
	private $user_id; // The id number of each user.
	private $message; // The messages that has been sent.
	private $created_on; // The timestamp of each message created.
	protected $connect; // A connection with the database. 


	/*
	 Setters and getters for each variable.
	*/
	public function setChatId($chat_id)
	{
		$this->chat_id = $chat_id;
	}

	function getChatId()
	{
		return $this->chat_id;
	}

	function setUserId($user_id)
	{
		$this->user_id = $user_id;
	}

	function getUserId()
	{
		return $this->user_id;
	}

	function setMessage($message)
	{
		$this->message = $message;
	}

	function getMessage()
	{
		return $this->message;
	}

	function setCreatedOn($created_on)
	{
		$this->created_on = $created_on;
	}

	function getCreatedOn()
	{
		return $this->created_on;
	}


	// A function for creating a connection with the database.
	public function __construct()
	{
		require_once("/var/www/your_domain/database/Database_connection.php"); #Here we call the database from a folder that is not on the web directly for increased security.

		$db = new Database_connection();

		$this->connect = $db->connect();
	}


	// A function that saves each message been sent by the users with AES-256 encryption to the database.
	function save_chat()
	{
		// Before storing the messages into the database, we encrypt them first using the AES-256 encryption.
		require ("/var/www/your_domain/database/aes.php");
		$query = "
		INSERT INTO chatrooms 
			(userid, msg, created_on) 
			VALUES (:userid, AES_ENCRYPT(:msg, '".$aeskey."'), :created_on)
		";
		$statement = $this->connect->prepare($query); // A connection with the database.

		// Using bindParam for bind a parameter to the specified variable name in a sql statement for access the database record.
		$statement->bindParam(':userid', $this->user_id);

		$statement->bindParam(':msg', $this->message);

		$statement->bindParam(':created_on', $this->created_on);

		$statement->execute();
	}

	
	// A function that retrieve each message been saved in the database for displaying them to the user.
	function get_all_chat_data()
	{
		// To access the encrypted messages we need to decrypt them first.
		require ("/var/www/your_domain/database/aes.php");
		$query = "
			SELECT *, AES_DECRYPT(chatrooms.msg, '".$aeskey."') msg, chatrooms.created_on FROM chatrooms 
			INNER JOIN chat_user_table 
			ON chat_user_table.user_id = chatrooms.userid 
			ORDER BY chatrooms.id ASC
		";

		$statement = $this->connect->prepare($query); // A connection with the database.

		$statement->execute();

		return $statement->fetchAll(PDO::FETCH_ASSOC); // Returning all the messages decrypted.
	}
}
?>