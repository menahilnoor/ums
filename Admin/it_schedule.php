<?php
include "../connection.php";
include "../check_login.php";

if ($role != "admin") {
    header("location: ../login.php");
    exit();
}

/* Create the schedule table if it does not exist. */
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS it_staff_schedule (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    department_center VARCHAR(150) NOT NULL,
    category ENUM('hardware','software','network') NOT NULL,
    schedule_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    task VARCHAR(255) NOT NULL,
    location VARCHAR(180) NOT NULL,
    remarks TEXT
)");

$page_title = "IT Staff Schedule";

$centers = array(
    "IT Support Center", "Network Operations Center",
    "Software Development Center", "Infrastructure & Systems Center",
    "Help Desk Center"
);

$categories = array("hardware", "software", "network");

if (isset($_GET["delete"])) {
    $id = (int)$_GET["delete"];
    mysqli_query($conn, "DELETE FROM it_staff_schedule WHERE id='$id'");
    header("location: it_schedule.php");
    exit();
}

$message = "";
$error = "";

if (isset($_POST["save"])) {

    $user = (int)$_POST["user_id"];
    $center = mysqli_real_escape_string($conn, $_POST["department_center"]);
    $cat = mysqli_real_escape_string($conn, $_POST["category"]);
    $date = mysqli_real_escape_string($conn, $_POST["schedule_date"]);
    $start = mysqli_real_escape_string($conn, $_POST["start_time"]);
    $end = mysqli_real_escape_string($conn, $_POST["end_time"]);
    $task = mysqli_real_escape_string($conn, $_POST["task"]);
    $loc = mysqli_real_escape_string($conn, $_POST["location"]);
    $remarks = mysqli_real_escape_string($conn, $_POST["remarks"]);

    if ($user < 1 || $center == "" || $cat == "" || $date == "" ||
        $start == "" || $end == "" || $task == "" || $loc == "") {

        $error = "Please complete all required fields.";

    } else {

        $query = "INSERT INTO it_staff_schedule
                  (user_id, department_center, category, schedule_date,
                   start_time, end_time, task, location, remarks)
                  VALUES
                  ('$user', '$center', '$cat', '$date', '$start',
                   '$end', '$task', '$loc', '$remarks')";

        if (mysqli_query($conn, $query)) {
            $message = "IT Staff schedule added successfully.";
        } else {
            $error = "Schedule could not be saved.";
        }
    }
}

$filter_center = "";
$filter_cat = "";

if (isset($_GET["center"])) {
    $filter_center = trim($_GET["center"]);
}

if (isset($_GET["category"])) {
    $filter_cat = trim($_GET["category"]);
}

$query = "SELECT * FROM it_staff_schedule WHERE id > 0";

if ($filter_center != "") {
    $center_sql = mysqli_real_escape_string($conn, $filter_center);
    $query = $query . " AND department_center='$center_sql'";
}

if ($filter_cat != "") {
    $cat_sql = mysqli_real_escape_string($conn, $filter_cat);
    $query = $query . " AND category='$cat_sql'";
}

$query = $query . " ORDER BY schedule_date, start_time, id DESC";
$rows = mysqli_query($conn, $query);

$staff = mysqli_query(
    $conn,
    "SELECT id, name FROM users WHERE role='it_staff' ORDER BY name"
);

include "../includes/header.php";
?><h1>IT Staff Schedule</h1><p class="sub">Admin can create and manage IT Staff schedules. Staff can only view their assigned schedule.</p>
<?php if($message) {
?><div class="done"><?php echo htmlspecialchars($message);
?></div><?php 
} if($error) {
?><div class="error"><?php echo htmlspecialchars($error);
?></div><?php 
}
?><div class="card form-card"><h3>Add Schedule</h3><form method="POST"><div class="two">
<div><label>IT Staff *</label><select name="user_id"><option value="">Select IT Staff</option><?php while($u=mysqli_fetch_assoc($staff)) {
?><option value="<?php echo $u['id'];
?>"><?php echo htmlspecialchars($u['name']);
?></option><?php 
}
?></select></div>
<div><label>IT Center / Department *</label><select name="department_center"><option value="">Select IT Center</option><?php foreach($centers as $c) {
?><option><?php echo htmlspecialchars($c);
?></option><?php 
}
?></select></div>
<div><label>Category *</label><select name="category"><option value="">Select Category</option><?php foreach($categories as $c) {
?><option value="<?php echo $c;
?>"><?php echo ucfirst($c);
?></option><?php 
}
?></select></div>
<div><label>Date *</label><input type="date" name="schedule_date"></div><div><label>Start Time *</label><input type="time" name="start_time"></div><div><label>End Time *</label><input type="time" name="end_time"></div><div><label>Task / Duty *</label><input name="task"></div><div><label>Location *</label><input name="location"></div><div class="full"><label>Remarks</label><textarea name="remarks"></textarea></div></div><button name="save">Add Schedule</button></form></div>
<div class="card form-card"><h3>Filter Schedule</h3><form method="GET"><div class="two"><div><label>IT Center / Department</label><select name="center"><option value="">All IT Centers</option><?php foreach($centers as $c) {
?><option value="<?php echo htmlspecialchars($c);
?>" <?php if($filter_center===$c)echo 'selected';
?>><?php echo htmlspecialchars($c);
?></option><?php 
}
?></select></div><div><label>Category</label><select name="category"><option value="">All Categories</option><?php foreach($categories as $c) {
?><option value="<?php echo $c;
?>" <?php if($filter_cat===$c)echo 'selected';
?>><?php echo ucfirst($c);
?></option><?php 
}
?></select></div></div><button>Apply Filters</button> <a class="clear-filter" href="it_schedule.php">Clear</a></form></div>
<div class="card"><div class="table-heading"><h3>IT Staff Schedule Records</h3></div><div class="table-responsive table-wrap"><table class="list"><tr><th>Staff</th><th>Date</th><th>Time</th><th>IT Center</th><th>Category</th><th>Task</th><th>Location</th><th>Action</th></tr><?php if(!$rows||mysqli_num_rows($rows)==0) {
?><tr><td colspan="8" class="empty">No schedule records available.</td></tr><?php 
} else {
    while($r=mysqli_fetch_assoc($rows)) {
?><tr><td><?php $staff_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM users WHERE id='" . $r["user_id"] . "' LIMIT 1")); echo htmlspecialchars($staff_info ? $staff_info["name"] : "Unknown");
?></td><td><?php echo htmlspecialchars($r['schedule_date']);
?></td><td><?php echo htmlspecialchars(date('h:i A', strtotime($r['start_time'])));
?> - <?php echo htmlspecialchars(date('h:i A', strtotime($r['end_time'])));
?></td><td><?php echo htmlspecialchars($r['department_center']);
?></td><td><?php echo ucfirst(htmlspecialchars($r['category']));
?></td><td><?php echo htmlspecialchars($r['task']);
?></td><td><?php echo htmlspecialchars($r['location']);
?></td><td><a class="edit-link action-edit" href="it_schedule.php?delete=<?php echo $r['id'];
?>" onclick="return confirm('Delete this schedule?');">Delete</a></td></tr><?php 
    }
}
?></table></div></div>
<?php include "../includes/footer.php";
?>