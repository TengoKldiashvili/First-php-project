<?php
$new_comment = mysqli_fetch_all($connect->query("SELECT name,post_id FROM comments ORDER BY created_at DESC LIMIT 5"));
$ads_comment = mysqli_fetch_all($connect->query("SELECT imgs, link FROM reklama ORDER BY created_at DESC LIMIT 1"));
$ads = $ads_comment[0];
// echo "<pre>";
//     print_r($ads);
//     echo "</pre>";
?>
    <div class="most-read">
    <div class="most_read_line">
    <p class="most-read-title">კომენტარები</p></div>
    <div class="newcomment">
        <ul>
        <?php
        for($n=0;$n<count($new_comment);$n++){ 
            $post_comment_id = $new_comment[$n][1];
            $comment_name = mysqli_fetch_all($connect->query("SELECT name FROM posts ORDER BY '$post_comment_id'")); ?>
            <li>
                <strong><?= htmlspecialchars($new_comment[$n][0]) ?></strong> on 
                <a href="?post=<?=$post_comment_id?>"><?=htmlspecialchars($comment_name[$n][0])?></a>
            </li>
        <?php } ?>
        </ul>
        <br>
        <br>
        <a href="<?= htmlspecialchars($ads[1]) ?>"><img style="width:90%;" src="<?= htmlspecialchars($ads[0]) ?>" alt=""></a>

    </div>
</div>

