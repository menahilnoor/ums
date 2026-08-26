<?php
include "../connection.php";
include "../check_login.php";
if ($role != "it_staff") {
    header("location: ../login.php");
    exit();
}
$page_title="Experience";
$message="";
$error="";
$row=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM it_staff_experience WHERE user_id='$user_id' LIMIT 1"));
$experience=array("No Experience","Less than 1 year","1–2 years","3–5 years","6–10 years","10+ years");
if(isset($_POST['save-btn'])&&!$row) {
    $total=mysqli_real_escape_string($conn,trim((isset($_POST['total_experience']) ? $_POST['total_experience'] : '')));
    $emp=mysqli_real_escape_string($conn,trim((isset($_POST['previous_employer']) ? $_POST['previous_employer'] : '')));
    $title=mysqli_real_escape_string($conn,trim((isset($_POST['previous_job_title']) ? $_POST['previous_job_title'] : '')));
    $start=mysqli_real_escape_string($conn,trim((isset($_POST['previous_start_date']) ? $_POST['previous_start_date'] : '')));
    $end=mysqli_real_escape_string($conn,trim((isset($_POST['previous_end_date']) ? $_POST['previous_end_date'] : '')));
    $details=mysqli_real_escape_string($conn,trim((isset($_POST['experience_details']) ? $_POST['experience_details'] : '')));
    if($total==='')$error='Please select your total experience.';
    else {
        $startSql=$start===''?'NULL':"'$start'";
        $endSql=$end===''?'NULL':"'$end'";
        if(mysqli_query($conn,"INSERT INTO it_staff_experience (user_id,total_experience,previous_employer,previous_job_title,previous_start_date,previous_end_date,experience_details) VALUES ('$user_id','$total','$emp','$title',$startSql,$endSql,'$details')")) {
            $message='Experience information added successfully. Only Admin can edit it now.';
            $row=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM it_staff_experience WHERE user_id='$user_id' LIMIT 1"));
        } else $error='Experience could not be saved. Please run the IT Staff database upgrade SQL first.';
    }
}
include "../includes/header.php"; ?><h1>Experience</h1><p class="sub">Add your previous IT work experience once. After submission only Admin can edit or delete it.</p><?php if($message){?><div class="done"><?php echo htmlspecialchars($message);?></div><?php }?><?php if($error){?><div class="error"><?php echo htmlspecialchars($error);?></div><?php }?><div class="card form-card"><?php if($row){?><div class="note"><strong>Experience already submitted.</strong> Only Admin can edit it.</div><table class="list"><tr><th>Total Experience</th><th>Previous Employer</th><th>Previous Job Title</th><th>Start Date</th><th>End Date</th><th>Details</th></tr><tr><td><?php echo htmlspecialchars($row['total_experience']);?></td><td><?php echo htmlspecialchars($row['previous_employer']);?></td><td><?php echo htmlspecialchars($row['previous_job_title']);?></td><td><?php echo htmlspecialchars($row['previous_start_date']);?></td><td><?php echo htmlspecialchars($row['previous_end_date']);?></td><td><?php echo htmlspecialchars($row['experience_details']);?></td></tr></table><?php }else{?><form method="POST"><h3>Experience Details</h3><div class="two"><div><label>Total IT Experience *</label><select name="total_experience" required><option value="">Select Experience</option><?php foreach($experience as $x){?><option><?php echo htmlspecialchars($x);?></option><?php }?></select></div><div><label>Previous Employer</label><input name="previous_employer"></div><div><label>Previous Job Title</label><input name="previous_job_title"></div><div><label>Previous Start Date</label><input type="date" name="previous_start_date"></div><div><label>Previous End Date</label><input type="date" name="previous_end_date"></div><div class="full"><label>Experience Details</label><textarea name="experience_details"></textarea></div></div><button type="submit" name="save-btn">Save Experience</button></form><?php }?></div><?php include "../includes/footer.php"; ?>
