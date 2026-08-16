<?php
if(isset($_GET['nav'])){
    $posts_id = is_scalar($_GET['nav']) ? intval($_GET['nav']) : 0;
    $navs_title = mysqli_fetch_assoc($connect->query("SELECT name, navs_description FROM navs WHERE id = $posts_id"));

    if (empty($navs_title)) {
        include "404error.php";
        return;
    }

    $posts = mysqli_fetch_all($connect->query("SELECT name, imgs, id, navs_id, event_date, location, registration_deadline FROM posts WHERE navs_id = $posts_id AND is_approved = 1 ORDER BY event_date IS NULL, event_date ASC"));
    $current_time = time();
    $three_days_later = $current_time + (3 * 86400);
}
?>

<section class="page-heading">
    <a class="back-link-small" href="/tech-world/">← მთავარი გვერდი</a>
    <p>ღონისძიებების კატეგორია</p>
    <h1><?=htmlspecialchars($navs_title['name'])?></h1>
    <?php if (!empty($navs_title['navs_description'])): ?>
        <span><?=htmlspecialchars($navs_title['navs_description'])?></span>
    <?php endif; ?>
</section>

<section class="category-events">
    <?php if (empty($posts)): ?>
        <div class="empty-state">
            <strong>ამ კატეგორიაში ღონისძიება ჯერ არ არის</strong>
            <p>ახალი ღონისძიებები მალე დაემატება.</p>
        </div>
    <?php else: ?>
        <div class="event-grid">
            <?php foreach($posts as $post){
                $event_status = '';
                $event_time = !empty($post[4]) ? strtotime($post[4]) : 0;
                $deadline_time = !empty($post[6]) ? strtotime($post[6]) : 0;

                if (!empty($event_time) && $event_time < $current_time) {
                    $event_status = 'ღონისძიება დასრულებულია';
                } elseif (!empty($event_time) && date('Y-m-d', $event_time) == date('Y-m-d', $current_time)) {
                    $event_status = 'ღონისძიება დღესაა';
                } elseif (!empty($deadline_time) && $deadline_time < $current_time) {
                    $event_status = 'რეგისტრაცია დასრულებულია';
                } elseif (!empty($deadline_time) && $deadline_time <= $three_days_later) {
                    $event_status = 'რეგისტრაცია მალე სრულდება';
                } elseif (!empty($deadline_time) && $deadline_time > $current_time) {
                    $event_status = 'რეგისტრაცია ღიაა';
                }
            ?>
                <article class="event-card">
                    <a class="event-card-image" href="/tech-world/event/<?=intval($post[2])?>">
                        <img src="<?=htmlspecialchars($post[1])?>" alt="<?=htmlspecialchars($post[0])?>">
                    </a>
                    <div class="event-card-body">
                        <div class="event-card-topline">
                            <span class="category-label"><?=htmlspecialchars($navs_title['name'])?></span>
                            <?php if (!empty($post[4])): ?>
                                <time><?=date('d.m.Y · H:i', strtotime($post[4]))?></time>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($event_status)): ?>
                            <span class="category-label event-status"><?=$event_status?></span>
                        <?php endif; ?>
                        <h3><a href="/tech-world/event/<?=intval($post[2])?>"><?=htmlspecialchars($post[0])?></a></h3>
                        <?php if (!empty($post[5])): ?>
                            <p class="card-location"><?=htmlspecialchars($post[5])?></p>
                        <?php endif; ?>
                        <a class="event-card-link" href="/tech-world/event/<?=intval($post[2])?>">ღონისძიების ნახვა <span>→</span></a>
                    </div>
                </article>
            <?php } ?>
        </div>
    <?php endif; ?>
</section>
