<?php
include "../connection.php";
include "../check_login.php";
if ($role != "teacher") {
    header("location: ../login.php");
    exit();
}
$page_title = "Work Experience";
$message = "";
if (isset($_POST["add-btn"])) {
    $organisation = $_POST["organisation"];
    $designation = $_POST["designation"];
    $from_date = $_POST["from_date"];
    $to_date = $_POST["to_date"];
    $details = $_POST["details"];
    $query = "INSERT INTO experiences (user_id, organisation, designation, from_date, to_date, details) VALUES ('$user_id', '$organisation', '$designation', '$from_date', '$to_date', '$details')";
    $run = mysqli_query($conn, $query);
    if ($run) {
        $message = "Work Experience added successfully.";
    }
    else {
        $message = "Could not save work experience.";
    }
}
$query = "SELECT * FROM experiences WHERE user_id='$user_id' ORDER BY id DESC";
$rows = mysqli_query($conn, $query);
include "../includes/header.php";
?>

<h1>Work Experience</h1>
<p class="sub">Add previous work experience.</p>

<?php if ($message != "") { ?>
    <div class="done"><?php echo $message; ?></div>
<?php } ?>

<div class="card">

    <h3>Saved Records</h3>

    <table class="list">

        <tr>
            <th>Organisation</th>
            <th>Designation</th>
            <th>From Date</th>
            <th>To Date</th>
            <th>Details</th>
        </tr>

        <?php if (mysqli_num_rows($rows) == 0) { ?>

            <tr>
                <td colspan="5" class="empty">
                    No records added yet.
                </td>
            </tr>

        <?php } else { ?>

            <?php while ($row = mysqli_fetch_assoc($rows)) { ?>

                <tr>
                    <td><?php echo $row["organisation"]; ?></td>
                    <td><?php echo $row["designation"]; ?></td>
                    <td><?php echo $row["from_date"]; ?></td>
                    <td><?php echo $row["to_date"]; ?></td>
                    <td><?php echo $row["details"]; ?></td>
                </tr>

            <?php } ?>

        <?php } ?>

    </table>

</div>

<div class="card form-card">

    <h3>Experience details</h3>

    <form method="POST">

        <div class="two">
            <div>
                <label>Organisation</label>
                <input type="text" name="organisation" required>
            </div>
            <div>
                <label>Designation</label>
                <input type="text" name="designation" required>
            </div>
            <div>
                <label>From Date</label>
                <input type="date" name="from_date" required>
            </div>
            <div>
                <label>To Date</label>
                <input type="date" name="to_date" required>
            </div>
            <div class="full">
                <label>Details</label>
                <textarea name="details" required></textarea>
            </div>
        </div>

        <button type="submit" name="add-btn">
            Add Record
        </button>

    </form>

</div>

<?php include "../includes/footer.php"; ?>
