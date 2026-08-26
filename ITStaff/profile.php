<?php
include "../connection.php";
include "../check_login.php";
if ($role != "it_staff") {
    header("location: ../login.php");
    exit();
}
$page_title="My Profile";
$message="";
$error="";
$personal=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM personal_details WHERE user_id='$user_id' LIMIT 1"));
$family=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM family_info WHERE user_id='$user_id' LIMIT 1"));
$emergency=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM emergency_contact WHERE user_id='$user_id' LIMIT 1"));
if(isset($_POST['save-btn'])) {
    if(!$personal) {
        $religion=mysqli_real_escape_string($conn, trim(isset($_POST['religion']) ? $_POST['religion'] : ''));
        $blood=mysqli_real_escape_string($conn, trim(isset($_POST['blood_group']) ? $_POST['blood_group'] : ''));
        $nationality=mysqli_real_escape_string($conn, trim(isset($_POST['nationality']) ? $_POST['nationality'] : ''));
        $domicile=mysqli_real_escape_string($conn, trim(isset($_POST['domicile']) ? $_POST['domicile'] : ''));
        $cnic=mysqli_real_escape_string($conn, trim(isset($_POST['cnic']) ? $_POST['cnic'] : ''));
        $address=mysqli_real_escape_string($conn, trim(isset($_POST['address']) ? $_POST['address'] : ''));
        if($religion===''||$blood===''||$nationality===''||$domicile===''||$cnic===''||$address==='')$error='Please complete the Personal Information section.';
        else if(!mysqli_query($conn,"INSERT INTO personal_details(user_id,religion,blood_group,nationality,domicile,cnic,address) VALUES('$user_id','$religion','$blood','$nationality','$domicile','$cnic','$address')"))$error='Personal information could not be saved.';
    }
    if($error===''&&!$family) {
        $father=mysqli_real_escape_string($conn, trim(isset($_POST['father_name']) ? $_POST['father_name'] : ''));
        $father_cnic=mysqli_real_escape_string($conn, trim(isset($_POST['father_cnic']) ? $_POST['father_cnic'] : ''));
        $father_occ=mysqli_real_escape_string($conn, trim(isset($_POST['father_occupation']) ? $_POST['father_occupation'] : ''));
        $father_contact=mysqli_real_escape_string($conn, trim(isset($_POST['father_contact']) ? $_POST['father_contact'] : ''));
        $mother=mysqli_real_escape_string($conn, trim(isset($_POST['mother_name']) ? $_POST['mother_name'] : ''));
        $guardian=mysqli_real_escape_string($conn, trim(isset($_POST['guardian_name']) ? $_POST['guardian_name'] : ''));
        $guardian_contact=mysqli_real_escape_string($conn, trim(isset($_POST['guardian_contact']) ? $_POST['guardian_contact'] : ''));
        if($father===''||$father_cnic===''||$father_occ===''||$father_contact===''||$mother===''||$guardian===''||$guardian_contact==='')$error='Please complete the Family Information section.';
        else if(!mysqli_query($conn,"INSERT INTO family_info(user_id,father_name,father_cnic,father_occupation,father_contact,mother_name,guardian_name,guardian_contact) VALUES('$user_id','$father','$father_cnic','$father_occ','$father_contact','$mother','$guardian','$guardian_contact')"))$error='Family information could not be saved.';
    }
    if($error===''&&!$emergency) {
        $em_name=mysqli_real_escape_string($conn, trim(isset($_POST['contact_name']) ? $_POST['contact_name'] : ''));
        $relation=mysqli_real_escape_string($conn, trim(isset($_POST['relation']) ? $_POST['relation'] : ''));
        $phone=mysqli_real_escape_string($conn, trim(isset($_POST['emergency_phone']) ? $_POST['emergency_phone'] : ''));
        $alt=mysqli_real_escape_string($conn, trim(isset($_POST['alt_phone']) ? $_POST['alt_phone'] : ''));
        $em_address=mysqli_real_escape_string($conn, trim(isset($_POST['emergency_address']) ? $_POST['emergency_address'] : ''));
        if($em_name===''||$relation===''||$phone===''||$em_address==='')$error='Please complete the Emergency Information section.';
        else if(!mysqli_query($conn,"INSERT INTO emergency_contact(user_id,contact_name,relation,phone,alt_phone,address) VALUES('$user_id','$em_name','$relation','$phone','$alt','$em_address')"))$error='Emergency information could not be saved.';
    }
    if($error==='')$message='My Profile information was saved successfully. Completed sections can now only be changed by Admin.';
    $personal=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM personal_details WHERE user_id='$user_id' LIMIT 1"));
    $family=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM family_info WHERE user_id='$user_id' LIMIT 1"));
    $emergency=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM emergency_contact WHERE user_id='$user_id' LIMIT 1"));
}
include "../includes/header.php";
?>
<h1>My Profile</h1>
<p class="sub">Enter your personal, family and emergency information. Each section can be submitted only once; after it is saved, only Admin can edit it.</p>
<?php if($message){?><div class="done"><?php echo htmlspecialchars($message);?></div><?php }?>
<?php if($error){?><div class="error"><?php echo htmlspecialchars($error);?></div><?php }?>

<div class="card form-card">
<form method="POST">
<h3>Personal Information</h3>
<?php if($personal){?><div class="note">Personal Information is already saved. Only Admin can edit it.</div><table class="list"><tr><th>Religion</th><td><?php echo htmlspecialchars($personal['religion']);?></td></tr><tr><th>Blood Group</th><td><?php echo htmlspecialchars($personal['blood_group']);?></td></tr><tr><th>Nationality</th><td><?php echo htmlspecialchars($personal['nationality']);?></td></tr><tr><th>Domicile</th><td><?php echo htmlspecialchars($personal['domicile']);?></td></tr><tr><th>CNIC</th><td><?php echo htmlspecialchars($personal['cnic']);?></td></tr><tr><th>Address</th><td><?php echo nl2br(htmlspecialchars($personal['address']));?></td></tr></table><?php }else{?>
<div class="two"><div><label>Religion *</label><select name="religion"><option value="">Select Religion</option><option>Islam</option><option>Christian</option><option>Hindu</option><option>Sikh</option><option>Other</option></select></div><div><label>Blood Group *</label><select name="blood_group"><option value="">Select Blood Group</option><?php foreach(array("A+","A-","B+","B-","AB+","AB-","O+","O-") as $x){?><option><?php echo $x;?></option><?php }?></select></div><div><label>Nationality *</label><input name="nationality"></div><div><label>Domicile *</label><select name="domicile"><option value="">Select City</option><?php foreach(array("Attock","Islamabad","Rawalpindi","Lahore","Karachi","Peshawar","Quetta","Multan","Faisalabad","Gujranwala","Sialkot","Taxila","Wah Cantt","Kamra","Hasan Abdal","Abbottabad","Haripur","Mardan","Nowshera","Swabi","Sargodha","Bahawalpur","Sahiwal","Muzaffarabad","Gilgit","Other") as $x){?><option><?php echo htmlspecialchars($x);?></option><?php }?></select></div><div><label>CNIC *</label><input name="cnic" placeholder="xxxxx-xxxxxxx-x"></div><div class="full"><label>Address *</label><textarea name="address"></textarea></div></div>
<?php }?>

<h3>Family Information</h3>
<?php if($family){?><div class="note">Family Information is already saved. Only Admin can edit it.</div><table class="list"><tr><th>Father Name</th><td><?php echo htmlspecialchars($family['father_name']);?></td></tr><tr><th>Father CNIC</th><td><?php echo htmlspecialchars($family['father_cnic']);?></td></tr><tr><th>Father Occupation</th><td><?php echo htmlspecialchars($family['father_occupation']);?></td></tr><tr><th>Father Contact</th><td><?php echo htmlspecialchars($family['father_contact']);?></td></tr><tr><th>Mother Name</th><td><?php echo htmlspecialchars($family['mother_name']);?></td></tr><tr><th>Guardian Name</th><td><?php echo htmlspecialchars($family['guardian_name']);?></td></tr><tr><th>Guardian Contact</th><td><?php echo htmlspecialchars($family['guardian_contact']);?></td></tr></table><?php }else{?>
<div class="two"><div><label>Father Name *</label><input name="father_name"></div><div><label>Father CNIC *</label><input name="father_cnic"></div><div><label>Father Occupation *</label><input name="father_occupation"></div><div><label>Father Contact *</label><input name="father_contact"></div><div><label>Mother Name *</label><input name="mother_name"></div><div><label>Guardian Name *</label><input name="guardian_name"></div><div class="full"><label>Guardian Contact *</label><input name="guardian_contact"></div></div>
<?php }?>

<h3>Emergency Information</h3>
<?php if($emergency){?><div class="note">Emergency Information is already saved. Only Admin can edit it.</div><table class="list"><tr><th>Contact Name</th><td><?php echo htmlspecialchars($emergency['contact_name']);?></td></tr><tr><th>Relation</th><td><?php echo htmlspecialchars($emergency['relation']);?></td></tr><tr><th>Phone</th><td><?php echo htmlspecialchars($emergency['phone']);?></td></tr><tr><th>Alternate Phone</th><td><?php echo htmlspecialchars($emergency['alt_phone']);?></td></tr><tr><th>Address</th><td><?php echo nl2br(htmlspecialchars($emergency['address']));?></td></tr></table><?php }else{?>
<div class="two"><div><label>Contact Person *</label><input name="contact_name"></div><div><label>Relation *</label><input name="relation"></div><div><label>Phone *</label><input name="emergency_phone"></div><div><label>Alternate Phone</label><input name="alt_phone"></div><div class="full"><label>Address *</label><textarea name="emergency_address"></textarea></div></div>
<?php }?>

<?php if(!$personal||!$family||!$emergency){?><button type="submit" name="save-btn">Save My Profile</button><?php }else{?><div class="done">All My Profile sections are completed.</div><?php }?>
</form>
</div>
<?php include "../includes/footer.php"; ?>
