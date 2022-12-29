<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "demo");

$clear_session_id="UPDATE session set status = 0 where sid ='".$_SESSION['id']."' and uid='".$_SESSION['userid']."'";
$inserted=$mysqli->query($clear_session_id);

session_destroy();

echo 'You have cleaned session';
header('Refresh: 2; URL = login.php');
?>