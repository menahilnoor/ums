<?php
include "../connection.php";
include "../check_login.php";

if ($role != "admin") {
    header("location: ../login.php");
    exit();
}

$page_title = "Admin Dashboard";

/* Count Students */
$student_count = 0;
$run = mysqli_query($conn, "SELECT id FROM users WHERE role='student'");
if ($run) {
    $student_count = mysqli_num_rows($run);
}

/* Count Teachers */
$teacher_count = 0;
$run = mysqli_query($conn, "SELECT id FROM users WHERE role='teacher'");
if ($run) {
    $teacher_count = mysqli_num_rows($run);
}

/* Count IT Staff */
$it_staff_count = 0;
$run = mysqli_query($conn, "SELECT id FROM users WHERE role='it_staff'");
if ($run) {
    $it_staff_count = mysqli_num_rows($run);
}

$total_users = $student_count + $teacher_count + $it_staff_count;

/* Student completed profiles */
$student_complete = 0;
$run = mysqli_query($conn, "SELECT id FROM users WHERE role='student'");
if ($run) {
    while ($user_row = mysqli_fetch_assoc($run)) {
        $check = mysqli_query($conn, "SELECT id FROM qualifications WHERE user_id='" . $user_row["id"] . "' LIMIT 1");
        if ($check && mysqli_num_rows($check) > 0) {
            $student_complete++;
        }
    }
}

/* Teacher completed profiles */
$teacher_complete = 0;
$run = mysqli_query($conn, "SELECT id FROM users WHERE role='teacher'");
if ($run) {
    while ($user_row = mysqli_fetch_assoc($run)) {
        $check = mysqli_query($conn, "SELECT id FROM qualifications WHERE user_id='" . $user_row["id"] . "' LIMIT 1");
        if ($check && mysqli_num_rows($check) > 0) {
            $teacher_complete++;
        }
    }
}

/* IT Staff completed profiles */
$it_staff_complete = 0;
$run = mysqli_query($conn, "SELECT id FROM users WHERE role='it_staff'");
if ($run) {
    while ($user_row = mysqli_fetch_assoc($run)) {
        $id = $user_row["id"];

        $p1 = mysqli_query($conn, "SELECT id FROM personal_details WHERE user_id='$id' LIMIT 1");
        $p2 = mysqli_query($conn, "SELECT id FROM family_info WHERE user_id='$id' LIMIT 1");
        $p3 = mysqli_query($conn, "SELECT id FROM emergency_contact WHERE user_id='$id' LIMIT 1");
        $p4 = mysqli_query($conn, "SELECT id FROM it_staff_education WHERE user_id='$id' LIMIT 1");
        $p5 = mysqli_query($conn, "SELECT id FROM it_staff_certifications WHERE user_id='$id' LIMIT 1");
        $p6 = mysqli_query($conn, "SELECT id FROM it_staff_experience WHERE user_id='$id' LIMIT 1");

        if ($p1 && mysqli_num_rows($p1) > 0 &&
            $p2 && mysqli_num_rows($p2) > 0 &&
            $p3 && mysqli_num_rows($p3) > 0 &&
            $p4 && mysqli_num_rows($p4) > 0 &&
            $p5 && mysqli_num_rows($p5) > 0 &&
            $p6 && mysqli_num_rows($p6) > 0) {
            $it_staff_complete++;
        }
    }
}

$student_incomplete = $student_count - $student_complete;
$teacher_incomplete = $teacher_count - $teacher_complete;
$it_staff_incomplete = $it_staff_count - $it_staff_complete;

if ($student_incomplete < 0) $student_incomplete = 0;
if ($teacher_incomplete < 0) $teacher_incomplete = 0;
if ($it_staff_incomplete < 0) $it_staff_incomplete = 0;

if ($student_count > 0) {
    $student_percent = round(($student_complete / $student_count) * 100);
} else {
    $student_percent = 0;
}

if ($teacher_count > 0) {
    $teacher_percent = round(($teacher_complete / $teacher_count) * 100);
} else {
    $teacher_percent = 0;
}

if ($it_staff_count > 0) {
    $it_staff_percent = round(($it_staff_complete / $it_staff_count) * 100);
} else {
    $it_staff_percent = 0;
}

if ($total_users > 0) {
    $student_width = round(($student_count / $total_users) * 100);
    $teacher_width = round(($teacher_count / $total_users) * 100);
    $it_width = round(($it_staff_count / $total_users) * 100);
} else {
    $student_width = 0;
    $teacher_width = 0;
    $it_width = 0;
}

$student_angle = $student_width * 3.6;
$teacher_angle = $student_angle + ($teacher_width * 3.6);
$student_end = $student_angle;
$teacher_end = $teacher_angle;

include "../includes/header.php";
?><h1>Admin Dashboard</h1>
<p class="sub">Manage students, teachers and IT staff from one place.</p>

<!-- Registration cards -->
<div class="tiles admin-stat-tiles">
    <a class="tile dashboard-tile admin-stat student-stat" href="student_management.php">
        <span class="num"><?php echo $student_count;
?></span>
        <span class="cap">Students Registered</span>
        <span class="tile-link">Open Student Management</span>
    </a>

    <a class="tile dashboard-tile admin-stat teacher-stat" href="teacher_management.php">
        <span class="num"><?php echo $teacher_count;
?></span>
        <span class="cap">Teachers Registered</span>
        <span class="tile-link">Open Teacher Management</span>
    </a>

    <a class="tile dashboard-tile admin-stat staff-stat" href="it_staff_management.php">
        <span class="num"><?php echo $it_staff_count;
?></span>
        <span class="cap">IT Staff Registered</span>
        <span class="tile-link">Open IT Staff Management</span>
    </a>

    <div class="tile admin-stat total-stat">
        <span class="num"><?php echo $total_users;
?></span>
        <span class="cap">Total Registered Users</span>
        <span class="tile-link">Students + Teachers + IT Staff</span>
    </div>
</div>

<!-- Role distribution graph -->
<div class="card dashboard-chart role-distribution-card">
    <div class="chart-heading">
        <div>
            <h3>Role Distribution</h3>
            <p>Registered users by role: students, teachers and IT staff.</p>
        </div>
        <div class="chart-total role-total">
            <?php echo $total_users;
?>            <span>Total Users</span>
        </div>
    </div>

    <div class="role-graph">
        <div class="role-donut-wrap">
            <div class="role-donut" style="--student-angle: <?php echo $student_end;
?>deg; --teacher-angle: <?php echo $teacher_end;
?>deg;">
                <div class="role-donut-hole">
                    <strong><?php echo $total_users;
?></strong>
                    <span>Users</span>
                </div>
            </div>
        </div>

        <div class="role-legend">
            <div class="role-legend-row">
                <span class="legend-dot student-dot"></span>
                <span class="legend-name">Students</span>
                <strong><?php echo $student_count;
?></strong>
                <span><?php echo $student_width;
?>%</span>
            </div>
            <div class="role-mini-bar"><span class="student-role-fill" style="width: <?php echo $student_width;
?>%;"></span></div>

            <div class="role-legend-row">
                <span class="legend-dot teacher-dot"></span>
                <span class="legend-name">Teachers</span>
                <strong><?php echo $teacher_count;
?></strong>
                <span><?php echo $teacher_width;
?>%</span>
            </div>
            <div class="role-mini-bar"><span class="teacher-role-fill" style="width: <?php echo $teacher_width;
?>%;"></span></div>

            <div class="role-legend-row">
                <span class="legend-dot staff-dot"></span>
                <span class="legend-name">IT Staff</span>
                <strong><?php echo $it_staff_count;
?></strong>
                <span><?php echo $it_width;
?>%</span>
            </div>
            <div class="role-mini-bar"><span class="staff-role-fill" style="width: <?php echo $it_width;
?>%;"></span></div>
        </div>
    </div>
</div>

<!-- Profile completion -->
<div class="card dashboard-chart">
    <div class="chart-heading">
        <div>
            <h3>Profile Completion</h3>
            <p>Overview of completed profile records for each registered role.</p>
        </div>
    </div>

    <div class="completion-grid completion-grid-three">
        <div class="completion-box role-completion student-completion">
            <h4>Students</h4>
            <div class="completion-number"><?php echo $student_percent;
?>%</div>
            <div class="progress"><div class="progress-fill student-progress" style="width: <?php echo $student_percent;
?>%;"></div></div>
            <p><?php echo $student_complete;
?> complete / <?php echo $student_incomplete;
?> incomplete</p>
        </div>

        <div class="completion-box role-completion teacher-completion">
            <h4>Teachers</h4>
            <div class="completion-number"><?php echo $teacher_percent;
?>%</div>
            <div class="progress"><div class="progress-fill teacher-progress" style="width: <?php echo $teacher_percent;
?>%;"></div></div>
            <p><?php echo $teacher_complete;
?> complete / <?php echo $teacher_incomplete;
?> incomplete</p>
        </div>

        <div class="completion-box role-completion staff-completion">
            <h4>IT Staff</h4>
            <div class="completion-number"><?php echo $it_staff_percent;
?>%</div>
            <div class="progress"><div class="progress-fill staff-progress" style="width: <?php echo $it_staff_percent;
?>%;"></div></div>
            <p><?php echo $it_staff_complete;
?> complete / <?php echo $it_staff_incomplete;
?> incomplete</p>
        </div>
    </div>
</div>

<?php include "../includes/footer.php";
?>