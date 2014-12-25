<?php
// Get ID of the Page.
ob_start();
$id = (int) $_GET['id'];
$type = $_GET['type'];
ob_end_clean();
session_start();
?>
<html>
<head>
<title>Comment Post</title>
</head>
<body>
<?php

// Allow Slashes, and allow Enters.
include("module/postfunctions.php");

// Execute, as soon as the Posting starts.
if ($_POST['submit']) {
        require("config.php");
        
        // MySQL
	$con = mysqli_connect($hosty, $uname, $paswd, $dbnme);
        
	// Message Markup
       	$message = clear($_POST['message']);
        $message2 = nl2br($message);
        
	// Better Username, and Date
	$uid = $_SESSION['uname']['uid'];
        $date = mktime();
	$ipaddress = $_SERVER['REMOTE_ADDR'];
	
	// Put it in the Database, and Inform the User, about it.
	$query = "INSERT INTO Comments (id, pid, message, uid, date, ipaddress) VALUES (NULL, $id, '$message2', $uid, $date, '$ipaddress')";
	
	mysqli_query($con, $query);
	echo "Comment Posted.<br /><a href='blogpost.php?id=".$id."'>View Comment</a>";
}

?>
</body>
</html>
