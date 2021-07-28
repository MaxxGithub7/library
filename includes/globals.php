<?php
include_once __DIR__ . '/util.php';
include_once __DIR__ . '/FormHandle.php';
include_once __DIR__ . '/Libreria.php';
include_once __DIR__ . '/header.php';

if (!isset($_SESSION['username'])) {
    header('Location: https://localhost/library/login.php');
}
