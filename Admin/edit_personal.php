<?php
include "../connection.php";
include "../check_login.php";
if($role != "admin") {
    header("location: ../login.php");
    exit();
} $id= $_GET["id"];
$query=" SELECT * FROM personal_details WHERE id='$id'";
$run= mysqli_query($conn, $query);
$row= mysqli_fetch_assoc($run);
if(! $row) {
    header("location: users.php");
    exit();
} $message= "";
if(isset($_POST["save-btn"])) {
    $religion= $_POST["religion"];
    $blood_group= $_POST["blood_group"];
    $nationality= $_POST["nationality"];
    $domicile= $_POST["domicile"];
    $cnic= $_POST["cnic"];
    $address= $_POST["address"];
    $query=" UPDATE personal_details SET religion='$religion', blood_group='$blood_group', nationality='$nationality', domicile='$domicile', cnic='$cnic', address='$address' WHERE id='$id'";
    $run= mysqli_query($conn, $query);
    if($run) {
        $message= "Record updated successfully.";
    } else {
        $message= "Record could not be updated.";
    } $run= mysqli_query($conn, "  SELECT * FROM personal_details WHERE id='$id'");
    $row= mysqli_fetch_assoc($run);
} $page_title= "Personal Information";
include "../includes/header.php";
?><h1>Edit Personal Information</h1>
<p class="sub">Only Admin can change this record.</p>

<?php if($message != "") {
?>    <div class="done"><?php echo $message;
?></div>
<?php 
}
?><div class="card form-card">

    <h3>Personal details</h3>

    <form method="POST">

        <label>Religion</label>
        <input type="text" name="religion" value="<?php echo $row["religion"];
?>" required>

        <label>Blood Group</label>
        <select name="blood_group" required>
            <option value="A+" <?php if($row["blood_group"] == "A+") echo "selected";
?>>A+</option>
            <option value="A-" <?php if($row["blood_group"] == "A-") echo "selected";
?>>A-</option>
            <option value="B+" <?php if($row["blood_group"] == "B+") echo "selected";
?>>B+</option>
            <option value="B-" <?php if($row["blood_group"] == "B-") echo "selected";
?>>B-</option>
            <option value="AB+" <?php if($row["blood_group"] == "AB+") echo "selected";
?>>AB+</option>
            <option value="AB-" <?php if($row["blood_group"] == "AB-") echo "selected";
?>>AB-</option>
            <option value="O+" <?php if($row["blood_group"] == "O+") echo "selected";
?>>O+</option>
            <option value="O-" <?php if($row["blood_group"] == "O-") echo "selected";
?>>O-</option>
        </select>

        <label>Nationality</label>
        <input type="text" name="nationality" value="<?php echo $row["nationality"];
?>" required>

        <label>Domicile</label>
        <select name="domicile" required>
            <option value="">Select City</option>
            <?php
$cities= array("Attock", "Islamabad", "Rawalpindi", "Lahore", "Karachi", "Peshawar", "Quetta", "Multan", "Faisalabad", "Gujranwala", "Sialkot", "Sargodha", "Bahawalpur", "Abbottabad", "Mardan", "Swat", "Nowshera", "Wah Cantt", "Taxila", "Kamra", "Haripur", "Jhelum", "Chakwal", "Gujrat", "Mianwali", "Dera Ismail Khan", "Bannu", "Kohat", "Muzaffarabad", "Gilgit", "Skardu", "Hyderabad", "Sukkur", "Larkana", "Other");
foreach($cities as $city) {
?>                <option value="<?php echo $city;
?>" <?php if($row["domicile"] == $city) echo "selected";
?>>
                    <?php echo $city;
?>                </option>
            <?php 
}
?>        </select>

        <label>CNIC / B-Form</label>
        <input type="text" name="cnic" value="<?php echo $row["cnic"];
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