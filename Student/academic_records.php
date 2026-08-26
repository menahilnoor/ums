<?php
include "../connection.php";
include "../check_login.php";
if ($role != "student") {
    header("location: ../login.php");
    exit();
}
$page_title = "Academic Records";
/*
   This page is the student's complete read-only academic data table.
   There is NO semester filter here because "View Semester" already has
   its own dedicated page in the sidebar.
*/
$current_department = isset($user["department"]) ? $user["department"] : "";
$current_semester = isset($user["current_semester"]) ? $user["current_semester"] : "";
/* Use the latest Admin-entered academic record to keep current department
   and current semester synchronized with the student's account. */
$latest_query = mysqli_query(
$conn,
"SELECT department, semester
     FROM semester_records
     WHERE student_id='$user_id'
       AND department IS NOT NULL AND department <> ''
       AND semester IS NOT NULL AND semester <> ''
     ORDER BY id DESC
     LIMIT 1"
);
if ($latest_query) {
    $latest = mysqli_fetch_assoc($latest_query);
    if ($latest) {
        $current_department = $latest["department"];
        $current_semester = $latest["semester"];
        mysqli_query(
        $conn,
        "UPDATE users SET department='$current_department', current_semester='$current_semester' WHERE id='$user_id'"
        );
    }
}
$query = "SELECT * FROM semester_records WHERE student_id='$user_id' ORDER BY (semester = '$current_semester') DESC, id DESC";
$rows = mysqli_query($conn, $query);
include "../includes/header.php";
?>

<h1>Academic Records</h1>
<p class="sub">All semester results entered by the Admin are stored here. You can only view them.</p>

<div class="card academic-summary-card">
    <div>
        <h3>Academic Summary</h3>
        <p><strong>Student:</strong> <?php echo htmlspecialchars($user["name"]); ?></p>
        <p><strong>Department:</strong> <?php echo $current_department != "" ? htmlspecialchars($current_department) : "Not added yet"; ?></p>
    </div>
    <div class="user-mark academic-semester-badge">
        Current Semester: <?php echo $current_semester != "" ? htmlspecialchars($current_semester) : "Not added yet"; ?>
    </div>
</div>

<div class="card semester-result-card">
    <div class="table-heading">
        <div>
            <h3>Complete Semester Data</h3>
            <p class="form-note">The current semester is shown first. Previous semester records remain available in this complete table.</p>
        </div>
    </div>

    <div class="table-responsive table-wrap">
        <table class="semester-result-table academic-records-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student</th>
                    <th>Department</th>
                    <th>Semester</th>
                    <th>Subject</th>
                    <th>CGPA</th>
                    <th>Grade</th>
                    <th>Attendance</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!$rows || mysqli_num_rows($rows) == 0) { ?>
                <tr>
                    <td colspan="9" class="empty">No academic records have been added by the Admin yet.</td>
                </tr>
            <?php } else { ?>
                <?php
                $count = 1;
                while ($row = mysqli_fetch_assoc($rows)) {
                    $isCurrent = ($row["semester"] === $current_semester);
                ?>
                    <tr class="<?php echo $isCurrent ? 'current-record-row' : ''; ?>">
                        <td><?php echo $count++; ?></td>
                        <td><?php echo htmlspecialchars($user["name"]); ?></td>
                        <td><?php echo htmlspecialchars($row["department"]); ?></td>
                        <td>
                            <span class="semester-badge <?php echo $isCurrent ? 'current-semester-badge' : ''; ?>">
                                <?php echo htmlspecialchars($row["semester"]); ?><?php echo $isCurrent ? ' (Current)' : ''; ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($row["subject"]); ?></td>
                        <td><?php echo htmlspecialchars($row["cgpa"]); ?></td>
                        <td><?php echo htmlspecialchars($row["grade"]); ?></td>
                        <td><?php echo htmlspecialchars($row["attendance"]); ?></td>
                        <td><?php echo htmlspecialchars($row["remarks"]); ?></td>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "../includes/footer.php"; ?>
