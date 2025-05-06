<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <main>
        <section class="product-listing">
            <h2>Search Results</h2>
            <div class="product-grid">
                <?php
                include 'INCLUDE/connection.php';

                $category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : null;
                $product_name = isset($_GET['product_name']) ? $_GET['product_name'] : '';

                $sql = "SELECT product_id, name, description, price, image_url FROM products WHERE 1=1";
                if ($category_id) {
                    $sql .= " AND category_id = ?";
                }
                if (!empty($product_name)) {
                    $sql .= " AND name LIKE ?";
                }

                $stmt = $conn->prepare($sql);

                if ($category_id && !empty($product_name)) {
                    $product_name = '%' . $product_name . '%';
                    $stmt->bind_param("is", $category_id, $product_name);
                } elseif ($category_id) {
                    $stmt->bind_param("i", $category_id);
                } elseif (!empty($product_name)) {
                    $product_name = '%' . $product_name . '%';
                    $stmt->bind_param("s", $product_name);
                }

                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="product-item">';
                        echo '<a href="product_details.php?id=' . $row["product_id"] . '">';
                        echo '<img src="' . $row["image_url"] . '" alt="' . $row["name"] . '" width="150">';
                        echo '<h3>' . $row["name"] . '</h3>';
                        echo '<p class="price">₹' . $row["price"] . '</p>';
                        echo '</a>';
                        echo '<form action="add_to_cart.php" method="post">';
                        echo '<input type="hidden" name="product_id" value="' . $row["product_id"] . '">';
                        echo '<button type="submit">Add to Cart</button>';
                        echo '</form>';
                        echo '</div>';
                    }
                } else {
                    echo "<p>No products found matching your criteria.</p>";
                }

                $stmt->close();
                $conn->close();
                ?>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> My E-commerce Store</p>
    </footer>
    <script src="js/script.js"></script>
</body>
</html>