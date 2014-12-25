<?php
header("Content-Type: text/html; charset=utf-8");
session_start();
?>
<!DOCTYPE HTML>
<html>
<head>
	<?php include("module/meta.php"); ?>
</head>
<body>
<?php
include("module/head.php");
include("module/menu.php");
include("module/blogbody.php");
include("module/foot.php");
?>
</body>
</html>
