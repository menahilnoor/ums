<?php
include "../connection.php";
include "../check_login.php";
if($role != "admin") {
    header("location: ../login.php");
    exit();
} $id= $_GET["id"];
$query=" SELECT * FROM users WHERE id='$id'";
$run= mysqli_query($conn, $query);
$edit_user= mysqli_fetch_assoc($run);
if(! $edit_user) {
    header("location: users.php");
    exit();
} $message= "";
$departments= array("BS Software Engineering", "BS Computer Science", "BS Artificial Intelligence", "BS Computer Engineering", "BS Electrical Engineering", "BS Business Administration", "BS Information Technology", "BS Data Science", "BS Cyber Security", "BS Mathematics", "BS Physics");
$semesters= array("Semester 1", "Semester 2", "Semester 3", "Semester 4", "Semester 5", "Semester 6", "Semester 7", "Semester 8");
if(isset($_POST["save-btn"])) {
    $name= $_POST["name"];
    $phone= $_POST["phone"];
    $dob= $_POST["dob"];
    $new_role= $_POST["role"];
    $department= $_POST["department"];
    $current_semester= $_POST["current_semester"];
    $main_subject= $_POST["main_subject"];
    $password= "";
    if(isset($_POST["password"])) {
        $password= $_POST["password"];
    }

    $query=" UPDATE users
              SET name='$name',
                  phone='$phone',
                  dob='$dob',
                  role='$new_role',
                  department='$department',
                  current_semester='$current_semester',
                  main_subject='$main_subject'
              WHERE id='$id'";

    if($password != "") {
        $query=" UPDATE users
                  SET name='$name',
                      phone='$phone',
                      dob='$dob',
                      role='$new_role',
                      department='$department',
                      current_semester='$current_semester',
                      main_subject='$main_subject',
                      password='$password'
                  WHERE id='$id'";
    }
    $run= mysqli_query($conn, $query);
    if($run) {
        $message= "User updated successfully.";
    } else {
        $message= "User could not be updated.";
    } $run= mysqli_query($conn, "  SELECT * FROM users WHERE id='$id'");
    $edit_user= mysqli_fetch_assoc($run);
} $page_title= "Edit User";
include "../includes/header.php";
?><h1>Edit User</h1>
<p class="sub">Only Admin can change department, semester and account information.</p>

<?php if($message != "") {
?>    <div class="done"><?php echo $message;
?></div>
<?php 
}
?><div class="card form-card">

<form method="POST">

    <div class="two">

        <div>
            <label>Name</label>
            <input type="text" name="name" value="<?php echo $edit_user["name"];
?>" required>
        </div>

        <div>
            <label>Email</label>
            <input type="text" value="<?php echo $edit_user["email"];
?>" disabled>
        </div>

        <div>
            <label>Password</label>
            <input type="text" name="password" placeholder="Leave blank to keep current password">
        </div>

        <div>
            <label>Phone</label>
            <input type="text" name="phone" value="<?php echo $edit_user["phone"];
?>">
        </div>

        <div>
            <label>Date of Birth</label>
            <input type="date" name="dob" value="<?php echo $edit_user["dob"];
?>">
        </div>

        <div>
            <label>Role</label>
            <select name="role">
                <option value="student" <?php if($edit_user["role"] == "student") echo "selected";
?>>Student</option>
                <option value="teacher" <?php if($edit_user["role"] == "teacher") echo "selected";
?>>Teacher</option>
                <option value="it_staff" <?php if($edit_user["role"] == "it_staff") echo "selected";
?>>IT Staff</option>
                <option value="admin" <?php if($edit_user["role"] == "admin") echo "selected";
?>>Admin</option>
            </select>
        </div>

        <div>
            <label>Department</label>
            <select name="department">
                <option value="">Select Department</option>
                <?php foreach($departments as $department_name) {
?>                    <option value="<?php echo $department_name;
?>" <?php if($edit_user["department"] == $department_name) echo "selected";
?>>
                        <?php echo $department_name;
?>                    </option>
                <?php 
}
?>            </select>
        </div>

        <div>
            <label>Current Semester</label>
            <select name="current_semester">
                <option value="">Select Semester</option>
                <?php foreach($semesters as $semester_name) {
?>                    <option value="<?php echo $semester_name;
?>" <?php if($edit_user["current_semester"] == $semester_name) echo "selected";
?>>
                        <?php echo $semester_name;
?>                    </option>
                <?php 
}
?>            </select>
        </div>

        <div>
            <label>Main Subject (Teacher)</label>
            <input type="text" name="main_subject" value="<?php echo $edit_user["main_subject"];
?>">
        </div>

    </div>

    <button type="submit" name="save-btn">Save Changes</button>

</form>

</div>

<?php include "../includes/footer.php";
?>