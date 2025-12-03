<?php
include "db/connect.php";
$nav = mysqli_fetch_all($connect->query("SELECT name,id FROM navs"));
?>
<div class="navheader">
    <nav>
        <ul>
        <?php
        $count = 0; 
        foreach($nav as $navs){
            if ($count >= 4) break;?>
            <li><a href="?nav=<?=$navs[1]?>"><?=htmlspecialchars($navs[0])?></a></li>
        <?php
            $count++;
        }?>
        <li></li>
        </ul>  
    </nav> 
</div>
