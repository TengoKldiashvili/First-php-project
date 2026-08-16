# TW — Tech World

TW is a small Georgian website for discovering hackathons and selected technology events. It is built with core PHP, MySQL, HTML, CSS, and a small amount of JavaScript.

## Main functionality

### Website

- Upcoming event cards
- Three event categories: hackathons, conferences, and workshops
- Event detail pages with date, location, organizer, description, deadline, and registration link
- User registration, login, and profile editing
- Responsive layout

### Admin panel

- Add, edit, view, and delete events
- Select an event category
- Add, edit, and delete empty categories
- Simple event and category overview

## Local setup

1. Place the project inside your local server document root.
2. Create a MySQL database named `tech_world`.
3. Import [`db/tech_world.sql`](db/tech_world.sql).
4. Create `db/connect.php` with your local database details:

```php
```php
$server = "localhost";
$user = "root";
$password = "";
$database = "tech_world";

$connect = mysqli_connect($server, $user, $password, $database);
mysqli_set_charset($connect, "utf8");
?>
```

5. Open the project through your local PHP server.

## Test administrator

- Email: `admin@tlaab.com`

The repository does not contain the admin plaintext password. After importing `db/tech_world.sql`, run the included CLI helper to set the admin password locally (it uses `password_hash`):

```bash
php db/set_admin_password.php admintlaab admin@tlaab.com
```

This script must be run locally on your machine (not committed with plaintext credentials).

## Project structure

- `index.php` — public page loading
- `src/` — public PHP pages, styles, and images
- `admin/` — event and category administration
- `db/ambioni.sql` — database schema and sample events
