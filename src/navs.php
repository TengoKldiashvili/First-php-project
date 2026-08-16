<?php
include "db/connect.php";
$nav = mysqli_fetch_all($connect->query("SELECT name,id FROM navs ORDER BY id"));
?>
<nav class="main-navigation" aria-label="ღონისძიებების კატეგორიები">
    <ul>
        <?php foreach($nav as $navs){ ?>
            <li><a href="/tech-world/category/<?=intval($navs[1])?>"><?=htmlspecialchars($navs[0])?></a></li>
        <?php } ?>
    </ul>
</nav>
