<?php
include_once __DIR__ . '/util.php';
include_once __DIR__ . '/Utenti.php';
\DataHandling\Utenti::registerUser($_POST);
