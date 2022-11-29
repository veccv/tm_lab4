<?php declare(strict_types=1);
session_start();

if (!isset($_SESSION['loggedin']))
{
    header('Location: index3.php');
    exit();
}

$user = $_SESSION['user'];
$link = mysqli_connect( "" , "", "", "");
if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); }
mysqli_query($link, "SET NAMES 'utf8'");
$result = mysqli_query($link, "SELECT * FROM break_ins WHERE login='$user' ORDER BY datetime DESC LIMIT 1");
$rekord = mysqli_fetch_array($result);

if ($rekord) {
    echo "<div style='color: red'>Ostatnie błędne logowanie na to konto zostało wykonane z IP: " . $rekord[2] . ", data: " . $rekord[1] . "</div>";
    echo "<br>";
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl"> <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> </head>
<BODY>
<a href="logout.php">Wyloguj się</a>
<br>
<a href="index.php">Powrót do menu głównego</a>
<br>



</BODY>
</HTML>