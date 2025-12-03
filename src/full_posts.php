<?php
if (isset($_GET['post'])) {
    $postid = $_GET['post'];
    $postid = mysqli_real_escape_string($connect, $postid);
    // post data
    $fullpost = mysqli_fetch_assoc($connect->query("SELECT name, imgs, description, created_at, id, navs_id, count FROM posts WHERE id = '$postid'"));

    // navs data
    $different_navs = mysqli_fetch_all($connect->query("SELECT name, id FROM navs"));

    // articles
    $related_article = $fullpost['navs_id'];
    $related_base = mysqli_fetch_all($connect->query("SELECT name, imgs, id FROM posts WHERE navs_id = '$related_article' ORDER BY RAND() LIMIT 2"));
    // count
    $new_count = $fullpost['count'] + 1;
    // echo "<pre>";
    // print_r($new_count);
    // echo "</pre>";
    $update_count = ($connect->query("UPDATE posts SET count = '$new_count' WHERE id = '$postid'"));
// comment upload
    if(isset($_POST['upload_comment'])){
        $name=$_POST['name'];
        $email=$_POST['email'];
        $text=$_POST['text'];
        $upload_comment = ($connect->query("INSERT INTO comments (name, email, comment, post_id) VALUES ('$name', '$email', '$text', '$postid')"));
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    //  echo "<pre>";
    // print_r($name);
    // echo "</pre>";
    // print_r($email);
    }
}
// comment download
    $download_comment = "SELECT name, comment, created_at FROM comments WHERE post_id = '$postid' ORDER BY created_at DESC";
    $comments = mysqli_fetch_all($connect->query($download_comment));
    //  echo "<pre>";
    // print_r($comments);
    // echo "</pre>";
?>
<div class="post_container">
    <!-- left side posts -->
    <div class="fullpost">
    <div class="text-section">
        <p><b><?=$fullpost['name']?></b></p>
    </div>
    <div class="share-section">
        <div class="author-info">
            <span><?=$fullpost['created_at']?></span>
        </div>
        <div class="meta-info">
            <span><span class="views-icon"></span> <?=$fullpost['count']?></span>
            <span><span class="comments-icon"></span><?=count($comments)?></span>
        </div>
    </div>
    <br>
    <div class="social-icons">
            <a href="#" class="facebook">F</a>
            <a href="#" class="twitter">X</a>
            <a href="#" class="pinterest">P</a>
            <a href="#" class="whatsapp">W</a>
        </div>
    <div class="image-section">
        <img src="<?=$fullpost['imgs']?>" alt="წმინდა იოანე ნათლისმცემელი">
    </div>
    <div class="fullpost_text">
        <span><?=$fullpost['description']?></span>
    </div>
    <div class="related-articles">
        <h3>მსგავსი სტატიები</h3>
        <div class="article">
        <a href="?post=<?=$related_base[0][2]?>">
            <img src="<?=$related_base[0][1]?>" alt="">
            <h4><?=$related_base[0][0]?></h4></a>
        </div>
        <div class="article">
        <a href="?post=<?=$related_base[1][2]?>">
            <img src="<?=$related_base[1][1]?>" alt="">
            <h4><?=$related_base[1][0]?></h4></a>
        </div>
    </div>
    <?php
        for($c=0;$c<count($comments);$c++){ ?>
    <div class="comment">
        <p><strong><?php echo htmlspecialchars($comments[$c][0]); ?></strong> <em><?php echo $comments[$c][2]; ?></em></p>
        <p><?php echo nl2br(htmlspecialchars($comments[$c][1])); ?></p>
    </div>
    <?php }?>
   <?php if(isset($_GET['comment-uploaded'])){
        echo "<div class='comment-section'><h3 style='color:#1da1f2'>კომენტარები დამატებულია</h3></div>";
}else{
        include "addcomment.php";

    }
    ?>
</div>
    <!-- RIGHT side nav -->
    <div class="most-read">
    <div class="most_read_line">
    <p class="most-read-title">კატეგორიები</p></div>
        <ul>
            <?php
        for($x=4; $x<17; $x++){
            ?>
            <li><a href="?nav=<?=$different_navs[$x][1]?>"><b><?=$different_navs[$x][0]?></b></a></li>
            <?php  } ?>
        </ul>
        <br>
        <?php include "new_comments.php";?>
</div>
</div>
</div>
</div>