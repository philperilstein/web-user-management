<?php
include_once("SessionQuery.php");
include_once("Session.php");
$session = new Session();

if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
        echo "SESSION: LOGGED IN!!<br />";
        include_once "UserQuery.php";
        include_once "User.php";        
        $getUserQuery = new UserQuery();
        $signedInUser = $getUserQuery->getUser("phil@perilstein.com");		
        $signedInUser->setSessionId(session_id());
        $res = $getUserQuery->updateUser($signedInUser);
        
        $loggedInUsers = $getUserQuery->getLoggedInUsers();
        echo "Logged in Users: <br />";
        	foreach($loggedInUsers as $singleUser){
			echo $singleUser->getEmail() . "<br />";
		}
}
else {
        echo "SESSION: NOT LOGGED IN!!<br />";
        include_once "UserQuery.php";
        include_once "User.php";

        $userQuery = new UserQuery();
        $user = $userQuery->getUser("phil@perilstein.com");
        
        $isvalid = $user->verifyPassword("password1");
        if ($isvalid){
                echo "LOGGED IN!<br />UPDATING SESSION!<br />";
                $_SESSION['login'] = true;
        }
        else {
                echo "INVALID PASSWORD<br />";
        }
}
?>