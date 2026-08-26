<?php

$current_page = basename($_SERVER["PHP_SELF"]);

?>
<!DOCTYPE html>

<html>

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $page_title; ?> | UMS</title>

    <link rel="icon" href="../images/logo.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css?v=20260825-responsive-bootstrap-v2">

</head>

<body>


<div class="navbar">

    <div class="brand">

        <img src="../images/logo.png">

        <span>
            UMS
            <small>COMSATS Attock Campus</small>
        </span>

    </div>


    <div class="nav-right">

        <a class="logout-button" href="../logout.php" title="Log out of UMS">
            ⇥ &nbsp; Log out
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
                src="../uploads/profile_pictures/<?php echo $user["profile_picture"]; ?>"
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


    <form
        class="sidebar-photo-form"
        action="../upload_picture.php"
        method="POST"
        enctype="multipart/form-data"
    >

        <input
            type="file"
            name="profile_picture"
            accept="image/jpeg,image/png,image/gif"
            required
        >

        <button type="submit">
            Upload photo
        </button>

    </form>

    <?php if (isset($_GET["photo_success"])) { ?>
        <div class="photo-message success-message">
            <?php echo $_GET["photo_success"]; ?>
        </div>
    <?php } ?>

    <?php if (isset($_GET["photo_error"])) { ?>
        <div class="photo-message error-message">
            <?php echo $_GET["photo_error"]; ?>
        </div>
    <?php } ?>


    <nav class="sidebar-menu" aria-label="Main navigation">

        <a class="<?php echo $current_page == "dashboard.php" ? "active" : ""; ?>" href="dashboard.php">
            <span class="menu-icon">⌂</span><span>Dashboard</span>
        </a>

        <?php if ($role == "student") { ?>
            <a class="<?php echo $current_page == "profile.php" ? "active" : ""; ?>" href="profile.php">
                <span class="menu-icon">◉</span><span>My Profile</span>
            </a>
            <a class="<?php echo $current_page == "education.php" ? "active" : ""; ?>" href="education.php">
                <span class="menu-icon">▥</span><span>Education</span>
            </a>
            <a class="<?php echo $current_page == "courses.php" ? "active" : ""; ?>" href="courses.php">
                <span class="menu-icon">▦</span><span>Courses</span>
            </a>
            <a class="<?php echo $current_page == "residence.php" ? "active" : ""; ?>" href="residence.php">
                <span class="menu-icon">⌂</span><span>Residence</span>
            </a>
            <a class="<?php echo $current_page == "academic_records.php" ? "active" : ""; ?>" href="academic_records.php">
                <span class="menu-icon">▤</span><span>Academic Record</span>
            </a>
            <a class="<?php echo $current_page == "semester.php" ? "active" : ""; ?>" href="semester.php">
                <span class="menu-icon">▤</span><span>View Semester</span>
            </a>
        <?php } ?>

        <?php if ($role == "teacher") { ?>
            <a class="<?php echo $current_page == "profile.php" ? "active" : ""; ?>" href="profile.php">
                <span class="menu-icon">◉</span><span>My Profile</span>
            </a>
            <a class="<?php echo $current_page == "qualification.php" ? "active" : ""; ?>" href="qualification.php">
                <span class="menu-icon">▤</span><span>Qualification</span>
            </a>
            <a class="<?php echo $current_page == "certification.php" ? "active" : ""; ?>" href="certification.php">
                <span class="menu-icon">✓</span><span>Certification</span>
            </a>
            <a class="<?php echo $current_page == "scheduler.php" ? "active" : ""; ?>" href="scheduler.php">
                <span class="menu-icon">▦</span><span>Scheduler</span>
            </a>
        <?php } ?>

        <?php if ($role == "it_staff") { ?>
            <a class="<?php echo $current_page == "profile.php" ? "active" : ""; ?>" href="profile.php">
                <span class="menu-icon">◉</span><span>My Profile</span>
            </a>
            <a class="<?php echo $current_page == "education.php" ? "active" : ""; ?>" href="education.php">
                <span class="menu-icon">▥</span><span>Education</span>
            </a>
            <a class="<?php echo $current_page == "experience.php" ? "active" : ""; ?>" href="experience.php">
                <span class="menu-icon">▤</span><span>Experience</span>
            </a>
            <a class="<?php echo $current_page == "certification.php" ? "active" : ""; ?>" href="certification.php">
                <span class="menu-icon">✓</span><span>Certification</span>
            </a>
            <a class="<?php echo $current_page == "scheduler.php" ? "active" : ""; ?>" href="scheduler.php">
                <span class="menu-icon">▦</span><span>IT Schedule</span>
            </a>
        <?php } ?>

        <?php if ($role == "admin") { ?>
            <a class="<?php echo $current_page == "student_management.php" ? "active" : ""; ?>" href="student_management.php">
                <span class="menu-icon">▤</span><span>Student Records</span>
            </a>
            <a class="<?php echo $current_page == "teacher_management.php" ? "active" : ""; ?>" href="teacher_management.php">
                <span class="menu-icon">▤</span><span>Teacher Records</span>
            </a>
            <a class="<?php echo $current_page == "it_staff_management.php" ? "active" : ""; ?>" href="it_staff_management.php">
                <span class="menu-icon">▤</span><span>IT Staff Records</span>
            </a>
            <a class="<?php echo $current_page == "student_records.php" ? "active" : ""; ?>" href="student_records.php">
                <span class="menu-icon">▤</span><span>Student Academic Records</span>
            </a>
            <a class="<?php echo $current_page == "scheduler.php" ? "active" : ""; ?>" href="scheduler.php">
                <span class="menu-icon">▦</span><span>Teacher Schedule</span>
            </a>
            <a class="<?php echo $current_page == "it_schedule.php" ? "active" : ""; ?>" href="it_schedule.php">
                <span class="menu-icon">▦</span><span>IT Staff Schedule</span>
            </a>
            <a class="<?php echo $current_page == "role_report.php" ? "active" : ""; ?>" href="role_report.php">
                <span class="menu-icon">▣</span><span>Role Based Report</span>
            </a>
        <?php } ?>

    </nav>



</div>


<div class="main">
