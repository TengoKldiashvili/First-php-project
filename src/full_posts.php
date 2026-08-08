<?php
if (isset($_GET['post'])) {
    $postid = $_GET['post'];
    $postid = mysqli_real_escape_string($connect, $postid);

    $fullpost = mysqli_fetch_assoc($connect->query("SELECT name, imgs, small_description, description, created_at, id, navs_id, count, organizer, location, event_date, registration_deadline, registration_url FROM posts WHERE id = '$postid'"));

    if (empty($fullpost)) {
        include "404error.php";
        return;
    }

    $navs_id = $fullpost['navs_id'];
    $nav = mysqli_fetch_assoc($connect->query("SELECT name, id FROM navs WHERE id = '$navs_id'"));

    $view_count = $fullpost['count'] + 1;
    $update_count = "UPDATE posts SET count = '$view_count' WHERE id = '$postid'";
    mysqli_query($connect, $update_count);
}
?>

<article class="event-page">
    <div class="event-page-heading">
        <a class="back-link-small" href="?nav=<?=$nav['id']?>">← <?=$nav['name']?></a>
        <span class="category-label"><?=$nav['name']?></span>
        <h1><?=$fullpost['name']?></h1>
        <?php if (!empty($fullpost['small_description'])): ?>
            <p><?=$fullpost['small_description']?></p>
        <?php endif; ?>
        <div class="event-heading-meta">
            <?php if (!empty($fullpost['event_date'])): ?>
                <span><small>თარიღი</small><?=date('d.m.Y · H:i', strtotime($fullpost['event_date']))?></span>
            <?php endif; ?>
            <?php if (!empty($fullpost['location'])): ?>
                <span><small>ადგილი</small><?=$fullpost['location']?></span>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!empty($fullpost['imgs'])): ?>
        <div class="event-cover">
            <img src="<?=$fullpost['imgs']?>" alt="<?=$fullpost['name']?>">
        </div>
    <?php endif; ?>

    <div class="event-detail-layout">
        <div class="event-description">
            <p class="content-label">ღონისძიების შესახებ</p>
            <div><?=$fullpost['description']?></div>
        </div>

        <aside class="event-facts">
            <h2>დეტალები</h2>
            <?php if (!empty($fullpost['event_date'])): ?>
                <div><span>ღონისძიების თარიღი</span><strong><?=date('d.m.Y · H:i', strtotime($fullpost['event_date']))?></strong></div>
            <?php endif; ?>
            <?php if (!empty($fullpost['location'])): ?>
                <div><span>ადგილმდებარეობა</span><strong><?=$fullpost['location']?></strong></div>
            <?php endif; ?>
            <?php if (!empty($fullpost['organizer'])): ?>
                <div><span>ორგანიზატორი</span><strong><?=$fullpost['organizer']?></strong></div>
            <?php endif; ?>
            <?php if (!empty($fullpost['registration_deadline'])): ?>
                <div><span>რეგისტრაციის ბოლო ვადა</span><strong><?=date('d.m.Y · H:i', strtotime($fullpost['registration_deadline']))?></strong></div>
            <?php endif; ?>
            <?php if (!empty($fullpost['registration_url'])): ?>
                <a class="registration-button" href="<?=htmlspecialchars($fullpost['registration_url'])?>" target="_blank" rel="noopener noreferrer">რეგისტრაცია <span>↗</span></a>
            <?php endif; ?>
        </aside>
    </div>
</article>
