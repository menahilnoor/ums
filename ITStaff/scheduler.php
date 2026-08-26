<?php
include "../connection.php";
include "../check_login.php";
if($role!="it_staff") {
    header("location: ../login.php");
    exit();
}
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS it_staff_schedule (id INT AUTO_INCREMENT PRIMARY KEY,user_id INT NOT NULL,department_center VARCHAR(150) NOT NULL,category ENUM('hardware','software','network') NOT NULL,schedule_date DATE NOT NULL,start_time TIME NOT NULL,end_time TIME NOT NULL,task VARCHAR(255) NOT NULL,location VARCHAR(180) NOT NULL,remarks TEXT,FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE)");
$page_title="IT Staff Schedule";
$categories=array("All Categories","Hardware","Software","Network");
$departments=array("All IT Centers","IT Support Center","Network Operations Center","Software Development Center","Infrastructure & Systems Center","Help Desk Center");
$category=(isset($_GET['category']) ? $_GET['category'] : 'All Categories');
$department=(isset($_GET['department']) ? $_GET['department'] : 'All IT Centers');
if(!in_array($category,$categories,true))$category='All Categories';
if(!in_array($department,$departments,true))$department='All IT Centers';
$q="SELECT * FROM it_staff_schedule WHERE user_id='$user_id'";
if($category!=='All Categories')$q.=" AND category='".mysqli_real_escape_string($conn,strtolower($category))."'";
if($department!=='All IT Centers')$q.=" AND department_center='".mysqli_real_escape_string($conn,$department)."'";
$q.=" ORDER BY schedule_date, start_time, id DESC";
$rows=mysqli_query($conn,$q);
include "../includes/header.php";
?>
<h1>IT Staff Schedule</h1>
<p class="sub">Admin manages your IT support schedule. You can only view it.</p>
<div class="card form-card">
<h3>Filter Schedule</h3>
<form method="GET"><div class="two">
<div><label>IT Center / Department</label><select name="department"><?php foreach($departments as $d){?><option value="<?php echo htmlspecialchars($d);?>" <?php if($department===$d)echo 'selected';?>><?php echo htmlspecialchars($d);?></option><?php }?></select></div>
<div><label>Category</label><select name="category"><?php foreach($categories as $c){?><option value="<?php echo htmlspecialchars($c);?>" <?php if($category===$c)echo 'selected';?>><?php echo htmlspecialchars($c);?></option><?php }?></select></div>
</div><button>View</button> <a class="clear-filter" href="scheduler.php">Clear</a></form>
</div>
<div class="card"><h3><?php echo htmlspecialchars($user['name']);?> Schedule</h3><div class="table-responsive table-wrap"><table class="list">
<tr><th>Date</th><th>Time</th><th>IT Center</th><th>Category</th><th>Task / Duty</th><th>Location</th></tr>
<?php if(!$rows||mysqli_num_rows($rows)==0){?><tr><td colspan="6" class="empty">No schedule records available.</td></tr><?php }else{while($r=mysqli_fetch_assoc($rows)){?>
<tr><td><?php echo htmlspecialchars($r['schedule_date']);?></td><td><?php echo htmlspecialchars(date('h:i A',strtotime($r['start_time'])));?> - <?php echo htmlspecialchars(date('h:i A',strtotime($r['end_time'])));?></td><td><?php echo htmlspecialchars($r['department_center']);?></td><td><?php echo htmlspecialchars(ucfirst($r['category']));?></td><td><?php echo htmlspecialchars($r['task']);?></td><td><?php echo htmlspecialchars($r['location']);?></td></tr>
<?php }}?></table></div></div>
<?php include "../includes/footer.php"; ?>
