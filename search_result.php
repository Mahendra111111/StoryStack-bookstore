<?php 
session_start();

// Establish a connection to the database using MySQLi
$link = new mysqli("localhost", "root", "", "final_shop");

// Check for connection errors
if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

// Sanitize and validate the search term
$search = isset($_GET['s']) ? trim($_GET['s']) : '';
if (empty($search)) {
    die("Search term cannot be empty.");
}

// Prepare the SQL query
$query = "SELECT * FROM book WHERE b_nm LIKE ?";
$stmt = $link->prepare($query);
$likeSearch = "%$search%";
$stmt->bind_param("s", $likeSearch);
$stmt->execute();
$res = $stmt->get_result();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php include("includes/head.inc.php"); ?>
</head>

<body>
    <!-- start header -->
    <div id="header">
        <div id="menu">
            <?php include("includes/menu.inc.php"); ?>
        </div>
    </div>

    <div id="logo-wrap">
        <div id="logo">
            <?php include("includes/logo.inc.php"); ?>
        </div>
    </div>
    <!-- end header -->

    <!-- start page -->
    <div id="page">
        <!-- start content -->
        <div id="content">
            <div class="post">
                <h1 class="title"><?php echo htmlspecialchars($search); ?></h1>
                <div class="entry">

                    <table border="0" width="100%">
                        <?php
                        $count = 0;
                        // Check if there are results
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                if ($count == 0) {
                                    echo '<tr>';
                                }

                                echo '<td valign="top" width="20%" align="center">
                                    <a href="detail.php?id=' . $row['b_id'] . '">
                                    <img src="' . htmlspecialchars($row['b_img']) . '" width="80" height="100">
                                    <br>' . htmlspecialchars($row['b_nm']) . '</a>
                                </td>';
                                $count++;

                                if ($count == 4) {
                                    echo '</tr>';
                                    $count = 0;
                                }
                            }
                            // Close the last row if it wasn't closed
                            if ($count > 0) {
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="4" align="center">No results found.</td></tr>';
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
        <!-- end content -->

        <!-- start sidebar -->
        <div id="sidebar">
            <?php include("includes/search.inc.php"); ?>
        </div>
        <!-- end sidebar -->
        <div style="clear: both;">&nbsp;</div>
    </div>
    <!-- end page -->

    <!-- start footer -->
    <div id="footer">
        <?php include("includes/footer.inc.php"); ?>
    </div>
    <!-- end footer -->
</body>
</html>