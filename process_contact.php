<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $msg = [];

    // Check if required fields are filled
    if (empty($_POST['nm']) || empty($_POST['email']) || empty($_POST['query'])) {
        $msg[] = "Please fulfill all requirements.";
    }

    // Validate name
    if (!preg_match("/^[a-zA-Z\s]+$/", $_POST['nm'])) {
        $msg[] = "Name must be in string format.";
    }

    // Validate email
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $msg[] = "Email must be in an appropriate format.";
    }

    // Check if there are any error messages
    if (!empty($msg)) {
        echo '<b>Error:</b><br><ul>';
        foreach ($msg as $k) {
            echo '<li>' . htmlspecialchars($k) . '</li>'; // Escaping output to prevent XSS
        }
        echo '</ul>';
    } else {
        // Sanitize input
        $nm = htmlspecialchars(trim($_POST['nm']));
        $email = htmlspecialchars(trim($_POST['email']));
        $question = htmlspecialchars(trim($_POST['query']));

        // Establish database connection
        $link = new mysqli("localhost", "root", "", "shop");

        // Check connection
        if ($link->connect_error) {
            die("Connection failed: " . $link->connect_error);
        }

        // Prepare the insert statement
        $stmt = $link->prepare("INSERT INTO contact (con_nm, con_email, con_query) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nm, $email, $question);

        // Execute the statement and check for errors
        if ($stmt->execute()) {
            header("Location: contact.php");
            exit; // Ensure no further code is executed after redirection
        } else {
            echo "Error: " . htmlspecialchars($stmt->error);
        }

        // Close statement and connection
        $stmt->close();
        $link->close();
    }
} else {
    header("Location: index.php");
    exit; // Ensure no further code is executed after redirection
}
?>
