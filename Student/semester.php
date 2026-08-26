<?php
include "../connection.php";
include "../check_login.php";
if ($role != "student") {
    header("location: ../login.php");
    exit();
}
$page_title = "View Semester";
$departments = array(
"BS Software Engineering",
"BS Computer Science",
"BS Artificial Intelligence",
"BS Computer Engineering",
"BS Electrical Engineering",
"BS Business Administration"
);
$allowed = array(
"Semester 1", "Semester 2", "Semester 3", "Semester 4",
"Semester 5", "Semester 6", "Semester 7", "Semester 8"
);
$current_department = isset($user["department"]) ? $user["department"] : "";
$current_semester = isset($user["current_semester"]) ? $user["current_semester"] : "";
/* Latest Admin record defines the student's current academic status. */
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
if (!in_array($current_semester, $allowed)) {
    $current_semester = "";
}
/* Only semesters that actually have records are offered as previous choices. */
$available_semesters = array();
$available_query = mysqli_query(
$conn,
"SELECT DISTINCT semester FROM semester_records
     WHERE student_id='$user_id' AND semester <> ''
     ORDER BY id DESC"
);
if ($available_query) {
    while ($available = mysqli_fetch_assoc($available_query)) {
        if (in_array($available["semester"], $allowed)) {
            $available_semesters[] = $available["semester"];
        }
    }
}
/* Previous semester selection. Current semester is never dependent on a button. */
$previous_semester = "";
if (isset($_GET["semester"]) && in_array($_GET["semester"], $allowed) && $_GET["semester"] !== $current_semester) {
    $previous_semester = $_GET["semester"];
}
$previous_department = $current_department;
if (isset($_GET["department"]) && in_array($_GET["department"], $departments) && $_GET["department"] === $current_department) {
    $previous_department = $_GET["department"];
}
$current_rows = false;
if ($current_semester !== "") {
    $current_rows = mysqli_query(
    $conn,
    "SELECT * FROM semester_records
         WHERE student_id='$user_id' AND semester='$current_semester'
         ORDER BY id DESC"
    );
}
$previous_rows = false;
if ($previous_semester !== "") {
    $previous_rows = mysqli_query(
    $conn,
    "SELECT * FROM semester_records
         WHERE student_id='$user_id'
           AND department='$previous_department'
           AND semester='$previous_semester'
         ORDER BY id DESC"
    );
}
include "../includes/header.php";
?>

<h1>View Semester</h1>
<p class="sub">Your current semester result is displayed automatically. Previous semesters can be selected when needed.</p>

<div class="card semester-current-card">
    <div class="top-header semester-identity-header">
        <div>
            <h3><?php echo htmlspecialchars($user["name"]); ?></h3>
            <span><?php echo $current_department !== "" ? htmlspecialchars($current_department) : "Department not added"; ?></span>
        </div>
        <div class="user-mark academic-semester-badge">
            <?php echo $current_semester !== "" ? htmlspecialchars($current_semester) : "No Current Semester"; ?>
        </div>
    </div>

    <h3 class="section-title">Current Semester Result</h3>
    <div class="table-responsive table-wrap">
        <table class="semester-result-table">
            <thead>
                <tr>
                    <th>Student Name</th>
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
            <?php if (!$current_rows || mysqli_num_rows($current_rows) == 0) { ?>
                <tr>
                    <td colspan="8" class="empty">No current semester record has been added by the Admin yet.</td>
                </tr>
            <?php } else { ?>
                <?php while ($row = mysqli_fetch_assoc($current_rows)) { ?>
                    <tr class="current-record-row">
                        <td><?php echo htmlspecialchars($user["name"]); ?></td>
                        <td><?php echo htmlspecialchars($row["department"]); ?></td>
                        <td><span class="semester-badge current-semester-badge"><?php echo htmlspecialchars($row["semester"]); ?> (Current)</span></td>
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

<?php if (count($available_semesters) > 1) { ?>
<div class="card semester-filter-card previous-semester-card">
    <h3>Previous Semester</h3>
    <p class="form-note">Select a previous semester only when you want to view its details. The current semester above is already visible.</p>

    <form method="GET" class="semester-selection-form">
        <div class="semester-filter-field department-field">
            <label for="department">Department</label>
            <select name="department" id="department" class="filter-select">
                <option value="">Select Department</option>
                <?php foreach ($departments as $department) { ?>
                    <option value="<?php echo htmlspecialchars($department); ?>" <?php echo ($current_department === $department) ? "selected" : ""; ?>>
                        <?php echo htmlspecialchars($department); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="semester-filter-field student-name-field-wrap">
            <label for="student_name">Student Name</label>
            <input id="student_name" class="student-name-field" type="text" value="<?php echo htmlspecialchars($user["name"]); ?>" readonly>
        </div>

        <div class="semester-filter-field semester-field">
            <label for="semester">Semester</label>
            <select name="semester" id="semester" class="filter-select">
                <option value="">Select Previous Semester</option>
                <?php foreach ($available_semesters as $s) { ?>
                    <?php if ($s === $current_semester) continue; ?>
                    <option value="<?php echo htmlspecialchars($s); ?>" <?php echo ($previous_semester === $s) ? "selected" : ""; ?>>
                        <?php echo htmlspecialchars($s); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <button type="submit" class="semester-view-submit">View</button>
    </form>
</div>
<?php } ?>

<?php if ($previous_semester !== "") { ?>
<div class="card semester-result-card previous-result-card">
    <div class="top-header">
        <div>
            <h3><?php echo htmlspecialchars($previous_semester); ?></h3>
            <span><?php echo htmlspecialchars($user["name"]); ?> - <?php echo htmlspecialchars($previous_department); ?></span>
        </div>
        <div class="user-mark previous-semester-badge">Previous</div>
    </div>

    <div class="table-responsive table-wrap">
        <table class="semester-result-table">
            <thead>
                <tr>
                    <th>Student Name</th>
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
            <?php if (!$previous_rows || mysqli_num_rows($previous_rows) == 0) { ?>
                <tr>
                    <td colspan="8" class="empty">No record available for this semester.</td>
                </tr>
            <?php } else { ?>
                <?php while ($row = mysqli_fetch_assoc($previous_rows)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user["name"]); ?></td>
                        <td><?php echo htmlspecialchars($row["department"]); ?></td>
                        <td><span class="semester-badge"><?php echo htmlspecialchars($row["semester"]); ?></span></td>
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
<?php } ?>

<?php include "../includes/footer.php"; ?>
