# SHOPNEST - PHP + MYSQL E-COMMERCE PLATFORM

ShopNest is a beginner-friendly e-commerce web application developed using PHP, MySQL, HTML, CSS, and JavaScript. The project demonstrates the basic functionality of an online shopping platform, including product browsing, user authentication, shopping cart management, contact forms, and order processing.

## FEATURES

* PHP pages connected to MySQL
* Dynamic products loaded from the products table
* Product details page
* Add products to cart
* Remove products from cart
* Update product quantity
* Dynamic cart count in the navigation bar
* Product search functionality
* Contact form with information stored in the contacts table
* Checkout system
* Orders stored in the orders and order_items tables
* User registration through signup.php
* User login through signin.php
* User logout through logout.php
* Protected My Account page
* Session-based authentication
* Optional Remember Me functionality
* Shared PHP header and footer
* Original CSS design retained

## USER AUTHENTICATION

## SIGN UP

The signup.php page allows new users to create an account.

Passwords are securely stored using PHP's password_hash() function and are never stored as plain text.

After successful registration, the user is automatically logged in.

## SIGN IN

The signin.php page verifies user credentials using PHP's password_verify() function.

The authenticated user's information is stored in PHP sessions:

$_SESSION['user_id']
$_SESSION['username']

These session values are used to maintain the user's login status throughout the website.

## REMEMBER ME

The optional Remember Me feature stores the user's email address in a cookie for 30 days.

The cookie is only used to pre-fill the login form. It does not automatically log the user in or authenticate the user.

The password is always required during login.

## LOGOUT

The logout.php page clears the current session using:

session_unset()
session_destroy()

The Remember Me cookie is left unchanged because it is only used to pre-fill the login form and cannot authenticate a user.

## MY ACCOUNT

The account.php page is protected and can only be accessed by logged-in users.

If a visitor is not logged in, they are automatically redirected to signin.php.

The page displays the logged-in user's basic information, such as their name and email address.

## DATABASE

The project uses MySQL as its database management system.

Database name:

shopnest

The database contains tables for:

* Users
* Products
* Contacts
* Orders
* Order Items

The complete database structure and sample data are provided in:

database.sql

## REQUIREMENTS

Before running the project, make sure the following are installed:

* XAMPP
* Apache
* MySQL
* PHP
* A modern web browser

## XAMPP SETUP

1. Copy the ShopNest folder into:

   C:\xampp\htdocs\

2. Open the XAMPP Control Panel.

3. Start:

   Apache
   MySQL

4. Open phpMyAdmin:

   http://localhost/phpmyadmin

5. Import the database.sql file.

6. The default MySQL configuration used by the project is:

   Host: localhost
   Username: root
   Password: empty
   Database: shopnest

7. If your MySQL username or password is different, update the database credentials in:

   config.php

8. Open the project in your browser:

   http://localhost/ShopNest/index.php

## EXTERNAL IMAGES

The product images are loaded from external Unsplash URLs.

Therefore, an active internet connection may be required for the product images to display correctly.

## CHECKOUT DISCLAIMER

The checkout system is implemented for demonstration and academic purposes only.

This project does not process real payments and does not use any real payment gateway.

## TECHNOLOGIES USED

HTML5
CSS3
JavaScript
PHP
MySQL
XAMPP

## PROJECT PURPOSE

ShopNest was developed as an academic and learning project to demonstrate the development of a basic e-commerce platform using PHP and MySQL.

The project provides practical experience with:

* PHP backend development
* MySQL database integration
* CRUD operations
* User authentication
* Sessions and cookies
* Shopping cart functionality
* Order management
* Form handling
* Frontend and backend integration

## SECURITY

The project implements basic security practices, including:

* Password hashing using password_hash()
* Password verification using password_verify()
* Session-based authentication
* Protected account pages
* Passwords are never stored in plain text

## DISCLAIMER

ShopNest is an educational project and is not intended for production use without additional security improvements, advanced validation, payment gateway integration, and proper production deployment.
