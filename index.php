<?php
if (isset($_GET['calendar'])) {
    ob_start();
}

include "db/connect.php";

if (isset($_GET['calendar'])) {
    include "src/calendar.php";
    exit();
}

session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (isset($_GET['submit_event']) && !isset($_SESSION['user_id'])) {
    header("Location: /First-php-project/login");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="TW — Tech World: ჰაკათონები, კონფერენციები და ტექნოლოგიური ვორქშოფები საქართველოში.">
    <title>TW — Tech World</title>
    <base href="/First-php-project/">
    <link rel="stylesheet" href="src/style.css">
</head>
<body>
<header class="site-header">
    <div class="site-header-inner">
        <a class="brand-logo" href="/First-php-project/" aria-label="Tech World მთავარი გვერდი">
            <strong>TW</strong>
            <span>Tech World<small>ტექნოლოგიური ღონისძიებები</small></span>
        </a>

        <?php include "src/navs.php"; ?>

        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="account-menu">
                <button class="account-button" type="button">
                    <span class="account-initial"><?=htmlspecialchars(mb_substr($_SESSION['username'], 0, 1))?></span>
                    <span><?=htmlspecialchars($_SESSION['username'])?></span>
                </button>
                <div class="account-dropdown">
                    <a href="?submit_event">ღონისძიების დამატება</a>
                    <a href="/First-php-project/profile">პროფილი</a>
                    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                        <a href="admin/index.php">ადმინისტრირება</a>
                    <?php endif; ?>
                    <a href="?logout">გასვლა</a>
                </div>
            </div>
        <?php else: ?>
            <a class="login-link" href="/First-php-project/login">შესვლა</a>
        <?php endif; ?>
    </div>
</header>

<main class="site-main">
<?php
if (isset($_GET['login'])) {
    include "src/login.php";
} elseif (isset($_GET['register'])) {
    include "src/register.php";
} elseif (isset($_GET['profile'])) {
    include "src/profile.php";
} elseif (isset($_GET['logout'])) {
    include "src/logout.php";
} elseif (isset($_GET['submit_event'])) {
    include "src/submit_event.php";
} elseif (isset($_GET['upcoming_events'])) {
    include "src/upcoming_events.php";
} elseif (isset($_GET['all_events'])) {
    include "src/all_events.php";
} elseif (isset($_GET['nav'])) {
    include "src/posts.php";
} elseif (isset($_GET['post'])) {
    include "src/full_posts.php";
} else {
    include "src/posts_top_header.php";
}
?>
</main>

<footer class="site-footer">
    <div class="site-footer-inner">
        <a class="footer-brand" href="/First-php-project/"><strong>TW</strong><span>Tech World</span></a>
        <p>ჰაკათონები და მნიშვნელოვანი Tech ღონისძიებები საქართველოში.</p>
        <span class="footer-copy"><?=date('Y')?> © Tech World</span>
    </div>
</footer>
</body>
</html>
