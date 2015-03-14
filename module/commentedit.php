<?php
include("config.php");

// MySQL
$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);

$aid = (int) $_GET['id'];
$admin = htmlentities($_SESSION['uname']['group'], ENT_QUOTES, 'UTF-8') <= 0;
$owner = htmlentities($_SESSION['uname']['uname'], ENT_QUOTES, 'UTF-8');

$uid = htmlentities($_SESSION['uname']['uid'], ENT_QUOTES, 'UTF-8');
$select = $mysqli->prepare("SELECT u.uid, c.id FROM Users AS u, Comments AS c WHERE u.uid = c.uid AND c.id = ".$aid);

$select->execute();
$select->bind_result($uuid, $cuid);
$select->store_result();

$gotoelse = 0;
include("module/postfunctions.php");
while ($select->fetch()) {
	while (true) {
		if ($admin && $gotoelse == 0) {
			if ($uid != $cuid) {
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
			if ($_POST['Submit']) {
			$cont2 = clear($_POST['Content']);
	       		$le = mktime();
			
       			$update = "UPDATE Comments SET Message='$cont2', lastedit=$le WHERE id=".$aid;
       			$result = $mysqli->query($update);
			
			if ($result) {
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
}
}
?>
