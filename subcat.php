<?php
session_start();

// MySQLi connection
$link = mysqli_connect("localhost", "root", "", "final_shop");

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get category ID and name from the URL, ensuring they are sanitized
$catId = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;
$catNm = isset($_GET['catnm']) ? htmlspecialchars($_GET['catnm']) : '';

// Prepare and execute the query to fetch subcategories
$stmt = $link->prepare("SELECT * FROM subcat WHERE parent_id = ?");
$stmt->bind_param("i", $catId);
$stmt->execute();
$res = $stmt->get_result();

$row1 = $res->fetch_assoc();

// Check if the requested category name matches
if ($catNm == $row1['subcat_nm']) {
    header("Location: booklist.php?subcatid=" . $row1['subcat_id'] . "&subcatnm=" . urlencode($row1["subcat_nm"]));
    exit();
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php include("includes/head.inc.php"); ?>
</head>

<body>
    <!-- Start Header -->
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
    <!-- End Header -->

    <!-- Start Page -->
    <div id="page">
        <!-- Start Content -->
        <div id="content">
            <div class="post">
                <h1 class="title"><?php echo $catNm; ?></h1>
                <div class="entry">
                    <ul>
                        <?php
                        // Loop through all subcategories
                        do {
                            echo '<li><a href="booklist.php?subcatid=' . $row1['subcat_id'] . '&subcatnm=' . urlencode($row1["subcat_nm"]) . '">' . htmlspecialchars($row1['subcat_nm']) . '</a></li>';
                        } while ($row1 = $res->fetch_assoc());
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Content -->

        <!-- Start Sidebar -->
        <div id="sidebar">
            <?php include("includes/search.inc.php"); ?>
        </div>
        <!-- End Sidebar -->
        <div style="clear: both;">&nbsp;</div>
    </div>
    <!-- End Page -->

    <!-- Start Footer -->
    <div id="footer">
        <?php include("includes/footer.inc.php"); ?>
    </div>
    <!-- End Footer -->
</body>
</html>
