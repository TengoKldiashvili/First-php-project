<?php
include_once "db/connect.php";
$post_top_header = mysqli_fetch_all($connect->query("SELECT name,imgs,id,created_at FROM posts WHERE navs_id = '5'"));
$different_navs = mysqli_fetch_all($connect->query("SELECT name,id FROM navs"));
$random_categorie = mysqli_fetch_all($connect->query("SELECT name, imgs, id,created_at,description FROM posts WHERE navs_id = '1' ORDER BY RAND() LIMIT 5"));

$other_categories_posts_one = mysqli_fetch_all($connect->query("SELECT name, imgs, id FROM posts WHERE navs_id = '2' ORDER BY id DESC LIMIT 1"));
$other_categories_posts_two = mysqli_fetch_all($connect->query("SELECT name, imgs, id FROM posts WHERE navs_id = '7' ORDER BY id DESC LIMIT 1"));
$other_categories_posts_three = mysqli_fetch_all($connect->query("SELECT name, imgs, id FROM posts WHERE navs_id = '15' ORDER BY id DESC LIMIT 1"));
$other_categories_posts_four = mysqli_fetch_all($connect->query("SELECT name, imgs, id FROM posts WHERE navs_id = '12' ORDER BY id DESC LIMIT 1"));
$other_categories_posts_five = mysqli_fetch_all($connect->query("SELECT name, imgs, id FROM posts WHERE navs_id = '10' ORDER BY id DESC LIMIT 1"));
$other_categories_posts_six = mysqli_fetch_all($connect->query("SELECT name, imgs, id FROM posts WHERE navs_id = '4' ORDER BY id DESC LIMIT 1"));
$newest_posts = mysqli_fetch_all($connect->query("SELECT * FROM posts ORDER BY created_at DESC LIMIT 7"));
    // echo "<pre>";
    // print_r($newest_posts);
    // echo "</pre>";
?>
<div class="posts">
  <div class="item1">
    <a href="?post=<?=$post_top_header[0][2]?>"><img src="<?=$post_top_header[0][1]?>" alt=""></a>
    <a href="?post=<?=$post_top_header[0][2]?>"><div class="posts-title"><?=$post_top_header[0][0]?></div></a>
  </div>
  <div class="item2">
  <a href="?post=<?=$post_top_header[1][2]?>"><img src="<?=$post_top_header[1][1]?>" alt=""></a>
  <a href="?post=<?=$post_top_header[1][2]?>"><div class="posts-title"><?=$post_top_header[1][0]?></div></a>
  </div>
  <div class="item3">
  <a href="?post=<?=$post_top_header[2][2]?>"><img src="<?=$post_top_header[2][1]?>" alt=""></a>
  <a href="?post=<?=$post_top_header[2][2]?>"><div class="posts-title"><?=$post_top_header[2][0]?></div></a>
  </div>
  <div class="item4">
  <a href="?post=<?=$post_top_header[3][2]?>"><img src="<?=$post_top_header[3][1]?>" alt=""></a>
  <a href="?post=<?=$post_top_header[3][2]?>"><div class="posts-title"><?=$post_top_header[3][0]?></div></a>
  </div>
</div>
<!-- rightside main -->
<div class="rightside_main">
<div class="main_of_categories">
<div class="main_of_title">
<a class="main_of_categories_a" href="?nav=<?=$different_navs[0][1]?>"><b>ქრისტიანობა</b></a>
</div>
<div class="moving_posts">
<div class="categories_leftside">
<a href="?post=<?=$random_categorie[1][2]?>"><img src="<?=$random_categorie[1][1]?>" alt="">
  <p><b><?=$random_categorie[1][0]?></b></p></a>
  <span><?=$random_categorie[1][4]?></span>
</div>
<div class="categories_rightside">
<div class="most-read">
<?php for($o=0;$o<4;$o++){ ?>
            <ul>
                <a href="?post=<?=$random_categorie[$o][2]?>"><li><img class="post-moving-img"  src="<?=$random_categorie[$o][1]?>" alt=""><p><?=$random_categorie[$o][0]?><span><?=$random_categorie[$o][3]?></span></p></li></a>
              </ul>
              <?php }?>
        </div>
    </div>

    </div>
    </div>
<div class="most-read">
    <div class="most_read_line">
    <p class="most-read-title">კატეგორიები</p></div>
        <ul>
            <?php
        for($x=4; $x<count($different_navs); $x++){
            ?>
            <li><a href="?nav=<?=$different_navs[$x][1]?>"><b><?=$different_navs[$x][0]?></b></a></li>
            <?php  } ?>
        </ul>
</div>
</div>
<div class="other_categories">
<div class="other_categories_items">
<div class="main_of_title">
<a class="main_of_categories_a" href="?nav=2"><b>ეკლესია მონასტრები</b></a>
</div>
<div class="posts_url_img">
<div class="other_categories_items_div">
    <a href="?post=<?=$other_categories_posts_one[0][2]?>"><img src="<?=$other_categories_posts_one[0][1]?>" alt=""></a>
    <a href="?post=<?=$other_categories_posts_one[0][2]?>"><div class="other_posts_title"><?=$other_categories_posts_one[0][0]?></div></a>
  </div>
  </div>
</div>
<div class="other_categories_items">
<div class="main_of_title">
<a class="main_of_categories_a" href="?nav=7"><b>დღესასწაულები</b></a>
</div>
<div class="posts_url_img">
<div class="other_categories_items_div">
    <a href="?post=<?=$other_categories_posts_two[0][2]?>"><img src="<?=$other_categories_posts_two[0][1]?>" alt=""></a>
    <a href="?post=<?=$other_categories_posts_two[0][2]?>"><div class="other_posts_title"><?=$other_categories_posts_two[0][0]?></div></a>
  </div>
  </div>
</div>
<div class="other_categories_items">
<div class="main_of_title">
<a class="main_of_categories_a" href="?nav=15"><b>ხატები და ფრესკები</b></a>
</div>
<div class="posts_url_img">
<div class="other_categories_items_div">
    <a href="?post=<?=$other_categories_posts_three[0][2]?>"><img src="<?=$other_categories_posts_three[0][1]?>" alt=""></a>
    <a href="?post=<?=$other_categories_posts_three[0][2]?>"><div class="other_posts_title"><?=$other_categories_posts_three[0][0]?></div></a>
  </div>
  </div>
</div>
<div class="other_categories_items">
<div class="main_of_title">
<a class="main_of_categories_a" href="?nav=12"><b>საეკლესიო ხელოვნება</b></a>
</div>
<div class="posts_url_img">
<div class="other_categories_items_div">
    <a href="?post=<?=$other_categories_posts_four[0][2]?>"><img src="<?=$other_categories_posts_four[0][1]?>" alt=""></a>
    <a href="?post=<?=$other_categories_posts_four[0][2]?>"><div class="other_posts_title"><?=$other_categories_posts_four[0][0]?></div></a>
  </div>
  </div>
</div>
<div class="other_categories_items">
<div class="main_of_title">
<a class="main_of_categories_a" href="?nav=10"><b>კულტურა</b></a>
</div>
<div class="posts_url_img">
<div class="other_categories_items_div">
    <a href="?post=<?=$other_categories_posts_five[0][2]?>"><img src="<?=$other_categories_posts_five[0][1]?>" alt=""></a>
    <a href="?post=<<?=$other_categories_posts_five[0][2]?>"><div class="other_posts_title"><?=$other_categories_posts_five[0][0]?></div></a>
  </div>
  </div>
</div>
<div class="other_categories_items">
<div class="main_of_title">
<a class="main_of_categories_a" href="?nav=4"><b>სხვადასხვა</b></a>
</div>
<div class="posts_url_img">
<div class="other_categories_items_div">
    <a href="?post=<?=$other_categories_posts_six[0][2]?>"><img src="<?=$other_categories_posts_six[0][1]?>" alt=""></a>
    <a href="?post=<?=$other_categories_posts_six[0][2]?>"><div class="other_posts_title"><?=$other_categories_posts_six[0][0]?></div></a>
  </div>
  </div>
</div>
</div>
<!-- VIDEO -->
<div class="rightside_main">
<div class="main_of_categories">
<div class="main_of_title">
<a class="main_of_categories_a" href="?nav=8"><b>ვიდეო</b></a>
</div>
<div class="youtube_post">
<iframe width="560" height="315" src="https://www.youtube.com/embed/Bk5eID8UbqY?si=Yw2hUTSZQtN07lms" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe> 
</div>
<!-- video end -->
<div class="main_of_title">
<a style="margin-top: 30px;" class="main_of_categories_a" href="?nav=8"><b>ახალი სტატიები</b></a>
</div>     
<div class="categories_rightside">
<div class="most-read">
<?php for($h=0;$h<5;$h++){ ?>
            <ul>
                <a href="?post=<?=$newest_posts[$h][7]?>"><li><img class="post-moving-img"  src="<?=$newest_posts[$h][4]?>" alt=""><p><?=$newest_posts[$h][1]?><span><?=$newest_posts[$h][5]?></span></p></li></a>
              </ul>
            <?php }?>
    </div>
    </div>
    <!-- video two -->
    <div style="margin-top: 40px" class="main_of_title">
<a class="main_of_categories_a" href="?nav=8"><b>საგალობელი</b></a>
</div>
<div class="youtube_post">
<iframe width="560" height="315" src="https://www.youtube.com/embed/1Rg3tTA78U4?si=OGlLMFglKDII_OUp" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
</div>
    <!-- video two -->
    </div>
<?php 
include "new_comments.php";
?>



</div>

