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
if ($powza <= 0 || $uid != $_SESSION['uname']['uid']) {
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
	if ($_POST['Submit']) {
		$cont2 = clear($_POST['Content']);
	       	$le = mktime();
		
       		$update = "UPDATE Comments SET Message='$cont2', lastedit=$le WHERE id=".$aid;
       		$result = $mysqli->query($update);
		
       		if ($result) {
       		//	$result->close();
       			$mysqli->close();
       		}
		echo "<h2>Edit Comment</h2>";
		echo "Done. <a href='index.php'>Return</a>.</div>";
	}
	else {
		$sql = "SELECT id, Message FROM Comments WHERE id = ".$aid;
		
		$stmt = $mysqli->prepare($sql);
		$stmt->execute();

		$stmt->bind_result($id, $msg);
		
		while($stmt->fetch()) {
			echo "<script type='text/javascript' src='module/codebutton.js'></script>";
			echo "<h2>Edit Comment</h2>";
			echo "<a href='index.php'>Return</a><br /><br />";
			echo "<form action='editcomment.php?id=$aid' method='post'>
<div id='BlogBody'>";
include ("module/textbuttons.php");

echo "<textarea name='Content' id='message' rows='20' cols='160' wrap='hard'>".$msg."</textarea><br />
<input name='Submit' type='submit' value='Edit'></div>
</form><br /><br />";
		}
		
		$stmt->close();
		$mysqli->close();
	}
}
?>
