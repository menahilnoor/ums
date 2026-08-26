 <?php
include "../connection.php";
include "../check_login.php";
if($role != "admin") {
    header("location: ../login.php");
    exit();
} $id= $_GET["id"];
$query=" SELECT * FROM users WHERE id='$id'";
$run= mysqli_query($conn, $query);
$view_user= mysqli_fetch_assoc($run);
if(! $view_user) {
    header("location: users.php");
    exit();
} $page_title= "View User";
include "../includes/header.php";
?>
<h1>
    <?php echo $view_user["name"];
?></h1>

<p class="sub">
    Complete information for this user.
</p>

<div class="card">

    <h3>
        Basic Account
    </h3>

    <table>

        <tr><th>Name</th><td><?php echo $view_user["name"];
?></td></tr>
        <tr><th>Email</th><td><?php echo $view_user["email"];
?></td></tr>
        <tr><th>Phone</th><td><?php echo $view_user["phone"];
?></td></tr>
        <tr><th>Date of Birth</th><td><?php echo $view_user["dob"];
?></td></tr>
        <tr><th>Role</th><td><?php echo ucfirst($view_user["role"]);
?></td></tr>
        <tr><th>Department</th><td><?php echo $view_user["department"];
?></td></tr>
        <tr><th>Current Semester</th><td><?php echo $view_user["current_semester"];
?></td></tr>
        <tr><th>Main Subject</th><td><?php echo $view_user["main_subject"];
?></td></tr>

    </table>

    <br>

    <a
        class="small-btn"
        href="edit_user.php?id=<?php echo $id;
?>"
    >
        Edit Account
    </a>

</div>

<?php
$query=" SELECT * FROM personal_details WHERE user_id='$id'";
$run= mysqli_query($conn, $query);
$row= mysqli_fetch_assoc($run);
?><div class="card">

    <h3>Personal Information</h3>

    <?php if($row) {
?>        <table>

            <tr><th>Religion</th><td><?php echo $row["religion"];
?></td></tr>
            <tr><th>Blood Group</th><td><?php echo $row["blood_group"];
?></td></tr>
            <tr><th>Nationality</th><td><?php echo $row["nationality"];
?></td></tr>
            <tr><th>Domicile</th><td><?php echo $row["domicile"];
?></td></tr>
            <tr><th>CNIC</th><td><?php echo $row["cnic"];
?></td></tr>
            <tr><th>Address</th><td><?php echo $row["address"];
?></td></tr>

        </table>

        <a class="small-btn" href="edit_personal.php?id=<?php echo $row["id"];
?>">
            Edit
        </a>

    <?php 
} else {
?>        <p class="pending">
            Not added.
        </p>

    <?php 
}
?></div>

<?php
$query=" SELECT * FROM family_info WHERE user_id='$id'";
$run= mysqli_query($conn, $query);
$row= mysqli_fetch_assoc($run);
?><div class="card">

    <h3>Family Information</h3>

    <?php if($row) {
?>        <table>

            <tr><th>Father Name</th><td><?php echo $row["father_name"];
?></td></tr>
            <tr><th>Father CNIC</th><td><?php echo $row["father_cnic"];
?></td></tr>
            <tr><th>Father Occupation</th><td><?php echo $row["father_occupation"];
?></td></tr>
            <tr><th>Father Contact</th><td><?php echo $row["father_contact"];
?></td></tr>
            <tr><th>Mother Name</th><td><?php echo $row["mother_name"];
?></td></tr>
            <tr><th>Guardian Name</th><td><?php echo $row["guardian_name"];
?></td></tr>
            <tr><th>Guardian Contact</th><td><?php echo $row["guardian_contact"];
?></td></tr>

        </table>

        <a class="small-btn" href="edit_family.php?id=<?php echo $row["id"];
?>">
            Edit
        </a>

    <?php 
} else {
?>        <p class="pending">
            Not added.
        </p>

    <?php 
}
?></div>

<?php
$query=" SELECT * FROM qualifications WHERE user_id='$id' ORDER BY id DESC";
$run= mysqli_query($conn, $query);
?><div class="card">

    <h3>Education</h3>

    <table class="list">

        <tr>
            <th>Degree</th>
            <th>Institution</th>
            <th>Study Group</th>
            <th>Specialization</th>
            <th>Year</th>
            <th>Marks / CGPA</th>
            <th>Grade</th>
            <th>Action</th>
        </tr>

        <?php if(mysqli_num_rows($run) == 0) {
?>            <tr>
                <td colspan="6" class="empty">
                    No records.
                </td>
            </tr>

        <?php 
} else {
?>            <?php while($row= mysqli_fetch_assoc($run)) {
?>                <tr>

                    <td><?php echo $row["degree"];
?></td>
                    <td><?php echo $row["institution"];
?></td>
                    <td><?php echo htmlspecialchars((isset($row["study_group"]) ? $row["study_group"] : ""));
?></td>
                    <td><?php echo htmlspecialchars((isset($row["specialization"]) ? $row["specialization"] : ""));
?></td>
                    <td><?php echo htmlspecialchars($row["passing_year"]);
?></td>
                    <td><?php echo htmlspecialchars((isset($row["marks"]) ? $row["marks"] : ""));
?></td>
                    <td><?php echo $row["grade"];
?></td>

                    <td class="action">

                        <a
                            class="edit-link"
                            href="edit_education.php?id=<?php echo $row["id"];
?>"
                        >
                            Edit
                        </a>

                        <a
                            class="delete-link"
                            href="delete_record.php?table=qualifications&id=<?php echo $row["id"];
?>&user=<?php echo $id;
?>"
                            onclick="return confirm('Delete this record?');"
                        >
                            Delete
                        </a>

                    </td>

                </tr>

            <?php 
    }
?>        <?php 
}
?>    </table>

</div>

<?php
$query=" SELECT * FROM courses WHERE user_id='$id' ORDER BY id DESC";
$run= mysqli_query($conn, $query);
?><div class="card">

    <h3>Courses</h3>

    <table class="list">

        <tr>
            <th>Semester</th>
          
            <th>Course Title</th>
            <th>Credit Hours</th>
            <th>Action</th>
        </tr>

        <?php if(mysqli_num_rows($run) == 0) {
?>            <tr>
                <td colspan="5" class="empty">
                    No records.
                </td>
            </tr>

        <?php 
} else {
?>            <?php while($row= mysqli_fetch_assoc($run)) {
?>                <tr>

                    <td><?php echo $row["semester"];
?></td>
                    
                    <td><?php echo $row["course_title"];
?></td>
                    <td><?php echo $row["credit_hours"];
?></td>

                    <td class="action">

                        <a
                            class="edit-link"
                            href="edit_courses.php?id=<?php echo $row["id"];
?>"
                        >
                            Edit
                        </a>

                        <a
                            class="delete-link"
                            href="delete_record.php?table=courses&id=<?php echo $row["id"];
?>&user=<?php echo $id;
?>"
                            onclick="return confirm('Delete this record?');"
                        >
                            Delete
                        </a>

                    </td>

                </tr>

            <?php 
    }
?>        <?php 
}
?>    </table>

</div>

<?php if($view_user["role"] == "student") {
?><?php
$query=" SELECT * FROM residence_info WHERE user_id='$id'";
    $run= mysqli_query($conn, $query);
    $row= mysqli_fetch_assoc($run);
?><div class="card">

    <h3>Residence</h3>

    <?php if($row) {
?>        <table>

            <tr><th>Status</th><td><?php echo $row["residence_status"];
?></td></tr>
            <tr><th>Hostel</th><td><?php echo $row["hostel_name"];
?></td></tr>
            <tr><th>Room</th><td><?php echo $row["room_no"];
?></td></tr>
            <tr><th>Transport</th><td><?php echo $row["transport_used"];
?></td></tr>
            <tr><th>Route</th><td><?php echo $row["transport_route"];
?></td></tr>
            <tr><th>Pick-up Point</th><td><?php echo $row["pick_up_point"];
?></td></tr>

        </table>

        <a class="small-btn" href="edit_residence.php?id=<?php echo $row["id"];
?>">
            Edit
        </a>

    <?php 
    } else {
?>        <p class="pending">Not added.</p>

    <?php 
    }
?></div>

<?php 
}
?>
<?php
$query=" SELECT * FROM emergency_contact WHERE user_id='$id'";
$run= mysqli_query($conn, $query);
$row= mysqli_fetch_assoc($run);
?><div class="card">

    <h3>Emergency Contact</h3>

    <?php if($row) {
?>        <table>

            <tr><th>Contact Name</th><td><?php echo $row["contact_name"];
?></td></tr>
            <tr><th>Relation</th><td><?php echo $row["relation"];
?></td></tr>
            <tr><th>Phone</th><td><?php echo $row["phone"];
?></td></tr>
            <tr><th>Alternate Phone</th><td><?php echo $row["alt_phone"];
?></td></tr>
            <tr><th>Address</th><td><?php echo $row["address"];
?></td></tr>

        </table>

        <a class="small-btn" href="edit_emergency.php?id=<?php echo $row["id"];
?>">
            Edit
        </a>

    <?php 
} else {
?>        <p class="pending">
            Not added.
        </p>

    <?php 
}
?></div>

<?php if($view_user["role"] == "it_staff") {
    $itd=mysqli_fetch_assoc(mysqli_query($conn, " SELECT * FROM it_staff_details WHERE user_id='$id' LIMIT 1"));
    $ite=mysqli_fetch_assoc(mysqli_query($conn, " SELECT * FROM it_staff_education WHERE user_id='$id' LIMIT 1"));
    $itc=mysqli_fetch_assoc(mysqli_query($conn, " SELECT * FROM it_staff_certifications WHERE user_id='$id' LIMIT 1"));
    $itx=mysqli_fetch_assoc(mysqli_query($conn, " SELECT * FROM it_staff_experience WHERE user_id='$id' LIMIT 1"));
    $itp=mysqli_fetch_assoc(mysqli_query($conn, " SELECT * FROM personal_details WHERE user_id='$id' LIMIT 1"));
    $itf=mysqli_fetch_assoc(mysqli_query($conn, " SELECT * FROM family_info WHERE user_id='$id' LIMIT 1"));
    $item=mysqli_fetch_assoc(mysqli_query($conn, " SELECT * FROM emergency_contact WHERE user_id='$id' LIMIT 1"));
?><div class="card"><h3>IT Staff Assignment</h3><table><tr><th>IT Center / Department</th><td><?php echo htmlspecialchars((isset($itd['department_center']) ? $itd['department_center'] : 'Not assigned'));
?></td></tr><tr><th>Category</th><td><?php echo htmlspecialchars(!empty($itd['category'])?ucfirst($itd['category']): 'Not assigned');
?></td></tr><tr><th>Joining Date</th><td><?php echo htmlspecialchars((isset($itd['joining_date']) ? $itd['joining_date'] : 'Not assigned'));
?></td></tr><tr><th>Job Title</th><td><?php echo htmlspecialchars((isset($itd['job_title']) ? $itd['job_title'] : 'Not added'));
?></td></tr><tr><th>Job Type</th><td><?php echo htmlspecialchars((isset($itd['employment_type']) ? $itd['employment_type'] : 'Not added'));
?></td></tr><tr><th>Job Location</th><td><?php echo htmlspecialchars((isset($itd['job_location']) ? $itd['job_location'] : 'Not added'));
?></td></tr><tr><th>Responsibilities</th><td><?php echo nl2br(htmlspecialchars((isset($itd['responsibilities']) ? $itd['responsibilities'] : 'Not added')));
?></td></tr></table></div>
<div class="card"><h3>IT Staff Personal Information</h3><?php if($itp) {
?><table><tr><th>Religion</th><td><?php echo htmlspecialchars($itp['religion']);
?></td></tr><tr><th>Blood Group</th><td><?php echo htmlspecialchars($itp['blood_group']);
?></td></tr><tr><th>Nationality</th><td><?php echo htmlspecialchars($itp['nationality']);
?></td></tr><tr><th>Domicile</th><td><?php echo htmlspecialchars($itp['domicile']);
?></td></tr><tr><th>CNIC</th><td><?php echo htmlspecialchars($itp['cnic']);
?></td></tr><tr><th>Address</th><td><?php echo nl2br(htmlspecialchars($itp['address']));
?></td></tr></table><?php 
    } else {
?><p class="pending">Not added.</p><?php 
    }
?></div>
<div class="card"><h3>IT Staff Education</h3><?php if($ite) {
?><table><tr><th>Degree</th><td><?php echo htmlspecialchars($ite['degree']);
?></td></tr><tr><th>Institution</th><td><?php echo htmlspecialchars($ite['institution']);
?></td></tr><tr><th>Specialization</th><td><?php echo htmlspecialchars($ite['specialization']);
?></td></tr><tr><th>Passing Year</th><td><?php echo htmlspecialchars($ite['passing_year']);
?></td></tr><tr><th>Study Group</th><td><?php echo htmlspecialchars($ite['study_group']);
?></td></tr></table><?php 
    } else {
?><p class="pending">Not added.</p><?php 
    }
?></div>
<div class="card"><h3>IT Staff Certification</h3><?php if($itc) {
?><table><tr><th>Certification</th><td><?php echo htmlspecialchars($itc['certification_name']);
?></td></tr><tr><th>Issued By</th><td><?php echo htmlspecialchars($itc['issued_by']);
?></td></tr><tr><th>Issue Date</th><td><?php echo htmlspecialchars($itc['issue_date']);
?></td></tr><tr><th>Expiry Date</th><td><?php echo htmlspecialchars($itc['expiry_date']);
?></td></tr><tr><th>Credential No.</th><td><?php echo htmlspecialchars($itc['credential_no']);
?></td></tr></table><?php 
    } else {
?><p class="pending">Not added.</p><?php 
    }
?></div>
<div class="card"><h3>IT Staff Experience</h3><?php if($itx) {
?><table><tr><th>Total Experience</th><td><?php echo htmlspecialchars($itx['total_experience']);
?></td></tr><tr><th>Previous Employer</th><td><?php echo htmlspecialchars($itx['previous_employer']);
?></td></tr><tr><th>Previous Job Title</th><td><?php echo htmlspecialchars($itx['previous_job_title']);
?></td></tr><tr><th>Start Date</th><td><?php echo htmlspecialchars($itx['previous_start_date']);
?></td></tr><tr><th>End Date</th><td><?php echo htmlspecialchars($itx['previous_end_date']);
?></td></tr><tr><th>Details</th><td><?php echo nl2br(htmlspecialchars($itx['experience_details']));
?></td></tr></table><?php 
    } else {
?><p class="pending">Not added.</p><?php 
    }
?></div>
<div class="card"><h3>IT Staff Family & Emergency</h3><?php if($itf) {
?><table><tr><th>Father Name</th><td><?php echo htmlspecialchars($itf['father_name']);
?></td></tr><tr><th>Mother Name</th><td><?php echo htmlspecialchars($itf['mother_name']);
?></td></tr><tr><th>Guardian</th><td><?php echo htmlspecialchars($itf['guardian_name']);
?></td></tr></table><?php 
    } else {
?><p class="pending">Family information not added.</p><?php 
    }
?><?php if($item) {
?><table><tr><th>Emergency Contact</th><td><?php echo htmlspecialchars($item['contact_name']);
?></td></tr><tr><th>Relation</th><td><?php echo htmlspecialchars($item['relation']);
?></td></tr><tr><th>Phone</th><td><?php echo htmlspecialchars($item['phone']);
?></td></tr></table><?php 
    }
?></div>
<div class="card"><a class="small-btn" href="edit_it_staff.php?id=<?php echo $id;
?>">Edit Complete IT Staff Record</a></div>
<?php 
}
?><?php if($view_user["role"] == "teacher") {
?><div class="card"><h3>Scheduler Records</h3><?php $sched=mysqli_query($conn, " SELECT * FROM teacher_teaching WHERE teacher_id='$id' ORDER BY semester,id DESC");
?><table class="list"><tr><th>Semester</th><th>Department</th><th>Subject</th><th>Action</th></tr><?php if(!$sched||mysqli_num_rows($sched)==0) {
?><tr><td colspan="4" class="empty">No scheduler records.</td></tr><?php 
    } else {
        while($sr=mysqli_fetch_assoc($sched)) {
?><tr><td><?php echo htmlspecialchars($sr['semester']);
?></td><td><?php echo htmlspecialchars($sr['department']);
?></td><td><?php echo htmlspecialchars($sr['subject']);
?></td><td><a class="edit-link" href="edit_scheduler.php?id=<?php echo $sr['id'];
?>">Edit</a> <a class="delete-link" href="delete_record.php?table=teacher_teaching&id=<?php echo $sr['id'];
?>&user=<?php echo $id;
?>&semester=<?php echo urlencode($sr['semester']);
?>" onclick="return confirm('Delete this schedule record?');">Delete</a></td></tr><?php 
        }
    }
?></table></div>
<div class="card"><h3>Certifications</h3><?php $rows=mysqli_query($conn, " SELECT * FROM teacher_certifications WHERE user_id='$id'");
?><table class="list"><tr><th>Certification</th><th>Issuer</th><th>Issue Date</th><th>Expiry Date</th><th>Action</th></tr><?php while($row=mysqli_fetch_assoc($rows)) {
?><tr><td><?php echo htmlspecialchars($row["certification_name"]);
?></td><td><?php echo htmlspecialchars($row["issued_by"]);
?></td><td><?php echo htmlspecialchars($row["issue_date"]);
?></td><td><?php echo htmlspecialchars($row["expiry_date"]);
?></td><td><a class="edit-link" href="edit_certification.php?id=<?php echo $row["id"];
?>">Edit</a> <a class="delete-link" href="delete_record.php?table=teacher_certifications&id=<?php echo $row["id"];
?>&user=<?php echo $id;
?>" onclick="return confirm('Delete this record?');">Delete</a></td></tr><?php 
    }
?></table></div>
<?php 
}
?><?php if($view_user["role"] == "student") {
?><div class="card">
    <h3>Student Academic Records</h3>
    <p>Semester academic records are managed in a separate Admin section.</p>
    <p>
        <a class="small-btn" href="student_records.php?user_id=<?php echo $id;
?>&semester=Semester%201">
            Open Student Academic Records
        </a>
    </p>
</div>
<?php 
}
?><?php include "../includes/footer.php";
?>