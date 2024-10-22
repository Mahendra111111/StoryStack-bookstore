<?php 
session_start();

// Establish a connection to the database using MySQLi
$link = new mysqli("localhost", "root", "", "shop");

// Check connection
if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

// Get subcategory ID from the URL, ensuring it's an integer
$cat = isset($_GET['subcatid']) ? (int)$_GET['subcatid'] : 0;

// Prepare total query to count the number of books in the subcategory
$totalq = "SELECT COUNT(*) AS total FROM book WHERE b_subcat = ?";
$totalStmt = $link->prepare($totalq);
$totalStmt->bind_param("i", $cat);
$totalStmt->execute();
$totalres = $totalStmt->get_result();
$totalrow = $totalres->fetch_assoc();

$page_per_page = 1;
$page_total_rec = $totalrow['total'];
$page_total_page = ceil($page_total_rec / $page_per_page);

// Determine the current page
$page_current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page_current_page < 1) {
    $page_current_page = 1; // Ensure page number is at least 1
}

// Calculate the offset for the SQL query
$offset = ($page_current_page - 1) * $page_per_page;

// Prepare the query to fetch books for the current page
$query = "SELECT * FROM book WHERE b_subcat = ? LIMIT ?, ?";
$stmt = $link->prepare($query);
$stmt->bind_param("iii", $cat, $offset, $page_per_page);
$stmt->execute();
$res = $stmt->get_result();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php include("includes/head.inc.php"); ?>
</head>

<body>
    <!-- Start header -->
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
    <!-- End header -->
    
    <!-- Start page -->
    <div id="page">
        <!-- Start content -->
        <div id="content">
            <div class="post">
                <h1 class="title"><?php echo htmlspecialchars($_GET['subcatnm']); ?></h1>
                <div class="entry">
                    <table style="border:none;" width="100%">
                        <?php
                        while ($row = $res->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td valign="top" width="20%" align="center">
                                <a href="detail.php?id=' . htmlspecialchars($row['b_id']) . '&cat=' . urlencode($_GET['subcatnm']) . '">
                                <img src="' . htmlspecialchars($row['b_img']) . '" width="80" height="100">
                                <br>' . htmlspecialchars($row['b_nm']) . '</a>
                            </td>';
                            echo '</tr>';
                        }
                        ?>
                    </table>
                    
                    <!-- Pagination Links -->
                    <div>
                        <?php
                        if ($page_current_page > 1) {
                            echo '<a href="booklist.php?subcatid=' . urlencode($_GET['subcatid']) . '&subcatnm=' . urlencode($_GET['subcatnm']) . '&page=' . ($page_current_page - 1) . '">Previous</a> ';
                        }

                        if ($page_total_page > $page_current_page) {
                            echo '<a href="booklist.php?subcatid=' . urlencode($_GET['subcatid']) . '&subcatnm=' . urlencode($_GET['subcatnm']) . '&page=' . ($page_current_page + 1) . '">Next</a>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- End content -->
        
        <!-- Start sidebar -->
        <div id="sidebar">
            <?php include("includes/search.inc.php"); ?>
        </div>
        <!-- End sidebar -->
        
        <div style="clear: both;">&nbsp;</div>
    </div>
    <!-- End page -->
    
    <!-- Start footer -->
    <div id="footer">
        <?php include("includes/footer.inc.php"); ?>
    </div>
    <!-- End footer -->
</body>
</html>

<?php
// Close the database connection
$totalStmt->close();
$stmt->close();
$link->close();
?>
