<?php
include "../db/connect.php";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['post_name'], $_POST['post_description'], $_POST['post_image'], $_POST['post_category'])) {
    $post_name = $_POST['post_name'];
    $post_description = $_POST['post_description'];
    $post_small_description = $_POST['post_small_description'] ?? '';
    $post_image = $_POST['post_image'];
    $post_category = $_POST['post_category'];

    $insert_new_post = $connect->query("
        INSERT INTO posts (name, navs_id, imgs, description, small_description, count) 
        VALUES ('$post_name', '$post_category', '$post_image', '$post_description', '$post_small_description', 0)
    ");
}
$posts_admin = mysqli_fetch_all($connect->query("SELECT name,id,navs_id,imgs,description,small_description FROM posts"), MYSQLI_ASSOC);
?>


<button id="add-post-btn" class="add-post-btn">სიახლის დამატება</button>

<div id="add-post-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>ახალი პოსტის დამატება</h2>
        <form action="" method="POST" class="add-post-form">
            <label for="post_name">პოსტის სახელი</label>
            <input type="text" id="post_name" name="post_name" required>
            
            <label for="post_description">პოსტის სრული აღწერა (სავალდებულოა)</label>
            <textarea id="post_description" name="post_description" required></textarea>
            
            <label for="post_small_description">მოკლე აღწერა</label>
            <textarea id="post_small_description" name="post_small_description"></textarea>
            
            <label for="post_image">სურათის ლინკი (სავალდებულოა)</label>
            <input type="url" id="post_image" name="post_image" required placeholder="https://example.com/image.jpg">

            <label for="post_category">კატეგორია</label>
            <select id="post_category" name="post_category">
                <?php
                $categories = mysqli_fetch_all($connect->query("SELECT id, name FROM navs"), MYSQLI_ASSOC);
                foreach ($categories as $category) {
                    echo "<option value='{$category['id']}'>{$category['name']}</option>";
                }
                ?>
            </select>
            
            <button type="submit" class="submit-btn">დამატება</button>
        </form>
    </div>
</div>

<div class="posts">
    <?php foreach($posts_admin as $postdetails): ?>
        <div class="post">
            <h2><?=$postdetails['name']?></h2>
            <p><?=$postdetails['description']?></p>
            <br>
            <a href="?post_details=<?=$postdetails['id']?>" class="post_detail">დეტალურად</a>
        </div>
    <?php endforeach; ?>
</div>

<script>
    var modal = document.getElementById("add-post-modal");
    var btn = document.getElementById("add-post-btn");
    var span = document.getElementsByClassName("close")[0];

    btn.onclick = function() {
        modal.style.display = "block";
    }

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>