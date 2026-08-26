<?php
include "../connection.php";
include "../check_login.php";
if($role!="teacher") {
    header("location: ../login.php");
    exit();
}
$page_title="Scheduler";
$allowed=array("All Semesters","Semester 1","Semester 2","Semester 3","Semester 4","Semester 5","Semester 6","Semester 7","Semester 8");
$semester=(isset($_GET['semester']) ? $_GET['semester'] : 'All Semesters');
if(!in_array($semester,$allowed,true))$semester='All Semesters';
$q="SELECT * FROM teacher_teaching WHERE teacher_id='$user_id'";
if($semester!=='All Semesters')$q.=" AND semester='".mysqli_real_escape_string($conn,$semester)."'";
$q.=" ORDER BY semester,id DESC";
$rows=mysqli_query($conn,$q);
include "../includes/header.php";
?><h1>Scheduler</h1><p class="sub">Admin manages your teaching schedule. You can only view it.</p><div class="card form-card"><h3>Filter Schedule</h3><form method="GET"><div class="two"><div><label>Semester</label><select name="semester"><?php foreach($allowed as $s){?><option value="<?php echo htmlspecialchars($s);?>" <?php if($semester===$s)echo 'selected';?>><?php echo htmlspecialchars($s);?></option><?php }?></select></div></div><button>View</button></form></div><div class="card"><h3><?php echo htmlspecialchars($user['name']);?> Schedule</h3><div class="table-responsive table-wrap"><table class="list"><tr><th>Semester</th><th>Department</th><th>Subject</th></tr><?php if(!$rows||mysqli_num_rows($rows)==0){?><tr><td colspan="3" class="empty">No scheduler records available.</td></tr><?php }else{while($r=mysqli_fetch_assoc($rows)){?><tr><td><?php echo htmlspecialchars($r['semester']);?></td><td><?php echo htmlspecialchars($r['department']);?></td><td><?php echo htmlspecialchars($r['subject']);?></td></tr><?php }}?></table></div></div><?php include "../includes/footer.php"; ?>
