<?php

include "connection.php";

$error = "";

if (isset($_POST["signup-btn"])) {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $dob = $_POST["dob"];
    $role = $_POST["role"];
    $password = $_POST["password"];

    if ($name == "" || $email == "" || $role == "" || $password == "") {

        $error = "Please fill all required fields.";

    }
    elseif ($role != "student" && $role != "teacher" && $role != "it_staff") {

        $error = "Please select Student, Teacher or IT Staff.";

    }
    else {

        $query = "SELECT * FROM users WHERE email='$email'";
        $run = mysqli_query($conn, $query);

        if (mysqli_num_rows($run) > 0) {

            $error = "This email is already registered.";

        }
        else {

            $query = "INSERT INTO users
                      (name, email, phone, dob, role, password)
                      VALUES
                      ('$name', '$email', '$phone', '$dob', '$role', '$password')";

            $run = mysqli_query($conn, $query);

            if ($run) {

                header("location: login.php?signup=done");
                exit();

            }
            else {

                $error = "Account could not be created.";

            }
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>

    <title>Sign Up | UMS</title>
    <link rel="icon" href="images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

</head>

<body class="bg-page">

<div class="box wide">

    <div class="box-top">

        <img src="images/logo.png">

        <h2>Create Account</h2>

        <p>COMSATS University Islamabad, Attock Campus</p>

    </div>


    <?php if ($error != "") { ?>

        <div class="error">
            <?php echo $error; ?>
        </div>

    <?php } ?>


    <form method="POST">

        <div class="two">

            <div>
                <label>Full Name</label>
                <input type="text" name="name" required>
            </div>

            <div>
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

        </div>


        <div class="two">

            <div>
                <label>Phone</label>
                <input type="text" name="phone">
            </div>

            <div>
                <label>Date of Birth</label>
                <input type="date" name="dob">
            </div>

        </div>


        <div class="two">

            <div>
                <label>Role</label>

                <select name="role" required>

                    <option value="">
                        Select Role
                    </option>

                    <option value="student">
                        Student
                    </option>

                    <option value="teacher">
                        Teacher
                    </option>

                    <option value="it_staff">
                        IT Staff
                    </option>

                </select>

            </div>


            <div>

                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    required
                >

            </div>

        </div>


        <button type="submit" name="signup-btn">
            Sign Up
        </button>

    </form>


    <p class="bottom-line">

        Already have an account?

        <a href="login.php">
            Log in
        </a>

    </p>

</div>

</body>
</html>
