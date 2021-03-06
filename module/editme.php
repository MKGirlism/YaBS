<?php
$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);

$aid = (int) $_GET['uid'];
$admin = htmlentities($_SESSION['uname']['group'], ENT_QUOTES, 'UTF-8') <= 0;
$owner = htmlentities($_SESSION['uname']['uname'], ENT_QUOTES, 'UTF-8');

$uid = htmlentities($_SESSION['uname']['uid'], ENT_QUOTES, 'UTF-8');
$select = $mysqli->prepare("SELECT uid FROM Users WHERE uid = ".$aid);

$select->execute();
$select->bind_result($uuid);
$select->store_result();

$gotoelse = 0;
while ($select->fetch()) {
	while (true) {
		if ($admin && $gotoelse == 0) {
                        if ($uid != $aid) {
                                header('Location: index.php');
                                exit();
                                break;
                        }
                        else {
                                $gotoelse = 1;
                                continue;
                        }
                }
                else {
		require("common.php");
		if(empty($_SESSION['uname'])) {
        	        header("Location: login.php");
        	        die("Redirecting to login.php");
        		break;
		}
		if(!empty($_POST)) {
			if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                	        die("Invalid Email Address");
                	}
			
                	if($_POST['email'] != $_SESSION['uname']['email']) {
                        	$query = "SELECT 1 FROM Users WHERE email = :email";
                        	$query_params = array(':email' => $_POST['email']);
				
                        	try {
                        	        $stmt = $db->prepare($query);
                        	        $result = $stmt->execute($query_params);
                        	}
                        	catch(PDOException $ex) {
                        	        die("Failed to run query: " . $ex->getMessage());
	                        }
				
                	        $row = $stmt->fetch();
                	        if($row) {
                	                die("This Email address is already in use");
                	        }
                	}
			
                	if(!empty($_POST['passwd'])) {
                	        $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
                	        $passwd = hash('sha256', $_POST['passwd'] . $salt);
               		        for($round = 0; $round < 65536; $round++) {
                	                $passwd = hash('sha256', $passwd . $salt);
                	        }
                	}
                	else {
                	        $passwd = null;
                	        $salt = null;
               		}
			
			$query_params = array(':email' => $_POST['email'], ':uid' => $_SESSION['uname']['uid']);
                	$query_params[':ava'] = $_POST['ava'];
			
			if($passwd !== null) {
                	        $query_params[':passwd'] = $passwd;
                	        $query_params[':salt'] = $salt;
                	}
			
                	$query = "UPDATE Users SET email = :email, ava = :ava";
			
                	if($passwd !== null) {
                	        $query .= ", passwd = :passwd, salt = :salt";
                	}
			
                	$query .= " WHERE uid = :uid";
			
			try {
                	        $stmt = $db->prepare($query);
                	        $result = $stmt->execute($query_params);
                	}
                	catch(PDOException $ex) {
                	        die("Failed to run query: " . $ex->getMessage());
                	}
			
                	$_SESSION['uname']['email'] = $_POST['email'];
			
                	require("config.php");
                	header("Location: index.php");
                	die("Redirecting to index.php");
		}
		else {
echo "<center><h1>Edit Account</h1></center>
<form action=\"editprofile.php\" method=\"post\">
        Username: <b>". htmlentities($_SESSION['uname']['uname'], ENT_QUOTES, 'UTF-8')."</b><br /><br />
        Email:<br /><input type=\"text\" name=\"email\" value=\"". htmlentities($_SESSION['uname']['email'], ENT_QUOTES, 'UTF-8') ."\" /><br />
        Avatar:<br /><input type=\"text\" name=\"ava\" value=\"". htmlentities($_SESSION['uname']['ava'], ENT_QUOTES, 'UTF-8') ."\" /><br />
        Password:<br /><input type=\"password\" name=\"passwd\" value=\"\" /><br />
        <i>(Leave blank if you don't want to change your password)</i><br />
        <input type=\"submit\" value=\"Update Account\" /><br />
</form><br />";
		}
		$mysqli->close();
		break;
}
	}
}
?>
