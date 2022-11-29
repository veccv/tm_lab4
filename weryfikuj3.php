<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<HEAD>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</HEAD>
<BODY>
<?php
$user = htmlentities ($_POST['user'], ENT_QUOTES, "UTF-8");
$pass = htmlentities ($_POST['pass'], ENT_QUOTES, "UTF-8");
$ipaddress = $_SERVER["REMOTE_ADDR"];
$auth_file = file_get_contents('auth.txt');
$lines = explode("\n", $auth_file);
$host = substr($lines[0], 5, -1);
$username = substr($lines[1], 9, -1);
$password = substr($lines[2], 9, -1);
$database = substr($lines[3], 7);
$link = mysqli_connect($host, $username, $password, $database);
if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); }
mysqli_query($link, "SET NAMES 'utf8'");
$result = mysqli_query($link, "SELECT * FROM users WHERE username='$user'");
$rekord = mysqli_fetch_array($result);

session_start();
$last_unsuccessful_login = $_SESSION['last_unsuccessful_login'];
$unsuccessful_login_attempts = 0;
$time_now = time();

if ($_SESSION['unsuccessful_login_attempts'] > 0) {
    $unsuccessful_login_attempts = $_SESSION['unsuccessful_login_attempts'];
}

// Sprawdzanie z bazą danych ostatniego logowania
$unsuccessful_login = mysqli_query($link, "SELECT datetime FROM break_ins WHERE login='$user' ORDER BY datetime DESC LIMIT 1");
$unsuccessful_login_rekord = mysqli_fetch_array($unsuccessful_login);
$datebase_time_now = mysqli_fetch_array(mysqli_query($link, "SELECT NOW()"));
if ($unsuccessful_login_rekord) {
    $login_time = strtotime($unsuccessful_login_rekord[0]) + 60;

    if ($login_time > strtotime($datebase_time_now[0])) {
        echo "Logowanie na konto zostało zablokowane, spróbuj ponownie później.";
        exit();
    }
}


if ($unsuccessful_login_attempts < 3) {
    if (!$rekord)
    {
        if (($last_unsuccessful_login + 60) > $time_now || $unsuccessful_login_attempts == 0) {
            $_SESSION['last_unsuccessful_login'] = time();
            $_SESSION['unsuccessful_login_attempts'] = $unsuccessful_login_attempts + 1;
        }

        mysqli_close($link);
        header('Location: index3.php');
        exit();
    } else {
        if ($rekord['password'] == $pass)
        {
            $_SESSION['unsuccessful_login_attempts'] = 0;

            session_start();
            $_SESSION['loggedin'] = true;
            $_SESSION['user'] = $user;

            $result2 = mysqli_query($link, "SELECT * FROM goscieportalu WHERE ipaddress='$ipaddress'");
            $rekord2 = mysqli_fetch_array($result2);
            $agent = $_SERVER['HTTP_USER_AGENT'];
            $przegladarka = array('Internet Explorer' => 'MSIE', 'Mozilla Firefox' => 'Firefox', 'Opera' => 'Opera', 'Chrome' => 'Chrome', 'Edge' => 'Edge');
            $system = array('Windows 2000' => 'NT 5.0', 'Windows XP' => 'NT 5.1', 'Windows Vista' => 'NT 6.0', 'Windows 7' => 'NT 6.1',
                'Windows 8' => 'NT 6.2', 'Windows 8.1' => 'NT 6.3', 'Windows 10' => 'NT 10', 'Linux' => 'Linux', 'iPhone' => 'iphone', 'Android' => 'android');
            foreach ($system as $nazwa => $id)
                if (strpos($agent, $id)) $system = $nazwa;
            foreach ($przegladarka as $nazwa => $id)
                if (strpos($agent, $id)) $przegladarka = $nazwa;

            $screenResolution =  $_COOKIE['screenresolution'];
            $windowResolution =  $_COOKIE['windowresolution'];
            $colors =  $_COOKIE['colors'];
            $cookies =  $_COOKIE['cookies'];
            $java =  $_COOKIE['java'];
            $lang =  $_COOKIE['lang'];
            mysqli_query($link, "INSERT INTO goscieportalu (ipaddress, web_browser, screen_resolution, window_resolution, screen_colors, cookies, java, lang, login) VALUES ('$ipaddress', '$przegladarka', '$screenResolution', '$windowResolution', '$colors', '$cookies', '$java', '$lang', '$user')");
            mysqli_close($link); 

            header('Location: index4.php');
        } else {
            if (($last_unsuccessful_login + 60) > $time_now || $unsuccessful_login_attempts == 0) {
                $_SESSION['last_unsuccessful_login'] = time();
                $_SESSION['unsuccessful_login_attempts'] = $unsuccessful_login_attempts + 1;
            }

            $_SESSION['last_unsuccessful_login'] = time();
            mysqli_close($link);
            header('Location: index3.php');
            exit();
        }
    }
} else {
    if (($last_unsuccessful_login + 60) < $time_now) {
        $_SESSION['unsuccessful_login_attempts'] = 1;
        mysqli_close($link);
        header('Location: index3.php');
        exit();
    } else {
        echo "Wykryto 3 nieudane próby logowania! Logowanie zostało zablokowane na minutę.";

        mysqli_query($link, "INSERT INTO break_ins (ipaddress, login) VALUES ('$ipaddress', '$user')");
        mysqli_close($link);
    }
}
?>
</BODY>
</HTML>