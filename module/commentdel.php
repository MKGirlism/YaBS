<?php
include("config.php");

// MySQL
$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);

$aid = (int) $_GET['id'];
$powza = htmlentities($_SESSION['uname']['group'], ENT_QUOTES, 'UTF-8');

$uid = htmlentities($_SESSION['uname']['uid'], ENT_QUOTES, 'UTF-8');
$select = "SELECT uid FROM Comments WHERE uid = ".$uid;
$results = $mysqli->query($select);

include("module/postfunctions.php");
if ($powza <= 0) {
        if ($results) {
                $results->close();
        }
        header('Location: index.php');
        exit();
}
else {
        if ($results) {
                $results->close();
        }
	
	// Execute after clicking "Submit".
	if ($_POST['Delete']) {
	        $update = "UPDATE Comments AS c SET c.delete = 1 WHERE c.id = ".$aid;
	        $result = $mysqli->query($update);
		
		echo "<h2>Delete Comment</h2>";
		echo "Removed. <a href='index.php'>Return</a>.</div>";
		
	        if ($result) {
			$result->close();
			$mysqli->close();
	        }
	}
	
	else if ($_POST['Undelete']) {
		$update = "UPDATE Comments AS c SET c.delete = 0 WHERE c.id = ".$aid;
	        $result = $mysqli->query($update);
		
		echo "<h2>Delete Comment</h2>";
		echo "Unremoved. <a href='index.php'>Return</a>.</div>";
		
	        if ($result) {
	                $result->close();
	                $mysqli->close();
	        }
	}
	
	else {
		$select = "SELECT c.delete FROM Comments AS c WHERE c.id = ".$aid;
		$stmt = $mysqli->prepare($select);
		$stmt->execute();
		
		$stmt->bind_result($del);
		
		while ($stmt->fetch()) {
	        	echo "<h2>Delete Comment</h2>";
	        	echo "<form action='deletecomment.php?id=$aid' method='post'>";
	        	echo "Choose your Action.<br />";
			if ($del == 0)
	        		echo "<input name='Delete' type='submit' value='Remove' /> <input name='Undelete' type='submit' value='Unremove' disabled /></div>";
			else
				echo "<input name='Delete' type='submit' value='Remove' disabled /> <input name='Undelete' type='submit' value='Unremove' /></div>";
	       		echo "</form><br /><br />";
		}
	}
}

// Close MySQL Connection.
$mysqli->close();
?>
