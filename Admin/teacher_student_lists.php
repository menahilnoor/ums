<?php
include "../connection.php";
include "../check_login.php";
if($role != "admin") {
    header("location: ../login.php");
    exit();
} $page_title= "Teacher and Student Lists";
$subject= "";
if(isset($_GET["subject"])) {
    $subject= $_GET["subject"];
} $subject_query= mysqli_query($conn, "SELECT DISTINCT main_subject
     FROM users
     WHERE role='teacher'
     AND main_subject IS NOT NULL
     AND main_subject != ''
     ORDER BY main_subject");
$teacher_query= "SELECT * FROM users WHERE role='teacher'";
if($subject != "") {
    $teacher_query .="  AND main_subject='$subject'";
} $teacher_query .= " ORDER BY name";
$teachers= mysqli_query($conn, $teacher_query);
$students= mysqli_query($conn, "SELECT * FROM users
     WHERE role='student'
     ORDER BY name");
$student_count= mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='student'"))["total"];
$teacher_count= mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='teacher'"))["total"];
include "../includes/header.php";
?><h1>Teacher & Student Lists</h1>

<p class="sub">
    A simple list of registered teachers and students.
</p>

<div class="lists-grid">

    <div class="card list-panel">

        <div class="section-title">
            <h3>List of Teachers</h3>
            <span><?php echo $teacher_count;
?> teachers</span>
        </div>

        <form method="GET" class="compact-filter">

            <select name="subject">

                <option value="">
                    All Subjects
                </option>

                <?php while($subject_row= mysqli_fetch_assoc($subject_query)) {
?>                    <option
                        value="<?php echo $subject_row["main_subject"];
?>"
                        <?php if($subject == $subject_row["main_subject"]) echo "selected";
?>                    >
                        <?php echo $subject_row["main_subject"];
?>                    </option>

                <?php 
}
?>            </select>

            <button type="submit">
                Filter
            </button>

        </form>

        <table class="list">

            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Subject</th>
            </tr>

            <?php
$n= 1;
while($teacher= mysqli_fetch_assoc($teachers)) {
?>            <tr>

                <td><?php echo $n;
?></td>

                <td>
                    <?php echo $teacher["name"];
?>                </td>

                <td>
                    <?php echo $teacher["main_subject"];
?>                </td>

            </tr>

            <?php
$n++;
}
?>        </table>

    </div>

    <div class="card list-panel">

        <div class="section-title">

            <h3>List of Students</h3>

            <span><?php echo $student_count;
?> students</span>

        </div>

        <table class="list">

            <tr>
                <th>#</th>
                <th>Student Name</th>
            </tr>

            <?php
$n= 1;
while($student= mysqli_fetch_assoc($students)) {
?>            <tr>

                <td>
                    <?php echo $n;
?>                </td>

                <td>
                    <?php echo $student["name"];
?>                </td>

            </tr>

            <?php
$n++;
}
?>        </table>

    </div>

</div>

<?php include "../includes/footer.php";
?>