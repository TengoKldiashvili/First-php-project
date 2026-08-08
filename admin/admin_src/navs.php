<?php
include "../db/connect.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_category_name'], $_POST['new_category_description'])) {
    $new_category_name = $_POST['new_category_name'];
    $new_category_description = $_POST['new_category_description'];
    $add_category = $connect->query("INSERT INTO navs (name, navs_description) VALUES ('$new_category_name', '$new_category_description')");
    if ($add_category) {
        header("Location: ?navs");
        exit();
    }
}

if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    $category_posts = mysqli_fetch_assoc($connect->query("SELECT COUNT(*) AS total FROM posts WHERE navs_id = '$delete_id'"));

    if ($category_posts['total'] == 0) {
        $del_result = mysqli_query($connect, "DELETE FROM navs WHERE id = '$delete_id'");
        header("Location: ?navs");
        exit();
    } else {
        $category_error = "კატეგორიის წაშლამდე მასში არსებული ღონისძიებები სხვა კატეგორიაში გადაიტანეთ.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['category_name'], $_POST['category_id'])) {
    $category_name = $_POST['category_name'];
    $category_description = $_POST['category_description'];
    $category_id = $_POST['category_id'];
    $updatenavs = $connect->query("UPDATE navs SET name = '$category_name', navs_description = '$category_description' WHERE id = '$category_id'");
    header("Location: ?navs");
    exit();
}

$categories = mysqli_fetch_all($connect->query("SELECT navs.id, navs.name, navs.navs_description, COUNT(posts.id) AS post_count FROM navs LEFT JOIN posts ON posts.navs_id = navs.id GROUP BY navs.id ORDER BY navs.id"), MYSQLI_ASSOC);
?>

<div class="admin-page-heading">
    <div>
        <p>საიტის ნავიგაცია</p>
        <h1>კატეგორიები</h1>
    </div>
</div>

<?php if (isset($category_error)): ?>
    <div class="admin-error-message"><?=$category_error?></div>
<?php endif; ?>

<div class="category-admin-layout">
    <div class="add-category-panel">
        <h2>ახალი კატეგორია</h2>
        <p>შეინარჩუნე მხოლოდ რამდენიმე მკაფიო კატეგორია.</p>
        <form action="?navs" method="POST" class="admin-form compact-form">
            <div class="form-field">
                <label for="new_category_name">კატეგორიის სახელი</label>
                <input type="text" id="new_category_name" name="new_category_name" maxlength="30" required>
            </div>
            <div class="form-field">
                <label for="new_category_description">მოკლე აღწერა</label>
                <textarea id="new_category_description" name="new_category_description" rows="4"></textarea>
            </div>
            <button type="submit" class="form-submit-btn">დამატება</button>
        </form>
    </div>

    <div class="categories-list">
        <?php foreach($categories as $category){ ?>
            <div class="category-row">
                <div class="category-main">
                    <span><?=$category['post_count']?> ღონისძიება</span>
                    <h2><?=$category['name']?></h2>
                    <p><?=$category['navs_description']?></p>
                </div>
                <div class="category-actions">
                    <button class="edit-btn" type="button" onclick="document.getElementById('edit-form-<?=$category['id']?>').style.display='block'">შეცვლა</button>
                    <?php if ($category['post_count'] == 0): ?>
                        <a href="?navs&delete=<?=$category['id']?>" onclick="return confirm('ნამდვილად გსურთ კატეგორიის წაშლა?')" class="delete-text-button">წაშლა</a>
                    <?php endif; ?>
                </div>

                <form id="edit-form-<?=$category['id']?>" action="?navs" method="POST" class="edit-category-form" style="display:none;">
                    <div class="form-field">
                        <label>კატეგორიის სახელი</label>
                        <input type="text" name="category_name" value="<?=$category['name']?>" maxlength="30" required>
                    </div>
                    <div class="form-field">
                        <label>მოკლე აღწერა</label>
                        <textarea name="category_description" rows="3"><?=$category['navs_description']?></textarea>
                    </div>
                    <input type="hidden" name="category_id" value="<?=$category['id']?>">
                    <div class="inline-actions">
                        <button type="submit" class="form-submit-btn">შენახვა</button>
                        <button type="button" class="cancel-btn" onclick="document.getElementById('edit-form-<?=$category['id']?>').style.display='none'">გაუქმება</button>
                    </div>
                </form>
            </div>
        <?php } ?>
    </div>
</div>
