-- Library Management System Database Schema
-- Import this file into phpMyAdmin for the 'librerry' database

-- Drop tables if they exist (to start fresh)
DROP TABLE IF EXISTS `loan_history`;
DROP TABLE IF EXISTS `loans`;
DROP TABLE IF EXISTS `reservations`;
DROP TABLE IF EXISTS `books`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `members`;
DROP TABLE IF EXISTS `librarians`;

-- Table: categories
CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: books
CREATE TABLE `books` (
  `book_id` int(11) NOT NULL AUTO_INCREMENT,
  `isbn` varchar(20) UNIQUE DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `publisher` varchar(255) DEFAULT NULL,
  `publication_year` year DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `available_quantity` int(11) NOT NULL DEFAULT 1,
  `shelf_location` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`book_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `books_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: members
CREATE TABLE `members` (
  `member_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) UNIQUE NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `membership_date` date NOT NULL DEFAULT current_timestamp(),
  `membership_expiry` date DEFAULT NULL,
  `status` enum('active','inactive','suspended') NOT NULL DEFAULT 'active',
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: librarians
CREATE TABLE `librarians` (
  `librarian_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) UNIQUE NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `username` varchar(50) UNIQUE NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','librarian') NOT NULL DEFAULT 'librarian',
  `hire_date` date NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`librarian_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: loans
CREATE TABLE `loans` (
  `loan_id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `librarian_id` int(11) DEFAULT NULL,
  `loan_date` date NOT NULL DEFAULT current_timestamp(),
  `due_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `status` enum('active','returned','overdue') NOT NULL DEFAULT 'active',
  `fine_amount` decimal(10,2) DEFAULT 0.00,
  `fine_paid` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`loan_id`),
  KEY `book_id` (`book_id`),
  KEY `member_id` (`member_id`),
  KEY `librarian_id` (`librarian_id`),
  CONSTRAINT `loans_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE,
  CONSTRAINT `loans_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`) ON DELETE CASCADE,
  CONSTRAINT `loans_ibfk_3` FOREIGN KEY (`librarian_id`) REFERENCES `librarians` (`librarian_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: reservations
CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `reservation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `expiry_date` date NOT NULL,
  `status` enum('pending','fulfilled','cancelled','expired') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`reservation_id`),
  KEY `book_id` (`book_id`),
  KEY `member_id` (`member_id`),
  CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE,
  CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: loan_history (for archiving returned loans)
CREATE TABLE `loan_history` (
  `history_id` int(11) NOT NULL AUTO_INCREMENT,
  `loan_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `librarian_id` int(11) DEFAULT NULL,
  `loan_date` date NOT NULL,
  `due_date` date NOT NULL,
  `return_date` date NOT NULL,
  `fine_amount` decimal(10,2) DEFAULT 0.00,
  `fine_paid` tinyint(1) DEFAULT 0,
  `archived_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`history_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample categories
INSERT INTO `categories` (`category_name`, `description`) VALUES
('Fiction', 'Fictional novels and stories'),
('Non-Fiction', 'Educational and informational books'),
('Science', 'Scientific books and research'),
('Technology', 'Computer science and technology books'),
('History', 'Historical books and biographies'),
('Children', 'Books for children'),
('Reference', 'Dictionaries, encyclopedias, and reference materials'),
('Biography', 'Biographies and memoirs'),
('Business', 'Business and economics books'),
('Arts', 'Art, music, and culture books');

-- Insert sample librarian (username: admin, password: admin123)
-- Note: In production, use proper password hashing with password_hash() in PHP
INSERT INTO `librarians` (`first_name`, `last_name`, `email`, `phone`, `username`, `password`, `role`) VALUES
('Admin', 'User', 'admin@library.com', '1234567890', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert sample books
INSERT INTO `books` (`isbn`, `title`, `author`, `publisher`, `publication_year`, `category_id`, `quantity`, `available_quantity`, `shelf_location`) VALUES
('978-0-545-01022-1', 'Harry Potter and the Sorcerer\'s Stone', 'J.K. Rowling', 'Scholastic', 1997, 1, 5, 5, 'A1-01'),
('978-0-06-112008-4', 'To Kill a Mockingbird', 'Harper Lee', 'Harper Perennial', 1960, 1, 3, 3, 'A1-05'),
('978-0-7432-7356-5', 'The Great Gatsby', 'F. Scott Fitzgerald', 'Scribner', 1925, 1, 4, 4, 'A1-10'),
('978-0-316-76948-0', '1984', 'George Orwell', 'Signet Classic', 1949, 1, 6, 6, 'A2-02'),
('978-0-262-03384-8', 'Introduction to Algorithms', 'Thomas H. Cormen', 'MIT Press', 2009, 4, 2, 2, 'C3-15'),
('978-0-134-68599-1', 'Clean Code', 'Robert C. Martin', 'Prentice Hall', 2008, 4, 3, 3, 'C3-20'),
('978-1-449-36517-5', 'Learning Python', 'Mark Lutz', 'O\'Reilly Media', 2013, 4, 4, 4, 'C3-25'),
('978-0-307-58837-1', 'Sapiens: A Brief History of Humankind', 'Yuval Noah Harari', 'Harper', 2015, 5, 5, 5, 'B1-10');

-- Insert sample member (email: member@test.com, password: member123)
INSERT INTO `members` (`first_name`, `last_name`, `email`, `phone`, `address`, `city`, `postal_code`, `membership_expiry`, `password`) VALUES
('John', 'Doe', 'member@test.com', '9876543210', '123 Main Street', 'Anytown', '12345', '2025-12-31', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');