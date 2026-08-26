<?php
include "../connection.php";
include "../check_login.php";
if($role != "admin") {
    header("location: ../login.php");
    exit();
} $id= $_GET["id"];
$query=" SELECT * FROM courses WHERE id='$id'";
$run= mysqli_query($conn, $query);
$row= mysqli_fetch_assoc($run);
if(! $row) {
    header("location: users.php");
    exit();
} $message= "";
if(isset($_POST["save-btn"])) {
    $semester= $_POST["semester"];
    $course_title= $_POST["course_title"];
    $credit_hours= $_POST["credit_hours"];
    $query=" UPDATE courses SET semester='$semester'='$course_code', course_title='$course_title', credit_hours='$credit_hours' WHERE id='$id'";
    $run= mysqli_query($conn, $query);
    if($run) {
        $message= "Record updated successfully.";
    } else {
        $message= "Record could not be updated.";
    } $run= mysqli_query($conn, "  SELECT * FROM courses WHERE id='$id'");
    $row= mysqli_fetch_assoc($run);
} $page_title= "Courses";
include "../includes/header.php";
?><h1>Edit Courses</h1>
<p class="sub">Only Admin can change this record.</p>

<?php if($message != "") {
?>    <div class="done"><?php echo $message;
?></div>
<?php 
}
?><div class="card form-card">

    <h3>Course details</h3>

    <form method="POST">

        <label>Semester</label>
        <input type="text" name="semester" value="<?php echo $row["semester"];
?>" required>

        <label>Course Code</label>
        <input type="text" name="course_code" value="<?php echo $row["course_code"];
?>" required>

        <label>Course Title</label>
        <input type="text" name="course_title" value="<?php echo $row["course_title"];
?>" required>

        <label>Credit Hours</label>
        <input type="text" name="credit_hours" value="<?php echo $row["credit_hours"];
?>" required>

        <button type="submit" name="save-btn">
            Save Changes
        </button>

    </form>

</div>

<?php include "../includes/footer.php";
?>