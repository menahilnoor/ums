<?php
include "../connection.php";
include "../check_login.php";
if ($role != "student") {
    header("location: ../login.php");
    exit();
}
$page_title = "View Information";
/*
   Get the current academic information.
   The users table is the first source. If it is empty,
   use the latest Admin academic record.
*/
$display_department = $user["department"];
$display_semester = $user["current_semester"];
$academic_query = mysqli_query(
$conn,
"SELECT *
     FROM semester_records
     WHERE student_id='$user_id'
     AND department IS NOT NULL
     AND department <> ''
     AND semester IS NOT NULL
     AND semester <> ''
     ORDER BY id DESC
     LIMIT 1"
);
$academic = false;
if ($academic_query) {
    $academic = mysqli_fetch_assoc($academic_query);
}
if ($academic) {
    if ($display_department == "") {
        $display_department = $academic["department"];
    }
    if ($display_semester == "") {
        $display_semester = $academic["semester"];
    }
    mysqli_query(
    $conn,
    "UPDATE users
         SET department='$display_department',
             current_semester='$display_semester'
         WHERE id='$user_id'"
    );
}
if ($display_department == "") {
    $display_department = "Not added yet";
}
if ($display_semester == "") {
    $display_semester = "Not added yet";
}
/* Fetch every saved section. */
$personal = mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT * FROM personal_details WHERE user_id='$user_id'"
)
);
$family = mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT * FROM family_info WHERE user_id='$user_id'"
)
);
$education = mysqli_query(
$conn,
"SELECT * FROM qualifications
     WHERE user_id='$user_id'
     ORDER BY id DESC"
);
$courses = mysqli_query(
$conn,
"SELECT * FROM courses
     WHERE user_id='$user_id'
     ORDER BY id DESC"
);
$residence = mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT * FROM residence_info WHERE user_id='$user_id'"
)
);
$emergency = mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT * FROM emergency_contact WHERE user_id='$user_id'"
)
);
$semester_rows = mysqli_query(
$conn,
"SELECT * FROM semester_records
     WHERE student_id='$user_id'
     ORDER BY id DESC"
);
include "../includes/header.php";
?>

<h1>View Information</h1>

<p class="sub">
    All information saved in your account is shown here.
</p>

<div class="card">
    <h3>Academic Account</h3>

    <table>
        <tr>
            <th>Name</th>
            <td><?php echo $user["name"]; ?></td>
        </tr>

        <tr>
            <th>Email</th>
            <td><?php echo $user["email"]; ?></td>
        </tr>

        <tr>
            <th>Phone</th>
            <td><?php echo $user["phone"]; ?></td>
        </tr>

        <tr>
            <th>Date of Birth</th>
            <td><?php echo $user["dob"]; ?></td>
        </tr>

        <tr>
            <th>Department</th>
            <td><?php echo $display_department; ?></td>
        </tr>

        <tr>
            <th>Current Semester</th>
            <td><?php echo $display_semester; ?></td>
        </tr>

        <tr>
            <th>Role</th>
            <td><?php echo ucfirst($user["role"]); ?></td>
        </tr>
    </table>
</div>

<div class="card">
    <h3>My Profile</h3>

    <?php if ($personal || $family || $emergency) { ?>

        <h4>Personal Information</h4>

        <?php if ($personal) { ?>
            <table>
                <tr><th>Religion</th><td><?php echo $personal["religion"]; ?></td></tr>
                <tr><th>Blood Group</th><td><?php echo $personal["blood_group"]; ?></td></tr>
                <tr><th>Nationality</th><td><?php echo $personal["nationality"]; ?></td></tr>
                <tr><th>Domicile</th><td><?php echo $personal["domicile"]; ?></td></tr>
                <tr><th>CNIC</th><td><?php echo $personal["cnic"]; ?></td></tr>
                <tr><th>Address</th><td><?php echo $personal["address"]; ?></td></tr>
            </table>
        <?php } else { ?>
            <p class="pending">Personal information not added.</p>
        <?php } ?>

        <h4>Family Information</h4>

        <?php if ($family) { ?>
            <table>
                <tr><th>Father Name</th><td><?php echo $family["father_name"]; ?></td></tr>
                <tr><th>Father CNIC</th><td><?php echo $family["father_cnic"]; ?></td></tr>
                <tr><th>Father Occupation</th><td><?php echo $family["father_occupation"]; ?></td></tr>
                <tr><th>Father Contact</th><td><?php echo $family["father_contact"]; ?></td></tr>
                <tr><th>Mother Name</th><td><?php echo $family["mother_name"]; ?></td></tr>
                <tr><th>Guardian Name</th><td><?php echo $family["guardian_name"]; ?></td></tr>
                <tr><th>Guardian Contact</th><td><?php echo $family["guardian_contact"]; ?></td></tr>
            </table>
        <?php } else { ?>
            <p class="pending">Family information not added.</p>
        <?php } ?>

        <h4>Emergency Contact</h4>

        <?php if ($emergency) { ?>
            <table>
                <tr><th>Contact Name</th><td><?php echo $emergency["contact_name"]; ?></td></tr>
                <tr><th>Relation</th><td><?php echo $emergency["relation"]; ?></td></tr>
                <tr><th>Phone</th><td><?php echo $emergency["phone"]; ?></td></tr>
                <tr><th>Alternative Phone</th><td><?php echo $emergency["alt_phone"]; ?></td></tr>
                <tr><th>Address</th><td><?php echo $emergency["address"]; ?></td></tr>
            </table>
        <?php } else { ?>
            <p class="pending">Emergency information not added.</p>
        <?php } ?>

    <?php } else { ?>

        <p class="pending">
            My Profile information has not been added yet.
        </p>

    <?php } ?>
</div>

<div class="card">
    <h3>Education</h3>

    <table class="list">
        <tr>
            <th>Degree</th>
            <th>Institution</th>
            <th>Passing Year</th>
            <th>CGPA</th>
            <th>Grade</th>
        </tr>

        <?php if (mysqli_num_rows($education) == 0) { ?>

            <tr>
                <td colspan="5" class="empty">
                    No education records.
                </td>
            </tr>

        <?php } else { ?>

            <?php while ($row = mysqli_fetch_assoc($education)) { ?>

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

<div class="card">
    <h3>Courses</h3>

    <table class="list">
        <tr>
            <th>Semester</th>
            
            <th>Course Title</th>
            <th>Credit Hours</th>
        </tr>

        <?php if (mysqli_num_rows($courses) == 0) { ?>

            <tr>
                <td colspan="4" class="empty">
                    No course records.
                </td>
            </tr>

        <?php } else { ?>

            <?php while ($row = mysqli_fetch_assoc($courses)) { ?>

                <tr>
                    <td><?php echo $row["semester"]; ?></td>
                   
                    <td><?php echo $row["course_title"]; ?></td>
                    <td><?php echo $row["credit_hours"]; ?></td>
                </tr>

            <?php } ?>

        <?php } ?>
    </table>
</div>

<div class="card">
    <h3>Residence</h3>

    <?php if ($residence) { ?>

        <table>
            <tr>
                <th>Residence</th>
                <td><?php echo $residence["residence_status"]; ?></td>
            </tr>

            <?php if ($residence["residence_status"] == "Hosteller") { ?>
                <tr>
                    <th>Hostel Name</th>
                    <td><?php echo $residence["hostel_name"]; ?></td>
                </tr>

                <tr>
                    <th>Room No.</th>
                    <td><?php echo $residence["room_no"]; ?></td>
                </tr>
            <?php } ?>

            <tr>
                <th>University Transport</th>
                <td><?php echo $residence["transport_used"]; ?></td>
            </tr>

            <?php if ($residence["transport_used"] == "Yes") { ?>
                <tr>
                    <th>Transport Route</th>
                    <td><?php echo $residence["transport_route"]; ?></td>
                </tr>

                <tr>
                    <th>Pick-up Point</th>
                    <td><?php echo $residence["pick_up_point"]; ?></td>
                </tr>
            <?php } ?>
        </table>

    <?php } else { ?>

        <p class="pending">Residence information not added.</p>

    <?php } ?>
</div>

<div class="card">
    <h3>Semester Records</h3>

    <table class="list">
        <tr>
            <th>Student</th>
            <th>Department</th>
            <th>Semester</th>
            <th>Subject</th>
            <th>CGPA</th>
            <th>Grade</th>
            <th>Attendance</th>
            <th>Remarks</th>
        </tr>

        <?php if (!$semester_rows || mysqli_num_rows($semester_rows) == 0) { ?>

            <tr>
                <td colspan="8" class="empty">
                    No semester records.
                </td>
            </tr>

        <?php } else { ?>

            <?php while ($row = mysqli_fetch_assoc($semester_rows)) { ?>

                <tr>
                    <td><?php echo $user["name"]; ?></td>
                    <td><?php echo $row["department"]; ?></td>
                    <td><?php echo $row["semester"]; ?></td>
                    <td><?php echo $row["subject"]; ?></td>
                    <td><?php echo $row["cgpa"]; ?></td>
                    <td><?php echo $row["grade"]; ?></td>
                    <td><?php echo $row["attendance"]; ?></td>
                    <td><?php echo $row["remarks"]; ?></td>
                </tr>

            <?php } ?>

        <?php } ?>
    </table>
</div>

<?php include "../includes/footer.php"; ?>