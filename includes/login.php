<?php
session_start();
include_once __DIR__ . '/util.php';
include_once __DIR__ . '/Utenti.php';

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header('Location: https://localhost/library/login.php');
    exit;
}

$loggedInUser = \DataHandling\Utenti::loginUser($_POST);
$_SESSION['username'] = $loggedInUser['username'];
$_SESSION['userId'] = $loggedInUser['id'];
header('Location: https://localhost/library');
exit;
