<?php
include "../connection.php";
include "../check_login.php";
if ($role != "it_staff") {
    header("location: ../login.php");
    exit();
}
$page_title = "Education";
$message = "";
$error = "";
$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM it_staff_education WHERE user_id='$user_id' LIMIT 1"));
$degrees = array("Matric","Intermediate","Associate Degree","BBA","BS Computer Science","BS Software Engineering","BS Information Technology","BS Computer Engineering","BS Cyber Security","BS Data Science","MS","MPhil","PhD","Other");
$specializations = array("IT Support","Networking","System Administration","Software Development","Database Administration","Cyber Security","Cloud Computing","Hardware Engineering","Computer Science","Information Technology","Other");
if (isset($_POST['save-btn']) && !$row) {
    $degree=mysqli_real_escape_string($conn,trim((isset($_POST['degree']) ? $_POST['degree'] : '')));
    $institution=mysqli_real_escape_string($conn,trim((isset($_POST['institution']) ? $_POST['institution'] : '')));
    $specialization=mysqli_real_escape_string($conn,trim((isset($_POST['specialization']) ? $_POST['specialization'] : '')));
    $passing_year=mysqli_real_escape_string($conn,trim((isset($_POST['passing_year']) ? $_POST['passing_year'] : '')));
    $study_group=mysqli_real_escape_string($conn,trim((isset($_POST['study_group']) ? $_POST['study_group'] : '')));
    $education_details=mysqli_real_escape_string($conn,trim((isset($_POST['education_details']) ? $_POST['education_details'] : '')));
    if($degree===''||$institution===''||$specialization===''||$passing_year==='') $error='Please fill all required fields.';
    else if(mysqli_query($conn,"INSERT INTO it_staff_education (user_id,degree,institution,specialization,passing_year,study_group,education_details) VALUES ('$user_id','$degree','$institution','$specialization','$passing_year','$study_group','$education_details')")) {
        $message='Education information added successfully. Only Admin can edit it now.';
        $row=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM it_staff_education WHERE user_id='$user_id' LIMIT 1"));
    }
    else $error='Education could not be saved. Please run the IT Staff database upgrade SQL first.';
}
include "../includes/header.php";
?>
<h1>Education</h1><p class="sub">Add your education/qualification information once. After submission only Admin can edit or delete it.</p>
<?php if($message){?><div class="done"><?php echo htmlspecialchars($message);?></div><?php }?><?php if($error){?><div class="error"><?php echo htmlspecialchars($error);?></div><?php }?>
<div class="card form-card">
<?php if($row){?><div class="note"><strong>Education already submitted.</strong> Only Admin can edit it.</div><table class="list"><tr><th>Degree</th><th>Institution</th><th>Specialization</th><th>Year</th><th>Study Group</th><th>Details</th></tr><tr><td><?php echo htmlspecialchars($row['degree']);?></td><td><?php echo htmlspecialchars($row['institution']);?></td><td><?php echo htmlspecialchars($row['specialization']);?></td><td><?php echo htmlspecialchars($row['passing_year']);?></td><td><?php echo htmlspecialchars($row['study_group']);?></td><td><?php echo htmlspecialchars($row['education_details']);?></td></tr></table><?php }else{?>
<form method="POST"><h3>Education Details</h3><div class="two">
<div><label>Degree / Qualification *</label><select name="degree" id="degree" required><option value="">Select Degree</option><?php foreach($degrees as $x){?><option><?php echo htmlspecialchars($x);?></option><?php }?></select></div>
<div><label>Institution / University *</label><input name="institution" required></div>
<div><label>Specialization *</label><select name="specialization" required><option value="">Select Specialization</option><?php foreach($specializations as $x){?><option><?php echo htmlspecialchars($x);?></option><?php }?></select></div>
<div><label>Passing Year *</label><input type="number" name="passing_year" min="1950" max="2100" required></div>
<div id="study-group-box" style="display:none"><label>Study Group</label><select name="study_group"><option value="">Select Group</option><option>Pre-Medical</option><option>Pre-Engineering</option><option>ICS</option><option>I.Com</option><option>FA</option><option>General Science</option><option>Arts</option><option>Other</option></select></div>
<div class="full"><label>Additional Education Details</label><textarea name="education_details"></textarea></div></div><button type="submit" name="save-btn">Save Education</button></form>
<?php }?></div>
<script>const d=document.getElementById('degree'),b=document.getElementById('study-group-box');function tg(){if(d&&b)b.style.display=(d.value==='Matric'||d.value==='Intermediate')?'block':'none';}if(d){d.addEventListener('change',tg);tg();}</script>
<?php include "../includes/footer.php"; ?>
