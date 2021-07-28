<?php
include_once __DIR__ . '/globals.php';
if ( isset( $_GET['id'] ) ) {
  \DataHandling\Libreria::deleteData($_GET['id']);
} else {
  \DataHandling\Libreria::deleteData(null, $_SESSION['userId']);
}
