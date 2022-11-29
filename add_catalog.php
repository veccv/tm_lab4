<?php
session_start();
$catalog_name = htmlentities ($_POST['catalog_name'], ENT_QUOTES, "UTF-8");
mkdir($_SESSION['user'] . "/" . $catalog_name);
header('Location: index4.php');


