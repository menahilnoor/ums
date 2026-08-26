<?php
include "../connection.php";
include "../check_login.php";
if($role != "admin") {
    header("location: ../login.php");
    exit();
} if(isset($_GET["table"]) && isset($_GET["id"])) {
    $table= $_GET["table"];
    $id= $_GET["id"];
    if($table == "personal_details") {
        header("location: edit_personal.php?id=$id");
    } elseif($table == "family_info") {
        header("location: edit_family.php?id=$id");
    } elseif($table == "qualifications") {
        header("location: edit_education.php?id=$id");
    } elseif($table == "courses") {
        header("location: edit_courses.php?id=$id");
    } elseif($table == "residence_info") {
        header("location: edit_residence.php?id=$id");
    } elseif($table == "emergency_contact") {
        header("location: edit_emergency.php?id=$id");
    } elseif($table == "experiences") {
        header("location: edit_experience.php?id=$id");
    } else {
        header("location: users.php");
    }
} else {
    header("location: users.php");
} exit();
?>