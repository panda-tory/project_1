<?php
session_start();

$_SESSION['userData'] = $_POST;

header("Location: confirmation.php");
exit();
?>
