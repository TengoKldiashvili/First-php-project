<?php
if(isset($_GET['nav'])){
    $posts_id = $_GET['nav'];
    $post_top_header = mysqli_fetch_all($connect->query("SELECT name, imgs, id, navs_id, created_at FROM posts WHERE navs_id = '$posts_id' ORDER BY id DESC LIMIT 17"));    
    $post_most_read = mysqli_fetch_all($connect->query("SELECT name, imgs, id, navs_id, created_at FROM posts WHERE navs_id = '$posts_id' ORDER BY count DESC LIMIT 4"));
    //  404 error
    for($i=0;$i<5;$i++){
    if (empty($post_top_header[$i])) {
        header("HTTP/1.0 404 Not Found");
        echo include "404error.php";
        exit;
    }
  }
}
$navs_id = $post_top_header[0][3];
    $navs_title =  mysqli_fetch_all($connect->query("SELECT name, navs_description FROM navs WHERE id = '$navs_id'"));
    $ads_comment = mysqli_fetch_all($connect->query("SELECT imgs, link FROM reklama ORDER BY created_at DESC LIMIT 1"));
    $ads = $ads_comment[0];
?>
<!-- <top_header_posts> -->
<div class="posts_navstitle">
    <h2><?=$navs_title[0][0]?></h2>
    <br>
    <i><?=$navs_title[0][1]?></i>
    <br>
    <br>
</div>
<div class="nav_posts">
  <div class="nav_item1">
    <a href="?post=<?=$post_top_header[0][2]?>"><img src="<?=$post_top_header[0][1]?>" alt=""></a>
    <a href="?post=<?=$post_top_header[0][2]?>"><div class="nav_posts-title"><?=$post_top_header[0][0]?></div></a>
  </div>
  <div class="nav_item2">
    <a href="?post=<?=$post_top_header[1][2]?>"><img src="<?=$post_top_header[1][1]?>" alt=""></a>
    <a href="?post=<?=$post_top_header[1][2]?>"><div class="posts-title"><?=$post_top_header[1][0]?></div></a>
  </div>
  <div class="nav_item3">
    <a href="?post=<?=$post_top_header[2][2]?>"><img src="<?=$post_top_header[2][1]?>" alt=""></a>
    <a href="?post=<?=$post_top_header[2][2]?>"><div class="posts-title"><?=$post_top_header[2][0]?></div></a>
  </div>
  <div class="nav_item4">
    <a href="?post=<?=$post_top_header[3][2]?>"><img src="<?=$post_top_header[3][1]?>" alt=""></a>
    <a href="?post=<?=$post_top_header[3][2]?>"><div class="posts-title"><?=$post_top_header[3][0]?></div></a>
  </div>
  <div class="nav_item5">
    <a href="?post=<?=$post_top_header[4][2]?>"><img src="<?=$post_top_header[4][1]?>" alt=""></a>
    <a href="?post=<?=$post_top_header[4][2]?>"><div class="posts-title"><?=$post_top_header[4][0]?></div></a>
  </div>
</div>
<!-- posts main content -->
<div class="posts_content">
  <div class="post_items">
    <?php for($i=5; $i<17; $i++) {
        if (empty($post_top_header[$i][1]) || empty($post_top_header[$i][0]) || empty($post_top_header[$i][4])) {
            break;
        }
    ?>
    <div class="onlypost">
        <a href="?post=<?=$post_top_header[$i][2]?>"><img src="<?=$post_top_header[$i][1]?>">
        <h3><?=$post_top_header[$i][0]?></h3></a>
        <p><?=$post_top_header[$i][4]?></p>
    </div>    
    <?php }?>
  </div>
  <!-- MOST READ -->
  <div class="most-read">
    <div class="most_read_line">
    <p class="most-read-title">ხშირად წაკითხული</p></div>        
                <ul>
                <?php for($p=0;$p<4;$p++){?>
                <li><img src="<?=$post_most_read[$p][1]?>" alt=""><p><a href="?post=<?=$post_most_read[$p][2]?>"><?=$post_most_read[$p][0]?></a><span><?=$post_most_read[$p][4]?></span></p></li>
                <?php } ?>
              </ul>
              <br>
              <a href="<?= htmlspecialchars($ads[1]) ?>"><img style="width:90%;" src="<?= htmlspecialchars($ads[0]) ?>" alt=""></a>

        </div>
        
    </div>
    
</div>