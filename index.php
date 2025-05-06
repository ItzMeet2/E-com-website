<?php
session_start();
include 'INCLUDE/connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cara</title>
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-image: url('IMAGES/background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .main-content {
            min-height: 100vh;
            padding: 20px;
            /* Removed the white background */
            background: transparent;
        }

        /* Add styles to make product content more readable */
        .product-listing {
            background: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .product-listing h2 {
            color: #fff;
            margin-bottom: 20px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .product-item {
            background: rgba(255, 255, 255, 0.9);
            padding: 15px;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .product-item:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <main class="main-content">  
        <?php
        // Get all categories
        $category_sql = "SELECT * FROM categories";
        $category_result = $conn->query($category_sql);

        if ($category_result->num_rows > 0) {
            while ($category = $category_result->fetch_assoc()) {
                echo '<section class="product-listing">';
                echo '<h2>' . $category["name"] . '</h2>';
                echo '<div class="product-grid">';

                // Get products for this category
                $sql = "SELECT product_id, name, description, price, image_url 
                       FROM products 
                       WHERE category_id = " . $category["category_id"];
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="product-item">';
                        echo '<a href="product_details.php?id=' . $row["product_id"] . '">';
                        echo '<img src="' . $row["image_url"] . '" alt="' . $row["name"] . '">';
                        echo '<h3>' . $row["name"] . '</h3>';
                        echo '<p class="price">₹' . number_format($row["price"], 2) . '</p>';
                        echo '</a>';
                        echo '<form action="add_to_cart.php" method="post">';
                        echo '<input type="hidden" name="product_id" value="' . $row["product_id"] . '">';
                        echo '<input type="hidden" name="redirect" value="cart.php">';
                        echo '<button type="submit" class="add-to-cart-btn">Add to Cart</button>';
                        echo '</form>';
                        echo '</div>';
                    }
                } else {
                    echo "<p class='no-products'>No products in this category.</p>";
                }
                echo '</div>';
                echo '</section>';
            }
        } else {
            echo "<p class='no-categories'>No categories available.</p>";
        }

        $conn->close();
        ?>
    </main>
    
    <?php include 'footer.php'; ?>
</body>
</html>