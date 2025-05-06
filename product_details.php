<?php
session_start();
include 'INCLUDE/connection.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$product_id = $_GET['id'];
$sql = "SELECT * FROM products WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <main>
        <div class="product-details">
            <div class="product-image">
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
            <div class="product-info">
                <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                <div class="description">
                    <h3>Description:</h3>
                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                </div>
                <p class="price">PRICE: ₹<?php echo number_format($product['price'], 2); ?></p>
                <p class="availability">Availability: In Stock</p>
                <div class="add-to-cart-section">
                    <form action="add_to_cart.php" method="post">
                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                        <div class="quantity-controls">
                            <label>Quantity:</label>
                            <input type="number" name="quantity" value="1" min="1" max="10">
                        </div>
                        <button type="submit" class="add-to-cart-btn">ADD TO CART</button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Rating and Comments Section -->
        <!-- Rating Section -->
        <div class="rating-section">
            <h3>Product Rating</h3>
            <?php
            // Get average rating
            $rating_sql = "SELECT AVG(rating) as avg_rating, COUNT(*) as total_ratings FROM ratings WHERE product_id = ?";
            $rating_stmt = $conn->prepare($rating_sql);
            $rating_stmt->bind_param("i", $product_id);
            $rating_stmt->execute();
            $rating_result = $rating_stmt->get_result()->fetch_assoc();
            $avg_rating = round($rating_result['avg_rating'], 1);
            $total_ratings = $rating_result['total_ratings'];
            
            // Get user's rating if logged in
            $user_rating = 0;
            if (isset($_SESSION['user_id'])) {
                $user_rating_sql = "SELECT rating FROM ratings WHERE user_id = ? AND product_id = ?";
                $user_rating_stmt = $conn->prepare($user_rating_sql);
                $user_rating_stmt->bind_param("ii", $_SESSION['user_id'], $product_id);
                $user_rating_stmt->execute();
                $user_rating_result = $user_rating_stmt->get_result();
                if ($user_rating_result->num_rows > 0) {
                    $user_rating = $user_rating_result->fetch_assoc()['rating'];
                }
            }
            ?>
            <div class="stars" data-product-id="<?php echo $product_id; ?>">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <span class="star <?php echo ($i <= $user_rating) ? 'active' : ''; ?>" data-rating="<?php echo $i; ?>">★</span>
                <?php endfor; ?>
            </div>
            <p>Average Rating: <?php echo $avg_rating; ?>/5 (<?php echo $total_ratings; ?> ratings)</p>
        </div>
        
        <!-- Add this JavaScript before closing body tag -->
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star');
            const productId = document.querySelector('.stars').dataset.productId;
        
            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const rating = this.dataset.rating;
                    
                    fetch('add_rating.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `product_id=${productId}&rating=${rating}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update stars display
                            stars.forEach(s => {
                                if (s.dataset.rating <= rating) {
                                    s.classList.add('active');
                                } else {
                                    s.classList.remove('active');
                                }
                            });
                            location.reload(); // Refresh to update average
                        } else {
                            alert(data.error || 'Error saving rating');
                        }
                    });
                });
        
                // Hover effects
                star.addEventListener('mouseover', function() {
                    const rating = this.dataset.rating;
                    stars.forEach(s => {
                        if (s.dataset.rating <= rating) {
                            s.classList.add('hover');
                        }
                    });
                });
        
                star.addEventListener('mouseout', function() {
                    stars.forEach(s => s.classList.remove('hover'));
                });
            });
        });
        </script>
        
        <!-- After the product details section -->
        <div class="comment-section">
            <h3>Comments</h3>
            <?php if (isset($_SESSION['user_id'])): ?>
                <form action="add_comment.php" method="post" class="comment-form">
                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                    <textarea name="comment" placeholder="Write your comment..." required></textarea>
                    <button type="submit">Post Comment</button>
                </form>
            <?php else: ?>
                <p>Please <a href="login.php">login</a> to post a comment.</p>
            <?php endif; ?>

            <div class="comments-list">
                <?php
                $comments_sql = "SELECT c.*, u.username FROM comments c 
                                JOIN users u ON c.user_id = u.user_id 
                                WHERE c.product_id = ? 
                                ORDER BY c.created_at DESC";
                $stmt = $conn->prepare($comments_sql);
                $stmt->bind_param("i", $product_id);
                $stmt->execute();
                $comments = $stmt->get_result();
                
                if ($comments->num_rows > 0):
                    while ($comment = $comments->fetch_assoc()):
                ?>
                    <div class="comment-item">
                        <div class="comment-user"><?php echo htmlspecialchars($comment['username']); ?></div>
                        <div class="comment-text"><?php echo htmlspecialchars($comment['comment']); ?></div>
                        <div class="comment-date"><?php echo date('M d, Y', strtotime($comment['created_at'])); ?></div>
                    </div>
                <?php 
                    endwhile;
                else:
                ?>
                    <p>No comments yet. Be the first to comment!</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>