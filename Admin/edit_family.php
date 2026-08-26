<?php
include "../connection.php";
include "../check_login.php";
if($role != "admin") {
    header("location: ../login.php");
    exit();
} $id= $_GET["id"];
$query=" SELECT * FROM family_info WHERE id='$id'";
$run= mysqli_query($conn, $query);
$row= mysqli_fetch_assoc($run);
if(! $row) {
    header("location: users.php");
    exit();
} $message= "";
if(isset($_POST["save-btn"])) {
    $father_name= $_POST["father_name"];
    $father_cnic= $_POST["father_cnic"];
    $father_occupation= $_POST["father_occupation"];
    $father_contact= $_POST["father_contact"];
    $mother_name= $_POST["mother_name"];
    $guardian_name= $_POST["guardian_name"];
    $guardian_contact= $_POST["guardian_contact"];
    $query=" UPDATE family_info SET father_name='$father_name', father_cnic='$father_cnic', father_occupation='$father_occupation', father_contact='$father_contact', mother_name='$mother_name', guardian_name='$guardian_name', guardian_contact='$guardian_contact' WHERE id='$id'";
    $run= mysqli_query($conn, $query);
    if($run) {
        $message= "Record updated successfully.";
    } else {
        $message= "Record could not be updated.";
    } $run= mysqli_query($conn, "  SELECT * FROM family_info WHERE id='$id'");
    $row= mysqli_fetch_assoc($run);
} $page_title= "Family Information";
include "../includes/header.php";
?><h1>Edit Family Information</h1>
<p class="sub">Only Admin can change this record.</p>

<?php if($message != "") {
?>    <div class="done"><?php echo $message;
?></div>
<?php 
}
?><div class="card form-card">

    <h3>Family details</h3>

    <form method="POST">

        <label>Father Name</label>
        <input type="text" name="father_name" value="<?php echo $row["father_name"];
?>" required>

        <label>Father CNIC</label>
        <input type="text" name="father_cnic" value="<?php echo $row["father_cnic"];
?>" required>

        <label>Father Occupation</label>
        <input type="text" name="father_occupation" value="<?php echo $row["father_occupation"];
?>" required>

        <label>Father Contact</label>
        <input type="text" name="father_contact" value="<?php echo $row["father_contact"];
?>" required>

        <label>Mother Name</label>
        <input type="text" name="mother_name" value="<?php echo $row["mother_name"];
?>" required>

        <label>Guardian Name</label>
        <input type="text" name="guardian_name" value="<?php echo $row["guardian_name"];
?>" required>

        <label>Guardian Contact</label>
        <input type="text" name="guardian_contact" value="<?php echo $row["guardian_contact"];
?>" required>

        <button type="submit" name="save-btn">
            Save Changes
        </button>

    </form>

</div>

<?php include "../includes/footer.php";
?>