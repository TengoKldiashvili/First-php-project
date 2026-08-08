<?php
if(isset($_GET['nav'])){
    $posts_id = $_GET['nav'];
    $posts_id = mysqli_real_escape_string($connect, $posts_id);
    $navs_title = mysqli_fetch_assoc($connect->query("SELECT name, navs_description FROM navs WHERE id = '$posts_id'"));

    if (empty($navs_title)) {
        include "404error.php";
        return;
    }

    $posts = mysqli_fetch_all($connect->query("SELECT name, imgs, id, navs_id, event_date, location FROM posts WHERE navs_id = '$posts_id' ORDER BY event_date IS NULL, event_date ASC"));
}
?>

<section class="page-heading">
    <a class="back-link-small" href="index.php">← მთავარი გვერდი</a>
    <p>ღონისძიებების კატეგორია</p>
    <h1><?=$navs_title['name']?></h1>
    <?php if (!empty($navs_title['navs_description'])): ?>
        <span><?=$navs_title['navs_description']?></span>
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
            <?php foreach($posts as $post){ ?>
                <article class="event-card">
                    <a class="event-card-image" href="?post=<?=$post[2]?>">
                        <img src="<?=$post[1]?>" alt="<?=$post[0]?>">
                    </a>
                    <div class="event-card-body">
                        <div class="event-card-topline">
                            <span class="category-label"><?=$navs_title['name']?></span>
                            <?php if (!empty($post[4])): ?>
                                <time><?=date('d.m.Y · H:i', strtotime($post[4]))?></time>
                            <?php endif; ?>
                        </div>
                        <h3><a href="?post=<?=$post[2]?>"><?=$post[0]?></a></h3>
                        <?php if (!empty($post[5])): ?>
                            <p class="card-location"><?=$post[5]?></p>
                        <?php endif; ?>
                        <a class="event-card-link" href="?post=<?=$post[2]?>">ღონისძიების ნახვა <span>→</span></a>
                    </div>
                </article>
            <?php } ?>
        </div>
    <?php endif; ?>
</section>
