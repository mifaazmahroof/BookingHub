<?php
session_start();
$returnURL = $_GET['returnURL'] ?? '/'; // fallback to home if not provided

session_destroy();

header("Location: $returnURL");
exit();
?>