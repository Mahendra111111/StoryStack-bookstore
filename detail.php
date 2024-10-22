<?php 
session_start();

// Establish a connection to the database using MySQLi
$link = new mysqli("localhost", "root", "", "final_shop");

// Check connection
if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

// Get book ID from the URL, ensuring it's an integer
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Prepare the SQL statement to prevent SQL injection
$q = "SELECT * FROM book WHERE b_id = ?";
$stmt = $link->prepare($q);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();
} else {
    die("No book found.");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" href="lightbox.css" type="text/css" media="screen" />
    <script src="js/prototype.js" type="text/javascript"></script>
    <script src="js/scriptaculous.js?load=effects" type="text/javascript"></script>
    <script src="js/lightbox.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/java.js"></script>
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
                <h1 class="title"><?php echo htmlspecialchars($row['b_nm']); ?></h1>
                <div class="entry">
                    <table border="0" width="100%">
                        <tr>
                            <td><hr color="purple"></td>
                        </tr>
                        <tr align="center" bgcolor="#EEE9F3">
                            <td>Item Details</td>
                        </tr>
                        <tr>
                            <td><hr color="purple"></td>
                        </tr>
                    </table>
                    
                    <table border="0" width="100%" bgcolor="#ffffff">
                        <tr> 
                            <td width="15%" rowspan="3">
                                <img src="<?php echo htmlspecialchars($row['b_img']); ?>" width="100">
                            </td>
                        </tr>
                        <tr> 
                            <td width="50%" height="100%">
                                <table border="0" width="100%" height="100%">
                                    <tr valign="top">
                                        <td align="right" width="10%">NAME</td>
                                        <td width="6%">: </td>
                                        <td align="left"><?php echo htmlspecialchars($row['b_nm']); ?></td>
                                    </tr>
                                    <tr>
                                        <td align="right">ISBN</td>
                                        <td>: </td>
                                        <td align="left"><?php echo htmlspecialchars($row['b_isbn']); ?></td>
                                    </tr>
                                    <tr>
                                        <td align="right">Publisher</td>
                                        <td>: </td>
                                        <td align="left"><?php echo htmlspecialchars($row['b_publisher']); ?></td>
                                    </tr>                                            
                                    <tr>
                                        <td align="right">Edition</td>
                                        <td>: </td>
                                        <td align="left"><?php echo htmlspecialchars($row['b_edition']); ?></td>
                                    </tr>
                                    <tr>
                                        <td align="right">PAGES</td>
                                        <td>: </td>
                                        <td align="left"><?php echo htmlspecialchars($row['b_page']); ?></td>
                                    </tr>
                                    <tr>
                                        <td align="right">PRICE</td>
                                        <td>: </td>
                                        <td align="left"><?php echo htmlspecialchars($row['b_price']); ?></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    
                    <tr valign="bottom">
                        <a href="<?php echo htmlspecialchars($row['b_img']); ?>" rel="lightbox"><img src="images/zoom.gif"></a>
                    </tr>
                    
                    <table border="0" width="100%">
                        <tr>
                            <td><hr color="purple"></td>
                        </tr>
                        <tr align="center" bgcolor="#EEE9F3">
                            <td>DESCRIPTION</td>
                        </tr>
                        <tr>
                            <td><hr color="purple"></td>
                        </tr>
                    </table>
                    
                    <?php echo nl2br(htmlspecialchars($row['b_desc'])); ?>
                    
                    <tr><td colspan="2"><hr color="purple"></td></tr>
                    
                    <table border="0" width="100%">
                        <tr align="center" bgcolor="#EEE9F3">
                            <?php
                            if (isset($_SESSION['status'])) {
                                
                                echo '<td><a href="process_cart.php?nm=' . urlencode($row['b_nm']) . '&cat=' . urlencode($_GET['cat']) . '&rate=' . htmlspecialchars($row['b_price']) . '">
                                    <img src="images/addcart.jpg">
                                </a></td>';
                            } else {
                                echo '<td><img src="images/addcart.jpg"><br><a href="register.php"><h4>Please Login..</h4></a></td>';
                            }
                            ?>
                        </tr>
                    </table>
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
