<?php
include "db/connect.php";
session_start();
?>
<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="TW — Tech World: ჰაკათონები, კონფერენციები და ტექნოლოგიური ვორქშოფები საქართველოში.">
    <title>TW — Tech World</title>
    <link rel="stylesheet" href="src/style.css">
</head>
<body>
<header class="site-header">
    <div class="site-header-inner">
        <a class="brand-logo" href="index.php" aria-label="Tech World მთავარი გვერდი">
            <strong>TW</strong>
            <span>Tech World<small>ტექნოლოგიური ღონისძიებები</small></span>
        </a>

        <?php include "src/navs.php"; ?>

        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="account-menu">
                <button class="account-button" type="button">
                    <span class="account-initial"><?=mb_substr($_SESSION['username'], 0, 1)?></span>
                    <span><?=$_SESSION['username']?></span>
                </button>
                <div class="account-dropdown">
                    <a href="?profile">პროფილი</a>
                    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                        <a href="admin/index.php">ადმინისტრირება</a>
                    <?php endif; ?>
                    <a href="?logout">გასვლა</a>
                </div>
            </div>
        <?php else: ?>
            <a class="login-link" href="?login">შესვლა</a>
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
        <a class="footer-brand" href="index.php"><strong>TW</strong><span>Tech World</span></a>
        <p>ჰაკათონები და მნიშვნელოვანი Tech ღონისძიებები საქართველოში.</p>
        <span class="footer-copy"><?=date('Y')?> © Tech World</span>
    </div>
</footer>
</body>
</html>
