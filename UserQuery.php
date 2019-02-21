<?php
include_once "User.php";

class UserQuery {
	
	private $database = "test";
	private $databaseAddr = "prod02";
	private $databaseUser = "testuser";
	private $databasePass = "test1234";
	
	function __construct() {
		$mysqli = new mysqli($this->databaseAddr, $this->databaseUser, $this->databasePass, $this->database);
		if ($mysqli->connect_errno) {
	    	return false;
	    }
		$stmt = $mysqli->prepare("CREATE TABLE IF NOT EXISTS users (
	    	id INT PRIMARY KEY AUTO_INCREMENT,
	        email VARCHAR(255) UNIQUE NOT NULL,
	        password VARCHAR(255) NOT NULL,
	        session_id VARCHAR(255));");
		$stmt->execute();
		$stmt->close();
	    $mysqli->close();
	}
	
	/**
	* Function to get a User object from email
	* @param string $email
	* @return object User
	*/
	public function getUser($email) {
	    $mysqli = new mysqli($this->databaseAddr, $this->databaseUser, $this->databasePass, $this->database);
	    if ($mysqli->connect_errno) {
	    	return false;
	    }

	    $stmt = $mysqli->prepare("SELECT * FROM users WHERE email=?");
	    $stmt->bind_param('s', strval($email));
	    $ret = $stmt->execute();
	    if (!$ret){
			return false;
		}
	    $stmt->bind_result($id, $email, $password, $sessionId);
	    $stmt->fetch();
	    $user = new User($id, $email, $password);
	    $user->setSessionId($sessionId);
	    
	    $stmt->close();
	    $mysqli->close();
	    return $user;
	}

	/**
	* Function to insert a new user object into the database
	* @param string email
	* @param string password
	* @return boolean success/failure
	*/
	public function addUser($email, $password) {
		$mysqli = new mysqli($this->databaseAddr, $this->databaseUser, $this->databasePass, $this->database);
	    if ($mysqli->connect_errno) {
	        echo "MySQL Failed Connection: " . $mysqli->connect_errno . $mysqli->connect_error;
	    }

	    $stmt = $mysqli->prepare("INSERT INTO users(email, password) VALUES(?, ?)");
		$stmt->bind_param('ss', strval($email), strval($password));
	    $ret = $stmt->execute();
	    
	    $stmt->close();
	    $mysqli->close();
		return $ret;
	}

	/**
	* Function to insert a new user object into the database
	* @param object $user
	* @return boolean success/failure
	*/
	public function updateUser($user) {
		$mysqli = new mysqli($this->databaseAddr, $this->databaseUser, $this->databasePass, $this->database);
	    if ($mysqli->connect_errno) {
	        echo "MySQL Failed Connection: " . $mysqli->connect_errno . $mysqli->connect_error;
	    }
		$stmt = $mysqli->prepare("UPDATE users SET email = ?, password = ?, session_id = ? WHERE id = ?");
		$stmt->bind_param('sssd', strval($user->getEmail()), strval($user->getPassword()), strval($user->getSessionId()), intval($user->getId()));
	    $ret = $stmt->execute();
		
	    
	    $stmt->close();
	    $mysqli->close();
		return $ret;
	}
	
	/**
	* Function to get the logged in users
	* @return Array[object{User}] Users
	*/
	public function getLoggedInUsers() {
	    $mysqli = new mysqli($this->databaseAddr, $this->databaseUser, $this->databasePass, $this->database);
	    if ($mysqli->connect_errno) {
	    	return false;
	    }

	    $stmt = $mysqli->prepare("SELECT users.id, users.email, users.password, users.session_id FROM users, sessions where users.session_id = sessions.id");
	    $ret = $stmt->execute();
	    if (!$ret){
			return false;
		}
	    $stmt->bind_result($id, $email, $password, $session_id);

		$users = Array();
        while ($stmt->fetch()) {
        	$user = new User($id, $email, $password, $session_id);
            array_push($users, $user);
        }
	    
	    $stmt->close();
	    $mysqli->close();
	    return $users;
	}
}
?>