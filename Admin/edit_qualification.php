<?php
include "../connection.php";
include "../check_login.php";
if($role != "admin") {
    header("location: ../login.php");
    exit();
} $id= $_GET["id"];
$query=" SELECT * FROM teacher_qualifications WHERE id='$id'";
$run= mysqli_query($conn, $query);
$row= mysqli_fetch_assoc($run);
if(! $row) {
    header("location: users.php");
    exit();
} $message= "";
if(isset($_POST["save-btn"])) {
    $name= $_POST["qualification_name"];
    $body= $_POST["university_institute"];
    $specialization= $_POST["specialization"];
    $year= $_POST["passing_year"];
    $grade= $_POST["grade"];
    $query=" UPDATE teacher_qualifications
              SET qualification_name='$name',
                  university_institute='$body',
                  specialization='$specialization',
                  passing_year='$year',
                  grade='$grade'
              WHERE id='$id'";
    $run= mysqli_query($conn, $query);
    if($run) {
        $message= "Qualification updated successfully.";
    } else {
        $message= "Qualification could not be updated.";
    } $run= mysqli_query($conn, "  SELECT * FROM teacher_qualifications WHERE id='$id'");
    $row= mysqli_fetch_assoc($run);
} $page_title= "Edit Qualification";
include "../includes/header.php";
?><h1>Edit Qualification</h1>
<p class="sub">Admin can update teacher qualification information.</p>

<?php if($message != "") {
?>    <div class="done"><?php echo $message;
?></div>
<?php 
}
?><div class="card form-card">

<form method="POST">

    <div class="two">
        <div>
            <label>Qualification</label>
            <input type="text" name="qualification_name" value="<?php echo $row["qualification_name"];
?>" required>
        </div>
        <div>
            <label>University / Institute</label>
            <input type="text" name="university_institute" value="<?php echo $row["university_institute"];
?>" required>
        </div>
        <div>
            <label>Specialization</label>
            <input type="text" name="specialization" value="<?php echo $row["specialization"];
?>" required>
        </div>
        <div>
            <label>Passing Year</label>
            <input type="text" name="passing_year" value="<?php echo $row["passing_year"];
?>" required>
        </div>
        <div>
            <label>Grade / CGPA</label>
            <input type="text" name="grade" value="<?php echo $row["grade"];
?>">
        </div>
    </div>

    <button type="submit" name="save-btn">Save Changes</button>

</form>

</div>

<?php include "../includes/footer.php";
?>