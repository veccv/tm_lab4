<?php
session_start();
$target_file = '';

if (strlen($_POST['catalog']) > 0) {
    $target_file = $_SESSION['user'] . "/" . $_POST['catalog'] . '/' . basename($_FILES["fileToUpload"]["name"]);
} else {
    $target_file = $_SESSION['user'] . "/" . basename($_FILES["fileToUpload"]["name"]);
}

if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    echo htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " uploaded.";
} else {
    echo $_GET['catalog'];
    echo "Error uploading file.";
}
header('Location: index4.php');
