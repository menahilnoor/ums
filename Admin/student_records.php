<?php
include "../connection.php";
include "../check_login.php";

if ($role != "admin") {
    header("location: ../login.php");
    exit();
}

$page_title = "Student Academic Records";

$allowed = array(
    "Semester 1", "Semester 2", "Semester 3", "Semester 4",
    "Semester 5", "Semester 6", "Semester 7", "Semester 8"
);

$departments = array(
    "BS Software Engineering", "BS Computer Science", "BS Artificial Intelligence",
    "BS Computer Engineering", "BS Electrical Engineering", "BS Business Administration",
    "BS Information Technology", "BS Data Science", "BS Cyber Security",
    "BS Mathematics", "BS Physics"
);

$filter_department = "";
$filter_semester = "";
$filter_student = "";

if (isset($_GET["department"])) {
    $filter_department = $_GET["department"];
}

if (isset($_GET["semester"])) {
    $filter_semester = $_GET["semester"];
}

if (isset($_GET["student_id"])) {
    $filter_student = $_GET["student_id"];
}

if (!in_array($filter_semester, $allowed)) {
    $filter_semester = "";
}

if (!in_array($filter_department, $departments)) {
    $filter_department = "";
}

$message = "";

if (isset($_POST["add-btn"])) {

    $student_id = $_POST["student_id"];
    $semester = $_POST["semester"];
    $department = $_POST["department"];
    $subject = $_POST["subject"];
    $cgpa = $_POST["cgpa"];
    $grade = $_POST["grade"];
    $attendance = $_POST["attendance"];
    $remarks = $_POST["remarks"];

    if (in_array($semester, $allowed) && in_array($department, $departments)) {

        $query = "INSERT INTO semester_records
                  (student_id, semester, department, subject, cgpa, grade, attendance, remarks)
                  VALUES
                  ('$student_id', '$semester', '$department', '$subject',
                   '$cgpa', '$grade', '$attendance', '$remarks')";

        if (mysqli_query($conn, $query)) {

            mysqli_query(
                $conn,
                "UPDATE users
                 SET department='$department', current_semester='$semester'
                 WHERE id='$student_id' AND role='student'"
            );

            header(
                "location: student_records.php?department=" .
                urlencode($department) .
                "&semester=" . urlencode($semester) .
                "&student_id=" . urlencode($student_id) .
                "&saved=1"
            );
            exit();

        } else {
            $message = "Could not save the academic record.";
        }
    }
}

if (isset($_GET["saved"])) {
    $message = "Student academic record added successfully.";
}

$students = mysqli_query(
    $conn,
    "SELECT id, name, department, current_semester
     FROM users
     WHERE role='student'
     ORDER BY name"
);

$filter_students = mysqli_query(
    $conn,
    "SELECT id, name, department
     FROM users
     WHERE role='student'
     ORDER BY name"
);

/* Get academic records without JOIN. */
$query = "SELECT * FROM semester_records WHERE id > 0";

if ($filter_department != "") {
    $filter_department_sql = mysqli_real_escape_string($conn, $filter_department);
    $query = $query . " AND department='$filter_department_sql'";
}

if ($filter_semester != "") {
    $filter_semester_sql = mysqli_real_escape_string($conn, $filter_semester);
    $query = $query . " AND semester='$filter_semester_sql'";
}

if ($filter_student != "") {
    $filter_student_sql = (int)$filter_student;
    $query = $query . " AND student_id='$filter_student_sql'";
}

$query = $query . " ORDER BY semester, id DESC";
$rows = mysqli_query($conn, $query);

include "../includes/header.php";
?><h1>Student Academic Records</h1>

<p class="sub">
    Admin manages student academic records. Students can only view their records.
</p>

<?php if($message != "") {
?>    <div class="done">
        <?php echo $message;
?>    </div>

<?php 
}
?><!-- Filter records -->
<div class="card form-card">

    <h3>Find Student Records</h3>

    <p class="note">
        Select Department, Semester and Student to view the required records.
        You can also leave a filter as "All" to see more records.
    </p>

    <form method="GET" class="searchbar">

        <div>
            <label>Department</label>

            <select name="department">

                <option value="">All Departments</option>

                <?php foreach($departments as $department_name) {
?>                    <option
                        value="<?php echo $department_name;
?>"
                        <?php if($filter_department == $department_name) echo "selected";
?>                    >
                        <?php echo $department_name;
?>                    </option>

                <?php 
}
?>            </select>
        </div>

        <div>
            <label>Semester</label>

            <select name="semester">

                <option value="">All Semesters</option>

                <?php foreach($allowed as $s) {
?>                    <option
                        value="<?php echo $s;
?>"
                        <?php if($filter_semester == $s) echo "selected";
?>                    >
                        <?php echo $s;
?>                    </option>

                <?php 
}
?>            </select>
        </div>

        <div>
            <label>Student Name</label>

            <select name="student_id">

                <option value="">All Students</option>

                <?php while($s= mysqli_fetch_assoc($filter_students)) {
?>                    <option
                        value="<?php echo $s["id"];
?>"
                        <?php if($filter_student == $s["id"]) echo "selected";
?>                    >
                        <?php echo $s["name"];
?>                    </option>

                <?php 
}
?>            </select>
        </div>

        <button type="submit">
            Show Records
        </button>

        <a class="clear-link" href="student_records.php">
            Clear
        </a>

    </form>

</div>

<!-- Filtered records -->
<div class="card">

    <h3>Academic Records</h3>

    <table class="list">

        <tr>
            <th>#</th>
            <th>Student Name</th>
            <th>Department</th>
            <th>Semester</th>
            <th>Subject</th>
            <th>CGPA</th>
            <th>Grade</th>
            <th>Attendance</th>
            <th>Remarks</th>
            <th>Action</th>
        </tr>

        <?php if(! $rows || mysqli_num_rows($rows) == 0) {
?>            <tr>
                <td colspan="10" class="empty">
                    No academic records found. Select filters or add a record below.
                </td>
            </tr>

        <?php 
} else {
?>            <?php
$n= 1;
    while($row= mysqli_fetch_assoc($rows)) {
?>                <tr>
                    <td><?php echo $n;
?></td>
                    <td><?php $student_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM users WHERE id='" . $row["student_id"] . "' LIMIT 1")); echo htmlspecialchars($student_info ? $student_info["name"] : "Unknown");
?></td>
                    <td><?php echo $row["department"];
?></td>
                    <td><?php echo $row["semester"];
?></td>
                    <td><?php echo $row["subject"];
?></td>
                    <td><?php echo $row["cgpa"];
?></td>
                    <td><?php echo $row["grade"];
?></td>
                    <td><?php echo $row["attendance"];
?></td>
                    <td><?php echo $row["remarks"];
?></td>
                    <td class="action">

                        <a
                            class="edit-link"
                            href="edit_semester_record.php?id=<?php echo $row["id"];
?>"
                        >
                            Edit
                        </a>

                        <a
                            class="delete-link"
                            href="delete_record.php?table=semester_records&id=<?php echo $row["id"];
?>&user=<?php echo $row["student_id"];
?>"
                            onclick="return confirm('Delete this record?');"
                        >
                            Delete
                        </a>

                    </td>
                </tr>

            <?php
$n++;
    }
?>        <?php 
}
?>    </table>

</div>

<!-- Add record -->
<div class="card form-card">

    <h3>Add Student Academic Record</h3>

    <form method="POST">

        <div class="two">

            <div>
                <label>Student Name</label>

                <select name="student_id" required>

                    <option value="">Select Student</option>

                    <?php while($s= mysqli_fetch_assoc($students)) {
?>                        <option value="<?php echo $s["id"];
?>">
                            <?php echo $s["name"];
?>                        </option>

                    <?php 
}
?>                </select>
            </div>

            <div>
                <label>Department</label>

                <select name="department" required>

                    <option value="">Select Department</option>

                    <?php foreach($departments as $department_name) {
?>                        <option value="<?php echo $department_name;
?>">
                            <?php echo $department_name;
?>                        </option>

                    <?php 
}
?>                </select>
            </div>

            <div>
                <label>Semester</label>

                <select name="semester" required>

                    <option value="">Select Semester</option>

                    <?php foreach($allowed as $s) {
?>                        <option value="<?php echo $s;
?>">
                            <?php echo $s;
?>                        </option>
                    <?php 
}
?>                </select>
            </div>

            <div>
                <label>Subject</label>
                <input type="text" name="subject" required>
            </div>

            <div>
                <label>CGPA</label>
                <input type="text" name="cgpa">
            </div>

            <div>
                <label>Grade</label>

                <select name="grade">
                    <option value="">Select</option>
                    <option>A+</option>
                    <option>A</option>
                    <option>B+</option>
                    <option>B</option>
                    <option>C+</option>
                    <option>C</option>
                    <option>D</option>
                    <option>F</option>
                </select>
            </div>

            <div>
                <label>Attendance</label>
                <input type="text" name="attendance" placeholder="e.g. 90%">
            </div>

            <div class="full">
                <label>Remarks</label>
                <input type="text" name="remarks">
            </div>

        </div>

        <button type="submit" name="add-btn">
            Add Record
        </button>

    </form>

</div>

<?php include "../includes/footer.php";
?>