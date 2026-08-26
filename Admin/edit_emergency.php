<?php
include "../connection.php";
include "../check_login.php";
if($role != "admin") {
    header("location: ../login.php");
    exit();
} $id= $_GET["id"];
$query=" SELECT * FROM emergency_contact WHERE id='$id'";
$run= mysqli_query($conn, $query);
$row= mysqli_fetch_assoc($run);
if(! $row) {
    header("location: users.php");
    exit();
} $message= "";
if(isset($_POST["save-btn"])) {
    $contact_name= $_POST["contact_name"];
    $relation= $_POST["relation"];
    $phone= $_POST["phone"];
    $alt_phone= $_POST["alt_phone"];
    $address= $_POST["address"];
    $query=" UPDATE emergency_contact SET contact_name='$contact_name', relation='$relation', phone='$phone', alt_phone='$alt_phone', address='$address' WHERE id='$id'";
    $run= mysqli_query($conn, $query);
    if($run) {
        $message= "Record updated successfully.";
    } else {
        $message= "Record could not be updated.";
    } $run= mysqli_query($conn, "  SELECT * FROM emergency_contact WHERE id='$id'");
    $row= mysqli_fetch_assoc($run);
} $page_title= "Emergency Contact";
include "../includes/header.php";
?><h1>Edit Emergency Contact</h1>
<p class="sub">Only Admin can change this record.</p>

<?php if($message != "") {
?>    <div class="done"><?php echo $message;
?></div>
<?php 
}
?><div class="card form-card">

    <h3>Emergency details</h3>

    <form method="POST">

        <label>Contact Person</label>
        <input type="text" name="contact_name" value="<?php echo $row["contact_name"];
?>" required>

        <label>Relation</label>
        <input type="text" name="relation" value="<?php echo $row["relation"];
?>" required>

        <label>Phone</label>
        <input type="text" name="phone" value="<?php echo $row["phone"];
?>" required>

        <label>Alternate Phone</label>
        <input type="text" name="alt_phone" value="<?php echo $row["alt_phone"];
?>" required>

        <label>Address</label>
        <textarea name="address" required><?php echo $row["address"];
?></textarea>

        <button type="submit" name="save-btn">
            Save Changes
        </button>

    </form>

</div>

<?php include "../includes/footer.php";
?>