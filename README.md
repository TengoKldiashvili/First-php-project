# Ambioni - Custom PHP CMS & Blog

A custom-built Content Management System (CMS) and blogging platform developed with core PHP and MySQL. This project provides a fully functional dynamic website with a dedicated admin panel for content management, user authentication, and interactive features.

## 🚀 Features

### Front-End (Public Website)
- **Dynamic Content:** Fetches articles, news, and categories directly from the database.
- **User Authentication:** Secure registration and login system.
- **Commenting System:** Authenticated users can leave comments on posts.
- **Contact/Messaging:** Visitors can send direct messages via a built-in contact form.
- **Responsive Navigation:** Dynamic menus generated from the database categories.

### Back-End (Admin Dashboard)
- **Post Management:** Create, edit, and delete articles/posts (with image and video support).
- **Category (Navs) Management:** Add or modify website navigation categories.
- **Comment Moderation:** View and manage user comments.
- **User Management:** Oversee registered users and assign admin privileges.
- **Message Inbox:** Read messages sent by visitors through the contact form.
- **Ads Management:** Manage promotional banners and links (Reklama).

## 🛠️ Technologies Used
- **Backend:** PHP 8+ (Core/Vanilla PHP)
- **Database:** MySQL / MariaDB
- **Frontend:** HTML5, CSS3, JavaScript
- **Server Environment:** Apache / Nginx (XAMPP/MAMP compatible)

## ⚙️ Installation & Setup

Follow these instructions to run the project locally.

1. **Clone the repository:**
   ```bash
   git clone [https://github.com/TengoKldiashvili/first-php-project.git](https://github.com/TengoKldiashvili/first-php-project.git)
   cd first-php-project
Database Setup:

Create a new MySQL database named ambioni.

Import the provided database schema: Navigate to the db/ folder and import ambioni.sql into your newly created database.

Database Configuration:

Create a new file named connect.php inside the db/ directory (or rename connect.example.php if available).

Add your local database credentials:

PHP
<?php
$server = "localhost";
$user = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$database = "ambioni"; 

$connect = mysqli_connect($server, $user, $password, $database);
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
Run the Application:

Place the project folder in your local server's document root (e.g., htdocs for XAMPP).

Open your browser and navigate to http://localhost/first-php-project/.

🔐 Default Admin Credentials
To access the /admin dashboard for testing purposes, use the following credentials (provided in the SQL dump):

Email: admin@gmail.com

Password: admin

(Note: Please change these credentials in a production environment and ensure sensitive files like connect.php are added to .gitignore)

📂 Project Structure
/admin - Backend dashboard files and styles.

/db - Database connection scripts and SQL schema dumps.

/src - Frontend logic, assets (images, CSS), and functional components (login, comments, etc.).

index.php - Main entry point of the website.

Developed by Tlab