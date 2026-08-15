<?php
$upcoming_posts = mysqli_fetch_all($connect->query("SELECT posts.name, posts.imgs, posts.id, posts.event_date, posts.location, navs.name, posts.registration_deadline FROM posts LEFT JOIN navs ON posts.navs_id = navs.id WHERE posts.is_approved = 1 AND posts.event_date IS NOT NULL AND posts.event_date >= NOW() ORDER BY posts.event_date ASC"));
$current_time = time();
$three_days_later = $current_time + (3 * 86400);
?>

<section class="page-heading">
    <a class="back-link-small" href="/First-php-project/">← მთავარი გვერდი</a>
    <p>ღონისძიებების კალენდარი</p>
    <h1>უახლოესი ღონისძიებები</h1>
    <span>ყველა დაგეგმილი ღონისძიება უახლოესი თარიღის მიხედვით.</span>
</section>

<section class="category-events">
    <?php if (empty($upcoming_posts)): ?>
        <div class="empty-state">
            <strong>უახლოესი ღონისძიებები ჯერ არ არის</strong>
            <p>ახალი ღონისძიებები მალე დაემატება.</p>
        </div>
    <?php else: ?>
        <div class="event-grid">
            <?php foreach($upcoming_posts as $post){
                $event_status = '';
                $event_time = strtotime($post[3]);
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
                    <a class="event-card-image" href="/First-php-project/event/<?=intval($post[2])?>">
                        <img src="<?=htmlspecialchars($post[1])?>" alt="<?=htmlspecialchars($post[0])?>">
                    </a>
                    <div class="event-card-body">
                        <div class="event-card-topline">
                            <span class="category-label"><?=htmlspecialchars($post[5])?></span>
                            <time><?=date('d.m.Y · H:i', $event_time)?></time>
                        </div>
                        <?php if (!empty($event_status)): ?>
                            <span class="category-label event-status"><?=$event_status?></span>
                        <?php endif; ?>
                        <h3><a href="/First-php-project/event/<?=intval($post[2])?>"><?=htmlspecialchars($post[0])?></a></h3>
                        <?php if (!empty($post[4])): ?>
                            <p class="card-location"><?=htmlspecialchars($post[4])?></p>
                        <?php endif; ?>
                        <a class="event-card-link" href="/First-php-project/event/<?=intval($post[2])?>">ღონისძიების ნახვა <span>→</span></a>
                    </div>
                </article>
            <?php } ?>
        </div>
    <?php endif; ?>
</section>
