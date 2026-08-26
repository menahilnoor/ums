<?php
include "../connection.php";
include "../check_login.php";
if ($role != "teacher") {
    header("location: ../login.php");
    exit();
}
$page_title="Certification";
$message="";
if(isset($_POST["add-btn"])) {
    $name=$_POST["certification_name"];
    $issuer=$_POST["issued_by"];
    $issue=$_POST["issue_date"];
    $expiry=$_POST["expiry_date"];
    $credential=$_POST["credential_no"];
    $run=mysqli_query($conn,"INSERT INTO teacher_certifications (user_id,certification_name,issued_by,issue_date,expiry_date,credential_no) VALUES ('$user_id','$name','$issuer','$issue','$expiry','$credential')");
    if($run) {
        $message="Certification added successfully.";
    } else {
        $message="Could not save certification.";
    }
}
$rows=mysqli_query($conn,"SELECT * FROM teacher_certifications WHERE user_id='$user_id' ORDER BY id DESC");
include "../includes/header.php";
?>
<h1>Certification</h1><p class="sub">Add professional certifications.</p>
<?php if($message!=""){ ?><div class="done"><?php echo $message; ?></div><?php } ?>
<div class="card"><h3>Saved Certifications</h3><table class="list"><tr><th>Certification</th><th>Issued By</th><th>Issue Date</th><th>Expiry Date</th></tr><?php if(mysqli_num_rows($rows)==0){ ?><tr><td colspan="4" class="empty">No certifications added yet.</td></tr><?php }else{ while($row=mysqli_fetch_assoc($rows)){ ?><tr><td><?php echo $row["certification_name"]; ?></td><td><?php echo $row["issued_by"]; ?></td><td><?php echo $row["issue_date"]; ?></td><td><?php echo $row["expiry_date"]; ?></td></tr><?php }} ?></table></div>
<div class="card form-card"><h3>Certification details</h3><form method="POST"><div class="two"><div><label>Certification Name</label><input name="certification_name" required></div><div><label>Issued By</label><input name="issued_by" required></div><div><label>Issue Date</label><input type="date" name="issue_date"></div><div><label>Expiry Date</label><input type="date" name="expiry_date"></div><div class="full"><label>Credential / Certificate No.</label><input name="credential_no"></div></div><button name="add-btn">Add Certification</button></form></div>
<?php include "../includes/footer.php"; ?>
