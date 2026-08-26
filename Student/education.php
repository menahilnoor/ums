<?php
include "../connection.php";
include "../check_login.php";
if ($role != "student") {
    header("location: ../login.php");
    exit();
}
$page_title = "Education";
$message = "";
if (isset($_POST["add-btn"])) {
    $degree = $_POST["degree"];
    $institution = $_POST["institution"];
    $passing_year = $_POST["passing_year"];
    $cgpa = $_POST["cgpa"];
    $grade = $_POST["grade"];
    $query = "INSERT INTO qualifications (user_id, degree, institution, passing_year, marks, grade) VALUES ('$user_id', '$degree', '$institution', '$passing_year', '$cgpa', '$grade')";
    $run = mysqli_query($conn, $query);
    if ($run) {
        $message = "Education added successfully.";
    }
    else {
        $message = "Could not save education.";
    }
}
$query = "SELECT * FROM qualifications WHERE user_id='$user_id' ORDER BY id DESC";
$rows = mysqli_query($conn, $query);
include "../includes/header.php";
?>

<h1>Education</h1>
<p class="sub">Add academic qualifications.</p>

<?php if ($message != "") { ?>
    <div class="done"><?php echo $message; ?></div>
<?php } ?>

<div class="card">

    <h3>Saved Records</h3>

    <table class="list">

        <tr>
            <th>Degree</th>
            <th>Institution</th>
            <th>Passing Year</th>
            <th>Marks / CGPA</th>
            <th>Grade</th>
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
                    <td><?php echo $row["degree"]; ?></td>
                    <td><?php echo $row["institution"]; ?></td>
                    <td><?php echo $row["passing_year"]; ?></td>
                    <td><?php echo $row["marks"]; ?></td>
                    <td><?php echo $row["grade"]; ?></td>
                </tr>

            <?php } ?>

        <?php } ?>

    </table>

</div>

<div class="card form-card">

    <h3>Education details</h3>

    <form method="POST">

        <div class="two">
            <div>
                <label>Degree</label>
                <select name="degree" required><option value="">Select Degree</option><option value="BS Software Engineering">BS Software Engineering</option>
<option value="BS Computer Science">BS Computer Science</option>
<option value="BS Artificial Intelligence">BS Artificial Intelligence</option>
<option value="BS Computer Engineering">BS Computer Engineering</option>
<option value="BS Electrical Engineering">BS Electrical Engineering</option>
<option value="BS Business Administration">BS Business Administration</option>
<option value="BS Information Technology">BS Information Technology</option>
<option value="BS Data Science">BS Data Science</option>
<option value="BS Cyber Security">BS Cyber Security</option>
<option value="BBA">BBA</option>
<option value="BS Mathematics">BS Mathematics</option>
<option value="BS Physics">BS Physics</option>
<option value="Other">Other</option></select>
            </div>
            <div>
                <label>Institution</label>
                <input type="text" name="institution" required>
            </div>
            <div>
                <label>Passing Year</label>
                <input type="text" name="passing_year" required>
            </div>
            <div>
                <label>CGPA</label>
                <input type="text" name="cgpa" required>
            </div>
            <div>
                <label>Grade</label>
                <input type="text" name="grade" required>
            </div>
        </div>

        <button type="submit" name="add-btn">
            Add Record
        </button>

    </form>

</div>

<?php include "../includes/footer.php"; ?>
