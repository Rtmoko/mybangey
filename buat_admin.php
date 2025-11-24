<?php
include "koneksi.php";

if(isset($_POST['add'])){
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    mysqli_query($mysqli, "INSERT INTO users(username, password, fullname) VALUES ('$_POST[username]','$password','Administrator')");
    header('location: index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="buat_admin.php" method="post">
        <input type="text" name="username" placeholder="username">
        <input type="text" name="password" placeholder="password">
        <input type="submit" name="add">
    </form>
</body>
</html>