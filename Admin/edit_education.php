<?php
include "../connection.php";
include "../check_login.php";
if($role!="admin") {
    header("location: ../login.php");
    exit();
} $id=(int)((isset($_GET['id']) ? $_GET['id'] : 0));
$row=mysqli_fetch_assoc(mysqli_query($conn, " SELECT * FROM qualifications WHERE id='$id'"));
if(!$row) {
    header("location: users.php");
    exit();
} $degrees=array("Matric", "Intermediate", "Associate Degree", "BBA", "BS Software Engineering", "BS Computer Science", "BS Artificial Intelligence", "BS Information Technology", "BS Data Science", "BS Cyber Security", "BS Computer Engineering", "BS Electrical Engineering", "MS", "MPhil", "PhD", "Other");
$specializations=array("Software Engineering", "Computer Science", "Artificial Intelligence", "Information Technology", "Data Science", "Cyber Security", "Computer Engineering", "Electrical Engineering", "Business Administration", "Mathematics", "Physics", "Information Systems", "Networks", "Database Systems", "Other");
$groups=array("Science", "General Science", "Arts", "Computer Science", "Pre-Medical", "Pre-Engineering", "ICS", "I.Com", "FA", "General Arts");
$message='';
if(isset($_POST['save-btn'])) {
    $degree=mysqli_real_escape_string($conn, $_POST['degree']);
    $institution=mysqli_real_escape_string($conn, $_POST['institution']);
    $year=mysqli_real_escape_string($conn, $_POST['passing_year']);
    $spec=mysqli_real_escape_string($conn, (isset($_POST['specialization']) ? $_POST['specialization'] : ''));
    $group=mysqli_real_escape_string($conn, (isset($_POST['study_group']) ? $_POST['study_group'] : ''));
    $marks=mysqli_real_escape_string($conn, (isset($_POST['marks']) ? $_POST['marks'] : ''));
    $grade=mysqli_real_escape_string($conn, (isset($_POST['grade']) ? $_POST['grade'] : ''));
    if(mysqli_query($conn, " UPDATE qualifications SET degree='$degree',institution='$institution',passing_year='$year',marks='$marks',grade='$grade',specialization='$spec',study_group='$group' WHERE id='$id'")) {
        $message='Education record updated successfully.';
        $row=mysqli_fetch_assoc(mysqli_query($conn, " SELECT * FROM qualifications WHERE id='$id'"));
    } else {
        $message='Record could not be updated: '.mysqli_error($conn);
    }
} $page_title='Edit Education';
include "../includes/header.php";
?><h1>Edit Education</h1><p class="sub">Qualification is managed as part of Education. Admin has full edit access.</p><?php if($message) {
?><div class="done"><?php echo htmlspecialchars($message);
?></div><?php 
}
?><div class="card form-card"><form method="POST"><div class="two"><div><label>Degree / Qualification</label><select name="degree" id="degree" required><?php foreach($degrees as $d) {
?><option <?php if(((isset($row['degree']) ? $row['degree'] : ''))===$d)echo 'selected';
?>><?php echo htmlspecialchars($d);
?></option><?php 
}
?></select></div><div><label>Institution</label><input name="institution" value="<?php echo htmlspecialchars((isset($row['institution']) ? $row['institution'] : ''));
?>" required></div><div id="groupWrap"><label>Study Group / Stream</label><select name="study_group"><option value="">Select Group</option><?php foreach($groups as $g) {
?><option <?php if(((isset($row['study_group']) ? $row['study_group'] : ''))===$g)echo 'selected';
?>><?php echo htmlspecialchars($g);
?></option><?php 
}
?></select></div><div><label>Specialization</label><select name="specialization"><option value="">Select Specialization</option><?php foreach($specializations as $sp) {
?><option <?php if(((isset($row['specialization']) ? $row['specialization'] : ''))===$sp)echo 'selected';
?>><?php echo htmlspecialchars($sp);
?></option><?php 
}
?></select></div><div><label>Passing Year</label><input type="number" name="passing_year" value="<?php echo htmlspecialchars((isset($row['passing_year']) ? $row['passing_year'] : ''));
?>" required></div><div><label>Marks / CGPA</label><input name="marks" value="<?php echo htmlspecialchars((isset($row['marks']) ? $row['marks'] : ''));
?>"></div><div><label>Grade</label><input name="grade" value="<?php echo htmlspecialchars((isset($row['grade']) ? $row['grade'] : ''));
?>"></div></div><button name="save-btn">Save Changes</button></form></div><script>const d=document.getElementById('degree'),w=document.getElementById('groupWrap');function x(){w.style.display=(d.value==='Matric'||d.value==='Intermediate')?'block':'none';}d.addEventListener('change',x);x();</script><?php include "../includes/footer.php";
?>