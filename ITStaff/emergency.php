<?php
include "../connection.php";
include "../check_login.php";
if($role!="it_staff") {
    header("location: ../login.php");
    exit();
}
$page_title="Emergency Contact";
$message="";
$family=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM emergency_contact WHERE user_id='$user_id'"));
if(isset($_POST["save-btn"])&&!$family) {
    $name=$_POST["contact_name"];
    $relation=$_POST["relation"];
    $phone=$_POST["phone"];
    $alt=$_POST["alt_phone"];
    $address=$_POST["address"];
    $query="INSERT INTO emergency_contact (user_id,contact_name,relation,phone,alt_phone,address) VALUES ('$user_id','$name','$relation','$phone','$alt','$address')";
    if(mysqli_query($conn,$query))$message="Emergency contact saved successfully.";
    else$message="Could not save emergency contact.";
    $family=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM emergency_contact WHERE user_id='$user_id'"));
}
include "../includes/header.php";
?><h1>Emergency Contact</h1><p class="sub">Enter an emergency contact.</p><?php if($message!=""){?><div class="done"><?php echo $message;?></div><?php }?><div class="card form-card"><h3>Emergency Details</h3><?php if($family){?><table><tr><th>Name</th><td><?php echo $family["contact_name"];?></td></tr><tr><th>Relation</th><td><?php echo $family["relation"];?></td></tr><tr><th>Phone</th><td><?php echo $family["phone"];?></td></tr><tr><th>Alternate Phone</th><td><?php echo $family["alt_phone"];?></td></tr><tr><th>Address</th><td><?php echo $family["address"];?></td></tr></table><?php }else{?><form method="POST"><div class="two"><div><label>Contact Name</label><input name="contact_name" required></div><div><label>Relation</label><input name="relation" required></div><div><label>Phone</label><input name="phone" required></div><div><label>Alternate Phone</label><input name="alt_phone"></div><div class="full"><label>Address</label><textarea name="address"></textarea></div></div><button name="save-btn">Save Contact</button></form><?php }?></div><?php include "../includes/footer.php";?>
