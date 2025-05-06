<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/> 
    <title>E-COMMERCE</title>
    <style>
        footer {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            padding: 60px 80px;
            background: rgba(255, 255, 255, 0.1);
            margin-top: 40px;
        }

        footer .col {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        footer .logo {
            height: 40px;
            margin-bottom: 20px;
        }

        footer h4 {
            color: #3498db;
            font-size: 18px;
            margin-bottom: 20px;
        }

        footer p {
            color: #fff;
            font-size: 14px;
            margin: 0 0 8px 0;
        }

        footer a {
            color: #fff;
            font-size: 14px;
            text-decoration: none;
            margin-bottom: 10px;
            transition: color 0.3s ease;
        }

        footer a:hover {
            color: #3498db;
        }

        footer .follow {
            margin-top: 20px;
        }

        footer .follow .icon {
            display: flex;
            gap: 15px;
        }

        footer .follow i {
            color: #fff;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        footer .follow i:hover {
            color: #3498db;
            transform: translateY(-3px);
        }

        footer .install .row {
            display: flex;
            gap: 10px;
            margin: 10px 0 15px 0;
        }

        footer .install .row img {
            height: 40px;
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        footer .install img {
            max-width: 220px;
        }

        footer .copyright {
            width: 100%;
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #95a5a6;
        }

        @media (max-width: 768px) {
            footer {
                padding: 40px 20px;
            }

            footer .col {
                width: 100%;
                margin-bottom: 30px;
            }

            footer .install {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    
    <footer class="section-p1">
        <div class="col">
            <img class="logo" src="IMAGES/logo.png" alt="">
            <h4>Contact</h4>
            <p><strong>Address:</strong> Surat </p>
            <p><strong>Phone:</strong> +91 99999 99999</p>
            <p><strong>Hours:</strong> 10:00-18:00, Mon - Sat</p>
            <div class="follow">
                <h4>Follow us</h4>
                <div class="icon">
                    <i class="fab fa-facebook-f"></i>
                    <i class="fab fa-twitter"></i>
                    <i class="fab fa-instagram"></i>
                    <i class="fab fa-pinterest-p"></i>
                    <i class="fab fa-youtube"></i>
                </div>
            </div>
        </div>
        <div class="col">
            <h4>About us</h4>
            <a href="#">About us</a>
            <a href="#">Delivery Information</a>
            <a href="#">Privacy Policy</a>
            <a href="#">Terms & Conditions</a>
            <a href="#">Contact us</a>
        </div>
        <div class="col">
            <h4>My Account</h4>
            <a href="login.php">Sign In</a>
            <a href="cart.php">View Cart</a>
            <a href="#">My Wishlist</a>
            <a href="#">Track My Order</a>
            <a href="#">Help</a>
        </div>
        <div class="col install">
            <h4>Install App</h4>
            <p>From App Store or Google Play</p>
            <div class="row">
                <img src="IMAGES/pay/app.jpg" alt="">
                <img src="IMAGES/pay/play.jpg" alt="">
            </div>
            <p>Secured Payment gateways</p>
            <img src="IMAGES/pay/pay.png" alt="">
        </div>
        <div class="copyright">
            <p>&copy; <?php echo date("Y"); ?> My Awesome Store. All rights reserved.</p>
        </div>
    </footer>
    
</body>
</html>











