<?php
session_start();
unlink($_SESSION['user'] . '/' . $_GET['file']);
header('Location: index4.php');
