<?php
include_once __DIR__ . '/globals.php';
if (isset($_GET['id'])) {
    \DataHandling\Library::updateData($_POST, $_GET['id']);
} else {
    try {
        \DataHandling\Library::insertData($_POST,  $_SESSION['disponibilita']);
    } catch (Exception $e) {
        echo 'Exception: ', $e->getMessage(), "\n";
    }
}
