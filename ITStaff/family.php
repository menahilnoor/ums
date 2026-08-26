<?php
include "../connection.php";
include "../check_login.php";
if($role!="it_staff") {
    header("location: ../login.php");
    exit();
}
$page_title="Family Information";
$message="";
$run=mysqli_query($conn,"SELECT * FROM it_staff_details WHERE user_id='$user_id'");
$row=mysqli_fetch_assoc($run);
if(isset($_POST["save-btn"])) {
    $f=$_POST["father_name"];
    $fc=$_POST["father_cnic"];
    $fo=$_POST["father_occupation"];
    $fp=$_POST["father_contact"];
    $m=$_POST["mother_name"];
    $g=$_POST["guardian_name"];
    $gp=$_POST["guardian_contact"];
    $query="INSERT INTO family_info (user_id,father_name,father_cnic,father_occupation,father_contact,mother_name,guardian_name,guardian_contact) VALUES ('$user_id','$f','$fc','$fo','$fp','$m','$g','$gp')";
    if(mysqli_query($conn,$query))$message="Family information saved successfully.";
    else$message="Could not save family information.";
}
$family=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM family_info WHERE user_id='$user_id'"));
include "../includes/header.php";
?><h1>Family Information</h1><p class="sub">Enter your family details.</p><?php if($message!=""){?><div class="done"><?php echo $message;?></div><?php }?><div class="card form-card"><h3>Family Details</h3><?php if($family){?><table><tr><th>Father Name</th><td><?php echo $family["father_name"];?></td></tr><tr><th>Father CNIC</th><td><?php echo $family["father_cnic"];?></td></tr><tr><th>Father Occupation</th><td><?php echo $family["father_occupation"];?></td></tr><tr><th>Father Contact</th><td><?php echo $family["father_contact"];?></td></tr><tr><th>Mother Name</th><td><?php echo $family["mother_name"];?></td></tr><tr><th>Guardian Name</th><td><?php echo $family["guardian_name"];?></td></tr><tr><th>Guardian Contact</th><td><?php echo $family["guardian_contact"];?></td></tr></table><?php }else{?><form method="POST"><div class="two"><div><label>Father Name</label><input name="father_name" required></div><div><label>Father CNIC</label><input name="father_cnic"></div><div><label>Father Occupation</label><input name="father_occupation"></div><div><label>Father Contact</label><input name="father_contact"></div><div><label>Mother Name</label><input name="mother_name"></div><div><label>Guardian Name</label><input name="guardian_name"></div><div><label>Guardian Contact</label><input name="guardian_contact"></div></div><button name="save-btn">Save Information</button></form><?php }?></div><?php include "../includes/footer.php";?>
