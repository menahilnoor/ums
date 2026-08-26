<?php
include "../connection.php";
include "../check_login.php";
if ($role != "teacher") {
    header("location: ../login.php");
    exit();
}
$page_title="Education";
$message="";
$error="";
$degree_options = array("Matric","Intermediate","Associate Degree","BBA","BS Software Engineering","BS Computer Science","BS Artificial Intelligence","BS Information Technology","BS Data Science","BS Cyber Security","BS Computer Engineering","BS Electrical Engineering","MS","MPhil","PhD","Other");
$specializations = array("Software Engineering","Computer Science","Artificial Intelligence","Information Technology","Data Science","Cyber Security","Computer Engineering","Electrical Engineering","Business Administration","Mathematics","Physics","Information Systems","Networks","Database Systems","Other");
$groups = array("Science","General Science","Arts","Computer Science","Pre-Medical","Pre-Engineering","ICS","I.Com","FA","General Arts");
if(isset($_POST["add-btn"])) {
    $degree=mysqli_real_escape_string($conn,trim((isset($_POST["degree"]) ? $_POST["degree"] : "")));
    $institution=mysqli_real_escape_string($conn,trim((isset($_POST["institution"]) ? $_POST["institution"] : "")));
    $specialization=mysqli_real_escape_string($conn,trim((isset($_POST["specialization"]) ? $_POST["specialization"] : "")));
    $study_group=mysqli_real_escape_string($conn,trim((isset($_POST["study_group"]) ? $_POST["study_group"] : "")));
    $passing_year=mysqli_real_escape_string($conn,trim((isset($_POST["passing_year"]) ? $_POST["passing_year"] : "")));
    if($degree==="" || $institution==="" || $passing_year==="") $error="Please fill all required education fields.";
    if($error==="") {
        $run=mysqli_query($conn,"INSERT INTO qualifications (user_id,degree,institution,passing_year,marks,grade,specialization,study_group) VALUES ('$user_id','$degree','$institution','$passing_year','','','$specialization','$study_group')");
        if($run) {
            $message="Education added successfully.";
        } else {
            $error="Could not save education: ".mysqli_error($conn);
        }
    }
}
$rows=mysqli_query($conn,"SELECT * FROM qualifications WHERE user_id='$user_id' ORDER BY id DESC");
include "../includes/header.php";
?>
<h1>Education</h1><p class="sub">Add your academic education. Qualification is now part of Education.</p>
<?php if($message!=""){ ?><div class="done"><?php echo htmlspecialchars($message); ?></div><?php } ?>
<?php if($error!=""){ ?><div class="error"><?php echo htmlspecialchars($error); ?></div><?php } ?>
<div class="card"><h3>Saved Education</h3><div class="table-responsive table-wrap"><table class="list"><tr><th>Degree</th><th>Institution</th><th>Study Group</th><th>Specialization</th><th>Year</th></tr><?php if(!$rows || mysqli_num_rows($rows)==0){ ?><tr><td colspan="5" class="empty">No education records added yet.</td></tr><?php }else{ while($row=mysqli_fetch_assoc($rows)){ ?><tr><td><?php echo htmlspecialchars($row["degree"]); ?></td><td><?php echo htmlspecialchars($row["institution"]); ?></td><td><?php echo htmlspecialchars((isset($row["study_group"]) ? $row["study_group"] : "")); ?></td><td><?php echo htmlspecialchars((isset($row["specialization"]) ? $row["specialization"] : (isset($row["marks"]) ? $row["marks"] : ""))); ?></td><td><?php echo htmlspecialchars($row["passing_year"]); ?></td></tr><?php }} ?></table></div></div>
<div class="card form-card"><h3>Add Education Record</h3><form method="POST"><div class="two">
<div><label>Degree / Qualification *</label><select name="degree" id="teacherDegree" required><option value="">Select Degree</option><?php foreach($degree_options as $d){ ?><option value="<?php echo htmlspecialchars($d); ?>"><?php echo htmlspecialchars($d); ?></option><?php } ?></select></div>
<div><label>Institution / University *</label><input name="institution" required></div>
<div id="studyGroupWrap" style="display:none"><label>Study Group / Stream</label><select name="study_group" id="studyGroup"><option value="">Select Group</option><?php foreach($groups as $g){ ?><option value="<?php echo htmlspecialchars($g); ?>"><?php echo htmlspecialchars($g); ?></option><?php } ?></select></div>
<div id="specializationWrap"><label>Specialization</label><select name="specialization"><option value="">Select Specialization</option><?php foreach($specializations as $s){ ?><option value="<?php echo htmlspecialchars($s); ?>"><?php echo htmlspecialchars($s); ?></option><?php } ?></select></div>
<div><label>Passing Year *</label><input name="passing_year" type="number" min="1950" max="2100" required></div>
</div><button type="submit" name="add-btn">Add Education</button></form></div>
<script>
(function(){const d=document.getElementById('teacherDegree'), g=document.getElementById('studyGroupWrap'), s=document.getElementById('specializationWrap'); function sync(){const v=d.value; const show=(v==='Matric'||v==='Intermediate'); g.style.display=show?'block':'none'; if(!show) document.getElementById('studyGroup').value=''; s.style.display=show?'none':'block';} d.addEventListener('change',sync); sync();})();
</script>
<?php include "../includes/footer.php"; ?>
