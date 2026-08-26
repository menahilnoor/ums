<?php
include "../connection.php";
include "../check_login.php";

if ($role != "admin") {
    header("location: ../login.php");
    exit();
}

$page_title = "IT Staff Records";

$centers = array(
    "IT Support Center",
    "Network Operations Center",
    "Software Development Center",
    "Infrastructure & Systems Center",
    "Help Desk Center"
);

$categories = array("hardware", "software", "network");

$center = "";
$category = "";

if (isset($_GET["center"])) {
    $center = trim($_GET["center"]);
}

if (isset($_GET["category"])) {
    $category = trim($_GET["category"]);
}

$staff_rows = array();

$run = mysqli_query($conn, "SELECT * FROM users WHERE role='it_staff' ORDER BY name");

if ($run) {
    while ($staff = mysqli_fetch_assoc($run)) {

        $details = mysqli_fetch_assoc(
            mysqli_query(
                $conn,
                "SELECT * FROM it_staff_details
                 WHERE user_id='" . $staff["id"] . "'
                 LIMIT 1"
            )
        );

        if ($details) {
            $staff["department_center"] = $details["department_center"];
            $staff["category"] = $details["category"];
            $staff["joining_date"] = $details["joining_date"];
            $staff["job_title"] = $details["job_title"];
            $staff["employment_type"] = $details["employment_type"];
        } else {
            $staff["department_center"] = "";
            $staff["category"] = "";
            $staff["joining_date"] = "";
            $staff["job_title"] = "";
            $staff["employment_type"] = "";
        }

        if ($center != "" && $staff["department_center"] != $center) {
            continue;
        }

        if ($category != "" && $staff["category"] != $category) {
            continue;
        }

        $staff_rows[] = $staff;
    }
}

$count_query = mysqli_query($conn, "SELECT id FROM users WHERE role='it_staff'");
$count = mysqli_num_rows($count_query);

include "../includes/header.php";
?>
<h1>IT Staff Records</h1>
<p class="sub">Admin can view, edit and manage complete IT Staff records.</p>

<div class="tiles">
    <div class="tile">
        <span class="num"><?php echo $count;
?></span>
        <span class="cap">IT Staff Registered</span>
    </div>
</div>

<div class="card form-card">
    <h3>Filter IT Staff</h3>

    <form method="GET">
        <div class="two">
            <div>
                <label>IT Center / Department</label>
                <select name="center">
                    <option value="">All IT Centers</option>
                    <?php foreach($centers as $c) {
?>                        <option value="<?php echo htmlspecialchars($c);
?>" <?php if($center == $c) echo "selected";
?>>
                            <?php echo htmlspecialchars($c);
?>                        </option>
                    <?php 
}
?>                </select>
            </div>

            <div>
                <label>Category</label>
                <select name="category">
                    <option value="">All Categories</option>
                    <?php foreach($categories as $c) {
?>                        <option value="<?php echo $c;
?>" <?php if($category == $c) echo "selected";
?>>
                            <?php echo ucfirst($c);
?>                        </option>
                    <?php 
}
?>                </select>
            </div>
        </div>

        <button>Apply Filters</button>
        <a class="clear-filter" href="it_staff_management.php">Clear</a>
    </form>
</div>

<div class="card">
    <div class="table-heading">
        <h3>All IT Staff Names & Records</h3>
        <span class="record-count">
            <?php echo count($staff_rows);
?> shown
        </span>
    </div>

    <div class="table-responsive table-wrap">
        <table class="list">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>IT Center</th>
                <th>Category</th>
                <th>Job Type</th>
                <th>Joining Date</th>
                <th>Action</th>
            </tr>

            <?php if(count($staff_rows) == 0) {
?>                <tr><td colspan="8" class="empty">No IT Staff found.</td></tr>
            <?php 
} else {
?>                <?php $i= 1;
?>                <?php foreach($staff_rows as $staff) {
?>                    <tr>
                        <td><?php echo $i++;
?></td>
                        <td><strong><?php echo htmlspecialchars($staff["name"]);
?></strong></td>
                        <td><?php echo htmlspecialchars($staff["email"]);
?></td>
                        <td><?php echo htmlspecialchars($staff["department_center"]?: "Not assigned");
?></td>
                        <td><?php echo htmlspecialchars($staff["category"]? ucfirst($staff["category"]): "Not assigned");
?></td>
                        <td><?php echo htmlspecialchars($staff["employment_type"]?: "Not added");
?></td>
                        <td><?php echo htmlspecialchars($staff["joining_date"]?: "Not assigned");
?></td>
                        <td class="action">
                            <a class="edit-link action-edit" href="view_user.php?id=<?php echo $staff["id"];
?>">View</a>
                            <a class="edit-link action-edit" href="edit_it_staff.php?id=<?php echo $staff["id"];
?>">Edit Complete IT Staff</a>
                            <a class="edit-link action-edit" href="edit_it_staff.php?id=<?php echo $staff["id"]; ?>#assignment">Job Assignment</a>
                            <a class="edit-link action-edit" href="it_schedule.php?user_id=<?php echo $staff["id"];
?>">IT Staff Schedule</a>
                            <a class="delete-link action-delete" href="delete_user.php?id=<?php echo $staff["id"];
?>" onclick="return confirm('Delete this IT Staff account?');">Delete</a>
                        </td>
                    </tr>
                <?php 
    }
?>            <?php 
}
?>        </table>
    </div>
</div>

<?php include "../includes/footer.php";
?>