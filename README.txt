# ShopNest PHP + MySQL Version

## Features
- PHP pages connected to MySQL
- Dynamic products loaded from `products`
- Product details page
- Add to cart / remove / update quantity
- Cart count in navbar
- Search products
- Contact form saved into `contacts`
- Checkout flow that saves orders into `orders` / `order_items`
- Sign up (`signup.php`), sign in (`signin.php`), sign out (`logout.php`)
- A protected "My Account" page (`account.php`) that only logged-in
  visitors can reach — redirects to `signin.php` otherwise
- Sessions + an optional "Remember me" cookie that pre-fills the login form
- Shared PHP header/footer
- Original CSS design retained

## Accounts & cookies
- signup.php` creates a row in `users` (passwords are hashed with
  password_hash`, never stored in plain text) and logs the new user in.
- signin.php` checks credentials with `password_verify`. This follows the
  classic session + "remember me" pattern: `$_SESSION['user_id']` (and
  `$_SESSION['username']`) is what actually keeps you signed in on every
  page. Checking "Remember me" just stores the typed email in a plain
  `username` cookie for 30 days so the login form pre-fills it next time —
  it does **not** log you in by itself; the password is always required.
- `logout.php` clears the session (`session_unset(); session_destroy();`).
  The remember-me cookie is left alone, same as before — it only ever
  pre-fills a field, it can't authenticate anyone on its own.
- `account.php` is a protected page: it checks `isset($_SESSION["username"])`
  and redirects to `signin.php` if you're not logged in, then shows your
  name/email and, if the remember-me cookie is present, a "welcome back
  (from cookie)" line.
- auth.php is a shared include (`require_once "auth.php";` after
  config.php used by every page to look up the current user from the
  session.

## XAMPP setup
1. Copy the `ShopNest` folder into `C:\xampp\htdocs\`.
2. Start **Apache** and **MySQL** from XAMPP.
3. Open `http://localhost/phpmyadmin`.
4. Import `database.sql`.
5. Open `http://localhost/ShopNest/index.php`.

Default XAMPP MySQL settings are already used:
- host: localhost
- user: root
- password: empty
- database: shopnest

If your MySQL password is different, edit `config.php`.

## Important
The product images use external Unsplash URLs, so internet access is needed for those images.
The checkout is a demonstration only; it does not process real payments.
