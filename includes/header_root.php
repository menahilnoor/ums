<?php

$current_page = basename($_SERVER["PHP_SELF"]);

?>
<!DOCTYPE html>

<html>

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $page_title; ?> | UMS</title>

    <link rel="icon" href="images/logo.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=20260825-responsive-bootstrap-v2">

</head>

<body>


<div class="navbar">

    <div class="brand">

        <img src="images/logo.png">

        <span>
            UMS
            <small>COMSATS Attock Campus</small>
        </span>

    </div>


    <div class="nav-right">

        <a href="logout.php">
            Log out
        </a>

    </div>

</div>


<div class="layout">


<div class="sidebar">

    <div class="avatar">

        <?php

        if ($user["profile_picture"] != "") {

        ?>

            <img
                class="avatar-image"
                src="uploads/profile_pictures/<?php echo $user["profile_picture"]; ?>"
            >

        <?php

        }
        else {

            echo strtoupper(substr($user["name"], 0, 1));

        }

        ?>

    </div>


    <p class="side-name">
        <?php echo $user["name"]; ?>
    </p>


    <p class="side-role">
        <?php echo ucfirst($role); ?>
    </p>


    <nav class="sidebar-menu" aria-label="Main navigation">
        <a class="<?php echo $current_page == "dashboard.php" ? "active" : ""; ?>" href="<?php echo $role_folder; ?>/dashboard.php">
            <span class="menu-icon">⌂</span><span>Dashboard</span>
        </a>

        <?php if ($role == "student") { ?>
            <a class="<?php echo $current_page == "profile.php" ? "active" : ""; ?>" href="<?php echo $role_folder; ?>/profile.php"><span class="menu-icon">◉</span><span>My Profile</span></a>
            <a class="<?php echo $current_page == "education.php" ? "active" : ""; ?>" href="<?php echo $role_folder; ?>/education.php"><span class="menu-icon">▥</span><span>Education</span></a>
            <a class="<?php echo $current_page == "courses.php" ? "active" : ""; ?>" href="<?php echo $role_folder; ?>/courses.php"><span class="menu-icon">▦</span><span>Courses</span></a>
            <a class="<?php echo $current_page == "residence.php" ? "active" : ""; ?>" href="<?php echo $role_folder; ?>/residence.php"><span class="menu-icon">⌂</span><span>Residence</span></a>
            <a class="<?php echo $current_page == "academic_records.php" ? "active" : ""; ?>" href="<?php echo $role_folder; ?>/academic_records.php"><span class="menu-icon">▤</span><span>Academic Record</span></a>
            <a class="<?php echo $current_page == "semester.php" ? "active" : ""; ?>" href="<?php echo $role_folder; ?>/semester.php"><span class="menu-icon">▤</span><span>View Semester</span></a>
        <?php } ?>

        <?php if ($role == "teacher") { ?>
            <a class="<?php echo $current_page == "profile.php" ? "active" : ""; ?>" href="<?php echo $role_folder; ?>/profile.php"><span class="menu-icon">◉</span><span>My Profile</span></a>
            <a class="<?php echo $current_page == "education.php" ? "active" : ""; ?>" href="<?php echo $role_folder; ?>/education.php"><span class="menu-icon">▥</span><span>Education</span></a>
            <a class="<?php echo $current_page == "qualification.php" ? "active" : ""; ?>" href="<?php echo $role_folder; ?>/qualification.php"><span class="menu-icon">▤</span><span>Qualification</span></a>
            <a class="<?php echo $current_page == "courses.php" ? "active" : ""; ?>" href="<?php echo $role_folder; ?>/courses.php"><span class="menu-icon">▦</span><span>Courses</span></a>
            <a class="<?php echo $current_page == "certification.php" ? "active" : ""; ?>" href="<?php echo $role_folder; ?>/certification.php"><span class="menu-icon">✓</span><span>Certification</span></a>
            <a class="<?php echo $current_page == "scheduler.php" ? "active" : ""; ?>" href="<?php echo $role_folder; ?>/scheduler.php"><span class="menu-icon">▦</span><span>Scheduler</span></a>
        <?php } ?>

        <?php if ($role == "admin") { ?>
            <a class="<?php echo $current_page == "users.php" ? "active" : ""; ?>" href="<?php echo $role_folder; ?>/users.php"><span class="menu-icon">◉</span><span>Manage Users</span></a>
        <?php } ?>
    </nav>



</div>


<div class="main">
