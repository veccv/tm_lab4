<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<HEAD>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</HEAD>
<BODY>
<?php
$user = htmlentities ($_POST['user'], ENT_QUOTES, "UTF-8");
$pass = htmlentities ($_POST['pass'], ENT_QUOTES, "UTF-8");
$pass_repeat = htmlentities ($_POST['pass_repeat'], ENT_QUOTES, "UTF-8");

$auth_file = file_get_contents('auth.txt');
$lines = explode("\n", $auth_file);
$host = substr($lines[0], 5, -1);
$username = substr($lines[1], 9, -1);
$password = substr($lines[2], 9, -1);
$database = substr($lines[3], 7);
$link = mysqli_connect($host, $username, $password, $database);
if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); } // obsługa błędu połączenia z BD
mysqli_query($link, "SET NAMES 'utf8'"); // ustawienie polskich znaków
$result = mysqli_query($link, "SELECT * FROM users WHERE username='$user'"); // wiersza, w którym login=login z formularza
$rekord = mysqli_fetch_array($result); // wiersza z BD, struktura zmiennej jak w BD
if(!$rekord) //Jeśli brak, to nie ma użytkownika o podanym loginie
{
    // Sprawdź czy hasła się zgadzają
    if ($pass == $pass_repeat) {
        mysqli_query($link, "INSERT INTO users (username, password) VALUES ('$user', '$pass')");
        mysqli_close($link);
        echo "Zarejestrowano użytkownika $user z hasłem $pass";
        mkdir($user);

    } else {
        echo "Podane hasła nie są takie same";
    }
} else {
    echo "Podany użytkownik już istnieje";
}
?>
<br>
<br>
<br>
<br>
<a href="index.php">Powrót do menu głównego aplikacji</a>
</BODY>
</HTML>