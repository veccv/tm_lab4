<?php declare(strict_types=1);
session_start();

if (!isset($_SESSION['loggedin']))
{
    header('Location: index3.php');
    exit();
}

$user = $_SESSION['user'];
$catalog = $_GET['catalog'];
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl"> <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> </head>
<BODY>
<a href="logout.php">Wyloguj się</a>
<br>
<a href="index.php">Powrót do menu głównego</a>
<br>
<br>
<br>
<br>
<?php
echo "<table border='1'>
<tr>
<th style='padding: 15px'>Podgląd</th>
<th style='padding: 15px'>Nazwa pliku</th>
<th style='padding: 15px'>Opcje</th>
</tr>";

$files = scandir($user . "/" . $catalog);
foreach ($files as $file) {
    if (strlen($file) > 1) {
        if ($file != "..") {
            echo "<tr>";
            echo "<td style='padding: 15px'>" . "</td>";
            echo "<td style='padding: 15px'>" . $file . "</td>";
            echo "<td style='padding: 15px'>" . "</td>";
            echo "</tr>";
        } else {
            echo "<tr>";
            echo "<td style='padding: 15px'>" . "</td>";
            echo "<td style='padding: 15px'> <a href='index4.php'>" . $file . "</a></td>";
            echo "<td style='padding: 15px'>" . "</td>";
            echo "</tr>";
        }
    }
}
echo "</table>";
echo "<br>";



?>
</BODY>
</HTML>
