<?php
header("Content-Type: text/html; charset=utf-8");
ob_start();
$id = (int) $_GET['id'];
if ($id < 1)
{
        header('Location: index.php');
        exit();
}
ob_end_clean();
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
include("module/commentpost.php");
include("module/foot.php");
?>
</body>
</html>
