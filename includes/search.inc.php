<ul>
    <li id="login">
        <?php
        // Ensure session is started at the beginning of the main file
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION['status'])) {
            echo '<h2>Hello : ' . htmlspecialchars($_SESSION['unm']) . '</h2>';
        } else {
            echo '<form action="process_login.php" method="POST">
                    <h2>LogIn</h2>
                    <b>Username:</b>
                    <br><input type="text" name="usernm" required><br>
                    <br>
                    <b>Password:</b>
                    <br><input type="password" name="pwd" required>
                    <input type="submit" id="x" value="Login" />
                  </form>';
        }
        ?>
    </li>

    <li id="search">
        <h2>Search</h2>
        <form method="GET" action="search_result.php">
            <fieldset>
                <input type="text" id="s" name="s" placeholder="Search Here" value="" required />
                <input type="submit" id="x" value="Search" />
            </fieldset>
        </form>
    </li>

    <li>
        <h2>Categories</h2>
        <ul>
            <?php
            // MySQLi connection
            $link = mysqli_connect("localhost", "root", "", "final_shop");

            if (!$link) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Query to fetch categories
            $query = "SELECT * FROM category";
            $stmt = $link->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                echo '<li><a href="subcat.php?cat=' . urlencode($row['cat_id']) . '&catnm=' . urlencode($row["cat_nm"]) . '">' . htmlspecialchars($row["cat_nm"]) . '</a></li>';
            }

            // Close connection
            $stmt->close();
            mysqli_close($link);
            ?>
        </ul>
    </li>
</ul>
