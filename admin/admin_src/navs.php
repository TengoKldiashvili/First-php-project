<?php
include "../db/connect.php";
$categories = mysqli_fetch_all($connect->query("SELECT id, name, navs_description FROM navs"), MYSQLI_ASSOC);

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
    $del_result = mysqli_query($connect, "DELETE FROM navs WHERE id = '$delete_id'");
    header("Location: ?navs");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['category_name'], $_POST['category_id'])) {
    $category_name = $_POST['category_name'];
    $category_id = $_POST['category_id'];
    $updatenavs = $connect->query("UPDATE navs SET name = '$category_name' WHERE id = '$category_id'");
    header("Location: ?navs");
    exit();
}
?>

<div class="add-category-form-container">
    <h2>ახალი კატეგორიის დამატება</h2>
    <form action="" method="POST" class="add-category-form">
        <label for="new_category_name">კატეგორიის სახელი</label>
        <input type="text" id="new_category_name" name="new_category_name" class="category-input" placeholder="შეიყვანეთ კატეგორიის სახელი" required>
        <br>
        <br>
        <label for="new_category_description">კატეგორიის აღწერა</label>
        <input type="text" id="new_category_description" name="new_category_description" class="category-input" placeholder="შეიყვანეთ კატეგორიის აღწერა">
        <button type="submit" class="submit-btn">დამატება</button>
    </form>
</div>

<table class="categories-table">
    <thead>
        <tr>
            <th>ნავიგაციის სახელი</th>
            <th>აღწერა</th>
            <th>მოქმედება</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($categories as $category){ ?>
            <tr class="category-row">
                <td class="category-name"><?=$category['name']?></td>
                <td class="category-description"><?=$category['navs_description']?></td>
                <td class="category-actions">
                    <button class="edit-btn" onclick="document.getElementById('edit-form-<?=$category['id']?>').style.display='block'">შეცვლა</button>
                    <a href="?navs&delete=<?=$category['id']?>" onclick="return confirm('ნამდვილად გსურთ პოსტის წაშლა?')" class="delete-btn">წაშლა</a>

                </td>
            </tr>

            <tr id="edit-form-<?=$category['id']?>" class="edit-form" style="display:none;">
                <form action="" method="POST" class="edit-category-form">
                    <td><input type="text" name="category_name" value="<?=$category['name']?>" class="category-input" required></td>
                    <td><input type="text" name="category_description" value="<?=$category['navs_description']?>" class="category-input"></td>
                    <td>
                        <input type="hidden" name="category_id" value="<?=$category['id']?>">
                        <button type="submit" class="submit-btn">შეცვლა</button>
                        <button type="button" class="cancel-btn" onclick="document.getElementById('edit-form-<?=$category['id']?>').style.display='none'">უკან</button>
                    </td>
                </form>
            </tr>
        <?php }?>
    </tbody>
</table>
