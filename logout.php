<?php
require_once 'db.php';
// session_start() is already in db.php but just in case
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
session_unset();
session_destroy();
header("Location: index.php");
exit;
