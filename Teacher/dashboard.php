<?php
include "../connection.php";
include "../check_login.php";
if($role!="teacher") {
    header("location: ../login.php");
    exit();
}
$page_title="Dashboard";
$profile=mysqli_num_rows(mysqli_query($conn,"SELECT * FROM personal_details WHERE user_id='$user_id'"));
$family=mysqli_num_rows(mysqli_query($conn,"SELECT * FROM family_info WHERE user_id='$user_id'"));
$emergency=mysqli_num_rows(mysqli_query($conn,"SELECT * FROM emergency_contact WHERE user_id='$user_id'"));
$education=mysqli_num_rows(mysqli_query($conn,"SELECT * FROM qualifications WHERE user_id='$user_id'"));
$certification=mysqli_num_rows(mysqli_query($conn,"SELECT * FROM teacher_certifications WHERE user_id='$user_id'"));
$scheduler=mysqli_num_rows(mysqli_query($conn,"SELECT * FROM teacher_teaching WHERE teacher_id='$user_id'"));
$filled=0;
if($profile>0&&$family>0&&$emergency>0)$filled++;
if($education>0)$filled++;
if($certification>0)$filled++;
if($scheduler>0)$filled++;
$percent=round(($filled/4)*100);
include "../includes/header.php";
?><h1>Welcome, <?php echo htmlspecialchars($user['name']);?></h1><p class="sub">Teacher Dashboard - University Management System.</p><div class="top-header"><div><h4>Department</h4></div><div class="user-mark"><?php echo htmlspecialchars($user['main_subject']?:'Teacher');?></div></div><div class="tiles"><div class="tile"><span class="num"><?php echo $percent;?>%</span><span class="cap">Profile Completion</span></div><div class="tile"><span class="num"><?php echo $scheduler;?></span><span class="cap">Scheduled Courses</span></div><div class="tile"><span class="num"><?php echo $certification;?></span><span class="cap">Certifications</span></div></div><div class="card"><h3>Information Completion</h3><div class="chart-box"><div class="donut" style="--value:<?php echo $percent;?>%"><span><?php echo $percent;?>%</span></div><div class="chart-legend"><strong><?php echo $filled;?> of 4 sections completed</strong><div class="bar"><span style="width:<?php echo $percent;?>%"></span></div><p class="form-note">Remaining: <?php echo 100-$percent;?>%</p></div></div></div><div class="card"><h3>Information Status</h3><table class="list"><tr><th>Section</th><th>Percentage</th><th>Open</th></tr><tr><td>Personal / Family / Emergency</td><td><span class="completion-pill"><?php echo ($profile>0&&$family>0&&$emergency>0)?100:0;?>%</span></td><td><a href="profile.php">Open</a></td></tr><tr><td>Education</td><td><span class="completion-pill"><?php echo $education>0?100:0;?>%</span></td><td><a href="education.php">Open</a></td></tr><tr><td>Certification</td><td><span class="completion-pill"><?php echo $certification>0?100:0;?>%</span></td><td><a href="certification.php">Open</a></td></tr><tr><td>Scheduler</td><td><span class="completion-pill"><?php echo $scheduler>0?100:0;?>%</span></td><td><a href="scheduler.php">Open</a></td></tr></table></div><div class="card"><h3>Teaching Schedule</h3><p>This teacher currently has <strong><?php echo $scheduler;?></strong> scheduled course record(s).</p></div><?php include "../includes/footer.php"; ?>
