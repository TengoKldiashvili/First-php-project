<?php
if(isset($_GET['post_details'])){
    $postdetailsid = $_GET['post_details'];
    $postdetails = mysqli_fetch_all($connect->query("SELECT name,id,navs_id,imgs,description,small_description FROM posts WHERE id = '$postdetailsid'"), MYSQLI_ASSOC);

    $navs_id = $postdetails[0]['navs_id'];
    $category = mysqli_fetch_assoc($connect->query("SELECT name FROM navs WHERE id = '$navs_id'")); 
}

if (isset($_GET['delete_post'])) {
    $delete_post_id = $_GET['delete_post'];
    $delete_post = mysqli_query($connect, "DELETE FROM posts WHERE id = '$delete_post_id'");
    if ($delete_post) {
        header("Location: ?post_before"); 
        exit();
    }
}
?>
<div class="posts">
    <?php foreach($postdetails as $full): ?>
        <div class="post">
            <img src="<?=$full['imgs']?>" alt="">
            <br>
            <p><strong>კატეგორია:</strong> <?=$category['name']?></p>
            <br>
            <h2><?=$full['name']?></h2>
            <br>
            <span><?=$full['description']?></span>
            <br>
            <br>

            <a href="?post_edit=<?=$full['id']?>" class="post_detail">რედაქტირება</a>

            <a href="?post_details=<?=$full['id']?>&delete_post=<?=$full['id']?>" onclick="return confirm('ნამდვილად გსურთ პოსტის წაშლა?')" class="delete-btn">წაშლა</a>
        </div>
    <?php endforeach; ?>
</div>
