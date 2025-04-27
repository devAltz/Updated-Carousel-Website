<?php
// Start session to access uploaded file info
session_start();

// Function to display a placeholder if no image exists
function getImageOrPlaceholder($image_path, $alt_text = "Image") {
    if (!empty($image_path) && file_exists($image_path)) {
        return '<img src="' . htmlspecialchars($image_path) . '" alt="' . htmlspecialchars($alt_text) . '">';
    } else {
        return '<div class="placeholder-image">' . htmlspecialchars($alt_text) . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Preview</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Header Styles */
        header {
            background-color: #2c3e50;
            color: white;
            padding: 20px 0;
        }
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
        }
        .navigation ul {
            list-style-type: none;
            display: flex;
            margin: 0;
            padding: 0;
        }
        .navigation li {
            margin-left: 20px;
        }
        .navigation a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        .navigation a:hover {
            text-decoration: underline;
        }
        
        /* Image-Only Carousel Styles */
        .image-carousel {
            position: relative;
            margin-top: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            height: 300px;
            margin-bottom: 20px; /* Add space between carousels */
        }
        .image-carousel-inner {
            display: flex;
            transition: transform 0.3s ease;
            height: 100%;
        }
        .image-carousel-item {
            flex: 0 0 100%;
            height: 100%;
            position: relative;
            background-color: #e9ecef;
        }
        .image-carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        /* Main Carousel Styles */
        .carousel {
            position: relative;
            background-color: #f8f9fa;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-top: 0;
        }
        .carousel-inner {
            display: flex;
            transition: transform 0.3s ease;
        }
        .carousel-item {
            flex: 0 0 100%;
            padding: 0;
            position: relative;
            min-height: 300px;
            display: flex;
            background-color: #e9ecef;
        }
        .carousel-image {
            flex: 0 0 60%;
            position: relative;
            overflow: hidden;
        }
        .carousel-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .carousel-caption {
            flex: 0 0 40%;
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .carousel-caption h3 {
            margin-top: 0;
            font-size: 1.8rem;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        .carousel-caption p {
            font-size: 1rem;
            line-height: 1.6;
            color: #555;
        }
        .placeholder-image {
            background-color: #ddd;
            color: #666;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        .carousel-control {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            background-color: rgba(0,0,0,0.5);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
        }
        .carousel-control-prev {
            left: 10px;
        }
        .carousel-control-next {
            right: 10px;
        }
        .carousel-indicators {
            position: absolute;
            bottom: 10px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            gap: 10px;
            z-index: 10;
        }
        .carousel-indicator {
            width: 10px;
            height: 10px;
            background-color: rgba(255,255,255,0.5);
            border-radius: 50%;
            cursor: pointer;
        }
        .carousel-indicator.active {
            background-color: white;
        }
        
        /* Combined Carousel Container */
        .carousel-container {
            margin-top: 20px;
        }
        
        /* Main Content Styles */
        .main-content {
            display: flex;
            margin-top: 30px;
            gap: 30px;
        }
        .content {
            flex: 1;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .sidebar {
            flex: 0 0 300px;
            position: sticky;
            top: 20px;
            align-self: flex-start;
        }
        .sidebar-widget {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .sidebar-widget h3 {
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .sidebar-banner img {
            width: 100%;
            border-radius: 4px;
        }
        
        /* Text Banner Styles */
        .text-banner {
            background-color: #3498db;
            color: white;
            padding: 25px;
            margin: 30px 0;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .text-banner h2 {
            margin-top: 0;
            margin-bottom: 10px;
        }
        .text-banner p {
            margin-bottom: 0;
            font-size: 1.1rem;
        }
        
        /* Footer Styles */
        footer {
            background-color: #2c3e50;
            color: white;
            padding: 40px 0 20px;
            margin-top: 40px;
        }
        .footer-content {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 30px;
        }
        .footer-section {
            flex: 1;
            min-width: 200px;
        }
        .footer-section h3 {
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .footer-section ul {
            list-style-type: none;
            padding: 0;
        }
        .footer-section li {
            margin-bottom: 10px;
        }
        .footer-section a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
        }
        .footer-section a:hover {
            color: white;
        }
        .footer-banner {
            background-color: #1a2c3d;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: 20px;
        }
        
        /* Admin Controls */
        .admin-controls {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
        }
        .admin-button {
            display: inline-block;
            background-color: #721c24;
            color: white;
            padding: 10px 15px;
            border-radius: 4px;
            text-decoration: none;
            margin-top: 10px;
        }
        
        /* No Carousel Items Message */
        .no-items-message {
            text-align: center;
            padding: 50px 20px;
            background-color: #e9ecef;
            border-radius: 8px;
            color: #555;
        }

        /* Responsive Styles */
        @media (max-width: 900px) {
            .carousel-image, .carousel-caption {
                flex: 0 0 100%;
            }
            .carousel-item {
                flex-direction: column;
                min-height: auto;
            }
            .carousel-image {
                height: 250px;
            }
            .main-content {
                flex-direction: column;
            }
            .sidebar {
                flex: 0 0 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container header-content">
            <div class="logo">Website Preview</div>
            <nav class="navigation">
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Services</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <!-- Combined Carousel Container -->
        <div class="carousel-container">
            <?php if (isset($_SESSION["carousel_items"]) && count($_SESSION["carousel_items"]) > 0): ?>
                <!-- Image-Only Carousel -->
                <div class="image-carousel">
                    <div class="image-carousel-inner" id="image-carousel-inner">
                        <?php foreach ($_SESSION["carousel_items"] as $index => $item): ?>
                            <div class="image-carousel-item">
                                <?php echo getImageOrPlaceholder($item["image"], $item["title"]); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Image Carousel Controls -->
                    <div class="carousel-control carousel-control-prev" id="img-prev-btn">
                        <i class="fas fa-chevron-left"></i>
                    </div>
                    <div class="carousel-control carousel-control-next" id="img-next-btn">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
                
                <!-- Main Carousel with Text -->
                <div class="carousel">
                    <div class="carousel-inner" id="carousel-inner">
                        <?php foreach ($_SESSION["carousel_items"] as $index => $item): ?>
                            <div class="carousel-item">
                                <div class="carousel-image">
                                    <?php echo getImageOrPlaceholder($item["image"], $item["title"]); ?>
                                </div>
                                <div class="carousel-caption">
                                    <h3><?php echo htmlspecialchars($item["title"]); ?></h3>
                                    <p><?php echo nl2br(htmlspecialchars($item["description"])); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Carousel Controls -->
                    <div class="carousel-control carousel-control-prev" id="prev-btn">
                        <i class="fas fa-chevron-left"></i>
                    </div>
                    <div class="carousel-control carousel-control-next" id="next-btn">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                    
                    <!-- Carousel Indicators -->
                    <div class="carousel-indicators" id="carousel-indicators">
                        <?php for ($i = 0; $i < count($_SESSION["carousel_items"]); $i++): ?>
                            <div class="carousel-indicator <?php echo $i === 0 ? 'active' : ''; ?>" data-slide="<?php echo $i; ?>"></div>
                        <?php endfor; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="no-items-message">
                    <i class="fas fa-images fa-3x" style="margin-bottom: 15px;"></i>
                    <h3>No Carousel Items Yet</h3>
                    <p>Add carousel items through the Content Management interface to see them appear here.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Text Banner -->
        <?php if (isset($_SESSION["text_banner"]) && !empty($_SESSION["text_banner"])): ?>
            <div class="text-banner">
                <h2>Announcements</h2>
                <p><?php echo nl2br(htmlspecialchars($_SESSION["text_banner"])); ?></p>
            </div>
        <?php endif; ?>
        
        <!-- Main Content Area -->
        <div class="main-content">
            <div class="content">
                <h2>Welcome to Our Website</h2>
                <p>This is a preview of your website content. The main content area will display your primary content. You can customize the carousel, banners, and other elements using the Content Management interface.</p>
                
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam in dui mauris. Vivamus hendrerit arcu sed erat molestie vehicula. Sed auctor neque eu tellus rhoncus ut eleifend nibh porttitor. Ut in nulla enim. Phasellus molestie magna non est bibendum non venenatis nisl tempor.</p>
                
                <h3>Features</h3>
                <ul>
                    <li>Dynamic Carousel with custom images, titles, and descriptions</li>
                    <li>Custom text banners for announcements</li>
                    <li>Sidebar with additional banner images</li>
                    <li>Custom footer content</li>
                </ul>
                
                <p>Suspendisse in justo eu magna luctus suscipit. Sed lectus. Integer euismod lacus luctus magna. Quisque cursus, metus vitae pharetra auctor, sem massa mattis sem, at interdum magna augue eget diam. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Morbi lacinia molestie dui.</p>
                
                <p>Praesent blandit dolor. Sed non quam. In vel mi sit amet augue congue elementum. Morbi in ipsum sit amet pede facilisis laoreet. Donec lacus nunc, viverra nec, blandit vel, egestas et, augue. Vestibulum tincidunt malesuada tellus. Ut ultrices ultrices enim. Curabitur sit amet mauris.</p>
            </div>
            
            <div class="sidebar">
                <div class="sidebar-widget">
                    <h3>About Us</h3>
                    <p>Learn more about our company and what we do.</p>
                    <p>We are committed to providing the best service to our customers.</p>
                </div>
                
                <?php if (isset($_SESSION["photo_banner_2"]) && !empty($_SESSION["photo_banner_2"])): ?>
                    <div class="sidebar-widget sidebar-banner">
                        <?php echo getImageOrPlaceholder($_SESSION["photo_banner_2"], "Sidebar Banner"); ?>
                    </div>
                <?php endif; ?>
                
                <div class="sidebar-widget">
                    <h3>Contact Info</h3>
                    <p><i class="fas fa-map-marker-alt"></i> 123 Main Street, City</p>
                    <p><i class="fas fa-phone"></i> (123) 456-7890</p>
                    <p><i class="fas fa-envelope"></i> info@example.com</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">About</a></li>
                        <li><a href="#">Services</a></li>
                        <li><a href="#">Projects</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Products</h3>
                    <ul>
                        <li><a href="#">Product 1</a></li>
                        <li><a href="#">Product 2</a></li>
                        <li><a href="#">Product 3</a></li>
                        <li><a href="#">Product 4</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Connect With Us</h3>
                    <ul>
                        <li><a href="#"><i class="fab fa-facebook"></i> Facebook</a></li>
                        <li><a href="#"><i class="fab fa-twitter"></i> Twitter</a></li>
                        <li><a href="#"><i class="fab fa-instagram"></i> Instagram</a></li>
                        <li><a href="#"><i class="fab fa-linkedin"></i> LinkedIn</a></li>
                    </ul>
                </div>
            </div>
            
            <?php if (isset($_SESSION["footer_banner"]) && !empty($_SESSION["footer_banner"])): ?>
                <div class="footer-banner">
                    <?php echo nl2br(htmlspecialchars($_SESSION["footer_banner"])); ?>
                </div>
            <?php endif; ?>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Website Preview. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Synchronized Carousels Functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Only initialize carousel if there are items
            if (document.querySelector('.carousel-item')) {
                const carouselInner = document.getElementById('carousel-inner');
                const imageCarouselInner = document.getElementById('image-carousel-inner');
                const prevBtn = document.getElementById('prev-btn');
                const nextBtn = document.getElementById('next-btn');
                const indicators = document.querySelectorAll('.carousel-indicator');
                
                let currentSlide = 0;
                const totalSlides = document.querySelectorAll('.carousel-item').length;
                
                // Function to update carousel position
                function showSlide(index) {
                    if (index < 0) {
                        index = totalSlides - 1;
                    } else if (index >= totalSlides) {
                        index = 0;
                    }
                    
                    currentSlide = index;
                    
                    // Update both carousels simultaneously
                    carouselInner.style.transform = `translateX(-${currentSlide * 100}%)`;
                    imageCarouselInner.style.transform = `translateX(-${currentSlide * 100}%)`;
                    
                    // Update indicators
                    indicators.forEach((indicator, i) => {
                        indicator.classList.toggle('active', i === currentSlide);
                    });
                }
                
                // Get image carousel buttons
                const imgPrevBtn = document.getElementById('img-prev-btn');
                const imgNextBtn = document.getElementById('img-next-btn');
                
                // Event listeners for main carousel controls
                if (prevBtn) {
                    prevBtn.addEventListener('click', () => {
                        showSlide(currentSlide - 1);
                    });
                }
                
                if (nextBtn) {
                    nextBtn.addEventListener('click', () => {
                        showSlide(currentSlide + 1);
                    });
                }
                
                // Event listeners for image carousel controls
                if (imgPrevBtn) {
                    imgPrevBtn.addEventListener('click', () => {
                        showSlide(currentSlide - 1);
                    });
                }
                
                if (imgNextBtn) {
                    imgNextBtn.addEventListener('click', () => {
                        showSlide(currentSlide + 1);
                    });
                }
                
                // Event listeners for indicators
                indicators.forEach((indicator, index) => {
                    indicator.addEventListener('click', () => {
                        showSlide(index);
                    });
                });
                
                // Auto-advance carousel
                let intervalId = setInterval(() => {
                    showSlide(currentSlide + 1);
                }, 5000); // Change slide every 5 seconds
                
                // Pause auto-advance on hover for both carousels
                const carousel = document.querySelector('.carousel');
                const imageCarousel = document.querySelector('.image-carousel');
                
                carousel.addEventListener('mouseenter', () => {
                    clearInterval(intervalId);
                });
                
                carousel.addEventListener('mouseleave', () => {
                    intervalId = setInterval(() => {
                        showSlide(currentSlide + 1);
                    }, 5000);
                });
                
                imageCarousel.addEventListener('mouseenter', () => {
                    clearInterval(intervalId);
                });
                
                imageCarousel.addEventListener('mouseleave', () => {
                    intervalId = setInterval(() => {
                        showSlide(currentSlide + 1);
                    }, 5000);
                });
                
                // Initialize carousel
                showSlide(0);
            }
        });
    </script>
</body>
</html>