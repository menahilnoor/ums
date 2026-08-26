<?php
include "../connection.php";
include "../check_login.php";
if ($role != "it_staff") {
    header("location: ../login.php");
    exit();
}
$page_title="Certification";
$message="";
$error="";
$row=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM it_staff_certifications WHERE user_id='$user_id' LIMIT 1"));
if(isset($_POST['save-btn'])&&!$row) {
    $name=mysqli_real_escape_string($conn,trim((isset($_POST['certification_name']) ? $_POST['certification_name'] : '')));
    $issuer=mysqli_real_escape_string($conn,trim((isset($_POST['issued_by']) ? $_POST['issued_by'] : '')));
    $issue=mysqli_real_escape_string($conn,trim((isset($_POST['issue_date']) ? $_POST['issue_date'] : '')));
    $expiry=mysqli_real_escape_string($conn,trim((isset($_POST['expiry_date']) ? $_POST['expiry_date'] : '')));
    $cred=mysqli_real_escape_string($conn,trim((isset($_POST['credential_no']) ? $_POST['credential_no'] : '')));
    if($name===''||$issuer===''||$issue==='')$error='Please fill all required fields.';
    else {
        $expirySql=$expiry===''?'NULL':"'$expiry'";
        if(mysqli_query($conn,"INSERT INTO it_staff_certifications (user_id,certification_name,issued_by,issue_date,expiry_date,credential_no) VALUES ('$user_id','$name','$issuer','$issue',$expirySql,'$cred')")) {
            $message='Certification added successfully. Only Admin can edit it now.';
            $row=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM it_staff_certifications WHERE user_id='$user_id' LIMIT 1"));
        } else $error='Certification could not be saved. Please run the IT Staff database upgrade SQL first.';
    }
}
include "../includes/header.php"; ?><h1>Certification</h1><p class="sub">Add your certification information once. After submission only Admin can edit or delete it.</p><?php if($message){?><div class="done"><?php echo htmlspecialchars($message);?></div><?php }?><?php if($error){?><div class="error"><?php echo htmlspecialchars($error);?></div><?php }?><div class="card form-card"><?php if($row){?><div class="note"><strong>Certification already submitted.</strong> Only Admin can edit it.</div><table class="list"><tr><th>Certification</th><th>Issued By</th><th>Issue Date</th><th>Expiry Date</th><th>Credential No.</th></tr><tr><td><?php echo htmlspecialchars($row['certification_name']);?></td><td><?php echo htmlspecialchars($row['issued_by']);?></td><td><?php echo htmlspecialchars($row['issue_date']);?></td><td><?php echo htmlspecialchars($row['expiry_date']);?></td><td><?php echo htmlspecialchars($row['credential_no']);?></td></tr></table><?php }else{?><form method="POST"><h3>Certification Details</h3><div class="two"><div><label>Certification Name *</label><input name="certification_name" required></div><div><label>Issued By *</label><input name="issued_by" required></div><div><label>Issue Date *</label><input type="date" name="issue_date" required></div><div><label>Expiry Date</label><input type="date" name="expiry_date"></div><div class="full"><label>Credential / Certificate Number</label><input name="credential_no"></div></div><button type="submit" name="save-btn">Save Certification</button></form><?php }?></div><?php include "../includes/footer.php"; ?>
