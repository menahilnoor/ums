<?php

session_start();

include "connection.php";

if (!isset($_SESSION["user_id"])) {
    header("location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

/*
    Make sure the users table has the profile_picture column.
    This also fixes older UMS databases without requiring
    the user to recreate the whole database.
*/
$column_check = mysqli_query(
    $conn,
    "SHOW COLUMNS FROM users LIKE 'profile_picture'"
);

if ($column_check && mysqli_num_rows($column_check) == 0) {

    mysqli_query(
        $conn,
        "ALTER TABLE users
         ADD profile_picture VARCHAR(255) NULL"
    );
}

$user_query = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE id='$user_id'"
);

$user = mysqli_fetch_assoc($user_query);

if (!$user) {
    header("location: login.php");
    exit();
}

/*
    Decide where the user should go after upload.
*/
if ($user["role"] == "admin") {
    $dashboard = "Admin/dashboard.php";
} elseif ($user["role"] == "teacher") {
    $dashboard = "Teacher/dashboard.php";
} elseif ($user["role"] == "it_staff") {
    $dashboard = "ITStaff/dashboard.php";
} else {
    $dashboard = "Student/dashboard.php";
}

/*
    Check whether a file was selected.
*/
if (!isset($_FILES["profile_picture"])) {
    header("location: $dashboard");
    exit();
}

if ($_FILES["profile_picture"]["error"] != 0) {
    header(
        "location: $dashboard?photo_error=Please%20select%20a%20valid%20picture."
    );
    exit();
}

$file_tmp = $_FILES["profile_picture"]["tmp_name"];
$file_size = $_FILES["profile_picture"]["size"];
$file_name = $_FILES["profile_picture"]["name"];

$extension = strtolower(
    pathinfo($file_name, PATHINFO_EXTENSION)
);

/*
    Maximum size = 5 MB.
*/
if ($file_size > 5 * 1024 * 1024) {
    header(
        "location: $dashboard?photo_error=Picture%20must%20be%20smaller%20than%205%20MB."
    );
    exit();
}

/*
    Allowed picture types.
*/
if (
    $extension != "jpg" &&
    $extension != "jpeg" &&
    $extension != "png" &&
    $extension != "gif"
) {
    header(
        "location: $dashboard?photo_error=Only%20JPG%2C%20JPEG%2C%20PNG%20and%20GIF%20are%20allowed."
    );
    exit();
}

/*
    Make sure the selected file is really an image.
*/
if (getimagesize($file_tmp) === false) {
    header(
        "location: $dashboard?photo_error=The%20selected%20file%20is%20not%20a%20valid%20picture."
    );
    exit();
}

/*
    Create the upload folder if it does not exist.
*/
$folder = __DIR__ . "/uploads/profile_pictures/";

if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
}

/*
    One fixed filename per user.
*/
$new_name = "user_" . $user_id . "." . $extension;
$new_path = $folder . $new_name;

/*
    Remove the user's old picture.
*/
$old_extensions = array("jpg", "jpeg", "png", "gif");

foreach ($old_extensions as $old_extension) {

    $old_path =
        $folder .
        "user_" .
        $user_id .
        "." .
        $old_extension;

    if (file_exists($old_path)) {
        unlink($old_path);
    }
}

/*
    Save the uploaded picture.
*/
if (!move_uploaded_file($file_tmp, $new_path)) {

    header(
        "location: $dashboard?photo_error=Picture%20could%20not%20be%20saved%20in%20the%20uploads%20folder."
    );

    exit();
}

/*
    Save the picture filename in the database.
*/
$update = mysqli_query(
    $conn,
    "UPDATE users
     SET profile_picture='$new_name'
     WHERE id='$user_id'"
);

if ($update) {

    header(
        "location: $dashboard?photo_success=Profile%20picture%20uploaded%20successfully."
    );

    exit();
}

/*
    If database saving fails, remove the new file.
*/
if (file_exists($new_path)) {
    unlink($new_path);
}

header(
    "location: $dashboard?photo_error=Picture%20was%20saved%20but%20the%20database%20could%20not%20be%20updated."
);

exit();

?>