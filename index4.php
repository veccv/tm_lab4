<?php declare(strict_types=1);
session_start();

if (!isset($_SESSION['loggedin']))
{
    header('Location: index3.php');
    exit();
}

$user = $_SESSION['user'];


$auth_file = file_get_contents('auth.txt');
$lines = explode("\n", $auth_file);
$host = substr($lines[0], 5, -1);
$username = substr($lines[1], 9, -1);
$password = substr($lines[2], 9, -1);
$database = substr($lines[3], 7);
$link = mysqli_connect($host, $username, $password, $database);

if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); }
mysqli_query($link, "SET NAMES 'utf8'");
$result = mysqli_query($link, "SELECT * FROM break_ins WHERE login='$user' ORDER BY datetime DESC LIMIT 1");
$rekord = mysqli_fetch_array($result);

if ($rekord) {
    echo "<div style='color: red'>Ostatnie błędne logowanie na to konto zostało wykonane z IP: " . $rekord[2] . ", data: " . $rekord[1] . "</div>";
    echo "<br>";
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<BODY style="padding: 15px">
<a href="logout.php">Wyloguj się</a>
<br>
<a href="index.php">Powrót do menu głównego</a>
<br>
<br>
<br>
<br>
<?php
echo "Dodaj nowy katalog ";
echo '<a href="add_catalog_form.php"><i class="glyphicon glyphicon-folder-close fa-6x"></i> </a><br><br>';
echo "Wyślij plik ";
echo '<a href="upload_file.php"><i class="glyphicon glyphicon-cloud-upload fa-6x"></i> </a><br><br>';

echo "<table border='1'>
<tr>
<th style='padding: 15px'>Podgląd</th>
<th style='padding: 15px'>Nazwa pliku</th>
<th style='padding: 15px'>Opcje</th>
</tr>";

$files = scandir($user);
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        echo "<tr>";
        if (!strpos($file, '.')) {
            echo "<td style='padding: 15px'>" . "</td>";
            echo "<td style='padding: 15px'> <a href='show_catalog.php?catalog=$file'>" . $file . "</a></td>";
            echo "<td style='padding: 15px'> <a href='remove_catalog.php?catalog=$file'>" . '<i class="glyphicon glyphicon-trash fa-6x"></i>' . "</a></td>";
        } else {
            if (strpos($file, ".jpg") || strpos($file, ".png") || strpos($file, ".gif")) {
                echo "<td style='padding: 15px'>" . "<a href='$user/$file'><img src='$user/$file' width='200px' height='150px'> </a>" . "</td>";
                echo "<td style='padding: 15px'>" . "<a download='$file' href='$user/$file'>$file</a>" . "</td>";

            } else if (strpos($file, ".mp3")) {
                echo "<td style='padding: 15px'>" . "<audio controls src='$user/$file'> </audio>" . "</td>";
                echo "<td style='padding: 15px'>" . "<a download='$file' href='$user/$file'>$file</a>" . "</td>";

            } else if (strpos($file, ".mp4")) {
                echo "<td style='padding: 15px'>" . "<video controls width='200px' height='150px' muted='true'><source src='$user/$file' type='video/mp4'></video>" . "</td>";
                echo "<td style='padding: 15px'>" . "<a download='$file' href='$user/$file'>$file</a>" . "</td>";
            } else {
                echo "<td style='padding: 15px'>" . "</td>";
                echo "<td style='padding: 15px'>" . "<a download='$file' href='$user/$file'>$file</a>" . "</td>";
            }
            echo "<td style='padding: 15px'> <a href='remove_file.php?file=$file'>" . '<i class="glyphicon glyphicon-trash fa-6x"></i>' . "</a></td>";
        }
        echo "</tr>";
    }
}
echo "</table>";
echo "<br>";
?>
</BODY>
</HTML>