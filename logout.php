<?php
session_start();

$_SESSION["username"] = null;

session_unset();

session_destroy();

header("Location: index.php");
exit();
?>