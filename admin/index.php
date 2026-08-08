<?php
include "../db/connect.php";
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../index.php?login");
    exit();
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
            <span><?=$_SESSION['username']?></span>
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
