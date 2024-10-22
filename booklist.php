<?php 
session_start();

// Establish a connection to the database using MySQLi
$link = new mysqli("localhost", "root", "", "final_shop");

// Check connection
if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

// Get subcategory ID from the URL, ensure it's an integer
$cat = isset($_GET['subcatid']) ? (int)$_GET['subcatid'] : 0;

$totalq = "SELECT COUNT(*) AS total FROM book WHERE b_subcat = ?";
$stmt = $link->prepare($totalq);
$stmt->bind_param("i", $cat);
$stmt->execute();
$totalres = $stmt->get_result();
$totalrow = $totalres->fetch_assoc();

$page_per_page = 6;
$page_total_rec = $totalrow['total'];
$page_total_page = ceil($page_total_rec / $page_per_page);

// Set current page
$page_current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page_current_page < 1) {
    $page_current_page = 1;
}

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
                    <table border="1" width="100%">
                        <br><br><br><br><br>
                        <?php
                        $offset = ($page_current_page - 1) * $page_per_page;
                        $query = "SELECT * FROM book WHERE b_subcat = ? LIMIT ?, ?";
                        $stmt = $link->prepare($query);
                        $stmt->bind_param("iii", $cat, $offset, $page_per_page);
                        $stmt->execute();
                        $res = $stmt->get_result();

                        $count = 0;
                        while ($row = $res->fetch_assoc()) {
                            if ($count == 0) {
                                echo '<tr>';
                            }
                            echo '<td valign="top" width="20%" align="center">
                                <a href="detail.php?id=' . $row['b_id'] . '&cat=' . urlencode($_GET['subcatnm']) . '">
                                <img src="' . htmlspecialchars($row['b_img']) . '" width="80" height="100">
                                <br>' . htmlspecialchars($row['b_nm']) . '</a>
                            </td>';
                            $count++;
                            
                            if ($count == 2) {
                                echo '</tr>';
                                $count = 0;
                            }
                        }

                        if ($count > 0) {
                            echo '</tr>'; // Close the last row if it's not complete
                        }

                        ?>
                    </table>
                    
                    <br><br><br>
                    <center>
                    <?php
                    if ($page_total_page > $page_current_page) {
                        echo '<a href="booklist.php?subcatid=' . urlencode($_GET['subcatid']) . '&subcatnm=' . urlencode($_GET['subcatnm']) . '&page=' . ($page_current_page + 1) . '">Next</a>';
                    }
                    
                    for ($i = 1; $i <= $page_total_page; $i++) {
                        echo '&nbsp;&nbsp;<a href="booklist.php?subcatid=' . urlencode($_GET['subcatid']) . '&subcatnm=' . urlencode($_GET['subcatnm']) . '&page=' . $i . '">' . $i . '</a>&nbsp;&nbsp;';
                    }
                    
                    if ($page_current_page > 1) {
                        echo '<a href="booklist.php?subcatid=' . urlencode($_GET['subcatid']) . '&subcatnm=' . urlencode($_GET['subcatnm']) . '&page=' . ($page_current_page - 1) . '">Previous</a>';
                    }
                    ?>
                    </center>
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
$stmt->close();
$link->close();
?>
