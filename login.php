<?php

include "connection.php";

session_start();

if (isset($_SESSION["user_id"])) {

    if ($_SESSION["role"] == "admin") {
        header("location: Admin/dashboard.php");
    }
    elseif ($_SESSION["role"] == "teacher") {
        header("location: Teacher/dashboard.php");
    }
    elseif ($_SESSION["role"] == "it_staff") {
        header("location: ITStaff/dashboard.php");
    }
    else {
        header("location: Student/dashboard.php");
    }

    exit();
}

$error = "";

if (isset($_GET["signup"])) {
    $message = "Account created successfully. Now log in.";
}
else {
    $message = "";
}


if (isset($_POST["login-btn"])) {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $selected_role = $_POST["role"] ?? "";

    $query = "SELECT * FROM users WHERE email='$email'";
    $run = mysqli_query($conn, $query);

    if (mysqli_num_rows($run) > 0) {

        $user = mysqli_fetch_assoc($run);

        if ($password == $user["password"]) {

            if ($selected_role !== $user["role"]) {
                $error = "Selected role does not match this account.";
            } else {

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["role"] = $user["role"];

            if ($user["role"] == "admin") {
                header("location: Admin/dashboard.php");
            }
            elseif ($user["role"] == "teacher") {
                header("location: Teacher/dashboard.php");
            }
            elseif ($user["role"] == "it_staff") {
                header("location: ITStaff/dashboard.php");
            }
            else {
                header("location: Student/dashboard.php");
            }

            exit();

            }
        }
        else {
            $error = "Wrong password";
        }

    }
    else {
        $error = "Email not found";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login | UMS</title>
    <link rel="icon" href="images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-page">

<div class="box">

    <div class="box-top">

        <img src="images/logo.png">

        <h2>University Management System</h2>

        <p>COMSATS University Islamabad, Attock Campus</p>

    </div>

    <?php if ($message != "") { ?>

        <div class="done">
            <?php echo $message; ?>
        </div>

    <?php } ?>

    <?php if ($error != "") { ?>

        <div class="error">
            <?php echo $error; ?>
        </div>

    <?php } ?>


    <form method="POST">

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <label>Role</label>
        <select name="role" required>
            <option value="">Select Role</option>
            <option value="student">Student</option>
            <option value="teacher">Teacher</option>
            <option value="it_staff">IT Staff</option>
            <option value="admin">Administrator</option>
        </select>

        <button type="submit" name="login-btn">
            Log In
        </button>

    </form>


    <p class="bottom-line">

        Don't have an account?

        <a href="signup.php">
            Sign up
        </a>

    </p>


    <p class="hint center">
        Demo admin: admin@ums.com / admin123
    </p>

</div>

</body>
</html>
