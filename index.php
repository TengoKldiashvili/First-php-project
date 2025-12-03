<?php
include "src/time.php";
include "db/connect.php";
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ამბიონი</title>
    <link rel="stylesheet" href="src/style.css">
</head>
<body>
<div class="topheader">
        <div class="intopheader">
        <div class="time"><span><?=$date?></span></div>
        <div class="fblogo"><a href="">f</a></div>
    </div>
</div>
<div class="logo">
    <a href="index.php"><img class="logoimg" src="src/imgs/logo.png" alt=""></a>
    <?php if (isset($_SESSION['user_id'])): ?>
    <div class="login">
        <img class="auth-icon-img" src="src/imgs/usericon.png" alt="Auth">
        <div class="profile-hover">
            <p>გამარჯობა, <?= $_SESSION['username'] ?>!</p>
            <a href="?profile">პროფილის რედაქტირება</a>
            <a href="?logout">გასვლა</a>
            
            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                <a href="admin">ადმინისტრატორი</a>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>
    <a class="login" href="?login"><img class="auth-icon-img" src="src/imgs/usericon.png" alt="Auth"></a>
<?php endif; ?>
</div>
</div>
</div>
</div>   

<?php 
    include "src/navs.php";
    if (isset($_GET['login'])) {
        include "src/login.php"; 
} elseif (isset($_GET['register'])){
    include "src/register.php";
} elseif (isset($_GET['contact'])) {
    include "src/contact.php";
} elseif (isset($_GET['profile'])) {
        include "src/profile.php";
}elseif(isset($_GET['logout'])){
    include "src/logout.php";
}elseif(isset($_GET['nav'])){
    include "src/posts.php";
}elseif(isset($_GET['post'])){
    include "src/full_posts.php";
}else{
    include "src/posts_top_header.php";
    }?>

<div class="blackcontainer">
<div class="endcontainer">
<span>2025 © Tengo kldiashvili</span>
<a style="color:#1da1f2" href="?contact">კონტაქტი</a>
<a href="">სარედაქციო გუნდი</a>
</div>
</div>
</body>
</html>


