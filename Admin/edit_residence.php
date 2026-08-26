<?php
include "../connection.php";
include "../check_login.php";
if($role != "admin") {
    header("location: ../login.php");
    exit();
} $id= $_GET["id"];
$query=" SELECT * FROM residence_info WHERE id='$id'";
$run= mysqli_query($conn, $query);
$row= mysqli_fetch_assoc($run);
if(! $row) {
    header("location: users.php");
    exit();
} $message= "";
if(isset($_POST["save-btn"])) {
    $residence_status= $_POST["residence_status"];
    $hostel_name= $_POST["hostel_name"];
    $room_no= $_POST["room_no"];
    $transport_used= $_POST["transport_used"];
    $transport_route= $_POST["transport_route"];
    $pick_up_point= $_POST["pick_up_point"];
    $query=" UPDATE residence_info SET residence_status='$residence_status', hostel_name='$hostel_name', room_no='$room_no', transport_used='$transport_used', transport_route='$transport_route', pick_up_point='$pick_up_point' WHERE id='$id'";
    $run= mysqli_query($conn, $query);
    if($run) {
        $message= "Record updated successfully.";
    } else {
        $message= "Record could not be updated.";
    } $run= mysqli_query($conn, "  SELECT * FROM residence_info WHERE id='$id'");
    $row= mysqli_fetch_assoc($run);
} $page_title= "Residence Information";
include "../includes/header.php";
?><h1>Edit Residence Information</h1>
<p class="sub">Only Admin can change this record.</p>

<?php if($message != "") {
?>    <div class="done"><?php echo $message;
?></div>
<?php 
}
?><div class="card form-card">

    <h3>Residence details</h3>

    <form method="POST">

        <label>Residence Status</label>
        <select name="residence_status" required>
            <option value="Day Scholar" <?php if($row["residence_status"] == "Day Scholar") echo "selected";
?>>Day Scholar</option>
            <option value="Hosteller" <?php if($row["residence_status"] == "Hosteller") echo "selected";
?>>Hosteller</option>
        </select>

        <label>Hostel Name</label>
        <input type="text" name="hostel_name" value="<?php echo $row["hostel_name"];
?>" required>

        <label>Room No.</label>
        <input type="text" name="room_no" value="<?php echo $row["room_no"];
?>" required>

        <label>Transport Used</label>
        <select name="transport_used" required>
            <option value="Yes" <?php if($row["transport_used"] == "Yes") echo "selected";
?>>Yes</option>
            <option value="No" <?php if($row["transport_used"] == "No") echo "selected";
?>>No</option>
        </select>

        <label>Transport Route</label>
        <input type="text" name="transport_route" value="<?php echo $row["transport_route"];
?>" required>

        <label>Pick-up Point</label>
        <input type="text" name="pick_up_point" value="<?php echo $row["pick_up_point"];
?>" required>

        <button type="submit" name="save-btn">
            Save Changes
        </button>

    </form>

</div>

<?php include "../includes/footer.php";
?>