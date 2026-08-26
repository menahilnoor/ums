<?php
include "../connection.php";
include "../check_login.php";
if($role != "admin") {
    header("location: ../login.php");
    exit();
} $id= $_GET["id"];
$query=" SELECT * FROM experiences WHERE id='$id'";
$run= mysqli_query($conn, $query);
$row= mysqli_fetch_assoc($run);
if(! $row) {
    header("location: users.php");
    exit();
} $message= "";
if(isset($_POST["save-btn"])) {
    $organisation= $_POST["organisation"];
    $designation= $_POST["designation"];
    $from_date= $_POST["from_date"];
    $to_date= $_POST["to_date"];
    $details= $_POST["details"];
    $query=" UPDATE experiences SET organisation='$organisation', designation='$designation', from_date='$from_date', to_date='$to_date', details='$details' WHERE id='$id'";
    $run= mysqli_query($conn, $query);
    if($run) {
        $message= "Record updated successfully.";
    } else {
        $message= "Record could not be updated.";
    } $run= mysqli_query($conn, "  SELECT * FROM experiences WHERE id='$id'");
    $row= mysqli_fetch_assoc($run);
} $page_title= "Work Experience";
include "../includes/header.php";
?><h1>Edit Work Experience</h1>
<p class="sub">Only Admin can change this record.</p>

<?php if($message != "") {
?>    <div class="done"><?php echo $message;
?></div>
<?php 
}
?><div class="card form-card">

    <h3>Experience details</h3>

    <form method="POST">

        <label>Organisation</label>
        <input type="text" name="organisation" value="<?php echo $row["organisation"];
?>" required>

        <label>Designation</label>
        <input type="text" name="designation" value="<?php echo $row["designation"];
?>" required>

        <label>From Date</label>
        <input type="date" name="from_date" value="<?php echo $row["from_date"];
?>" required>

        <label>To Date</label>
        <input type="date" name="to_date" value="<?php echo $row["to_date"];
?>" required>

        <label>Details</label>
        <textarea name="details" required><?php echo $row["details"];
?></textarea>

        <button type="submit" name="save-btn">
            Save Changes
        </button>

    </form>

</div>

<?php include "../includes/footer.php";
?>