<?php
include "../connection.php";
include "../check_login.php";
if ($role != "student") {
    header("location: ../login.php");
    exit();
}
$page_title = "Dashboard";
/* Count saved sections */
$personal = 0;
$family = 0;
$education = 0;
$courses = 0;
$residence = 0;
$emergency = 0;
$result = mysqli_query($conn, "SELECT * FROM personal_details WHERE user_id='$user_id'");
if ($result) {
    $personal = mysqli_num_rows($result);
}
$result = mysqli_query($conn, "SELECT * FROM family_info WHERE user_id='$user_id'");
if ($result) {
    $family = mysqli_num_rows($result);
}
$result = mysqli_query($conn, "SELECT * FROM qualifications WHERE user_id='$user_id'");
if ($result) {
    $education = mysqli_num_rows($result);
}
$result = mysqli_query($conn, "SELECT * FROM courses WHERE user_id='$user_id'");
if ($result) {
    $courses = mysqli_num_rows($result);
}
$result = mysqli_query($conn, "SELECT * FROM residence_info WHERE user_id='$user_id'");
if ($result) {
    $residence = mysqli_num_rows($result);
}
$result = mysqli_query($conn, "SELECT * FROM emergency_contact WHERE user_id='$user_id'");
if ($result) {
    $emergency = mysqli_num_rows($result);
}
$filled = 0;
if ($personal > 0 && $family > 0 && $emergency > 0) {
    $filled++;
}
if ($education > 0) {
    $filled++;
}
if ($courses > 0) {
    $filled++;
}
if ($residence > 0) {
    $filled++;
}
$percent = round(($filled / 4) * 100);
/*
   Department and Current Semester are stored in users.
   Admin updates these when adding a semester record.
*/
$display_department = isset($user["department"]) ? $user["department"] : "";
$display_semester = isset($user["current_semester"]) ? $user["current_semester"] : "";
/*
   Try to get the latest academic record.
   If the table/query is not available, do not crash the dashboard.
*/
$latest_record = mysqli_query(
$conn,
"SELECT department, semester
     FROM semester_records
     WHERE student_id='$user_id'
     AND department <> ''
     AND semester <> ''
     ORDER BY id DESC
     LIMIT 1"
);
if ($latest_record) {
    $latest = mysqli_fetch_assoc($latest_record);
    if ($latest) {
        $display_department = $latest["department"];
        $display_semester = $latest["semester"];
        mysqli_query(
        $conn,
        "UPDATE users
             SET department='$display_department',
                 current_semester='$display_semester'
             WHERE id='$user_id'"
        );
    }
}
if ($display_department == "") {
    $display_department = "Not added yet";
}
if ($display_semester == "") {
    $display_semester = "Not added yet";
}
include "../includes/header.php";
?>

<h1>
    Welcome, <?php echo $user["name"]; ?>
</h1>

<p class="sub">
    Student Dashboard - University Management System.
</p>

<div class="top-header">

    <div>
        <h4>Academic Information</h4>
        <span>
            Department: <?php echo $display_department; ?>
        </span>
    </div>

    <div class="user-mark academic-semester-badge">
        Current Semester: <?php echo $display_semester; ?>
    </div>

</div>

<div class="tiles">

    <div class="tile">
        <span class="num">
            <?php echo $percent; ?>%
        </span>

        <span class="cap">
            Profile Completion
        </span>
    </div>

    <a class="tile" href="education.php">
        <span class="num">
            <?php echo $education; ?>
        </span>

        <span class="cap">
            Education Records
        </span>
    </a>

    <a class="tile" href="courses.php">
        <span class="num">
            <?php echo $courses; ?>
        </span>

        <span class="cap">
            Courses
        </span>
    </a>

</div>

<div class="card dashboard-completion-card">

    <div class="completion-title-row">
        <div>
            <h3>Information Completion</h3>
            <p class="completion-subtitle">Your profile progress at a glance</p>
        </div>
        <span class="completion-status"><?php echo $percent; ?>% Complete</span>
    </div>

    <div class="chart-box">

        <div class="donut" style="--value:<?php echo $percent; ?>%">
            <span><?php echo $percent; ?>%</span>
        </div>

        <div class="chart-legend">

            <strong>
                <?php echo $filled; ?> of 4 sections completed
            </strong>

            <div class="bar">
                <span style="width:<?php echo $percent; ?>%"></span>
            </div>

            <p class="form-note">
                Remaining: <?php echo 100 - $percent; ?>%
            </p>

        </div>

    </div>

</div>

<div class="card">

    <h3>Information Status</h3>

    <p class="note">
        You can add your information. After it is saved, only the Admin can change or delete it.
    </p>

    <table class="list">

        <tr>
            <th>Section</th>
            <th>Status</th>
            <th>Open</th>
        </tr>

        <tr>
            <td>My Profile</td>

            <td>
                <?php
                if ($personal > 0 && $family > 0 && $emergency > 0) {
                    echo '<span class="ok">Added</span>';
                } else {
                    echo '<span class="pending">Not complete</span>';
                }
                ?>
            </td>

            <td>
                <a href="profile.php">Open</a>
            </td>
        </tr>

        <tr>
            <td>Education</td>

            <td>
                <?php
                if ($education > 0) {
                    echo '<span class="ok">Added</span>';
                } else {
                    echo '<span class="pending">Not added</span>';
                }
                ?>
            </td>

            <td>
                <a href="education.php">Open</a>
            </td>
        </tr>

        <tr>
            <td>Courses</td>

            <td>
                <?php
                if ($courses > 0) {
                    echo '<span class="ok">Added</span>';
                } else {
                    echo '<span class="pending">Not added</span>';
                }
                ?>
            </td>

            <td>
                <a href="courses.php">Open</a>
            </td>
        </tr>

        <tr>
            <td>Residence</td>

            <td>
                <?php
                if ($residence > 0) {
                    echo '<span class="ok">Added</span>';
                } else {
                    echo '<span class="pending">Not added</span>';
                }
                ?>
            </td>

            <td>
                <a href="residence.php">Open</a>
            </td>
        </tr>

    </table>

</div>

<div class="card">

    <h3>Account</h3>

    <table>

        <tr>
            <th>Name</th>
            <td><?php echo $user["name"]; ?></td>
        </tr>

        <tr>
            <th>Email</th>
            <td><?php echo $user["email"]; ?></td>
        </tr>

        <tr>
            <th>Phone</th>
            <td><?php echo $user["phone"]; ?></td>
        </tr>

        <tr>
            <th>Department</th>
            <td><?php echo $display_department; ?></td>
        </tr>

        <tr>
            <th>Current Semester</th>
            <td><?php echo $display_semester; ?></td>
        </tr>

        <tr>
            <th>Role</th>
            <td><?php echo ucfirst($user["role"]); ?></td>
        </tr>

    </table>

</div>

<?php include "../includes/footer.php"; ?>
