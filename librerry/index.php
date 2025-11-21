<?php
// Include database connection
include 'connect.php';

// Get statistics from database
$total_books_query = "SELECT SUM(quantity) as total FROM books";
$total_books_result = $conn->query($total_books_query);
$total_books = $total_books_result->fetch_assoc()['total'] ?? 0;

$available_books_query = "SELECT SUM(available_quantity) as available FROM books";
$available_books_result = $conn->query($available_books_query);
$available_books = $available_books_result->fetch_assoc()['available'] ?? 0;

$total_members_query = "SELECT COUNT(*) as total FROM members WHERE status='active'";
$total_members_result = $conn->query($total_members_query);
$total_members = $total_members_result->fetch_assoc()['total'] ?? 0;

$active_loans_query = "SELECT COUNT(*) as total FROM loans WHERE status='active'";
$active_loans_result = $conn->query($active_loans_query);
$active_loans = $active_loans_result->fetch_assoc()['total'] ?? 0;

// Get recent books
$recent_books_query = "SELECT b.*, c.category_name FROM books b 
                       LEFT JOIN categories c ON b.category_id = c.category_id 
                       ORDER BY b.date_added DESC LIMIT 6";
$recent_books = $conn->query($recent_books_query);

// Get popular categories
$categories_query = "SELECT c.*, COUNT(b.book_id) as book_count 
                     FROM categories c 
                     LEFT JOIN books b ON c.category_id = b.category_id 
                     GROUP BY c.category_id 
                     ORDER BY book_count DESC LIMIT 8";
$categories = $conn->query($categories_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System - Home</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <svg class="logo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
                <span class="logo-text">LibraryMS</span>
            </div>
            <ul class="nav-menu">
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="books.php">Books</a></li>
                <li><a href="categories.php">Categories</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
            <div class="nav-buttons">
                <a href="login.php" class="btn btn-outline">Login</a>
                <a href="register.php" class="btn btn-primary">Register</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Welcome to Our Library</h1>
                <p class="hero-subtitle">Discover thousands of books, manage your reading list, and explore new worlds of knowledge</p>
                <div class="hero-buttons">
                    <a href="books.php" class="btn btn-large btn-primary">Browse Books</a>
                    <a href="register.php" class="btn btn-large btn-outline-white">Join Now</a>
                </div>
            </div>
            <div class="hero-image">
                <div class="book-stack">
                    <div class="book book-1"></div>
                    <div class="book book-2"></div>
                    <div class="book book-3"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon stat-icon-blue">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                    </div>
                    <h3 class="stat-number"><?php echo number_format($total_books); ?></h3>
                    <p class="stat-label">Total Books</p>
                </div>
                <div class="stat-card">
                    <div class="stat-icon stat-icon-green">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M9 12l2 2 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="stat-number"><?php echo number_format($available_books); ?></h3>
                    <p class="stat-label">Available Books</p>
                </div>
                <div class="stat-card">
                    <div class="stat-icon stat-icon-purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <h3 class="stat-number"><?php echo number_format($total_members); ?></h3>
                    <p class="stat-label">Active Members</p>
                </div>
                <div class="stat-card">
                    <div class="stat-icon stat-icon-orange">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            <path d="M9 10h.01"></path>
                            <path d="M15 10h.01"></path>
                            <path d="M9.5 15a3.5 3.5 0 005 0"></path>
                        </svg>
                    </div>
                    <h3 class="stat-number"><?php echo number_format($active_loans); ?></h3>
                    <p class="stat-label">Active Loans</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Recent Books Section -->
    <section class="recent-books">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Recently Added Books</h2>
                <a href="books.php" class="view-all">View All →</a>
            </div>
            <div class="books-grid">
                <?php while($book = $recent_books->fetch_assoc()): ?>
                <div class="book-card">
                    <div class="book-cover">
                        <?php if($book['cover_image']): ?>
                            <img src="<?php echo htmlspecialchars($book['cover_image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                        <?php else: ?>
                            <div class="book-cover-placeholder">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                </svg>
                            </div>
                        <?php endif; ?>
                        <?php if($book['available_quantity'] > 0): ?>
                            <span class="badge badge-available">Available</span>
                        <?php else: ?>
                            <span class="badge badge-unavailable">Unavailable</span>
                        <?php endif; ?>
                    </div>
                    <div class="book-info">
                        <h3 class="book-title"><?php echo htmlspecialchars($book['title']); ?></h3>
                        <p class="book-author"><?php echo htmlspecialchars($book['author']); ?></p>
                        <p class="book-category"><?php echo htmlspecialchars($book['category_name']); ?></p>
                        <div class="book-footer">
                            <span class="book-year"><?php echo $book['publication_year']; ?></span>
                            <a href="book-details.php?id=<?php echo $book['book_id']; ?>" class="btn btn-small">View Details</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Browse by Category</h2>
                <a href="categories.php" class="view-all">View All →</a>
            </div>
            <div class="categories-grid">
                <?php while($category = $categories->fetch_assoc()): ?>
                <a href="books.php?category=<?php echo $category['category_id']; ?>" class="category-card">
                    <div class="category-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                    </div>
                    <h3 class="category-name"><?php echo htmlspecialchars($category['category_name']); ?></h3>
                    <p class="category-count"><?php echo $category['book_count']; ?> books</p>
                </a>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <h2 class="section-title center">Why Choose Our Library?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <h3 class="feature-title">24/7 Access</h3>
                    <p class="feature-description">Browse and reserve books anytime, anywhere with our online system</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                    </div>
                    <h3 class="feature-title">Vast Collection</h3>
                    <p class="feature-description">Thousands of books across multiple categories and genres</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                            <path d="M2 17l10 5 10-5"></path>
                            <path d="M2 12l10 5 10-5"></path>
                        </svg>
                    </div>
                    <h3 class="feature-title">Easy Management</h3>
                    <p class="feature-description">Track your loans, reservations, and reading history effortlessly</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                            <path d="M2 17l10 5 10-5M2 12l10 5 10-5"></path>
                        </svg>
                    </div>
                    <h3 class="feature-title">New Arrivals</h3>
                    <p class="feature-description">Regular updates with the latest books and publications</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h3 class="footer-title">About Library</h3>
                    <p class="footer-text">Your digital gateway to knowledge and learning. Manage your reading journey with ease.</p>
                    <div class="social-links">
                        <a href="#" class="social-link">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class="social-link">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                        <a href="#" class="social-link">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="footer-col">
                    <h3 class="footer-title">Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="books.php">Browse Books</a></li>
                        <li><a href="categories.php">Categories</a></li>
                        <li><a href="about.php">About Us</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3 class="footer-title">Member Area</h3>
                    <ul class="footer-links">
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                        <li><a href="my-account.php">My Account</a></li>
                        <li><a href="my-loans.php">My Loans</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3 class="footer-title">Contact Info</h3>
                    <ul class="footer-links">
                        <li>📧 info@library.com</li>
                        <li>📞 +1 234 567 8900</li>
                        <li>📍 123 Library Street</li>
                        <li>🕐 Mon-Fri: 9AM - 6PM</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Library Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>