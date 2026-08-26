<?php
include "../connection.php";
include "../check_login.php";
if($role != "admin") {
    header("location: ../login.php");
    exit();
} $id= $_GET["id"];
if($id != "" && $id != $user_id) {
    $query=" DELETE FROM users WHERE id='$id'";
    mysqli_query($conn, $query);
} header("location: users.php");
exit();
?>