<?php
include "../connection.php";
include "../check_login.php";
if($role!="it_staff") {
    header("location: ../login.php");
    exit();
}
$page_title="Job Assignment";
$assignment=mysqli_fetch_assoc(mysqli_query($conn,"SELECT department_center,category,joining_date,job_title,employment_type,job_location,responsibilities FROM it_staff_details WHERE user_id='$user_id' LIMIT 1"));
include "../includes/header.php";
?>
<h1>Job / Admin Assignment</h1>
<p class="sub">These details are entered and controlled by Admin. IT Staff can only view them.</p>
<div class="card">
<h3>Assignment Details</h3>
<table class="info-table">
<tr><th>IT Center / Department</th><td><?php echo (isset($assignment['department_center']) && $assignment['department_center'] != '' ? nl2br(htmlspecialchars($assignment['department_center'])) : 'Not assigned yet');?></td></tr>
<tr><th>Category</th><td><?php echo !empty($assignment['category'])?htmlspecialchars(ucfirst($assignment['category'])):'Not assigned yet';?></td></tr>
<tr><th>Joining Date</th><td><?php echo (isset($assignment['joining_date']) && $assignment['joining_date'] != '' ? nl2br(htmlspecialchars($assignment['joining_date'])) : 'Not assigned yet');?></td></tr>
<tr><th>Job Title</th><td><?php echo (isset($assignment['job_title']) && $assignment['job_title'] != '' ? nl2br(htmlspecialchars($assignment['job_title'])) : 'Not assigned yet');?></td></tr>
<tr><th>Job Type</th><td><?php echo (isset($assignment['employment_type']) && $assignment['employment_type'] != '' ? nl2br(htmlspecialchars($assignment['employment_type'])) : 'Not assigned yet');?></td></tr>
<tr><th>Job Location</th><td><?php echo (isset($assignment['job_location']) && $assignment['job_location'] != '' ? nl2br(htmlspecialchars($assignment['job_location'])) : 'Not assigned yet');?></td></tr>
<tr><th>Responsibilities</th><td><?php echo (isset($assignment['responsibilities']) && $assignment['responsibilities'] != '' ? nl2br(htmlspecialchars($assignment['responsibilities'])) : 'Not assigned yet');?></td></tr>
</table>
</div>
<?php include "../includes/footer.php"; ?>
