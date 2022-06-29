<?php
/*
 A class for accessing and storing data into the database table responsible for the private chat.
*/

class PrivateChat
{
	// Variables.
	private $chat_message_id; // The id number of each message (Primary key).
	private $to_user_id; // The id number of the message receiver.
	private $from_user_id; // The id number of the message sender.
	private $chat_message; // The messages that has been sent.
	private $timestamp; // The timestamp for each message sent.
	private $status; // The status (received or not) for each message.
	protected $connect; // A connection with the database. 


	/*
	 Setters and getters for each variable.
	*/
	function setChatMessageId($chat_message_id)
	{
		$this->chat_message_id = $chat_message_id;
	}

	function getChatMessageId()
	{
		return $this->chat_message_id;
	}

	function setToUserId($to_user_id)
	{
		$this->to_user_id = $to_user_id;
	}

	function getToUserId()
	{
		return $this->to_user_id;
	}

	function setFromUserId($from_user_id)
	{
		$this->from_user_id = $from_user_id;
	}

	function getFromUserId()
	{
		return $this->from_user_id;
	}

	function setChatMessage($chat_message)
	{
		$this->chat_message = $chat_message;
	}

	function getChatMessage()
	{
		return $this->chat_message;
	}

	function setTimestamp($timestamp)
	{
		$this->timestamp = $timestamp;
	}

	function getTimestamp()
	{
		return $this->timestamp;
	}

	function setStatus($status)
	{
		$this->status = $status;
	}

	function getStatus()
	{
		return $this->status;
	}


	// A function for creating a connection with the database.
	public function __construct()
	{
		require_once("/var/www/your_domain/database/Database_connection.php");

		$db = new Database_connection();

		$this->connect = $db->connect();
	}



	// A function that saves each message been sent by the users with AES-256 encryption to the database.
	function save_chat()
	{
		// Before storing the messages into the database, we encrypt them first using the AES-256 encryption.
		require ("/var/www/your_domain/database/aes.php");
		$query = "
		INSERT INTO chat_message 
			(to_user_id, from_user_id, chat_message, timestamp, status) 
			VALUES (:to_user_id, :from_user_id, AES_ENCRYPT(:chat_message, '".$aeskey."'), :timestamp, :status)
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':to_user_id', $this->to_user_id);

		$statement->bindParam(':from_user_id', $this->from_user_id);

		$statement->bindParam(':chat_message', $this->chat_message);

		$statement->bindParam(':timestamp', $this->timestamp);

		$statement->bindParam(':status', $this->status);

		$statement->execute();

		return $this->connect->lastInsertId();
	}


	// A function that retrieve each message been saved in the database for displaying them to the user.
	function get_all_chat_data()
	{
		// To access the encrypted messages we need to decrypt them first.
		require ("/var/www/your_domain/database/aes.php");
		$query = "
		SELECT a.user_name as from_user_name, b.user_name as to_user_name, AES_DECRYPT(chat_message.chat_message,'".$aeskey."') chat_message, timestamp, status, to_user_id, from_user_id  
			FROM chat_message 
		INNER JOIN chat_user_table a 
			ON chat_message.from_user_id = a.user_id 
		INNER JOIN chat_user_table b 
			ON chat_message.to_user_id = b.user_id 
		WHERE (chat_message.from_user_id = :from_user_id AND chat_message.to_user_id = :to_user_id) 
		OR (chat_message.from_user_id = :to_user_id AND chat_message.to_user_id = :from_user_id)";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':from_user_id', $this->from_user_id);

		$statement->bindParam(':to_user_id', $this->to_user_id);

		$statement->execute();

		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	
	// A function the updates the message status as the message been read.
	function update_chat_status()
	{
		$query = "UPDATE chat_message SET status = :status WHERE chat_message_id = :chat_message_id";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':status', $this->status);

		$statement->bindParam(':chat_message_id', $this->chat_message_id);

		$statement->execute();
	}

	
	// A function the change the message status in the database as the message been read as (yes).
	function change_chat_status()
	{
		$query = "UPDATE chat_message 
			SET status = 'Yes' 
			WHERE from_user_id = :from_user_id 
			AND to_user_id = :to_user_id 
			AND status = 'No'";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':from_user_id', $this->from_user_id);

		$statement->bindParam(':to_user_id', $this->to_user_id);

		$statement->execute();
	}
}
?>