<?php
include "../connection.php";
include "../check_login.php";

if ($role != "admin") {
    header("location: ../login.php");
    exit();
}

$page_title = "Teacher Records";

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

$teacher_rows = array();

$run = mysqli_query($conn, "SELECT * FROM users WHERE role='teacher' ORDER BY name");

if ($run) {
    while ($teacher = mysqli_fetch_assoc($run)) {

        if ($department != "" && $teacher["department"] != $department) {
            continue;
        }

        if ($semester != "") {
            $schedule = mysqli_query(
                $conn,
                "SELECT id FROM teacher_teaching
                 WHERE teacher_id='" . $teacher["id"] . "'
                 AND semester='" . mysqli_real_escape_string($conn, $semester) . "'
                 LIMIT 1"
            );

            if (!$schedule || mysqli_num_rows($schedule) == 0) {
                continue;
            }
        }

        $teacher_rows[] = $teacher;
    }
}

$count_query = mysqli_query($conn, "SELECT id FROM users WHERE role='teacher'");
$count = mysqli_num_rows($count_query);

include "../includes/header.php";
?>
<h1>Teacher Records</h1>
<p class="sub">Admin can view, edit and manage complete teacher records.</p>

<div class="tiles">
    <div class="tile">
        <span class="num"><?php echo $count;
?></span>
        <span class="cap">Teachers Registered</span>
    </div>
</div>

<div class="card form-card">
    <h3>Filter Teachers</h3>

    <form method="GET">
        <div class="two">
            <div>
                <label>Department</label>
                <select name="department">
                    <option value="">All Departments</option>
                    <?php foreach($departments as $d) {
?>                        <option value="<?php echo htmlspecialchars($d);
?>" <?php if($department == $d) echo "selected";
?>>
                            <?php echo htmlspecialchars($d);
?>                        </option>
                    <?php 
}
?>                </select>
            </div>

            <div>
                <label>Semester</label>
                <select name="semester">
                    <option value="">All Semesters</option>
                    <?php foreach($semesters as $s) {
?>                        <option value="<?php echo $s;
?>" <?php if($semester == $s) echo "selected";
?>>
                            <?php echo $s;
?>                        </option>
                    <?php 
}
?>                </select>
            </div>
        </div>

        <button>Apply Filters</button>
        <a class="clear-filter" href="teacher_management.php">Clear</a>
    </form>
</div>

<div class="card">
    <div class="table-heading">
        <h3>All Teacher Names & Records</h3>
        <span class="record-count">
            <?php echo count($teacher_rows);
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
                <th>Main Subject</th>
                <th>Action</th>
            </tr>

            <?php if(count($teacher_rows) == 0) {
?>                <tr><td colspan="6" class="empty">No teachers found.</td></tr>
            <?php 
} else {
?>                <?php $i= 1;
?>                <?php foreach($teacher_rows as $teacher) {
?>                    <tr>
                        <td><?php echo $i++;
?></td>
                        <td><strong><?php echo htmlspecialchars($teacher["name"]);
?></strong></td>
                        <td><?php echo htmlspecialchars($teacher["email"]);
?></td>
                        <td><?php echo htmlspecialchars($teacher["department"]?: "Not assigned");
?></td>
                        <td><?php echo htmlspecialchars($teacher["main_subject"]?: "Not assigned");
?></td>
                        <td class="action">
                            <a class="edit-link action-edit" href="view_user.php?id=<?php echo $teacher["id"];
?>">View</a>
                            <a class="edit-link action-edit" href="edit_user.php?id=<?php echo $teacher["id"];
?>">Edit Complete Teacher</a>
                            <a class="edit-link action-edit" href="scheduler.php?teacher_id=<?php echo $teacher["id"];
?>">Teacher Schedule</a>
                            <a class="delete-link action-delete" href="delete_user.php?id=<?php echo $teacher["id"];
?>" onclick="return confirm('Delete this teacher account?');">Delete</a>
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