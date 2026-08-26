<?php
include "../connection.php";
include "../check_login.php";

if ($role != "admin") {
    header("location: ../login.php");
    exit();
}

$page_title = "Teacher Schedule";

$semesters = array(
    "Semester 1", "Semester 2", "Semester 3", "Semester 4",
    "Semester 5", "Semester 6", "Semester 7", "Semester 8"
);

$departments = array(
    "BS Software Engineering", "BS Computer Science", "BS Artificial Intelligence",
    "BS Computer Engineering", "BS Electrical Engineering", "BS Business Administration",
    "BS Information Technology", "BS Data Science", "BS Cyber Security",
    "BS Mathematics", "BS Physics"
);

$fd = "";
$fs = "";
$ft = 0;

if (isset($_GET["department"])) {
    $fd = trim($_GET["department"]);
}

if (isset($_GET["semester"])) {
    $fs = trim($_GET["semester"]);
}

if (isset($_GET["teacher_id"])) {
    $ft = (int)$_GET["teacher_id"];
}

$message = "";
$error = "";

if (isset($_POST["add-btn"])) {

    $tid = (int)$_POST["teacher_id"];
    $sem = mysqli_real_escape_string($conn, $_POST["semester"]);
    $dep = mysqli_real_escape_string($conn, $_POST["department"]);
    $sub = mysqli_real_escape_string($conn, trim($_POST["subject"]));

    if ($tid <= 0 || $sem == "" || $dep == "" || $sub == "") {
        $error = "Please complete all scheduler fields.";
    } else {

        $query = "INSERT INTO teacher_teaching
                  (teacher_id, semester, department, subject)
                  VALUES
                  ('$tid', '$sem', '$dep', '$sub')";

        if (mysqli_query($conn, $query)) {
            $message = "Teacher schedule added successfully.";
        } else {
            $error = "Schedule could not be saved.";
        }
    }
}

$teachers = mysqli_query(
    $conn,
    "SELECT id, name FROM users WHERE role='teacher' ORDER BY name"
);

$query = "SELECT * FROM teacher_teaching WHERE id > 0";

if ($fd != "") {
    $fd_sql = mysqli_real_escape_string($conn, $fd);
    $query = $query . " AND department='$fd_sql'";
}

if ($fs != "") {
    $fs_sql = mysqli_real_escape_string($conn, $fs);
    $query = $query . " AND semester='$fs_sql'";
}

if ($ft > 0) {
    $query = $query . " AND teacher_id='$ft'";
}

$query = $query . " ORDER BY semester, department, id DESC";
$rows = mysqli_query($conn, $query);

include "../includes/header.php";
?><h1>Scheduler</h1><p class="sub">Admin schedules teachers and filters the complete schedule by Department and Semester.</p><?php if($message) {
?><div class="done"><?php echo htmlspecialchars($message);
?></div><?php 
}
?><?php if($error) {
?><div class="error"><?php echo htmlspecialchars($error);
?></div><?php 
}
?><div class="card form-card"><h3>Filter Schedule</h3><form method="GET"><div class="two"><div><label>Department</label><select name="department"><option value="">All Departments</option><?php foreach($departments as $d) {
?><option value="<?php echo htmlspecialchars($d);
?>" <?php if($fd===$d)echo 'selected';
?>><?php echo htmlspecialchars($d);
?></option><?php 
}
?></select></div><div><label>Semester</label><select name="semester"><option value="">All Semesters</option><?php foreach($semesters as $s) {
?><option value="<?php echo $s;
?>" <?php if($fs===$s)echo 'selected';
?>><?php echo $s;
?></option><?php 
}
?></select></div><div><label>Teacher</label><select name="teacher_id"><option value="0">All Teachers</option><?php mysqli_data_seek($teachers, 0);
while($t=mysqli_fetch_assoc($teachers)) {
?><option value="<?php echo $t['id'];
?>" <?php if($ft===(int)$t['id'])echo 'selected';
?>><?php echo htmlspecialchars($t['name']);
?></option><?php 
}
?></select></div></div><button>Apply Filters</button> <a class="clear-filter" href="scheduler.php">Clear</a></form></div><div class="card"><div class="table-heading"><h3>Complete Teaching Schedule</h3><span class="record-count"><?php echo $rows?mysqli_num_rows($rows): 0;
?> records</span></div><div class="table-responsive table-wrap"><table class="list"><tr><th>#</th><th>Teacher</th><th>Department</th><th>Semester</th><th>Subject</th><th>Action</th></tr><?php $i=1;
if(!$rows||mysqli_num_rows($rows)==0) {
?><tr><td colspan="6" class="empty">No scheduler records match the filters.</td></tr><?php 
} else {
    while($r=mysqli_fetch_assoc($rows)) {
?><tr><td><?php echo $i++;
?></td><td><?php $teacher_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM users WHERE id='" . $r["teacher_id"] . "' LIMIT 1")); echo htmlspecialchars($teacher_info ? $teacher_info["name"] : "Unknown");
?></td><td><?php echo htmlspecialchars($r['department']);
?></td><td><?php echo htmlspecialchars($r['semester']);
?></td><td><?php echo htmlspecialchars($r['subject']);
?></td><td><a class="edit-link action-edit" href="edit_scheduler.php?id=<?php echo $r['id'];
?>">Edit</a> <a class="delete-link action-delete" href="delete_record.php?table=teacher_teaching&id=<?php echo $r['id'];
?>&user=<?php echo $r['teacher_id'];
?>&semester=<?php echo urlencode($r['semester']);
?>" onclick="return confirm('Delete this schedule record?');">Delete</a></td></tr><?php 
    }
}
?></table></div></div><div class="card form-card"><h3>Add Schedule Record</h3><form method="POST"><div class="two"><div><label>Teacher</label><select name="teacher_id" required><option value="">Select Teacher</option><?php mysqli_data_seek($teachers, 0);
while($t=mysqli_fetch_assoc($teachers)) {
?><option value="<?php echo $t['id'];
?>"><?php echo htmlspecialchars($t['name']);
?></option><?php 
}
?></select></div><div><label>Semester</label><select name="semester" required><option value="">Select Semester</option><?php foreach($semesters as $s) {
?><option><?php echo $s;
?></option><?php 
}
?></select></div><div><label>Department</label><select name="department" required><option value="">Select Department</option><?php foreach($departments as $d) {
?><option><?php echo htmlspecialchars($d);
?></option><?php 
}
?></select></div><div><label>Subject</label><input name="subject" required></div></div><button name="add-btn">Add Schedule</button></form></div><?php include "../includes/footer.php";
?>