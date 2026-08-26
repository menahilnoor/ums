<?php
include "../connection.php";
include "../check_login.php";
if ($role != "teacher") {
    header("location: ../login.php");
    exit();
}
$page_title = "View Information";
include "../includes/header.php";
?>

<h1>View Information</h1>
<p class="sub">All teacher information saved in your account.</p>

<div class="card"><h3>Basic Account</h3><table><tr><th>Name</th><td><?php echo $user["name"]; ?></td></tr><tr><th>Email</th><td><?php echo $user["email"]; ?></td></tr><tr><th>Phone</th><td><?php echo $user["phone"]; ?></td></tr><tr><th>Department</th><td><?php echo $user["department"]; ?></td></tr><tr><th>Main Subject</th><td><?php echo $user["main_subject"]; ?></td></tr></table></div>

<?php
$personal=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM personal_details WHERE user_id='$user_id'"));
$family=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM family_info WHERE user_id='$user_id'"));
$emergency=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM emergency_contact WHERE user_id='$user_id'"));
?>
<div class="card"><h3>Personal / Family / Emergency</h3><?php if($personal&&$family&&$emergency){ ?><table><tr><th>Religion</th><td><?php echo $personal["religion"]; ?></td></tr><tr><th>Domicile</th><td><?php echo $personal["domicile"]; ?></td></tr><tr><th>Father</th><td><?php echo $family["father_name"]; ?></td></tr><tr><th>Guardian</th><td><?php echo $family["guardian_name"]; ?></td></tr><tr><th>Emergency</th><td><?php echo $emergency["contact_name"]; ?> - <?php echo $emergency["phone"]; ?></td></tr></table><?php }else{ ?><p class="pending">Not added.</p><?php } ?></div>

<?php $run=mysqli_query($conn,"SELECT * FROM qualifications WHERE user_id='$user_id' ORDER BY id DESC"); ?>
<div class="card"><h3>Education</h3><table class="list"><tr><th>Degree</th><th>Institution</th><th>Year</th></tr><?php if(mysqli_num_rows($run)==0){ ?><tr><td colspan="3" class="empty">No records.</td></tr><?php }else{while($row=mysqli_fetch_assoc($run)){?><tr><td><?php echo $row["degree"]; ?></td><td><?php echo $row["institution"]; ?></td><td><?php echo $row["passing_year"]; ?></td></tr><?php }} ?></table></div>

<?php $run=mysqli_query($conn,"SELECT * FROM teacher_certifications WHERE user_id='$user_id' ORDER BY id DESC"); ?>
<div class="card"><h3>Certification</h3><table class="list"><tr><th>Certification</th><th>Issued By</th><th>Issue Date</th><th>Expiry Date</th></tr><?php if(mysqli_num_rows($run)==0){ ?><tr><td colspan="3" class="empty">No records.</td></tr><?php }else{while($row=mysqli_fetch_assoc($run)){?><tr><td><?php echo $row["certification_name"]; ?></td><td><?php echo $row["issued_by"]; ?></td><td><?php echo $row["issue_date"]; ?></td><td><?php echo $row["expiry_date"]; ?></td></tr><?php }} ?></table></div>


<?php
$run = mysqli_query(
    $conn,
    "SELECT * FROM teacher_teaching
     WHERE teacher_id='$user_id'
     ORDER BY id DESC"
);
?>

<div class="card">

    <h3>Teaching Assignments</h3>

    <table class="list">

        <tr>
            <th>Semester</th>
            <th>Department</th>
            <th>Subject</th>
        </tr>

        <?php if (mysqli_num_rows($run) == 0) { ?>

            <tr>
                <td colspan="3" class="empty">
                    No records.
                </td>
            </tr>

        <?php } else { ?>

            <?php while ($row = mysqli_fetch_assoc($run)) { ?>

                <tr>
                    <td><?php echo $row["semester"]; ?></td>
                    <td><?php echo $row["department"]; ?></td>
                    <td><?php echo $row["subject"]; ?></td>
                </tr>

            <?php } ?>

        <?php } ?>

    </table>

</div>

<?php include "../includes/footer.php"; ?>
