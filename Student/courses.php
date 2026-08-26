<?php
include "../connection.php";
include "../check_login.php";
if ($role != "student") {
    header("location: ../login.php");
    exit();
}
$page_title = "Courses";
$message = "";
if (isset($_POST["add-btn"])) {
    $semester = $_POST["semester"];
    $course_title = $_POST["course_title"];
    $credit_hours = $_POST["credit_hours"];
    $query = "INSERT INTO courses (user_id, semester, course_title, credit_hours) VALUES ('$user_id', '$semester', '$course_title', '$credit_hours')";
    $run = mysqli_query($conn, $query);
    if ($run) {
        $message = "Courses added successfully.";
    }
    else {
        $message = "Could not save courses.";
    }
}
$query = "SELECT * FROM courses WHERE user_id='$user_id' ORDER BY id DESC";
$rows = mysqli_query($conn, $query);
include "../includes/header.php";
?>

<h1>Courses</h1>
<p class="sub">Add courses.</p>

<?php if ($message != "") { ?>
    <div class="done"><?php echo $message; ?></div>
<?php } ?>

<div class="card">

    <h3>Saved Records</h3>

    <table class="list">

        <tr>
            <th>Semester</th>
            
            <th>Course Title</th>
            <th>Credit Hours</th>
        </tr>

        <?php if (mysqli_num_rows($rows) == 0) { ?>

            <tr>
                <td colspan="4" class="empty">
                    No records added yet.
                </td>
            </tr>

        <?php } else { ?>

            <?php while ($row = mysqli_fetch_assoc($rows)) { ?>

                <tr>
                    <td><?php echo $row["semester"]; ?></td>
                    
                    <td><?php echo $row["course_title"]; ?></td>
                    <td><?php echo $row["credit_hours"]; ?></td>
                </tr>

            <?php } ?>

        <?php } ?>

    </table>

</div>

<div class="card form-card">

    <h3>Course details</h3>

    <form method="POST">

        <div class="two">
            <div>
                <label>Semester</label>
                <select name="semester" required><option value="">Select Semester</option><option value="Semester 1">Semester 1</option>
<option value="Semester 2">Semester 2</option>
<option value="Semester 3">Semester 3</option>
<option value="Semester 4">Semester 4</option>
<option value="Semester 5">Semester 5</option>
<option value="Semester 6">Semester 6</option>
<option value="Semester 7">Semester 7</option>
<option value="Semester 8">Semester 8</option></select>
            </div>
            <div>
                <label>Course Title</label>
                <input type="text" name="course_title" required>
            </div>
            <div>
                <label>Credit Hours</label>
                <input type="text" name="credit_hours" required>
            </div>
        </div>

        <button type="submit" name="add-btn">
            Add Record
        </button>

    </form>

</div>

<?php include "../includes/footer.php"; ?>
