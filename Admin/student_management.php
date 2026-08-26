<?php
include "../connection.php";
include "../check_login.php";

if ($role != "admin") {
    header("location: ../login.php");
    exit();
}

$page_title = "Student Records";

$departments = array(
    "BS Software Engineering", "BS Computer Science", "BS Artificial Intelligence",
    "BS Computer Engineering", "BS Business Administration", "BS Information Technology",
    "BS Data Science", "BS Cyber Security", "BS Mathematics", "BS Physics"
);

$semesters = array(
    "Semester 1", "Semester 2", "Semester 3", "Semester 4",
    "Semester 5", "Semester 6", "Semester 7", "Semester 8"
);

$department = "";
$semester = "";

if (isset($_GET["department"])) {
    $department = trim($_GET["department"]);
}

if (isset($_GET["semester"])) {
    $semester = trim($_GET["semester"]);
}

$query = "SELECT * FROM users WHERE role='student'";

if ($department != "") {
    $department = mysqli_real_escape_string($conn, $department);
    $query = $query . " AND department='$department'";
}

if ($semester != "") {
    $semester = mysqli_real_escape_string($conn, $semester);
    $query = $query . " AND current_semester='$semester'";
}

$query = $query . " ORDER BY name";
$students = mysqli_query($conn, $query);

$count_query = mysqli_query($conn, "SELECT id FROM users WHERE role='student'");
$count = mysqli_num_rows($count_query);

include "../includes/header.php";
?>
<h1>Student Records</h1>
<p class="sub">Admin can view, edit and manage complete student records.</p>

<div class="tiles">
    <div class="tile">
        <span class="num"><?php echo $count;
?></span>
        <span class="cap">Students Registered</span>
    </div>
</div>

<div class="card form-card">
    <h3>Filter Students</h3>

    <form method="GET">
        <div class="two">
            <div>
                <label>Department</label>
                <select name="department">
                    <option value="">All Departments</option>
                    <?php foreach($departments as $d) {
?>                        <option value="<?php echo htmlspecialchars($d);
?>"
                            <?php if($department == $d) echo "selected";
?>>
                            <?php echo htmlspecialchars($d);
?>                        </option>
                    <?php 
}
?>                </select>
            </div>

            <div>
                <label>Current Semester</label>
                <select name="semester">
                    <option value="">All Semesters</option>
                    <?php foreach($semesters as $s) {
?>                        <option value="<?php echo $s;
?>"
                            <?php if($semester == $s) echo "selected";
?>>
                            <?php echo $s;
?>                        </option>
                    <?php 
}
?>                </select>
            </div>
        </div>

        <button>Apply Filters</button>
        <a class="clear-filter" href="student_management.php">Clear</a>
    </form>
</div>

<div class="card">
    <div class="table-heading">
        <h3>All Student Names & Records</h3>
        <span class="record-count">
            <?php echo $students? mysqli_num_rows($students): 0;
?> shown
        </span>
    </div>

    <div class="table-responsive table-wrap">
        <table class="list">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Current Semester</th>
                <th>Action</th>
            </tr>

            <?php if(! $students || mysqli_num_rows($students) == 0) {
?>                <tr>
                    <td colspan="6" class="empty">No students found.</td>
                </tr>
            <?php 
} else {
?>                <?php $i= 1;
?>
                <?php while($student= mysqli_fetch_assoc($students)) {
?>                    <tr>
                        <td><?php echo $i++;
?></td>
                        <td><strong><?php echo htmlspecialchars($student["name"]);
?></strong></td>
                        <td><?php echo htmlspecialchars($student["email"]);
?></td>
                        <td><?php echo htmlspecialchars($student["department"]?: "Not assigned");
?></td>
                        <td><?php echo htmlspecialchars($student["current_semester"]?: "Not assigned");
?></td>
                        <td class="action">
                            <a class="edit-link action-edit" href="view_user.php?id=<?php echo $student["id"];
?>">View</a>
                            <a class="edit-link action-edit" href="edit_user.php?id=<?php echo $student["id"];
?>">Edit Complete Student</a>
                            <a class="edit-link action-edit" href="student_records.php?user_id=<?php echo $student["id"];
?>&semester=<?php echo urlencode($student["current_semester"]?: "Semester 1");
?>">Student Academic Records</a>
                            <a class="delete-link action-delete" href="delete_user.php?id=<?php echo $student["id"];
?>" onclick="return confirm('Delete this student account?');">Delete</a>
                        </td>
                    </tr>
                <?php 
    }
?>            <?php 
}
?>        </table>
    </div>
</div>

<?php include "../includes/footer.php";
?>