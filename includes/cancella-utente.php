<?php
session_start();
include_once __DIR__ . '/util.php';
include_once __DIR__ . '/Utenti.php';
\DataHandling\Utenti::deleteUser($_SESSION['userId']);

