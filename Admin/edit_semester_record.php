<?php
include "../connection.php";
include "../check_login.php";
if($role != "admin") {
    header("location: ../login.php");
    exit();
} $id= $_GET["id"];
$run= mysqli_query($conn, "  SELECT * FROM semester_records WHERE id='$id'");
$row= mysqli_fetch_assoc($run);
if(! $row) {
    header("location: student_records.php");
    exit();
} $allowed= array("Semester 1", "Semester 2", "Semester 3", "Semester 4", "Semester 5", "Semester 6", "Semester 7", "Semester 8");
$departments= array("BS Software Engineering", "BS Computer Science", "BS Artificial Intelligence", "BS Computer Engineering", "BS Electrical Engineering", "BS Business Administration", "BS Information Technology", "BS Data Science", "BS Cyber Security", "BS Mathematics", "BS Physics");
$message= "";
if(isset($_POST["save-btn"])) {
    $semester= $_POST["semester"];
    $department= $_POST["department"];
    $subject= $_POST["subject"];
    $cgpa= $_POST["cgpa"];
    $grade= $_POST["grade"];
    $attendance= $_POST["attendance"];
    $remarks= $_POST["remarks"];
    $query=" UPDATE semester_records
              SET semester='$semester',
                  department='$department',
                  subject='$subject',
                  cgpa='$cgpa',
                  grade='$grade',
                  attendance='$attendance',
                  remarks='$remarks'
              WHERE id='$id'";
    if(mysqli_query($conn, $query)) {
        /* Update the student's current Department and Semester too. */
        mysqli_query($conn, "  UPDATE users SET department='$department', current_semester='$semester'
             WHERE id='{$row["student_id"]
    } ' AND role='student'");
    $message= "Semester record updated successfully.";
    $run= mysqli_query($conn, "  SELECT * FROM semester_records WHERE id='$id'");
    $row= mysqli_fetch_assoc($run);
} else {
    $message= "Could not update the record.";
}
} $page_title= "Edit Semester Record";
include "../includes/header.php";
?><h1>Edit Semester Record</h1>

<p class="sub">
    Only Admin can change this academic record.
</p>

<?php if($message != "") {
?>    <div class="done">
        <?php echo $message;
?>    </div>

<?php 
}
?><div class="card form-card">

<form method="POST">

<div class="two">

    <div>
        <label>Semester</label>

        <select name="semester">

            <?php foreach($allowed as $s) {
?>                <option
                    value="<?php echo $s;
?>"
                    <?php if($row["semester"] == $s) echo "selected";
?>                >
                    <?php echo $s;
?>                </option>

            <?php 
}
?>        </select>
    </div>

    <div>
        <label>Department</label>

        <select name="department">

            <option value="">Select Department</option>

            <?php foreach($departments as $d) {
?>                <option
                    value="<?php echo $d;
?>"
                    <?php if($row["department"] == $d) echo "selected";
?>                >
                    <?php echo $d;
?>                </option>

            <?php 
}
?>        </select>
    </div>

    <div>
        <label>Subject</label>
        <input type="text" name="subject" value="<?php echo $row["subject"];
?>" required>
    </div>

    <div>
        <label>CGPA</label>
        <input type="text" name="cgpa" value="<?php echo $row["cgpa"];
?>">
    </div>

    <div>
        <label>Grade</label>

        <select name="grade">

            <option value="">Select</option>

            <?php
$grades= array("A+", "A", "B+", "B", "C+", "C", "D", "F");
foreach($grades as $grade_name) {
?>                <option
                    value="<?php echo $grade_name;
?>"
                    <?php if($row["grade"] == $grade_name) echo "selected";
?>                >
                    <?php echo $grade_name;
?>                </option>

            <?php 
}
?>        </select>

    </div>

    <div>
        <label>Attendance</label>
        <input type="text" name="attendance" value="<?php echo $row["attendance"];
?>">
    </div>

    <div class="full">
        <label>Remarks</label>
        <input type="text" name="remarks" value="<?php echo $row["remarks"];
?>">
    </div>

</div>

<button type="submit" name="save-btn">
    Save Changes
</button>

<a class="clear" href="student_records.php?user_id=<?php echo $row["user_id"];
?>&semester=<?php echo urlencode($row["semester"]);
?>">
    Back
</a>

</form>

</div>

<?php include "../includes/footer.php";
?>