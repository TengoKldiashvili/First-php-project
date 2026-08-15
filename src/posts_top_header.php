<?php
include_once "db/connect.php";
$upcoming_posts = mysqli_fetch_all($connect->query("SELECT posts.name, posts.imgs, posts.id, posts.event_date, posts.location, navs.name FROM posts LEFT JOIN navs ON posts.navs_id = navs.id WHERE posts.is_approved = 1 AND posts.event_date IS NOT NULL AND posts.event_date >= NOW() ORDER BY posts.event_date ASC LIMIT 6"));
$latest_posts = mysqli_fetch_all($connect->query("SELECT posts.name, posts.imgs, posts.id, posts.event_date, posts.location, navs.name FROM posts LEFT JOIN navs ON posts.navs_id = navs.id WHERE posts.is_approved = 1 ORDER BY posts.created_at DESC LIMIT 6"));
$registration_deadlines = mysqli_fetch_all($connect->query("SELECT id, registration_deadline FROM posts WHERE is_approved = 1"), MYSQLI_ASSOC);
$post_deadlines = [];

foreach ($registration_deadlines as $registration_deadline) {
    $post_deadlines[$registration_deadline['id']] = $registration_deadline['registration_deadline'];
}

$current_time = time();
$three_days_later = $current_time + (3 * 86400);
$recommended_events = [];

if (isset($_SESSION['user_id'])) {
    $recommendation_user_id = intval($_SESSION['user_id']);
    $viewed_events = mysqli_fetch_all($connect->query("SELECT posts.id, posts.navs_id, posts.organizer, posts.location FROM user_event_views LEFT JOIN posts ON user_event_views.post_id = posts.id WHERE user_event_views.user_id = $recommendation_user_id"), MYSQLI_ASSOC);

    if (!empty($viewed_events)) {
        $viewed_post_ids = [];
        $viewed_categories = [];
        $viewed_organizers = [];
        $viewed_locations = [];

        foreach ($viewed_events as $viewed_event) {
            $viewed_post_ids[] = $viewed_event['id'];

            if (!in_array($viewed_event['navs_id'], $viewed_categories)) {
                $viewed_categories[] = $viewed_event['navs_id'];
            }

            if (!empty($viewed_event['organizer']) && !in_array($viewed_event['organizer'], $viewed_organizers)) {
                $viewed_organizers[] = $viewed_event['organizer'];
            }

            if (!empty($viewed_event['location']) && !in_array($viewed_event['location'], $viewed_locations)) {
                $viewed_locations[] = $viewed_event['location'];
            }
        }

        $recommendation_candidates = mysqli_fetch_all($connect->query("SELECT posts.name, posts.imgs, posts.id, posts.event_date, posts.location, navs.name AS category_name, posts.navs_id, posts.organizer FROM posts LEFT JOIN navs ON posts.navs_id = navs.id WHERE posts.is_approved = 1 AND posts.event_date IS NOT NULL AND posts.event_date >= NOW() ORDER BY posts.event_date ASC"), MYSQLI_ASSOC);

        foreach ($recommendation_candidates as $candidate_event) {
            if (in_array($candidate_event['id'], $viewed_post_ids)) {
                continue;
            }

            $candidate_event['recommendation_score'] = 0;

            if (in_array($candidate_event['navs_id'], $viewed_categories)) {
                $candidate_event['recommendation_score'] += 3;
            }

            if (!empty($candidate_event['organizer']) && in_array($candidate_event['organizer'], $viewed_organizers)) {
                $candidate_event['recommendation_score'] += 2;
            }

            if (!empty($candidate_event['location']) && in_array($candidate_event['location'], $viewed_locations)) {
                $candidate_event['recommendation_score'] += 1;
            }

            if ($candidate_event['recommendation_score'] > 0) {
                $recommended_events[] = $candidate_event;
            }
        }

        usort($recommended_events, function ($first_event, $second_event) {
            if ($first_event['recommendation_score'] == $second_event['recommendation_score']) {
                return strtotime($first_event['event_date']) - strtotime($second_event['event_date']);
            }

            return $second_event['recommendation_score'] - $first_event['recommendation_score'];
        });

        $recommended_events = array_slice($recommended_events, 0, 4);
    }
}
?>

<section class="home-hero">
    <div class="hero-content">
        <p class="hero-eyebrow">TW — TECH WORLD</p>
        <h1>იპოვე შენი შემდეგი <span>Tech ღონისძიება</span></h1>
        <p class="hero-description">ჰაკათონები, კონფერენციები და პრაქტიკული ვორქშოფები საქართველოში — ყველაფერი ერთ სივრცეში.</p>
        <a class="primary-button" href="#events">ღონისძიებების ნახვა <span>↓</span></a>
    </div>
    <div class="hero-visual" aria-hidden="true">
    </div>
</section>

<section class="events-section upcoming-events-section" id="events">
    <div class="section-heading">
        <div>
            <p>ღონისძიებების კალენდარი</p>
            <h2>უახლოესი ღონისძიებები</h2>
        </div>
        <a class="view-all-link" href="/First-php-project/upcoming">ყველას ნახვა →</a>
    </div>

    <?php if (empty($upcoming_posts)): ?>
        <div class="empty-state">
            <strong>ახალი ღონისძიებები მალე დაემატება</strong>
            <p>თვალი ადევნე Tech World-ს და არ გამოტოვო შემდეგი შესაძლებლობა.</p>
        </div>
    <?php else: ?>
        <div class="upcoming-events-list">
            <?php foreach($upcoming_posts as $post){
                $event_status = '';
                $event_time = !empty($post[3]) ? strtotime($post[3]) : 0;
                $deadline_time = !empty($post_deadlines[$post[2]]) ? strtotime($post_deadlines[$post[2]]) : 0;

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
                <article class="upcoming-event-row">
                    <time class="upcoming-event-date" datetime="<?=date('c', $event_time)?>">
                        <strong><?=date('d', $event_time)?></strong>
                        <span><?=date('M', $event_time)?></span>
                        <small><?=date('H:i', $event_time)?></small>
                    </time>
                    <div class="upcoming-event-copy">
                        <h3><a href="/First-php-project/event/<?=intval($post[2])?>"><?=htmlspecialchars($post[0])?></a></h3>
                        <div class="upcoming-event-meta">
                            <span class="category-label"><?=htmlspecialchars($post[5])?></span>
                            <?php if (!empty($post[4])): ?>
                                <span><?=htmlspecialchars($post[4])?></span>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($event_status)): ?>
                            <span class="category-label event-status"><?=$event_status?></span>
                        <?php endif; ?>
                    </div>
                    <a class="upcoming-event-arrow" href="/First-php-project/event/<?=intval($post[2])?>" aria-label="<?=htmlspecialchars($post[0])?> — ღონისძიების ნახვა">→</a>
                </article>
            <?php } ?>
        </div>
    <?php endif; ?>
</section>

<?php if (isset($_SESSION['user_id']) && !empty($recommended_events)): ?>
    <section class="events-section recommended-events-section">
        <div class="section-heading">
            <div>
                <p>შენი ინტერესების მიხედვით</p>
                <h2>რეკომენდებული შენთვის</h2>
            </div>
            <span>ღონისძიებები, რომლებიც შენს ნანახ ღონისძიებებს ემთხვევა</span>
        </div>

        <div class="recommended-events-grid">
            <?php foreach($recommended_events as $recommended_event): ?>
                <article class="recommended-event-card">
                    <a class="recommended-event-image" href="/First-php-project/event/<?=intval($recommended_event['id'])?>">
                        <img src="<?=htmlspecialchars($recommended_event['imgs'])?>" alt="<?=htmlspecialchars($recommended_event['name'])?>">
                    </a>
                    <div class="recommended-event-body">
                        <p class="recommended-event-kicker">შენთვის შერჩეული</p>
                        <div class="recommended-event-meta">
                            <span><?=htmlspecialchars($recommended_event['category_name'])?></span>
                            <time><?=date('d.m.Y · H:i', strtotime($recommended_event['event_date']))?></time>
                        </div>
                        <h3><a href="/First-php-project/event/<?=intval($recommended_event['id'])?>"><?=htmlspecialchars($recommended_event['name'])?></a></h3>
                        <?php if (!empty($recommended_event['location'])): ?>
                            <p class="recommended-event-location"><?=htmlspecialchars($recommended_event['location'])?></p>
                        <?php endif; ?>
                        <a class="recommended-event-link" href="/First-php-project/event/<?=intval($recommended_event['id'])?>">ღონისძიების ნახვა <span>→</span></a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>

<section class="events-section general-events-section">
    <div class="section-heading">
        <div>
            <p>ბოლოს დამატებული</p>
            <h2>ღონისძიებები</h2>
        </div>
        <a class="view-all-link" href="/First-php-project/events">ყველას ნახვა →</a>
    </div>

    <?php if (empty($latest_posts)): ?>
        <div class="empty-state">
            <strong>ღონისძიებები ჯერ არ არის</strong>
            <p>ახალი ღონისძიებები მალე დაემატება.</p>
        </div>
    <?php else: ?>
        <div class="event-grid general-events-grid">
            <?php foreach($latest_posts as $post){
                $event_status = '';
                $event_time = !empty($post[3]) ? strtotime($post[3]) : 0;
                $deadline_time = !empty($post_deadlines[$post[2]]) ? strtotime($post_deadlines[$post[2]]) : 0;

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
                <article class="event-card general-event-card">
                    <a class="event-card-image" href="/First-php-project/event/<?=intval($post[2])?>">
                        <img src="<?=htmlspecialchars($post[1])?>" alt="<?=htmlspecialchars($post[0])?>">
                    </a>
                    <div class="event-card-body">
                        <div class="event-card-topline">
                            <span class="category-label"><?=htmlspecialchars($post[5])?></span>
                            <?php if (!empty($post[3])): ?>
                                <time><?=date('d.m.Y · H:i', strtotime($post[3]))?></time>
                            <?php endif; ?>
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
