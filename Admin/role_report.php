<?php
include "../connection.php";
include "../check_login.php";
if($role != "admin") {
    header("location: ../login.php");
    exit();
} $page_title= "Role Based Report";
$selected= trim((isset($_GET['role_filter']) ? $_GET['role_filter'] : ''));
$allowed= array('student', 'teacher', 'it_staff');
if(! in_array($selected, $allowed, true)) {
    $selected= '';
} $where= $selected? "role='". mysqli_real_escape_string($conn, $selected). "'": "role IN ('student','teacher','it_staff')";
$rows= mysqli_query($conn, "  SELECT id, name, email, role, department, current_semester FROM users WHERE $where ORDER BY role, name");
// IT staff details are optional for Student/Teacher rows. Do not query the
// IT table for every row because older databases may not have that table yet.
$it_table_exists= false;
$table_check= mysqli_query($conn, "SHOW TABLES LIKE 'it_staff_details'");
if($table_check && mysqli_num_rows($table_check)> 0) {
    $it_table_exists= true;
} include "../includes/header.php";
?><h1>Role Based Report</h1>
<p class="sub">Select Student, Teacher or IT Staff from the dropdown to view the role-based data table.</p>

<div class="card form-card">
    <h3>Select Role</h3>
    <form method="GET">
        <div class="two">
            <div>
                <label>Role</label>
                <select name="role_filter">
                    <option value="">All Roles</option>
                    <option value="student" <?php if($selected === 'student') echo 'selected';
?>>Student</option>
                    <option value="teacher" <?php if($selected === 'teacher') echo 'selected';
?>>Teacher</option>
                    <option value="it_staff" <?php if($selected === 'it_staff') echo 'selected';
?>>IT Staff</option>
                </select>
            </div>
        </div>
        <button type="submit">View Report</button>
    </form>
</div>

<div class="card">
    <div class="table-heading">
        <h3><?php echo $selected? htmlspecialchars(ucwords(str_replace('_', ' ', $selected))): 'All Roles';
?> Report</h3>
        <span class="record-count"><?php echo $rows? mysqli_num_rows($rows): 0;
?> records</span>
    </div>

    <div class="table-responsive table-wrap">
        <table class="list">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Department / IT Center</th>
                <th>Semester</th>
                <th>Category</th>
                <th>Action</th>
            </tr>

            <?php
$i= 1;
if(! $rows || mysqli_num_rows($rows) === 0) {
?>                <tr>
                    <td colspan="8" class="empty">No records found.</td>
                </tr>
            <?php

} else {
    while($r= mysqli_fetch_assoc($rows)) {
        $department= (isset($r['department']) ? $r['department'] : '');
        $semester= (isset($r['current_semester']) ? $r['current_semester'] : '');
        $category= '—';
        // Only access IT staff details when the current user is actually IT staff.
        if($r['role'] === 'it_staff') {
            if($it_table_exists) {
                $uid= (int)$r['id'];
                $it_query= mysqli_query($conn, "  SELECT department_center, category FROM it_staff_details WHERE user_id='$uid' LIMIT 1");
                if($it_query) {
                    $it= mysqli_fetch_assoc($it_query);
                    if($it) {
                        $department= (isset($it['department_center']) ? $it['department_center'] : '');
                        $category= (isset($it['category']) ? $it['category'] : '');
                    }
                }
            } else {
                $department= '';
                $category= '';
            }
        }
?>                <tr>
                    <td><?php echo $i++;
?></td>
                    <td><strong><?php echo htmlspecialchars($r['name']);
?></strong></td>
                    <td><?php echo htmlspecialchars($r['email']);
?></td>
                    <td>
                        <span class="role-badge role-<?php echo htmlspecialchars($r['role']);
?>">
                            <?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $r['role'])));
?>                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($department !== ''? $department: 'Not assigned');
?></td>
                    <td><?php echo htmlspecialchars($semester !== ''? $semester: '—');
?></td>
                    <td><?php echo htmlspecialchars($r['role'] === 'it_staff'?($category !== ''? ucfirst($category): 'Not assigned'): '—');
?></td>
                    <td>
                        <a class="edit-link action-edit" href="view_user.php?id=<?php echo (int)$r['id'];
?>">View</a>
                        <a class="edit-link action-edit" href="edit_user.php?id=<?php echo (int)$r['id'];
?>">Edit</a>
                    </td>
                </tr>
            <?php

    }
}
?>        </table>
    </div>
</div>

<?php include "../includes/footer.php";
?>