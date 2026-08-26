<?php
include "../connection.php";
include "../check_login.php";
if($role != "admin") {
    header("location: ../login.php");
    exit();
} $user_id_view= "";
if(isset($_GET["user_id"])) {
    $user_id_view= $_GET["user_id"];
} $semester= "Semester 1";
if(isset($_GET["semester"])) {
    $semester= $_GET["semester"];
} header("location: student_records.php?user_id=". $user_id_view. "&semester=". urlencode($semester));
exit();
?>