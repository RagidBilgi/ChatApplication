<?php
/*
 A class for accessing and storing data into the database table responsible for the user personal data.
*/

class ChatUser
{
	// Variables.
	private $user_id; // The id number of each user (Primary key).
	private $user_name; // The name of each user.
	private $user_email; // The email of each user.
	private $user_password; // The password of each user.
	private $user_profile; // The profile image of each user.
	private $user_status; // The account status (Active or not) of each user.
	private $user_created_on; // The timestamp of each user profile creation.
	private $user_verification_code; // The verification code of each user.
	private $user_login_status; // The status (Login or Logout) of each user.
	private $user_token; // The identifier of a user in the database.
	private $user_connection_id; // The connection id number of each user.
	private $timestamp; // The timestamp for each login and logout of each user.
	private $user_logout; // The user logout data.
	private $activity_id; // The id number of each user activity (login and logout) (Primary key).
	private $hash; // Using a strong one-way hashing algorithm for passwords.
	public $connect; // A connection with the database.

	/*
	 Setters and getters for each variable.
	*/
	function setUserId($user_id)
	{
		$this->user_id = $user_id;
	}

	function getUserId()
	{
		return $this->user_id;
	}

	function setUserName($user_name)
	{
		$this->user_name = $user_name;
	}

	function getUserName()
	{
		return $this->user_name;
	}

	function setUserEmail($user_email)
	{
		$this->user_email = $user_email;
	}

	function getUserEmail()
	{
		return $this->user_email;
	}

	function setUserPassword($user_password)
	{
		// password_hash creates a new password hash using a strong one-way hashing algorithm.
		$hash = password_hash($user_password, 
         	 PASSWORD_DEFAULT);
		$this->user_password = $hash;
	}

	function checkHash($user_password)
	{
		password_verify($user_password, $hash); // Verifying the entered password by the user with the registered one in the database.
	}

	function getUserPassword()
	{
		return $this->user_password;
		}

	function setUserProfile($user_profile)
	{
		$this->user_profile = $user_profile;
	}

	function getUserProfile()
	{
		return $this->user_profile;
	}

	function setUserStatus($user_status)
	{
		$this->user_status = $user_status;
	}

	function getUserStatus()
	{
		return $this->user_status;
	}

	function setUserCreatedOn($user_created_on)
	{
		$this->user_created_on = $user_created_on;
	}

	function getUserCreatedOn()
	{
		return $this->user_created_on;
	}

	function setUserVerificationCode($user_verification_code)
	{
		$this->user_verification_code = $user_verification_code;
	}

	function getUserVerificationCode()
	{
		return $this->user_verification_code;
	}

	function setUserLoginStatus($user_login_status)
	{
		$this->user_login_status = $user_login_status;
	}

	function getUserLoginStatus()
	{
		return $this->user_login_status;
	}

	function setUserToken($user_token)
	{
		$this->user_token = $user_token;
	}

	function getUserToken()
	{
		return $this->user_token;
	}

	function setUserConnectionId($user_connection_id)
	{
		$this->user_connection_id = $user_connection_id;
	}

	function getUserConnectionId()
	{
		return $this->user_connection_id;
	}

	function setTimestamp($timestamp)
	{
		$this->timestamp = $timestamp;
	}

	function getTimestamp()
	{
		return $this->timestamp;
	}
	
	function setUserLogout($user_logout)
	{
		$this->user_logout = $user_logout;
	}

	function getUserLogout()
	{
		return $this->user_logout;
	}

	function setActivity_id($activity_id)
	{
		$this->activity_id = $activity_id;
	}

	function getActivity_id()
	{
		return $this->activity_id;
	}


	// A function for creating a connection with the database.
	public function __construct()
	{
		require_once("/var/www/your_domain/database/Database_connection.php");#Here we call the database from a folder that is not on the web directly for increased security.

		$db = new Database_connection();

		$this->connect = $db->connect();
	}

	
	// A function for validating the email adress domain.
	function isEmailValid(){
		if(empty($this->user_email) === true){
			return false;
		}
		$array_domains = array("@ragidhallak.com");
		foreach($array_domains as $domain){
			if(preg_match('|' . $domain . '$|', $this->user_email)){
				return true;
			}
		}
		return false;
	}


	// A function for creating an avatar profile picture.
	function make_avatar($character)
	{
	    $path = "images/". time() . ".png";
		$image = imagecreate(200, 200);
		$red = rand(0, 255);
		$green = rand(0, 255);
		$blue = rand(0, 255);
	    imagecolorallocate($image, $red, $green, $blue);  
	    $textcolor = imagecolorallocate($image, 255,255,255);

	    $font = dirname(__FILE__) . '/font/arial.ttf';

	    imagettftext($image, 100, 0, 55, 150, $textcolor, $font, $character);
	    imagepng($image, $path);
	    imagedestroy($image);
	    return $path;
	}

	// A function for accessing all data from the general chat by verifying the login email address.
	function get_user_data_by_email()
	{
		$query = "
		SELECT * FROM chat_user_table 
		WHERE user_email = :user_email
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':user_email', $this->user_email);

		if($statement->execute())
		{
			$user_data = $statement->fetch(PDO::FETCH_ASSOC);
		}
		return $user_data;
	}


	// A function for storing the data of each user into the database.
	function save_data()
	{
		$query = "
		INSERT INTO chat_user_table (user_name, user_email, user_password, user_profile, user_status, user_created_on, user_verification_code) 
		VALUES (:user_name, :user_email, :user_password, :user_profile, :user_status, :user_created_on, :user_verification_code)";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':user_name', $this->user_name);

		$statement->bindParam(':user_email', $this->user_email);

		$statement->bindParam(':user_password', $this->user_password);

		$statement->bindParam(':user_profile', $this->user_profile);

		$statement->bindParam(':user_status', $this->user_status);

		$statement->bindParam(':user_created_on', $this->user_created_on);

		$statement->bindParam(':user_verification_code', $this->user_verification_code);

		if($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	// A function for checking whether the verification code entered by the user is correct or not.
	function is_valid_email_verification_code()
	{
		$query = "
		SELECT * FROM chat_user_table 
		WHERE user_verification_code = :user_verification_code
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':user_verification_code', $this->user_verification_code);

		$statement->execute();

		if($statement->rowCount() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	// A function that activates the user account by checking the validation of the verification code entered by the user.
	function enable_user_account()
	{
		$query = "
		UPDATE chat_user_table 
		SET user_status = :user_status 
		WHERE user_verification_code = :user_verification_code
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':user_status', $this->user_status);

		$statement->bindParam(':user_verification_code', $this->user_verification_code);

		if($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	// A function that updates the user's status with (login or logout).
	function update_user_login_data()
	{
		$query = "
		UPDATE chat_user_table 
		SET user_login_status = :user_login_status, user_token = :user_token  
		WHERE user_id = :user_id
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':user_login_status', $this->user_login_status);

		$statement->bindParam(':user_token', $this->user_token);

		$statement->bindParam(':user_id', $this->user_id);

		if($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	// A function that access the user data for each user with id.
	function get_user_data_by_id()
	{
		$query = "
		SELECT * FROM chat_user_table 
		WHERE user_id = :user_id";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':user_id', $this->user_id);

		try
		{
			if($statement->execute())
			{
				$user_data = $statement->fetch(PDO::FETCH_ASSOC);
			}
			else
			{
				$user_data = array();
			}
		}
		catch (Exception $error)
		{
			echo $error->getMessage();
		}
		return $user_data;
	}


	// A function that uploads images to the user profile account.
	function upload_image($user_profile)
	{
		$extension = explode('.', $user_profile['name']);
		$new_name = rand() . '.' . $extension[1];
		$destination = 'images/' . $new_name;
		move_uploaded_file($user_profile['tmp_name'], $destination);
		return $destination;
	}


	// A function that enables the user update their personal data.
	function update_data()
	{
		$query = "
		UPDATE chat_user_table 
		SET user_name = :user_name, 
		user_email = :user_email, 
		user_password = :user_password, 
		user_profile = :user_profile  
		WHERE user_id = :user_id
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':user_name', $this->user_name);

		$statement->bindParam(':user_email', $this->user_email);

		$statement->bindParam(':user_password', $this->user_password);

		$statement->bindParam(':user_profile', $this->user_profile);

		$statement->bindParam(':user_id', $this->user_id);

		if($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	// A function that access all the personal data of the user.
	function get_user_all_data()
	{
		$query = "
		SELECT * FROM chat_user_table 
		";

		$statement = $this->connect->prepare($query);

		$statement->execute();

		$data = $statement->fetchAll(PDO::FETCH_ASSOC);

		return $data;
	}


	// A fuction for getting the users' whole data with the status count.
	function get_user_all_data_with_status_count()
	{
		$query = "
		SELECT user_id, user_name, user_profile, user_login_status, 
		(SELECT COUNT(*) FROM chat_message WHERE to_user_id = :user_id AND from_user_id = chat_user_table.user_id AND status = 'No') 
		AS count_status FROM chat_user_table";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':user_id', $this->user_id);

		$statement->execute();

		$data = $statement->fetchAll(PDO::FETCH_ASSOC);

		return $data;
	}

	// A fuction for updating the id of each user connection.
	function update_user_connection_id()
	{
		$query = "
		UPDATE chat_user_table 
		SET user_connection_id = :user_connection_id 
		WHERE user_token = :user_token
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':user_connection_id', $this->user_connection_id);

		$statement->bindParam(':user_token', $this->user_token);

		$statement->execute();
	}


	// A fuction for getting the user id from it's unique token.
	function get_user_id_from_token()
	{
		$query = "
		SELECT user_id FROM chat_user_table 
		WHERE user_token = :user_token
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':user_token', $this->user_token);

		$statement->execute();

		$user_id = $statement->fetch(PDO::FETCH_ASSOC);

		return $user_id;
	}


	// A function the keeps the status of each user updated as (login or logout).
	function save_status_data()
	{
		$query = "
		INSERT INTO status_table (activity_id, user_email, user_login_status, timestamp) 
		VALUES (:activity_id, :user_email, :user_login_status, :timestamp)
		";
		$statement = $this->connect->prepare($query);
		
		$statement->bindParam(':activity_id', $this->activity_id);

		$statement->bindParam(':user_email', $this->user_email);

		$statement->bindParam(':user_login_status', $this->user_login_status);

		$statement->bindParam(':timestamp', $this->timestamp);

		$statement->execute();
	}
}
?>


