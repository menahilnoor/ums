<?php
include "../connection.php";
include "../check_login.php";
if($role!="it_staff") {
    header("location: ../login.php");
    exit();
}
$page_title="Personal Information";
$message="";
$run=mysqli_query($conn,"SELECT * FROM it_staff_details WHERE user_id='$user_id'");
$row=mysqli_fetch_assoc($run);
if(isset($_POST["save-btn"])&&!$row) {
    $religion=$_POST["religion"];
    $blood=$_POST["blood_group"];
    $nationality=$_POST["nationality"];
    $domicile=$_POST["domicile"];
    $cnic=$_POST["cnic"];
    $address=$_POST["address"];
    $query="INSERT INTO it_staff_details (user_id,religion,blood_group,nationality,domicile,cnic,address) VALUES ('$user_id','$religion','$blood','$nationality','$domicile','$cnic','$address')";
    if(mysqli_query($conn,$query))$message="Personal information saved successfully.";
    else$message="Could not save information.";
    $run=mysqli_query($conn,"SELECT * FROM it_staff_details WHERE user_id='$user_id'");
    $row=mysqli_fetch_assoc($run);
}
include "../includes/header.php";
?><h1>Personal Information</h1><p class="sub">Enter your personal details. Admin can edit them later.</p><?php if($message!=""){?><div class="done"><?php echo $message;?></div><?php }?><div class="card form-card"><h3>Personal Details</h3><?php if($row&&($row["religion"]!=""||$row["cnic"]!=""||$row["address"]!="")){?><table><tr><th>Religion</th><td><?php echo $row["religion"];?></td></tr><tr><th>Blood Group</th><td><?php echo $row["blood_group"];?></td></tr><tr><th>Nationality</th><td><?php echo $row["nationality"];?></td></tr><tr><th>Domicile</th><td><?php echo $row["domicile"];?></td></tr><tr><th>CNIC</th><td><?php echo $row["cnic"];?></td></tr><tr><th>Address</th><td><?php echo $row["address"];?></td></tr></table><?php }else{?><form method="POST"><div class="two"><div><label>Religion</label><input name="religion" required></div><div><label>Blood Group</label><select name="blood_group" required><option value="">Select</option><option>A+</option><option>A-</option><option>B+</option><option>B-</option><option>AB+</option><option>AB-</option><option>O+</option><option>O-</option></select></div><div><label>Nationality</label><input name="nationality" required></div><div><label>Domicile</label><input name="domicile" required></div><div><label>CNIC</label><input name="cnic" required></div><div class="full"><label>Address</label><textarea name="address" required></textarea></div></div><button name="save-btn">Save Information</button></form><?php }?></div><?php include "../includes/footer.php";?>
