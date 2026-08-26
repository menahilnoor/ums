<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("location: ../login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$query = "SELECT * FROM users WHERE id='$user_id'";
$run = mysqli_query($conn, $query);

$user = mysqli_fetch_assoc($run);

if (!$user) {
    session_destroy();
    header("location: ../login.php");
    exit();
}

$role = $user["role"];

?>
