<?php
include "../connection.php";
include "../check_login.php";
if ($role != "student") {
    header("location: ../login.php");
    exit();
}
$page_title = "My Profile";
$message = "";
/* Save Personal Information */
if (isset($_POST["save_personal"])) {
    $religion = $_POST["religion"];
    $blood_group = $_POST["blood_group"];
    $nationality = $_POST["nationality"];
    $domicile = $_POST["domicile"];
    $cnic = $_POST["cnic"];
    $address = $_POST["address"];
    $check = mysqli_query(
    $conn,
    "SELECT * FROM personal_details WHERE user_id='$user_id'"
    );
    if (mysqli_num_rows($check) > 0) {
        mysqli_query(
        $conn,
        "UPDATE personal_details SET
             religion='$religion',
             blood_group='$blood_group',
             nationality='$nationality',
             domicile='$domicile',
             cnic='$cnic',
             address='$address'
             WHERE user_id='$user_id'"
        );
    }  else {
        mysqli_query(
        $conn,
        "INSERT INTO personal_details
             (user_id, religion, blood_group, nationality, domicile, cnic, address)
             VALUES
             ('$user_id', '$religion', '$blood_group', '$nationality', '$domicile', '$cnic', '$address')"
        );
    }
    $message = "Personal information saved.";
}
/* Save Family Information */
if (isset($_POST["save_family"])) {
    $father_name = $_POST["father_name"];
    $father_cnic = $_POST["father_cnic"];
    $father_occupation = $_POST["father_occupation"];
    $father_contact = $_POST["father_contact"];
    $mother_name = $_POST["mother_name"];
    $guardian_name = $_POST["guardian_name"];
    $guardian_contact = $_POST["guardian_contact"];
    $check = mysqli_query(
    $conn,
    "SELECT * FROM family_info WHERE user_id='$user_id'"
    );
    if (mysqli_num_rows($check) > 0) {
        mysqli_query(
        $conn,
        "UPDATE family_info SET
             father_name='$father_name',
             father_cnic='$father_cnic',
             father_occupation='$father_occupation',
             father_contact='$father_contact',
             mother_name='$mother_name',
             guardian_name='$guardian_name',
             guardian_contact='$guardian_contact'
             WHERE user_id='$user_id'"
        );
    }  else {
        mysqli_query(
        $conn,
        "INSERT INTO family_info
             (user_id, father_name, father_cnic, father_occupation,
              father_contact, mother_name, guardian_name, guardian_contact)
             VALUES
             ('$user_id', '$father_name', '$father_cnic', '$father_occupation',
              '$father_contact', '$mother_name', '$guardian_name', '$guardian_contact')"
        );
    }
    $message = "Family information saved.";
}
/* Save Emergency Information */
if (isset($_POST["save_emergency"])) {
    $contact_name = $_POST["contact_name"];
    $relation = $_POST["relation"];
    $phone = $_POST["phone"];
    $alt_phone = $_POST["alt_phone"];
    $emergency_address = $_POST["emergency_address"];
    $check = mysqli_query(
    $conn,
    "SELECT * FROM emergency_contact WHERE user_id='$user_id'"
    );
    if (mysqli_num_rows($check) > 0) {
        mysqli_query(
        $conn,
        "UPDATE emergency_contact SET
             contact_name='$contact_name',
             relation='$relation',
             phone='$phone',
             alt_phone='$alt_phone',
             address='$emergency_address'
             WHERE user_id='$user_id'"
        );
    }  else {
        mysqli_query(
        $conn,
        "INSERT INTO emergency_contact
             (user_id, contact_name, relation, phone, alt_phone, address)
             VALUES
             ('$user_id', '$contact_name', '$relation', '$phone',
              '$alt_phone', '$emergency_address')"
        );
    }
    $message = "Emergency contact information saved.";
}
/* Get saved information */
$personal = mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT * FROM personal_details WHERE user_id='$user_id'"
)
);
$family = mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT * FROM family_info WHERE user_id='$user_id'"
)
);
$emergency = mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT * FROM emergency_contact WHERE user_id='$user_id'"
)
);
include "../includes/header.php";
?>

<h1>My Profile</h1>

<p class="sub">
    Manage your personal, family and emergency information in one place.
</p>

<?php if ($message != "") { ?>

<div class="done">
    <?php echo $message; ?>
</div>

<?php } ?>

<!-- Personal Information -->
<div class="card form-card">

    <h3>Personal Information</h3>

    <form method="POST">

        <div class="two">

            <div>
                <label>Religion</label>

                <select name="religion">

                    <option value="">Select Religion</option>

                    <option
                        value="Islam"
                        <?php if (((isset($personal["religion"]) ? $personal["religion"] : "")) == "Islam") echo "selected"; ?>
                    >
                        Islam
                    </option>

                    <option
                        value="Christian"
                        <?php if (((isset($personal["religion"]) ? $personal["religion"] : "")) == "Christian") echo "selected"; ?>
                    >
                        Christian
                    </option>

                    <option
                        value="Hindu"
                        <?php if (((isset($personal["religion"]) ? $personal["religion"] : "")) == "Hindu") echo "selected"; ?>
                    >
                        Hindu
                    </option>

                    <option
                        value="Other"
                        <?php if (((isset($personal["religion"]) ? $personal["religion"] : "")) == "Other") echo "selected"; ?>
                    >
                        Other
                    </option>

                </select>
            </div>

            <div>
                <label>Blood Group</label>

                <select name="blood_group">

                    <option value="">Select Blood Group</option>

                    <?php
                    $groups = array("A+","A-","B+","B-","AB+","AB-","O+","O-");

                    foreach ($groups as $group) {
                    ?>

                    <option
                        value="<?php echo $group; ?>"
                        <?php if (((isset($personal["blood_group"]) ? $personal["blood_group"] : "")) == $group) echo "selected"; ?>
                    >
                        <?php echo $group; ?>
                    </option>

                    <?php } ?>

                </select>
            </div>

            <div>
                <label>Nationality</label>
                <input
                    type="text"
                    name="nationality"
                    value="<?php echo (isset($personal["nationality"]) ? $personal["nationality"] : ""); ?>"
                >
            </div>

            <div>
                <label>Domicile</label>
                <select name="domicile">
                    <option value="">Select City</option>
                    <?php
                    $cities = array(
                        "Attock", "Islamabad", "Rawalpindi", "Lahore", "Karachi",
                        "Peshawar", "Quetta", "Multan", "Faisalabad", "Gujranwala",
                        "Sialkot", "Sargodha", "Bahawalpur", "Abbottabad", "Mardan",
                        "Swat", "Nowshera", "Wah Cantt", "Taxila", "Kamra",
                        "Haripur", "Jhelum", "Chakwal", "Gujrat", "Mianwali",
                        "Dera Ismail Khan", "Bannu", "Kohat", "Muzaffarabad", "Gilgit",
                        "Skardu", "Hyderabad", "Sukkur", "Larkana", "Other"
                    );
                    foreach ($cities as $city) {
                    ?>
                        <option value="<?php echo $city; ?>" <?php if (((isset($personal["domicile"]) ? $personal["domicile"] : "")) == $city) echo "selected"; ?>>
                            <?php echo $city; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div>
                <label>CNIC</label>
                <input
                    type="text"
                    name="cnic"
                    value="<?php echo (isset($personal["cnic"]) ? $personal["cnic"] : ""); ?>"
                >
            </div>

            <div>
                <label>Address</label>
                <input
                    type="text"
                    name="address"
                    value="<?php echo (isset($personal["address"]) ? $personal["address"] : ""); ?>"
                >
            </div>

        </div>

        <button type="submit" name="save_personal">
            Save Personal Information
        </button>

    </form>

</div>

<!-- Family Information -->
<div class="card form-card">

    <h3>Family Information</h3>

    <form method="POST">

        <div class="two">

            <div>
                <label>Father Name</label>
                <input
                    type="text"
                    name="father_name"
                    value="<?php echo (isset($family["father_name"]) ? $family["father_name"] : ""); ?>"
                >
            </div>

            <div>
                <label>Father CNIC</label>
                <input
                    type="text"
                    name="father_cnic"
                    value="<?php echo (isset($family["father_cnic"]) ? $family["father_cnic"] : ""); ?>"
                >
            </div>

            <div>
                <label>Father Occupation</label>
                <input
                    type="text"
                    name="father_occupation"
                    value="<?php echo (isset($family["father_occupation"]) ? $family["father_occupation"] : ""); ?>"
                >
            </div>

            <div>
                <label>Father Contact</label>
                <input
                    type="text"
                    name="father_contact"
                    value="<?php echo (isset($family["father_contact"]) ? $family["father_contact"] : ""); ?>"
                >
            </div>

            <div>
                <label>Mother Name</label>
                <input
                    type="text"
                    name="mother_name"
                    value="<?php echo (isset($family["mother_name"]) ? $family["mother_name"] : ""); ?>"
                >
            </div>

            <div>
                <label>Guardian Name</label>
                <input
                    type="text"
                    name="guardian_name"
                    value="<?php echo (isset($family["guardian_name"]) ? $family["guardian_name"] : ""); ?>"
                >
            </div>

            <div>
                <label>Guardian Contact</label>
                <input
                    type="text"
                    name="guardian_contact"
                    value="<?php echo (isset($family["guardian_contact"]) ? $family["guardian_contact"] : ""); ?>"
                >
            </div>

        </div>

        <button type="submit" name="save_family">
            Save Family Information
        </button>

    </form>

</div>

<!-- Emergency Information -->
<div class="card form-card">

    <h3>Emergency Contact</h3>

    <form method="POST">

        <div class="two">

            <div>
                <label>Contact Name</label>
                <input
                    type="text"
                    name="contact_name"
                    value="<?php echo (isset($emergency["contact_name"]) ? $emergency["contact_name"] : ""); ?>"
                >
            </div>

            <div>
                <label>Relation</label>
                <input
                    type="text"
                    name="relation"
                    value="<?php echo (isset($emergency["relation"]) ? $emergency["relation"] : ""); ?>"
                >
            </div>

            <div>
                <label>Phone</label>
                <input
                    type="text"
                    name="phone"
                    value="<?php echo (isset($emergency["phone"]) ? $emergency["phone"] : ""); ?>"
                >
            </div>

            <div>
                <label>Alternative Phone</label>
                <input
                    type="text"
                    name="alt_phone"
                    value="<?php echo (isset($emergency["alt_phone"]) ? $emergency["alt_phone"] : ""); ?>"
                >
            </div>

            <div>
                <label>Address</label>
                <input
                    type="text"
                    name="emergency_address"
                    value="<?php echo (isset($emergency["address"]) ? $emergency["address"] : ""); ?>"
                >
            </div>

        </div>

        <button type="submit" name="save_emergency">
            Save Emergency Information
        </button>

    </form>

</div>

<?php include "../includes/footer.php"; ?>