<?php

class User {
    private $id = NULL;
    private $email = NULL;
    private $password = NULL;
    private $sessionId = NULL;

	/**
	* User Constructor
	* @param int id
	* @param string $email 
	* @param string $password
	*/
    function __construct($id, $email, $password) {
    	$this->id = $id;
		$this->email = $email;
		$this->password = $password;
    }

	/**
	* Function to get userid
	* @return int id
	*/
    public function getId() {
        return $this->id;
    }
    
    /**
	* Function to get user email
	* @return string email
	*/
    public function getEmail() {
        return $this->email;
    }
    
    /**
	* Function to set user email
	* @param string $email
	*/
    public function setEmail($email) {
		$this->email = $email;
	}
    
    /**
	* Function to ser user password
	* @param string $password plain-text
	*/
    public function setPassword($password){
		$this->password = $this->hashPassword($password);
	}
	
    /**
	* Function to get user hashed password
	* @return string password
	*/
    public function getPassword() {
        return $this->password;
    }

	/**
	* Function to hash a plain-text password
	* @param string $plainTextPassword
	* @return string the hashed password
	*/
	public static function hashPassword($plainTextPassword) {
    	return password_hash($plainTextPassword, PASSWORD_DEFAULT);
	}

	/**
	* Function to authenticate a user
	* @param string $plainTextPassword
	* @return boolean true if valid, false otherwise
	*/
	public function verifyPassword($plainTextPassword){
		return password_verify($plainTextPassword, $this->getPassword()); 
	}
	
	/**
	* Function to set a user session id
	* @param string $sessionId
	*/
	public function setSessionId($sessionId){
		$this->sessionId = $sessionId;
	}
	
	/**
	* Function to get a user's latest session id
	* @return string sessionid
	*/
	public function getSessionId() {
		return $this->sessionId;
	}
}
?>