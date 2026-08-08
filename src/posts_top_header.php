<?php
include_once "db/connect.php";
$post_top_header = mysqli_fetch_all($connect->query("SELECT posts.name, posts.imgs, posts.id, posts.event_date, posts.location, navs.name FROM posts LEFT JOIN navs ON posts.navs_id = navs.id WHERE posts.event_date >= NOW() OR posts.event_date IS NULL ORDER BY posts.event_date IS NULL, posts.event_date ASC LIMIT 6"));
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

<section class="events-section" id="events">
    <div class="section-heading">
        <div>
            <p>ღონისძიებების კალენდარი</p>
            <h2>უახლოესი ღონისძიებები</h2>
        </div>
        <span>აირჩიე ღონისძიება და გაიგე ყველა დეტალი</span>
    </div>

    <?php if (empty($post_top_header)): ?>
        <div class="empty-state">
            <strong>ახალი ღონისძიებები მალე დაემატება</strong>
            <p>თვალი ადევნე Tech World-ს და არ გამოტოვო შემდეგი შესაძლებლობა.</p>
        </div>
    <?php else: ?>
        <div class="event-grid">
            <?php foreach($post_top_header as $post){ ?>
                <article class="event-card">
                    <a class="event-card-image" href="?post=<?=$post[2]?>">
                        <img src="<?=$post[1]?>" alt="<?=$post[0]?>">
                    </a>
                    <div class="event-card-body">
                        <div class="event-card-topline">
                            <span class="category-label"><?=$post[5]?></span>
                            <?php if (!empty($post[3])): ?>
                                <time><?=date('d.m.Y · H:i', strtotime($post[3]))?></time>
                            <?php endif; ?>
                        </div>
                        <h3><a href="?post=<?=$post[2]?>"><?=$post[0]?></a></h3>
                        <?php if (!empty($post[4])): ?>
                            <p class="card-location"><?=$post[4]?></p>
                        <?php endif; ?>
                        <a class="event-card-link" href="?post=<?=$post[2]?>">ღონისძიების ნახვა <span>→</span></a>
                    </div>
                </article>
            <?php } ?>
        </div>
    <?php endif; ?>
</section>
