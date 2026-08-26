<?php
include "../connection.php";
include "../check_login.php";
if ($role != "student") {
    header("location: ../login.php");
    exit();
}
$page_title = "Residence";
$message = "";
$query = "SELECT * FROM residence_info WHERE user_id='$user_id'";
$run = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($run);
if (isset($_POST["save-btn"]) && !$row) {
    $residence_status = $_POST["residence_status"];
    $hostel_name = isset($_POST["hostel_name"]) ? $_POST["hostel_name"] : "";
    $room_no = isset($_POST["room_no"]) ? $_POST["room_no"] : "";
    $university_transport = $_POST["university_transport"];
    $transport_route = isset($_POST["transport_route"]) ? $_POST["transport_route"] : "";
    $pick_up_point = isset($_POST["pick_up_point"]) ? $_POST["pick_up_point"] : "";
    if ($residence_status == "Day Scholar") {
        $hostel_name = "";
        $room_no = "";
    }
    if ($university_transport == "No") {
        $transport_route = "";
        $pick_up_point = "";
    }
    $query = "INSERT INTO residence_info (user_id, residence_status, hostel_name, room_no, transport_used, transport_route, pick_up_point) VALUES ('$user_id', '$residence_status', '$hostel_name', '$room_no', '$university_transport', '$transport_route', '$pick_up_point')";
    $run = mysqli_query($conn, $query);
    if ($run) {
        $message = "Residence information added successfully.";
    }
    else {
        $message = "Information could not be saved.";
    }
    $run = mysqli_query($conn, "SELECT * FROM residence_info WHERE user_id='$user_id'");
    $row = mysqli_fetch_assoc($run);
}
include "../includes/header.php";
?>
<h1>Residence</h1>
<p class="sub">Choose your residence type and university transport.</p>
<?php if ($message != "") { ?><div class="done"><?php echo $message; ?></div><?php } ?>
<div class="card form-card">
<h3>Residence details</h3>
<?php if ($row) { ?>
<table>
<tr><th>Residence</th><td><?php echo $row["residence_status"]; ?></td></tr>
<?php if ($row["residence_status"] == "Hosteller") { ?>
<tr><th>Hostel Name</th><td><?php echo $row["hostel_name"]; ?></td></tr>
<tr><th>Room No.</th><td><?php echo $row["room_no"]; ?></td></tr>
<?php } ?>
<tr><th>University Transport</th><td><?php echo $row["transport_used"]; ?></td></tr>
<?php if ($row["transport_used"] == "Yes") { ?>
<tr><th>Transport Route</th><td><?php echo $row["transport_route"]; ?></td></tr>
<tr><th>Pick-up Point</th><td><?php echo $row["pick_up_point"]; ?></td></tr>
<?php } ?>
</table>
<?php } else { ?>
<form method="POST">
<div class="two">
<div><label>Residence Type</label><select name="residence_status" id="residence_status" onchange="changeResidence()" required><option value="">Select</option><option value="Day Scholar">Day Scholar</option><option value="Hosteller">Hosteller</option></select></div>
<div><label>Hostel Name</label><input type="text" name="hostel_name" id="hostel_name" disabled></div>
<div><label>Room No.</label><input type="text" name="room_no" id="room_no" disabled></div>
<div><label>University Transport</label><select name="university_transport" id="university_transport" onchange="changeTransport()" required><option value="">Select</option><option value="Yes">Yes</option><option value="No">No</option></select></div>
<div><label>Transport Route</label><input type="text" name="transport_route" id="transport_route" disabled></div>
<div><label>Pick-up Point</label><input type="text" name="pick_up_point" id="pick_up_point" disabled></div>
</div>
<button type="submit" name="save-btn">Save information</button>
</form>
<script>
function changeResidence(){
    var type=document.getElementById("residence_status").value;
    var hostel=document.getElementById("hostel_name");
    var room=document.getElementById("room_no");
    if(type==="Hosteller"){ hostel.disabled=false; room.disabled=false; hostel.required=true; room.required=true; }
    else { hostel.disabled=true; room.disabled=true; hostel.required=false; room.required=false; hostel.value=""; room.value=""; }
}
function changeTransport(){
    var use=document.getElementById("university_transport").value;
    var route=document.getElementById("transport_route");
    var point=document.getElementById("pick_up_point");
    if(use==="Yes"){ route.disabled=false; point.disabled=false; route.required=true; point.required=true; }
    else { route.disabled=true; point.disabled=true; route.required=false; point.required=false; route.value=""; point.value=""; }
}
</script>
<?php } ?>
</div>
<?php include "../includes/footer.php"; ?>
