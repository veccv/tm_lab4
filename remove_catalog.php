<?php
session_start();
$files = scandir($_SESSION['user'] . '/' . $_GET['catalog']);
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        unlink($_SESSION['user'] . '/' . $_GET['catalog'] . '/' . $_GET['file']);
    }
}
rmdir($_SESSION['user'] . '/' . $_GET['catalog']);
header('Location: index4.php');