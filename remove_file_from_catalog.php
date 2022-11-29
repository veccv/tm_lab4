<?php
session_start();
unlink($_SESSION['user'] . '/' . $_GET['catalog'] . '/' . $_GET['file']);
header('Location: show_catalog.php?catalog=' . $_GET['catalog']);
