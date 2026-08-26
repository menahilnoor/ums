<?php
include "../connection.php";
include "../check_login.php";
if($role != "admin") {
    header("location: ../login.php");
    exit();
} $id= $_GET["id"];
$query=" SELECT * FROM teacher_certifications WHERE id='$id'";
$run= mysqli_query($conn, $query);
$row= mysqli_fetch_assoc($run);
if(! $row) {
    header("location: users.php");
    exit();
} $message= "";
if(isset($_POST["save-btn"])) {
    $name= $_POST["certification_name"];
    $issuer= $_POST["issued_by"];
    $issue= $_POST["issue_date"];
    $expiry= $_POST["expiry_date"];
    $credential= $_POST["credential_no"];
    $query=" UPDATE teacher_certifications
              SET certification_name='$name',
                  issued_by='$issuer',
                  issue_date='$issue',
                  expiry_date='$expiry',
                  credential_no='$credential'
              WHERE id='$id'";
    $run= mysqli_query($conn, $query);
    if($run) {
        $message= "Certification updated successfully.";
    } else {
        $message= "Certification could not be updated.";
    } $run= mysqli_query($conn, "  SELECT * FROM teacher_certifications WHERE id='$id'");
    $row= mysqli_fetch_assoc($run);
} $page_title= "Edit Certification";
include "../includes/header.php";
?><h1>Edit Certification</h1>
<p class="sub">Admin can update teacher certification information.</p>

<?php if($message != "") {
?>    <div class="done"><?php echo $message;
?></div>
<?php 
}
?><div class="card form-card">

<form method="POST">

    <div class="two">
        <div>
            <label>Certification Name</label>
            <input type="text" name="certification_name" value="<?php echo $row["certification_name"];
?>" required>
        </div>
        <div>
            <label>Issued By</label>
            <input type="text" name="issued_by" value="<?php echo $row["issued_by"];
?>" required>
        </div>
        <div>
            <label>Issue Date</label>
            <input type="date" name="issue_date" value="<?php echo $row["issue_date"];
?>">
        </div>
        <div>
            <label>Expiry Date</label>
            <input type="date" name="expiry_date" value="<?php echo $row["expiry_date"];
?>">
        </div>
        <div class="full">
            <label>Credential / Certificate No.</label>
            <input type="text" name="credential_no" value="<?php echo $row["credential_no"];
?>">
        </div>
    </div>

    <button type="submit" name="save-btn">Save Changes</button>

</form>

</div>

<?php include "../includes/footer.php";
?>