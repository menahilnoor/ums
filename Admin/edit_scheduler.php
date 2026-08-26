<?php
include "../connection.php";
include "../check_login.php";
if($role!="admin") {
    header("location: ../login.php");
    exit();
} $id=(int)((isset($_GET['id']) ? $_GET['id'] : 0));
$row=mysqli_fetch_assoc(mysqli_query($conn, " SELECT * FROM teacher_teaching WHERE id='$id'"));
if(!$row) {
    header("location: scheduler.php");
    exit();
} $semesters=array("Semester 1", "Semester 2", "Semester 3", "Semester 4", "Semester 5", "Semester 6", "Semester 7", "Semester 8");
$departments=array("BS Software Engineering", "BS Computer Science", "BS Artificial Intelligence", "BS Computer Engineering", "BS Electrical Engineering", "BS Business Administration", "BS Information Technology", "BS Data Science", "BS Cyber Security", "BS Mathematics", "BS Physics");
$message='';
if(isset($_POST['save-btn'])) {
    $sem=mysqli_real_escape_string($conn, $_POST['semester']);
    $dep=mysqli_real_escape_string($conn, $_POST['department']);
    $sub=mysqli_real_escape_string($conn, $_POST['subject']);
    if(mysqli_query($conn, " UPDATE teacher_teaching SET semester='$sem',department='$dep',subject='$sub' WHERE id='$id'")) {
        $message='Schedule updated successfully.';
        $row=mysqli_fetch_assoc(mysqli_query($conn, " SELECT * FROM teacher_teaching WHERE id='$id'"));
    } else$message='Could not update schedule: '.mysqli_error($conn);
} $page_title='Edit Schedule';
include "../includes/header.php";
?><h1>Edit Schedule</h1><p class="sub">Admin can edit every scheduler record.</p><?php if($message) {
?><div class="done"><?php echo htmlspecialchars($message);
?></div><?php 
}
?><div class="card form-card"><form method="POST"><div class="two"><div><label>Semester</label><select name="semester"><?php foreach($semesters as $s) {
?><option <?php if($row['semester']===$s)echo 'selected';
?>><?php echo $s;
?></option><?php 
}
?></select></div><div><label>Department</label><select name="department"><?php foreach($departments as $d) {
?><option <?php if($row['department']===$d)echo 'selected';
?>><?php echo htmlspecialchars($d);
?></option><?php 
}
?></select></div><div class="full"><label>Subject</label><input name="subject" value="<?php echo htmlspecialchars($row['subject']);
?>" required></div></div><button name="save-btn">Save Changes</button></form></div><?php include "../includes/footer.php";
?>