<?php
include "../connection.php";
include "../check_login.php";
if ($role != "teacher") {
    header("location: ../login.php");
    exit();
}
$page_title = "Personal / Family / Emergency";
$message = "";
$personal = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM personal_details WHERE user_id='$user_id'"));
$family = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM family_info WHERE user_id='$user_id'"));
$emergency = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM emergency_contact WHERE user_id='$user_id'"));
if (isset($_POST["save-btn"]) && !$personal && !$family && !$emergency) {
    $religion=$_POST["religion"];
    $blood_group=$_POST["blood_group"];
    $nationality=$_POST["nationality"];
    $domicile=$_POST["domicile"];
    $cnic=$_POST["cnic"];
    $address=$_POST["address"];
    $father_name=$_POST["father_name"];
    $father_cnic=$_POST["father_cnic"];
    $father_occupation=$_POST["father_occupation"];
    $father_contact=$_POST["father_contact"];
    $mother_name=$_POST["mother_name"];
    $guardian_name=$_POST["guardian_name"];
    $guardian_contact=$_POST["guardian_contact"];
    $contact_name=$_POST["contact_name"];
    $relation=$_POST["relation"];
    $phone=$_POST["phone"];
    $alt_phone=$_POST["alt_phone"];
    $emergency_address=$_POST["emergency_address"];
    mysqli_query($conn, "INSERT INTO personal_details (user_id,religion,blood_group,nationality,domicile,cnic,address) VALUES ('$user_id','$religion','$blood_group','$nationality','$domicile','$cnic','$address')");
    mysqli_query($conn, "INSERT INTO family_info (user_id,father_name,father_cnic,father_occupation,father_contact,mother_name,guardian_name,guardian_contact) VALUES ('$user_id','$father_name','$father_cnic','$father_occupation','$father_contact','$mother_name','$guardian_name','$guardian_contact')");
    mysqli_query($conn, "INSERT INTO emergency_contact (user_id,contact_name,relation,phone,alt_phone,address) VALUES ('$user_id','$contact_name','$relation','$phone','$alt_phone','$emergency_address')");
    $message="Personal, family and emergency information added successfully.";
    $personal=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM personal_details WHERE user_id='$user_id'"));
    $family=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM family_info WHERE user_id='$user_id'"));
    $emergency=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM emergency_contact WHERE user_id='$user_id'"));
}
include "../includes/header.php";
?>
<h1>Personal, Family & Emergency</h1><p class="sub">Add the teacher's main personal information in one place.</p>
<?php if($message!=""){ ?><div class="done"><?php echo $message; ?></div><?php } ?>
<div class="card form-card">
<?php if($personal || $family || $emergency){ ?><div class="note">Information is already saved. Only Admin can change it.</div>
<table><tr><th>Religion</th><td><?php echo $personal["religion"]; ?></td></tr><tr><th>Domicile</th><td><?php echo $personal["domicile"]; ?></td></tr><tr><th>Father</th><td><?php echo $family["father_name"]; ?></td></tr><tr><th>Guardian</th><td><?php echo $family["guardian_name"]; ?></td></tr><tr><th>Emergency Contact</th><td><?php echo $emergency["contact_name"]; ?></td></tr><tr><th>Emergency Phone</th><td><?php echo $emergency["phone"]; ?></td></tr></table>
<?php } else { ?>
<form method="POST"><h3>Personal details</h3><div class="two"><div><label>Religion</label><select name="religion" required><option value="">Select Religion</option><option>Islam</option><option>Christian</option><option>Hindu</option><option>Sikh</option><option>Other</option></select></div><div><label>Blood Group</label><select name="blood_group" required><option value="">Select</option><option value="A+">A+</option>
<option value="A-">A-</option>
<option value="B+">B+</option>
<option value="B-">B-</option>
<option value="AB+">AB+</option>
<option value="AB-">AB-</option>
<option value="O+">O+</option>
<option value="O-">O-</option></select></div><div><label>Nationality</label><input name="nationality" required></div><div><label>Domicile</label><select name="domicile" required><option value="">Select City</option><option value="Attock">Attock</option><option value="Islamabad">Islamabad</option><option value="Rawalpindi">Rawalpindi</option><option value="Lahore">Lahore</option><option value="Karachi">Karachi</option><option value="Peshawar">Peshawar</option><option value="Quetta">Quetta</option><option value="Multan">Multan</option><option value="Faisalabad">Faisalabad</option><option value="Gujranwala">Gujranwala</option><option value="Sialkot">Sialkot</option><option value="Gujrat">Gujrat</option><option value="Jhelum">Jhelum</option><option value="Chakwal">Chakwal</option><option value="Taxila">Taxila</option><option value="Wah Cantt">Wah Cantt</option><option value="Kamra">Kamra</option><option value="Hasan Abdal">Hasan Abdal</option><option value="Hassan Abdal">Hassan Abdal</option><option value="Havelian">Havelian</option><option value="Abbottabad">Abbottabad</option><option value="Haripur">Haripur</option><option value="Mansehra">Mansehra</option><option value="Mardan">Mardan</option><option value="Nowshera">Nowshera</option><option value="Charsadda">Charsadda</option><option value="Swabi">Swabi</option><option value="Mingora">Mingora</option><option value="Saidu Sharif">Saidu Sharif</option><option value="Sargodha">Sargodha</option><option value="Mianwali">Mianwali</option><option value="Bhakkar">Bhakkar</option><option value="Khushab">Khushab</option><option value="Bannu">Bannu</option><option value="Kohat">Kohat</option><option value="Dera Ismail Khan">Dera Ismail Khan</option><option value="Bahawalpur">Bahawalpur</option><option value="Sahiwal">Sahiwal</option><option value="Rahim Yar Khan">Rahim Yar Khan</option><option value="Dera Ghazi Khan">Dera Ghazi Khan</option><option value="Okara">Okara</option><option value="Kasur">Kasur</option><option value="Sheikhupura">Sheikhupura</option><option value="Narowal">Narowal</option><option value="Hafizabad">Hafizabad</option><option value="Wazirabad">Wazirabad</option><option value="Muzaffargarh">Muzaffargarh</option><option value="Hyderabad">Hyderabad</option><option value="Sukkur">Sukkur</option><option value="Larkana">Larkana</option><option value="Nawabshah">Nawabshah</option><option value="Mirpur Khas">Mirpur Khas</option><option value="Thatta">Thatta</option><option value="Gwadar">Gwadar</option><option value="Turbat">Turbat</option><option value="Khuzdar">Khuzdar</option><option value="Muzaffarabad">Muzaffarabad</option><option value="Mirpur AJK">Mirpur AJK</option><option value="Gilgit">Gilgit</option><option value="Skardu">Skardu</option><option value="Chilas">Chilas</option><option value="Chitral">Chitral</option></select></div><div><label>CNIC</label><input name="cnic" required></div><div class="full"><label>Address</label><textarea name="address" required></textarea></div></div>
<h3>Family details</h3><div class="two"><div><label>Father Name</label><input name="father_name" required></div><div><label>Father CNIC</label><input name="father_cnic" required></div><div><label>Father Occupation</label><input name="father_occupation" required></div><div><label>Father Contact</label><input name="father_contact" required></div><div><label>Mother Name</label><input name="mother_name" required></div><div><label>Guardian Name</label><input name="guardian_name" required></div><div class="full"><label>Guardian Contact</label><input name="guardian_contact" required></div></div>
<h3>Emergency details</h3><div class="two"><div><label>Contact Person</label><input name="contact_name" required></div><div><label>Relation</label><input name="relation" required></div><div><label>Phone</label><input name="phone" required></div><div><label>Alternate Phone</label><input name="alt_phone"></div><div class="full"><label>Address</label><textarea name="emergency_address" required></textarea></div></div><button type="submit" name="save-btn">Save information</button></form>
<?php } ?></div>
<?php include "../includes/footer.php"; ?>
