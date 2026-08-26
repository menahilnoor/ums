<?php
include "../connection.php";
include "../check_login.php";
if($role!="admin") {
    header("location: it_staff_management.php");
    exit();
} $id=(int)((isset($_GET['id']) ? $_GET['id'] : 0));
$user=mysqli_fetch_assoc(mysqli_query($conn, " SELECT * FROM users WHERE id='$id' AND role='it_staff' LIMIT 1"));
if(!$user) {
    header("location: it_staff_management.php");
    exit();
} $page_title="Edit IT Staff";
$message="";
$error="";
$centers=array("IT Support Center", "Network Operations Center", "Software Development Center", "Infrastructure & Systems Center", "Help Desk Center");
$categories=array("hardware", "software", "network");
$job_types=array("Full Time", "Part Time", "Contract", "Temporary", "Daily Wage", "Internship", "Probation", "Freelance", "Visiting");
$degrees=array("Matric", "Intermediate", "Associate Degree", "BBA", "BS Computer Science", "BS Software Engineering", "BS Information Technology", "BS Computer Engineering", "BS Cyber Security", "BS Data Science", "MS", "MPhil", "PhD", "Other");
$specializations=array("IT Support", "Networking", "System Administration", "Software Development", "Database Administration", "Cyber Security", "Cloud Computing", "Hardware Engineering", "Computer Science", "Information Technology", "Other");
$experience_types=array("No Experience", "Less than 1 year", "1–2 years", "3–5 years", "6–10 years", "10+ years");
$personal=mysqli_fetch_assoc(mysqli_query($conn, " SELECT * FROM personal_details WHERE user_id='$id' LIMIT 1"));
$family=mysqli_fetch_assoc(mysqli_query($conn, " SELECT * FROM family_info WHERE user_id='$id' LIMIT 1"));
$emergency=mysqli_fetch_assoc(mysqli_query($conn, " SELECT * FROM emergency_contact WHERE user_id='$id' LIMIT 1"));
$education=mysqli_fetch_assoc(mysqli_query($conn, " SELECT * FROM it_staff_education WHERE user_id='$id' LIMIT 1"));
$cert=mysqli_fetch_assoc(mysqli_query($conn, " SELECT * FROM it_staff_certifications WHERE user_id='$id' LIMIT 1"));
$exp=mysqli_fetch_assoc(mysqli_query($conn, " SELECT * FROM it_staff_experience WHERE user_id='$id' LIMIT 1"));
$assignment=mysqli_fetch_assoc(mysqli_query($conn, " SELECT * FROM it_staff_details WHERE user_id='$id' LIMIT 1"));
if(isset($_POST['save-btn'])) {
    $name=mysqli_real_escape_string($conn, trim(isset($_POST['name']) ? $_POST['name'] : ''));
    $phone=mysqli_real_escape_string($conn, trim(isset($_POST['phone']) ? $_POST['phone'] : ''));
    $dob=mysqli_real_escape_string($conn, trim(isset($_POST['dob']) ? $_POST['dob'] : ''));
    $religion=mysqli_real_escape_string($conn, trim(isset($_POST['religion']) ? $_POST['religion'] : ''));
    $blood=mysqli_real_escape_string($conn, trim(isset($_POST['blood_group']) ? $_POST['blood_group'] : ''));
    $nationality=mysqli_real_escape_string($conn, trim(isset($_POST['nationality']) ? $_POST['nationality'] : ''));
    $domicile=mysqli_real_escape_string($conn, trim(isset($_POST['domicile']) ? $_POST['domicile'] : ''));
    $cnic=mysqli_real_escape_string($conn, trim(isset($_POST['cnic']) ? $_POST['cnic'] : ''));
    $address=mysqli_real_escape_string($conn, trim(isset($_POST['address']) ? $_POST['address'] : ''));
    $father=mysqli_real_escape_string($conn, trim(isset($_POST['father_name']) ? $_POST['father_name'] : ''));
    $father_cnic=mysqli_real_escape_string($conn, trim(isset($_POST['father_cnic']) ? $_POST['father_cnic'] : ''));
    $father_occ=mysqli_real_escape_string($conn, trim(isset($_POST['father_occupation']) ? $_POST['father_occupation'] : ''));
    $father_contact=mysqli_real_escape_string($conn, trim(isset($_POST['father_contact']) ? $_POST['father_contact'] : ''));
    $mother=mysqli_real_escape_string($conn, trim(isset($_POST['mother_name']) ? $_POST['mother_name'] : ''));
    $guardian=mysqli_real_escape_string($conn, trim(isset($_POST['guardian_name']) ? $_POST['guardian_name'] : ''));
    $guardian_contact=mysqli_real_escape_string($conn, trim(isset($_POST['guardian_contact']) ? $_POST['guardian_contact'] : ''));
    $em_name=mysqli_real_escape_string($conn, trim(isset($_POST['contact_name']) ? $_POST['contact_name'] : ''));
    $relation=mysqli_real_escape_string($conn, trim(isset($_POST['relation']) ? $_POST['relation'] : ''));
    $em_phone=mysqli_real_escape_string($conn, trim(isset($_POST['emergency_phone']) ? $_POST['emergency_phone'] : ''));
    $alt_phone=mysqli_real_escape_string($conn, trim(isset($_POST['alt_phone']) ? $_POST['alt_phone'] : ''));
    $em_address=mysqli_real_escape_string($conn, trim(isset($_POST['emergency_address']) ? $_POST['emergency_address'] : ''));
    $degree=mysqli_real_escape_string($conn, trim(isset($_POST['degree']) ? $_POST['degree'] : ''));
    $institution=mysqli_real_escape_string($conn, trim(isset($_POST['institution']) ? $_POST['institution'] : ''));
    $spec=mysqli_real_escape_string($conn, trim(isset($_POST['specialization']) ? $_POST['specialization'] : ''));
    $year=mysqli_real_escape_string($conn, trim(isset($_POST['passing_year']) ? $_POST['passing_year'] : ''));
    $group=mysqli_real_escape_string($conn, trim(isset($_POST['study_group']) ? $_POST['study_group'] : ''));
    $edu_details=mysqli_real_escape_string($conn, trim(isset($_POST['education_details']) ? $_POST['education_details'] : ''));
    $cert_name=mysqli_real_escape_string($conn, trim(isset($_POST['certification_name']) ? $_POST['certification_name'] : ''));
    $issued_by=mysqli_real_escape_string($conn, trim(isset($_POST['issued_by']) ? $_POST['issued_by'] : ''));
    $issue_date=mysqli_real_escape_string($conn, trim(isset($_POST['issue_date']) ? $_POST['issue_date'] : ''));
    $expiry_date=mysqli_real_escape_string($conn, trim(isset($_POST['expiry_date']) ? $_POST['expiry_date'] : ''));
    $credential=mysqli_real_escape_string($conn, trim(isset($_POST['credential_no']) ? $_POST['credential_no'] : ''));
    $total_exp=mysqli_real_escape_string($conn, trim(isset($_POST['total_experience']) ? $_POST['total_experience'] : ''));
    $prev_emp=mysqli_real_escape_string($conn, trim(isset($_POST['previous_employer']) ? $_POST['previous_employer'] : ''));
    $prev_title=mysqli_real_escape_string($conn, trim(isset($_POST['previous_job_title']) ? $_POST['previous_job_title'] : ''));
    $prev_start=mysqli_real_escape_string($conn, trim(isset($_POST['previous_start_date']) ? $_POST['previous_start_date'] : ''));
    $prev_end=mysqli_real_escape_string($conn, trim(isset($_POST['previous_end_date']) ? $_POST['previous_end_date'] : ''));
    $exp_details=mysqli_real_escape_string($conn, trim(isset($_POST['experience_details']) ? $_POST['experience_details'] : ''));
    $job_title=mysqli_real_escape_string($conn, trim(isset($_POST['job_title']) ? $_POST['job_title'] : ''));
    $job_type=mysqli_real_escape_string($conn, trim(isset($_POST['employment_type']) ? $_POST['employment_type'] : ''));
    $job_location=mysqli_real_escape_string($conn, trim(isset($_POST['job_location']) ? $_POST['job_location'] : ''));
    $responsibilities=mysqli_real_escape_string($conn, trim(isset($_POST['responsibilities']) ? $_POST['responsibilities'] : ''));
    $center=mysqli_real_escape_string($conn, trim(isset($_POST['department_center']) ? $_POST['department_center'] : ''));
    $category=mysqli_real_escape_string($conn, trim(isset($_POST['category']) ? $_POST['category'] : ''));
    $joining=mysqli_real_escape_string($conn, trim(isset($_POST['joining_date']) ? $_POST['joining_date'] : ''));
    $password= "";
    if(isset($_POST["password"])) {
        $password= mysqli_real_escape_string($conn, $_POST["password"]);
    }

    $dob_sql = "NULL";
    if($dob != "") {
        $dob_sql = "'$dob'";
    }

    if($password != "") {
        mysqli_query($conn, "UPDATE users SET name='$name', phone='$phone', dob=$dob_sql, password='$password' WHERE id='$id'");
    } else {
        mysqli_query($conn, "UPDATE users SET name='$name', phone='$phone', dob=$dob_sql WHERE id='$id'");
    }
    if($personal) {
        mysqli_query($conn, " UPDATE personal_details SET religion='$religion',blood_group='$blood',nationality='$nationality',domicile='$domicile',cnic='$cnic',address='$address' WHERE user_id='$id'");
    } else if($religion!==''||$blood!==''||$nationality!==''||$domicile!==''||$cnic!==''||$address!=='') {
        mysqli_query($conn, " INSERT INTO personal_details(user_id,religion,blood_group,nationality,domicile,cnic,address) VALUES('$id','$religion','$blood','$nationality','$domicile','$cnic','$address')");
    } if($family) {
        mysqli_query($conn, " UPDATE family_info SET father_name='$father',father_cnic='$father_cnic',father_occupation='$father_occ',father_contact='$father_contact',mother_name='$mother',guardian_name='$guardian',guardian_contact='$guardian_contact' WHERE user_id='$id'");
    } else if($father!==''||$mother!==''||$guardian!=='') {
        mysqli_query($conn, " INSERT INTO family_info(user_id,father_name,father_cnic,father_occupation,father_contact,mother_name,guardian_name,guardian_contact) VALUES('$id','$father','$father_cnic','$father_occ','$father_contact','$mother','$guardian','$guardian_contact')");
    } if($emergency) {
        mysqli_query($conn, " UPDATE emergency_contact SET contact_name='$em_name',relation='$relation',phone='$em_phone',alt_phone='$alt_phone',address='$em_address' WHERE user_id='$id'");
    } else if($em_name!==''||$relation!==''||$em_phone!=='') {
        mysqli_query($conn, " INSERT INTO emergency_contact(user_id,contact_name,relation,phone,alt_phone,address) VALUES('$id','$em_name','$relation','$em_phone','$alt_phone','$em_address')");
    } $expirySql=$expiry_date===''?'NULL': " '$expiry_date'";
    $startSql=$prev_start===''?'NULL': " '$prev_start'";
    $endSql=$prev_end===''?'NULL': " '$prev_end'";
    mysqli_query($conn, " INSERT INTO it_staff_education(user_id,degree,institution,specialization,passing_year,study_group,education_details) VALUES('$id','$degree','$institution','$spec','$year','$group','$edu_details') ON DUPLICATE KEY UPDATE degree=VALUES(degree),institution=VALUES(institution),specialization=VALUES(specialization),passing_year=VALUES(passing_year),study_group=VALUES(study_group),education_details=VALUES(education_details)");
    mysqli_query($conn, " INSERT INTO it_staff_certifications(user_id,certification_name,issued_by,issue_date,expiry_date,credential_no) VALUES('$id','$cert_name','$issued_by',".($issue_date===''?'NULL': " '$issue_date'").",$expirySql,'$credential') ON DUPLICATE KEY UPDATE certification_name=VALUES(certification_name),issued_by=VALUES(issued_by),issue_date=VALUES(issue_date),expiry_date=VALUES(expiry_date),credential_no=VALUES(credential_no)");
    mysqli_query($conn, " INSERT INTO it_staff_experienchtmlspecialchars(user_id,total_experience,previous_employer,previous_job_title,previous_start_date,previous_end_date,experience_details) VALUES('$id','$total_exp','$prev_emp','$prev_title',$startSql,$endSql,'$exp_details') ON DUPLICATE KEY UPDATE total_experience=VALUES(total_experience),previous_employer=VALUES(previous_employer),previous_job_title=VALUES(previous_job_title),previous_start_date=VALUES(previous_start_date),previous_end_date=VALUES(previous_end_date),experience_details=VALUES(experience_details)");
    $catSql=$category===''?'NULL': " '$category'";
    $joinSql=$joining===''?'NULL': " '$joining'";
    $sql="INSERT INTO it_staff_details(user_id,job_title,employment_type,job_location,responsibilities,department_center,category,joining_date,religion,blood_group,nationality,domicile,cnic,address,highest_qualification,institution,specialization,graduation_year,study_group,education_details,certification_name,certification_body,issue_date,expiry_date,credential_no,total_experience,previous_employer,previous_job_title,previous_start_date,previous_end_date,experience_details) VALUES('$id','$job_title','$job_type','$job_location','$responsibilities','$center',$catSql,$joinSql,'$religion','$blood','$nationality','$domicile','$cnic','$address','$degree','$institution','$spec','$year','$group','$edu_details','$cert_name','$issued_by',".($issue_date===''?'NULL': " '$issue_date'").",$expirySql,'$credential','$total_exp','$prev_emp','$prev_title',$startSql,$endSql,'$exp_details') ON DUPLICATE KEY UPDATE job_title=VALUES(job_title),employment_type=VALUES(employment_type),job_location=VALUES(job_location),responsibilities=VALUES(responsibilities),department_center=VALUES(department_center),category=VALUES(category),joining_date=VALUES(joining_date),religion=VALUES(religion),blood_group=VALUES(blood_group),nationality=VALUES(nationality),domicile=VALUES(domicile),cnic=VALUES(cnic),address=VALUES(address),highest_qualification=VALUES(highest_qualification),institution=VALUES(institution),specialization=VALUES(specialization),graduation_year=VALUES(graduation_year),study_group=VALUES(study_group),education_details=VALUES(education_details),certification_name=VALUES(certification_name),certification_body=VALUES(certification_body),issue_date=VALUES(issue_date),expiry_date=VALUES(expiry_date),credential_no=VALUES(credential_no),total_experience=VALUES(total_experience),previous_employer=VALUES(previous_employer),previous_job_title=VALUES(previous_job_title),previous_start_date=VALUES(previous_start_date),previous_end_date=VALUES(previous_end_date),experience_details=VALUES(experience_details)";
    if(mysqli_query($conn, $sql)) {
        $message="IT Staff record updated successfully.";
    } else {
        $error="Could not update IT Staff record: ".mysqli_error($conn);
    } $user=mysqli_fetch_assoc(mysqli_query($conn, " SELECT * FROM users WHERE id='$id'"));
    $personal=mysqli_fetch_assoc(mysqli_query($conn, " SELECT * FROM personal_details WHERE user_id='$id' LIMIT 1"));
    $family=mysqli_fetch_assoc(mysqli_query($conn, " SELECT * FROM family_info WHERE user_id='$id' LIMIT 1"));
    $emergency=mysqli_fetch_assoc(mysqli_query($conn, " SELECT * FROM emergency_contact WHERE user_id='$id' LIMIT 1"));
    $education=mysqli_fetch_assoc(mysqli_query($conn, " SELECT * FROM it_staff_education WHERE user_id='$id' LIMIT 1"));
    $cert=mysqli_fetch_assoc(mysqli_query($conn, " SELECT * FROM it_staff_certifications WHERE user_id='$id' LIMIT 1"));
    $exp=mysqli_fetch_assoc(mysqli_query($conn, " SELECT * FROM it_staff_experience WHERE user_id='$id' LIMIT 1"));
    $assignment=mysqli_fetch_assoc(mysqli_query($conn, " SELECT * FROM it_staff_details WHERE user_id='$id' LIMIT 1"));
} include "../includes/header.php";
?><h1>Edit IT Staff</h1><p class="sub">Admin has full edit access. IT Staff normally enters the profile sections themselves; Admin controls the Job / Assignment section.</p>
<?php if($message) {
?><div class="done"><?php echo htmlspecialchars($message);
?></div><?php 
}
?><?php if($error) {
?><div class="error"><?php echo htmlspecialchars($error);
?></div><?php 
}
?><div class="card form-card"><form method="POST">
<h3>Account</h3><div class="two"><div><label>Name</label><input name="name" value="<?php echo htmlspecialchars($user['name']);
?>" required></div><div><label>Email</label><input value="<?php echo htmlspecialchars($user['email']);
?>" disabled></div><div><label>Password</label><input type="text" name="password" placeholder="Leave blank to keep current password"></div><div><label>Phone</label><input name="phone" value="<?php echo htmlspecialchars($user['phone']);
?>"></div><div><label>Date of Birth</label><input type="date" name="dob" value="<?php echo htmlspecialchars($user['dob']);
?>"></div></div>
<h3>Personal Information</h3><div class="two"><div><label>Religion</label><select name="religion"><option value="">Select</option><?php foreach(array('Islam', 'Christian', 'Hindu', 'Sikh', 'Other') as $x) {
?><option <?php if(((isset($personal['religion']) ? $personal['religion'] : ''))===$x)echo 'selected';
?>><?php echo $x;
?></option><?php 
}
?></select></div><div><label>Blood Group</label><input name="blood_group" value="<?php echo htmlspecialchars((isset($personal['blood_group']) ? $personal['blood_group'] : ''));
?>"></div><div><label>Nationality</label><input name="nationality" value="<?php echo htmlspecialchars((isset($personal['nationality']) ? $personal['nationality'] : ''));
?>"></div><div><label>Domicile</label><input name="domicile" value="<?php echo htmlspecialchars((isset($personal['domicile']) ? $personal['domicile'] : ''));
?>"></div><div><label>CNIC</label><input name="cnic" value="<?php echo htmlspecialchars((isset($personal['cnic']) ? $personal['cnic'] : ''));
?>"></div><div class="full"><label>Address</label><textarea name="address"><?php echo htmlspecialchars((isset($personal['address']) ? $personal['address'] : ''));
?></textarea></div></div>
<h3>Family Information</h3><div class="two"><div><label>Father Name</label><input name="father_name" value="<?php echo htmlspecialchars((isset($family['father_name']) ? $family['father_name'] : ''));
?>"></div><div><label>Father CNIC</label><input name="father_cnic" value="<?php echo htmlspecialchars((isset($family['father_cnic']) ? $family['father_cnic'] : ''));
?>"></div><div><label>Father Occupation</label><input name="father_occupation" value="<?php echo htmlspecialchars((isset($family['father_occupation']) ? $family['father_occupation'] : ''));
?>"></div><div><label>Father Contact</label><input name="father_contact" value="<?php echo htmlspecialchars((isset($family['father_contact']) ? $family['father_contact'] : ''));
?>"></div><div><label>Mother Name</label><input name="mother_name" value="<?php echo htmlspecialchars((isset($family['mother_name']) ? $family['mother_name'] : ''));
?>"></div><div><label>Guardian Name</label><input name="guardian_name" value="<?php echo htmlspecialchars((isset($family['guardian_name']) ? $family['guardian_name'] : ''));
?>"></div><div class="full"><label>Guardian Contact</label><input name="guardian_contact" value="<?php echo htmlspecialchars((isset($family['guardian_contact']) ? $family['guardian_contact'] : ''));
?>"></div></div>
<h3>Emergency Information</h3><div class="two"><div><label>Contact Name</label><input name="contact_name" value="<?php echo htmlspecialchars((isset($emergency['contact_name']) ? $emergency['contact_name'] : ''));
?>"></div><div><label>Relation</label><input name="relation" value="<?php echo htmlspecialchars((isset($emergency['relation']) ? $emergency['relation'] : ''));
?>"></div><div><label>Phone</label><input name="emergency_phone" value="<?php echo htmlspecialchars((isset($emergency['phone']) ? $emergency['phone'] : ''));
?>"></div><div><label>Alternate Phone</label><input name="alt_phone" value="<?php echo htmlspecialchars((isset($emergency['alt_phone']) ? $emergency['alt_phone'] : ''));
?>"></div><div class="full"><label>Address</label><textarea name="emergency_address"><?php echo htmlspecialchars((isset($emergency['address']) ? $emergency['address'] : ''));
?></textarea></div></div>
<h3>Education</h3><div class="two"><div><label>Degree / Qualification</label><select name="degree"><option value="">Select Degree</option><?php foreach($degrees as $x) {
?><option <?php if(((isset($education['degree']) ? $education['degree'] : ''))===$x)echo 'selected';
?>><?php echo htmlspecialchars($x);
?></option><?php 
}
?></select></div><div><label>Institution</label><input name="institution" value="<?php echo htmlspecialchars((isset($education['institution']) ? $education['institution'] : ''));
?>"></div><div><label>Specialization</label><select name="specialization"><option value="">Select Specialization</option><?php foreach($specializations as $x) {
?><option <?php if(((isset($education['specialization']) ? $education['specialization'] : ''))===$x)echo 'selected';
?>><?php echo htmlspecialchars($x);
?></option><?php 
}
?></select></div><div><label>Passing Year</label><input type="number" name="passing_year" value="<?php echo htmlspecialchars((isset($education['passing_year']) ? $education['passing_year'] : ''));
?>"></div><div><label>Study Group</label><input name="study_group" value="<?php echo htmlspecialchars((isset($education['study_group']) ? $education['study_group'] : ''));
?>"></div><div class="full"><label>Education Details</label><textarea name="education_details"><?php echo htmlspecialchars((isset($education['education_details']) ? $education['education_details'] : ''));
?></textarea></div></div>
<h3>Certification</h3><div class="two"><div><label>Certification Name</label><input name="certification_name" value="<?php echo htmlspecialchars((isset($cert['certification_name']) ? $cert['certification_name'] : ''));
?>"></div><div><label>Issued By</label><input name="issued_by" value="<?php echo htmlspecialchars((isset($cert['issued_by']) ? $cert['issued_by'] : ''));
?>"></div><div><label>Issue Date</label><input type="date" name="issue_date" value="<?php echo htmlspecialchars((isset($cert['issue_date']) ? $cert['issue_date'] : ''));
?>"></div><div><label>Expiry Date</label><input type="date" name="expiry_date" value="<?php echo htmlspecialchars((isset($cert['expiry_date']) ? $cert['expiry_date'] : ''));
?>"></div><div class="full"><label>Credential No.</label><input name="credential_no" value="<?php echo htmlspecialchars((isset($cert['credential_no']) ? $cert['credential_no'] : ''));
?>"></div></div>
<h3>Experience</h3><div class="two"><div><label>Total IT Experience</label><select name="total_experience"><option value="">Select Experience</option><?php foreach($experience_types as $x) {
?><option <?php if(((isset($exp['total_experience']) ? $exp['total_experience'] : ''))===$x)echo 'selected';
?>><?php echo htmlspecialchars($x);
?></option><?php 
}
?></select></div><div><label>Previous Employer</label><input name="previous_employer" value="<?php echo htmlspecialchars((isset($exp['previous_employer']) ? $exp['previous_employer'] : ''));
?>"></div><div><label>Previous Job Title</label><input name="previous_job_title" value="<?php echo htmlspecialchars((isset($exp['previous_job_title']) ? $exp['previous_job_title'] : ''));
?>"></div><div><label>Previous Start Date</label><input type="date" name="previous_start_date" value="<?php echo htmlspecialchars((isset($exp['previous_start_date']) ? $exp['previous_start_date'] : ''));
?>"></div><div><label>Previous End Date</label><input type="date" name="previous_end_date" value="<?php echo htmlspecialchars((isset($exp['previous_end_date']) ? $exp['previous_end_date'] : ''));
?>"></div><div class="full"><label>Experience Details</label><textarea name="experience_details"><?php echo htmlspecialchars((isset($exp['experience_details']) ? $exp['experience_details'] : ''));
?></textarea></div></div>
<h3>Admin Job / Assignment</h3><div class="note">Only Admin controls these fields.</div><div class="two"><div><label>IT Center / Department</label><select name="department_center"><option value="">Select IT Center</option><?php foreach($centers as $x) {
?><option <?php if(((isset($assignment['department_center']) ? $assignment['department_center'] : ''))===$x)echo 'selected';
?>><?php echo htmlspecialchars($x);
?></option><?php 
}
?></select></div><div><label>Category</label><select name="category"><option value="">Select Category</option><?php foreach($categories as $x) {
?><option value="<?php echo $x;
?>" <?php if(((isset($assignment['category']) ? $assignment['category'] : ''))===$x)echo 'selected';
?>><?php echo ucfirst($x);
?></option><?php 
}
?></select></div><div><label>Joining Date</label><input type="date" name="joining_date" value="<?php echo htmlspecialchars((isset($assignment['joining_date']) ? $assignment['joining_date'] : ''));
?>"></div><div><label>Job Title</label><input name="job_title" value="<?php echo htmlspecialchars((isset($assignment['job_title']) ? $assignment['job_title'] : ''));
?>"></div><div><label>Job Type</label><select name="employment_type"><option value="">Select Job Type</option><?php foreach($job_types as $x) {
?><option <?php if(((isset($assignment['employment_type']) ? $assignment['employment_type'] : ''))===$x)echo 'selected';
?>><?php echo htmlspecialchars($x);
?></option><?php 
}
?></select></div><div><label>Job Location</label><input name="job_location" value="<?php echo htmlspecialchars((isset($assignment['job_location']) ? $assignment['job_location'] : ''));
?>"></div><div class="full"><label>Responsibilities</label><textarea name="responsibilities"><?php echo htmlspecialchars((isset($assignment['responsibilities']) ? $assignment['responsibilities'] : ''));
?></textarea></div></div>
<button type="submit" name="save-btn">Save IT Staff Record</button>
</form></div>
<?php include "../includes/footer.php";
?>