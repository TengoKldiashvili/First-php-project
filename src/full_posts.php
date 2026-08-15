<?php
if (isset($_GET['post'])) {
    $postid = is_scalar($_GET['post']) ? intval($_GET['post']) : 0;

    $fullpost = mysqli_fetch_assoc($connect->query("SELECT name, imgs, small_description, description, created_at, id, navs_id, count, organizer, location, event_date, registration_deadline, registration_url FROM posts WHERE id = $postid AND is_approved = 1"));

    if (empty($fullpost)) {
        include "404error.php";
        return;
    }

    $navs_id = intval($fullpost['navs_id']);
    $nav = mysqli_fetch_assoc($connect->query("SELECT name, id FROM navs WHERE id = $navs_id"));
    $registration_scheme = !empty($fullpost['registration_url']) ? parse_url($fullpost['registration_url'], PHP_URL_SCHEME) : '';
    $safe_registration_url = !empty($fullpost['registration_url']) && filter_var($fullpost['registration_url'], FILTER_VALIDATE_URL) && in_array($registration_scheme, ['http', 'https']);

    $event_status = '';
    $current_time = time();
    $three_days_later = $current_time + (3 * 86400);
    $event_time = !empty($fullpost['event_date']) ? strtotime($fullpost['event_date']) : 0;
    $deadline_time = !empty($fullpost['registration_deadline']) ? strtotime($fullpost['registration_deadline']) : 0;

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

    mysqli_query($connect, "UPDATE posts SET count = count + 1 WHERE id = $postid");

    if (isset($_SESSION['user_id'])) {
        $view_user_id = intval($_SESSION['user_id']);
        mysqli_query($connect, "INSERT IGNORE INTO user_event_views (user_id, post_id) VALUES ($view_user_id, $postid)");
    }
}
?>

<article class="event-page">
    <div class="event-page-heading">
        <a class="back-link-small" href="/First-php-project/category/<?=intval($nav['id'])?>">← <?=htmlspecialchars($nav['name'])?></a>
        <div class="event-page-labels">
            <span class="category-label"><?=htmlspecialchars($nav['name'])?></span>
            <?php if (!empty($event_status)): ?>
                <span class="category-label event-status"><?=$event_status?></span>
            <?php endif; ?>
        </div>
        <h1><?=htmlspecialchars($fullpost['name'])?></h1>
        <?php if (!empty($fullpost['small_description'])): ?>
            <p><?=htmlspecialchars($fullpost['small_description'])?></p>
        <?php endif; ?>
        <div class="event-heading-meta">
            <?php if (!empty($fullpost['event_date'])): ?>
                <span><small>თარიღი</small><?=date('d.m.Y · H:i', strtotime($fullpost['event_date']))?></span>
            <?php endif; ?>
            <?php if (!empty($fullpost['location'])): ?>
                <span><small>ადგილი</small><?=htmlspecialchars($fullpost['location'])?></span>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!empty($fullpost['imgs'])): ?>
        <div class="event-cover">
            <img src="<?=htmlspecialchars($fullpost['imgs'])?>" alt="<?=htmlspecialchars($fullpost['name'])?>">
        </div>
    <?php endif; ?>

    <div class="event-detail-layout">
        <section class="event-description">
            <p class="content-label">ღონისძიების შესახებ</p>
            <div><?=nl2br(htmlspecialchars($fullpost['description']))?></div>
        </section>

        <section class="event-facts" aria-labelledby="event-information-title">
            <p class="content-label">მთავარი დეტალები</p>
            <h2 id="event-information-title">ღონისძიების ინფორმაცია</h2>
            <div class="event-facts-grid">
                <?php if (!empty($fullpost['event_date'])): ?>
                    <div class="event-fact"><span>ღონისძიების თარიღი</span><strong><?=date('d.m.Y · H:i', strtotime($fullpost['event_date']))?></strong></div>
                <?php endif; ?>
                <?php if (!empty($fullpost['location'])): ?>
                    <div class="event-fact"><span>ადგილმდებარეობა</span><strong><?=htmlspecialchars($fullpost['location'])?></strong></div>
                <?php endif; ?>
                <?php if (!empty($fullpost['organizer'])): ?>
                    <div class="event-fact"><span>ორგანიზატორი</span><strong><?=htmlspecialchars($fullpost['organizer'])?></strong></div>
                <?php endif; ?>
                <?php if (!empty($fullpost['registration_deadline'])): ?>
                    <div class="event-fact"><span>რეგისტრაციის ბოლო ვადა</span><strong><?=date('d.m.Y · H:i', strtotime($fullpost['registration_deadline']))?></strong></div>
                <?php endif; ?>
            </div>
            <div class="event-actions">
                <?php if ($safe_registration_url): ?>
                    <a class="registration-button" href="<?=htmlspecialchars($fullpost['registration_url'])?>" target="_blank" rel="noopener noreferrer">რეგისტრაცია <span>↗</span></a>
                <?php endif; ?>
                <?php if (!empty($fullpost['event_date'])): ?>
                    <a class="registration-button calendar-button" href="?calendar=<?=intval($fullpost['id'])?>">კალენდარში დამატება <span>↓</span></a>
                <?php endif; ?>
            </div>
        </section>
    </div>
</article>
