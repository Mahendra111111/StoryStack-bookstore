<?php

if (!empty($_POST)) {
    $msg = "";

    // Check if required fields are filled
    if (empty($_POST['fnm']) || empty($_POST['unm']) || empty($_POST['gender']) || empty($_POST['pwd']) || empty($_POST['cpwd']) || empty($_POST['mail']) || empty($_POST['city'])) {
        $msg .= "<li>Please fill all required fields.";
    }

    // Check if passwords match
    if ($_POST['pwd'] != $_POST['cpwd']) {
        $msg .= "<li>Passwords do not match. Please try again.";
    }

    // Email validation
    if (!preg_match("/^[a-z0-9_]+[a-z0-9_.]*@[a-z0-9_-]+[a-z0-9_.-]*\.[a-z]{2,5}$/", $_POST['mail'])) {
        $msg .= "<li>Please enter a valid email address.";
    }

    // Password length check (max 10 characters)
    if (strlen($_POST['pwd']) > 10) {
        $msg .= "<li>Password should be at most 10 characters long.";
    }

    // Name validation (must not be numeric)
    if (is_numeric($_POST['fnm'])) {
        $msg .= "<li>Name must be in text format.";
    }

    // If there are errors, redirect with error message
    if ($msg != "") {
        header("Location: register.php?error=" . urlencode($msg));
        exit();
    } else {
        $fnm = $_POST['fnm'];
        $unm = $_POST['unm'];
        $pwd = $_POST['pwd'];
        $gender = $_POST['gender'];
        $email = $_POST['mail'];
        $contact = $_POST['contact'];
        $city = $_POST['city'];

        // MySQLi database connection
        $link = mysqli_connect("localhost", "root", "", "final_shop");

        if (!$link) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Prepared statement to insert user data
        $stmt = $link->prepare("INSERT INTO user (u_fnm, u_unm, u_pwd, u_gender, u_email, u_contact, u_city) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $fnm, $unm, $pwd, $gender, $email, $contact, $city);

        if ($stmt->execute()) {
            header("Location: register.php?ok=1"); // Registration successful
        } else {
            die("Error executing query: " . $stmt->error);
        }

        // Close the statement and connection
        $stmt->close();
        mysqli_close($link);
    }
} else {
    header("Location: index.php");
}
?>
