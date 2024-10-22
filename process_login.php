<?php
session_start();

if (!empty($_POST)) {
    $errors = [];

    // Check for empty username
    if (empty($_POST['usernm'])) {
        $errors[] = "No such User";
    }

    // Check for empty password
    if (empty($_POST['pwd'])) {
        $errors[] = "Password Incorrect........";
    }

    // If there are errors, display them
    if (!empty($errors)) {
        echo '<b>Error:</b><br>';
        foreach ($errors as $error) {
            echo '<li>' . htmlspecialchars($error);
        }
    } else {
        // Database connection using MySQLi
        $link = mysqli_connect("localhost", "root", "", "final_shop");
        
        if (!$link) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $unm = $_POST['usernm'];
        $pwd = $_POST['pwd'];

        // Prepared statement to prevent SQL injection
        $stmt = $link->prepare("SELECT * FROM user WHERE u_unm = ?");
        $stmt->bind_param("s", $unm);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        // Check if user exists
        if (!empty($row)) {
            // Check if the password matches
            if ($pwd === $row['u_pwd']) {
                // Set session variables
                $_SESSION = array();
                $_SESSION['unm'] = $row['u_unm'];
                $_SESSION['uid'] = $row['u_pwd'];
                $_SESSION['status'] = true;

                // Redirect based on user role
                if ($_SESSION['unm'] !== "admin") {
                    header("Location: index.php");
                } else {
                    header("Location: admin/index.php");
                }
                exit();
            } else {
                echo 'Incorrect Password....';
            }
        } else {
            echo 'Invalid User';
        }

        // Close the statement and connection
        $stmt->close();
        mysqli_close($link);
    }
} else {
    header("Location: index.php");
}
?>
