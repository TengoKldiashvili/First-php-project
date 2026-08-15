<?php
include "../db/connect.php";
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../index.php?login");
    exit();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['approve_post'])) {
    if (!isset($_POST['csrf_token']) || !is_string($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        http_response_code(403);
        exit("არასწორი მოთხოვნა.");
    }

    $approve_post_id = is_scalar($_POST['approve_post']) ? intval($_POST['approve_post']) : 0;
    $approve_post = false;

    if ($approve_post_id > 0) {
        $approve_post = mysqli_query($connect, "UPDATE posts SET is_approved = 1 WHERE id = $approve_post_id AND is_approved = 0");
    }

    if ($approve_post) {
        header("Location: ?post_before&approved=true");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TW — ადმინისტრირება</title>
    <link rel="stylesheet" href="adminstyle.css">
</head>
<body>
    <header class="admin-header">
        <a class="admin-brand" href="index.php"><strong>TW</strong><span>Tech World <small>ადმინისტრირება</small></span></a>
        <div class="admin-header-actions">
            <span><?=htmlspecialchars($_SESSION['username'])?></span>
            <a href="../index.php">საიტზე დაბრუნება ↗</a>
        </div>
    </header>

    <div class="admin-layout">
        <aside class="admin-sidebar">
            <p>მართვა</p>
            <nav>
                <a href="index.php">მთავარი</a>
                <a href="?post_before">ღონისძიებები</a>
                <a href="?navs">კატეგორიები</a>
            </nav>
        </aside>

        <main class="admin-content">
        <?php
        if(isset($_GET['post_before'])){
            include "admin_src/post_before.php";
        } elseif(isset($_GET['post_details'])){
            include "admin_src/post_after.php";
        } elseif(isset($_GET['post_edit'])){
            include "admin_src/post_edit.php";
        } elseif(isset($_GET['navs'])){
            include "admin_src/navs.php";
        } else {
            include "admin_src/dashbord.php";
        }
        ?>
        </main>
    </div>
</body>
</html>
