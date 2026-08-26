<?php
include "../connection.php";
include "../check_login.php";
if($role != "admin") {
    header("location: ../login.php");
    exit();
} $id= isset($_GET["id"])? (int)$_GET["id"]: 0;
$user= isset($_GET["user"])? (int)$_GET["user"]: 0;
$table= isset($_GET["table"])? $_GET["table"]: "";
$allowed_tables= array("qualifications", "courses", "teacher_certifications", "teacher_qualifications", "semester_records", "teacher_teaching", "personal_details", "family_info", "residence_info", "emergency_contact");
if($id> 0 && in_array($table, $allowed_tables)) {
    $query=" DELETE FROM $table WHERE id='$id'";
    mysqli_query($conn, $query);
} if($table == "semester_records") {
    $latest_query=" SELECT department, semester
                     FROM semester_records
                     WHERE student_id='$user'
                     ORDER BY id DESC LIMIT 1";
    $latest_result= mysqli_query($conn, $latest_query);
    $latest= mysqli_fetch_assoc($latest_result);
    if($latest) {
        $department= mysqli_real_escape_string($conn, $latest["department"]);
        $semester= mysqli_real_escape_string($conn, $latest["semester"]);
        mysqli_query($conn, " UPDATE users SET department='$department', current_semester='$semester' WHERE id='$user'");
    } else {
        mysqli_query($conn, " UPDATE users SET department='', current_semester='' WHERE id='$user'");
    } $semester= isset($_GET["semester"])? $_GET["semester"]: "Semester 1";
    header("location: student_records.php?user_id=$user&semester=". urlencode($semester));
    exit();
} if($table == "teacher_teaching") {
    $semester= isset($_GET["semester"])? $_GET["semester"]: "Semester 1";
    header("location: scheduler.php?teacher_id=$user&semester=". urlencode($semester));
    exit();
} header("location: view_user.php?id=$user");
exit();
?>