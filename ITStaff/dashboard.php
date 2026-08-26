<?php
include "../connection.php";
include "../check_login.php";

if ($role != "it_staff") {
    header("location: ../login.php");
    exit();
}

$page_title = "Dashboard";

/* Check Personal Information */
$personal = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT id FROM personal_details WHERE user_id='$user_id' LIMIT 1")
);

/* Check Family Information */
$family = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT id FROM family_info WHERE user_id='$user_id' LIMIT 1")
);

/* Check Emergency Information */
$emergency = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT id FROM emergency_contact WHERE user_id='$user_id' LIMIT 1")
);

/* Check Education */
$education = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT id FROM it_staff_education WHERE user_id='$user_id' LIMIT 1")
);

/* Check Certification */
$certification = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT id FROM it_staff_certifications WHERE user_id='$user_id' LIMIT 1")
);

/* Check Experience */
$experience = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT id FROM it_staff_experience WHERE user_id='$user_id' LIMIT 1")
);

$filled = 0;

if ($personal && $family && $emergency) {
    $filled++;
}

if ($education) {
    $filled++;
}

if ($certification) {
    $filled++;
}

if ($experience) {
    $filled++;
}

$percent = round(($filled / 4) * 100);

$assignment = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT department_center, category, joining_date, job_title,
                employment_type, job_location, responsibilities
         FROM it_staff_details
         WHERE user_id='$user_id'
         LIMIT 1"
    )
);

$assignment_complete = false;

if ($assignment &&
    $assignment["department_center"] != "" &&
    $assignment["category"] != "" &&
    $assignment["joining_date"] != "" &&
    $assignment["job_title"] != "" &&
    $assignment["employment_type"] != "" &&
    $assignment["job_location"] != "" &&
    $assignment["responsibilities"] != "") {

    $assignment_complete = true;
}

include "../includes/header.php";
?>
<h1>Welcome, <?php echo htmlspecialchars($user['name']); ?></h1>
<p class="sub">IT Staff Dashboard - University Management System.</p>

<div class="top-header job-details-header">
    <div>
        <h4>Job Details</h4>
        <span>Job details are managed by Admin.</span>
    </div>
    <div class="job-status-area">
       
        <a class="job-details-link" href="assignment.php">View Job Details</a>
    </div>
</div>

<div class="tiles">
    <div class="tile">
        <span class="num"><?php echo $percent; ?>%</span>
        <span class="cap">Profile Completion</span>
    </div>
</div>

<div class="card dashboard-completion-card">
    <div class="completion-title-row">
        <div><h3>Information Completion</h3><p class="completion-subtitle">Your profile progress at a glance</p></div>
        <span class="completion-status"><?php echo $percent; ?>% Complete</span>
    </div>
    <div class="chart-box">
        <div class="donut" style="--value:<?php echo $percent; ?>%"><span><?php echo $percent; ?>%</span></div>
        <div class="chart-legend">
            <strong><?php echo $filled; ?> of 4 sections completed</strong>
            <div class="bar"><span style="width:<?php echo $percent; ?>%"></span></div>
            <p class="form-note">Remaining: <?php echo 100-$percent; ?>%</p>
        </div>
    </div>
</div>

<div class="card">
    <h3>Information Status</h3>
    <p class="note">Complete your profile from My Profile, Education, Certification and Experience. After each section is saved, only Admin can edit or delete it.</p>
    <table class="list">
        <tr><th>Section</th><th>Status</th><th>Open</th></tr>
        <tr><td>My Profile</td><td><?php echo $personal?'<span class="ok">Added</span>':'<span class="pending">Not added</span>'; ?></td><td><a href="profile.php">Open</a></td></tr>
        <tr><td>Education</td><td><?php echo $education?'<span class="ok">Added</span>':'<span class="pending">Not added</span>'; ?></td><td><a href="education.php">Open</a></td></tr>
        <tr><td>Certification</td><td><?php echo $certification?'<span class="ok">Added</span>':'<span class="pending">Not added</span>'; ?></td><td><a href="certification.php">Open</a></td></tr>
        <tr><td>Experience</td><td><?php echo $experience?'<span class="ok">Added</span>':'<span class="pending">Not added</span>'; ?></td><td><a href="experience.php">Open</a></td></tr>
    </table>
</div>
<?php include "../includes/footer.php"; ?>
