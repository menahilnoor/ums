<?php
include "../connection.php";
include "../check_login.php";
if($role!="it_staff") {
    header("location: ../login.php");
    exit();
}
$page_title="View Information";
include "../includes/header.php";
$personal=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM personal_details WHERE user_id='$user_id' LIMIT 1"));
$family=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM family_info WHERE user_id='$user_id' LIMIT 1"));
$emergency=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM emergency_contact WHERE user_id='$user_id' LIMIT 1"));
$education=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM it_staff_education WHERE user_id='$user_id' LIMIT 1"));
$cert=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM it_staff_certifications WHERE user_id='$user_id' LIMIT 1"));
$experience=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM it_staff_experience WHERE user_id='$user_id' LIMIT 1"));
?>
<h1>View Information</h1>
<p class="sub">All IT Staff information saved in your account is shown here.</p>

<div class="card"><h3>Basic Account</h3><table>
<tr><th>Name</th><td><?php echo (isset($user['name']) && $user['name'] != '' ? nl2br(htmlspecialchars($user['name'])) : 'Not added yet');?></td></tr>
<tr><th>Email</th><td><?php echo (isset($user['email']) && $user['email'] != '' ? nl2br(htmlspecialchars($user['email'])) : 'Not added yet');?></td></tr>
<tr><th>Phone</th><td><?php echo (isset($user['phone']) && $user['phone'] != '' ? nl2br(htmlspecialchars($user['phone'])) : 'Not added yet');?></td></tr>
<tr><th>Date of Birth</th><td><?php echo (isset($user['dob']) && $user['dob'] != '' ? nl2br(htmlspecialchars($user['dob'])) : 'Not added yet');?></td></tr>
<tr><th>Role</th><td>IT Staff</td></tr>
</table></div>

<div class="card"><h3>Personal / Family / Emergency</h3>
<?php if($personal||$family||$emergency){ ?>
<table>
<tr><th>Religion</th><td><?php echo (isset($personal['religion']) && $personal['religion'] != '' ? nl2br(htmlspecialchars($personal['religion'])) : 'Not added yet');?></td></tr>
<tr><th>Blood Group</th><td><?php echo (isset($personal['blood_group']) && $personal['blood_group'] != '' ? nl2br(htmlspecialchars($personal['blood_group'])) : 'Not added yet');?></td></tr>
<tr><th>Nationality</th><td><?php echo (isset($personal['nationality']) && $personal['nationality'] != '' ? nl2br(htmlspecialchars($personal['nationality'])) : 'Not added yet');?></td></tr>
<tr><th>Domicile</th><td><?php echo (isset($personal['domicile']) && $personal['domicile'] != '' ? nl2br(htmlspecialchars($personal['domicile'])) : 'Not added yet');?></td></tr>
<tr><th>CNIC</th><td><?php echo (isset($personal['cnic']) && $personal['cnic'] != '' ? nl2br(htmlspecialchars($personal['cnic'])) : 'Not added yet');?></td></tr>
<tr><th>Address</th><td><?php echo (isset($personal['address']) && $personal['address'] != '' ? nl2br(htmlspecialchars($personal['address'])) : 'Not added yet');?></td></tr>
<tr><th>Father Name</th><td><?php echo (isset($family['father_name']) && $family['father_name'] != '' ? nl2br(htmlspecialchars($family['father_name'])) : 'Not added yet');?></td></tr>
<tr><th>Father Occupation</th><td><?php echo (isset($family['father_occupation']) && $family['father_occupation'] != '' ? nl2br(htmlspecialchars($family['father_occupation'])) : 'Not added yet');?></td></tr>
<tr><th>Mother Name</th><td><?php echo (isset($family['mother_name']) && $family['mother_name'] != '' ? nl2br(htmlspecialchars($family['mother_name'])) : 'Not added yet');?></td></tr>
<tr><th>Guardian Name</th><td><?php echo (isset($family['guardian_name']) && $family['guardian_name'] != '' ? nl2br(htmlspecialchars($family['guardian_name'])) : 'Not added yet');?></td></tr>
<tr><th>Emergency Contact</th><td><?php echo (isset($emergency['contact_name']) && $emergency['contact_name'] != '' ? nl2br(htmlspecialchars($emergency['contact_name'])) : 'Not added yet');?></td></tr>
<tr><th>Emergency Relation</th><td><?php echo (isset($emergency['relation']) && $emergency['relation'] != '' ? nl2br(htmlspecialchars($emergency['relation'])) : 'Not added yet');?></td></tr>
<tr><th>Emergency Phone</th><td><?php echo (isset($emergency['phone']) && $emergency['phone'] != '' ? nl2br(htmlspecialchars($emergency['phone'])) : 'Not added yet');?></td></tr>
</table>
<?php }else{ ?><p class="pending">Not added.</p><?php } ?>
</div>

<div class="card"><h3>Education</h3><div class="table-responsive table-wrap"><table class="list">
<tr><th>Degree / Qualification</th><th>Institution</th><th>Specialization</th><th>Passing Year</th><th>Study Group</th></tr>
<?php if(!$education){?><tr><td colspan="5" class="empty">No records.</td></tr><?php }else{?><tr><td><?php echo (isset($education['degree']) && $education['degree'] != '' ? nl2br(htmlspecialchars($education['degree'])) : 'Not added yet');?></td><td><?php echo (isset($education['institution']) && $education['institution'] != '' ? nl2br(htmlspecialchars($education['institution'])) : 'Not added yet');?></td><td><?php echo (isset($education['specialization']) && $education['specialization'] != '' ? nl2br(htmlspecialchars($education['specialization'])) : 'Not added yet');?></td><td><?php echo (isset($education['passing_year']) && $education['passing_year'] != '' ? nl2br(htmlspecialchars($education['passing_year'])) : 'Not added yet');?></td><td><?php echo (isset($education['study_group']) && $education['study_group'] != '' ? nl2br(htmlspecialchars($education['study_group'])) : 'Not added yet');?></td></tr><?php }?>
</table></div></div>

<div class="card"><h3>Certification</h3><div class="table-responsive table-wrap"><table class="list">
<tr><th>Certification</th><th>Issued By</th><th>Issue Date</th><th>Expiry Date</th><th>Credential No.</th></tr>
<?php if(!$cert){?><tr><td colspan="5" class="empty">No records.</td></tr><?php }else{?><tr><td><?php echo (isset($cert['certification_name']) && $cert['certification_name'] != '' ? nl2br(htmlspecialchars($cert['certification_name'])) : 'Not added yet');?></td><td><?php echo (isset($cert['issued_by']) && $cert['issued_by'] != '' ? nl2br(htmlspecialchars($cert['issued_by'])) : 'Not added yet');?></td><td><?php echo (isset($cert['issue_date']) && $cert['issue_date'] != '' ? nl2br(htmlspecialchars($cert['issue_date'])) : 'Not added yet');?></td><td><?php echo (isset($cert['expiry_date']) && $cert['expiry_date'] != '' ? nl2br(htmlspecialchars($cert['expiry_date'])) : 'Not added yet');?></td><td><?php echo (isset($cert['credential_no']) && $cert['credential_no'] != '' ? nl2br(htmlspecialchars($cert['credential_no'])) : 'Not added yet');?></td></tr><?php }?>
</table></div></div>

<div class="card"><h3>Experience</h3><div class="table-responsive table-wrap"><table class="list">
<tr><th>Total Experience</th><th>Previous Employer</th><th>Job Title</th><th>Start Date</th><th>End Date</th></tr>
<?php if(!$experience){?><tr><td colspan="5" class="empty">No records.</td></tr><?php }else{?><tr><td><?php echo (isset($experience['total_experience']) && $experience['total_experience'] != '' ? nl2br(htmlspecialchars($experience['total_experience'])) : 'Not added yet');?></td><td><?php echo (isset($experience['previous_employer']) && $experience['previous_employer'] != '' ? nl2br(htmlspecialchars($experience['previous_employer'])) : 'Not added yet');?></td><td><?php echo (isset($experience['previous_job_title']) && $experience['previous_job_title'] != '' ? nl2br(htmlspecialchars($experience['previous_job_title'])) : 'Not added yet');?></td><td><?php echo (isset($experience['previous_start_date']) && $experience['previous_start_date'] != '' ? nl2br(htmlspecialchars($experience['previous_start_date'])) : 'Not added yet');?></td><td><?php echo (isset($experience['previous_end_date']) && $experience['previous_end_date'] != '' ? nl2br(htmlspecialchars($experience['previous_end_date'])) : 'Not added yet');?></td></tr><?php }?>
</table></div></div>

<?php include "../includes/footer.php"; ?>
