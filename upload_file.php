<!DOCTYPE html>
<html>
<body>
<form action="upload.php" method="post" enctype="multipart/form-data">
    Select file to upload:
    <?php
    $catalog = $_GET['catalog'];
    echo "<input type='hidden' name='catalog' value='$catalog' />"
    ?>
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload" name="submit">
</form>
<br>
<br>
<br>
<br>
<a href="index4.php">Powrót do katalogu użytkownika</a>
</body>
</html>