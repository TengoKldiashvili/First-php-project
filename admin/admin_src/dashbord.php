<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../../index.php?login");
    exit();
}

include_once __DIR__ . "/../../db/connect.php";

$post_count = mysqli_fetch_assoc($connect->query("SELECT COUNT(*) AS total FROM posts"));
$category_count = mysqli_fetch_assoc($connect->query("SELECT COUNT(*) AS total FROM navs"));
$next_post = mysqli_fetch_assoc($connect->query("SELECT name, event_date, id FROM posts WHERE event_date >= NOW() ORDER BY event_date ASC LIMIT 1"));
?>

<div class="admin-page-heading">
    <div>
        <p>TW ადმინისტრირება</p>
        <h1>მთავარი</h1>
    </div>
    <a class="primary-admin-button" href="?post_before">ღონისძიების დამატება</a>
</div>

<div class="dashboard-stats">
    <div class="stat-card">
        <span>ღონისძიებები</span>
        <strong><?=$post_count['total']?></strong>
        <a href="?post_before">მართვა →</a>
    </div>
    <div class="stat-card">
        <span>კატეგორიები</span>
        <strong><?=$category_count['total']?></strong>
        <a href="?navs">მართვა →</a>
    </div>
    <div class="stat-card next-event-card">
        <span>უახლოესი ღონისძიება</span>
        <?php if (!empty($next_post)): ?>
            <strong><?=htmlspecialchars($next_post['name'])?></strong>
            <small><?=date('d.m.Y · H:i', strtotime($next_post['event_date']))?></small>
            <a href="?post_details=<?=intval($next_post['id'])?>">ნახვა →</a>
        <?php else: ?>
            <strong>ჯერ არ არის</strong>
            <a href="?post_before">დამატება →</a>
        <?php endif; ?>
    </div>
</div>
