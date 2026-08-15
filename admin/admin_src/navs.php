<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../../index.php?login");
    exit();
}

include_once __DIR__ . "/../../db/connect.php";

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || !is_string($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        http_response_code(403);
        exit("არასწორი მოთხოვნა.");
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_category_name'], $_POST['new_category_description'])) {
    $new_category_name = is_string($_POST['new_category_name']) ? trim($_POST['new_category_name']) : '';
    $new_category_description = is_string($_POST['new_category_description']) ? trim($_POST['new_category_description']) : '';

    if (empty($new_category_name) || mb_strlen($new_category_name) > 30) {
        $category_error = "კატეგორიის სახელი უნდა შეიცავდეს 1-დან 30-მდე სიმბოლოს.";
    } else {
        $add_category = mysqli_prepare($connect, "INSERT INTO navs (name, navs_description) VALUES (?, ?)");
        mysqli_stmt_bind_param($add_category, "ss", $new_category_name, $new_category_description);
    }

    if (isset($add_category) && mysqli_stmt_execute($add_category)) {
        header("Location: ?navs");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_category'])) {
    $delete_id = is_scalar($_POST['delete_category']) ? intval($_POST['delete_category']) : 0;
    $category_posts = mysqli_fetch_assoc($connect->query("SELECT COUNT(*) AS total FROM posts WHERE navs_id = $delete_id"));

    if ($delete_id > 0 && $category_posts['total'] == 0) {
        $del_result = mysqli_query($connect, "DELETE FROM navs WHERE id = $delete_id");
        header("Location: ?navs");
        exit();
    } else {
        $category_error = "კატეგორიის წაშლამდე მასში არსებული ღონისძიებები სხვა კატეგორიაში გადაიტანეთ.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['category_name'], $_POST['category_id'])) {
    $category_name = is_string($_POST['category_name']) ? trim($_POST['category_name']) : '';
    $category_description = isset($_POST['category_description']) && is_string($_POST['category_description']) ? trim($_POST['category_description']) : '';
    $category_id = is_scalar($_POST['category_id']) ? intval($_POST['category_id']) : 0;

    if (empty($category_name) || mb_strlen($category_name) > 30 || $category_id <= 0) {
        $category_error = "კატეგორიის მონაცემები არასწორია.";
    } else {
        $update_category = mysqli_prepare($connect, "UPDATE navs SET name = ?, navs_description = ? WHERE id = ?");
        mysqli_stmt_bind_param($update_category, "ssi", $category_name, $category_description, $category_id);

        if (mysqli_stmt_execute($update_category)) {
            header("Location: ?navs");
            exit();
        }
    }
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
    <div class="admin-error-message"><?=htmlspecialchars($category_error)?></div>
<?php endif; ?>

<div class="category-admin-layout">
    <div class="add-category-panel">
        <h2>ახალი კატეგორია</h2>
        <p>შეინარჩუნე მხოლოდ რამდენიმე მკაფიო კატეგორია.</p>
        <form action="?navs" method="POST" class="admin-form compact-form">
            <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION['csrf_token'])?>">
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
                    <h2><?=htmlspecialchars($category['name'])?></h2>
                    <p><?=htmlspecialchars($category['navs_description'])?></p>
                </div>
                <div class="category-actions">
                    <button class="edit-btn" type="button" onclick="document.getElementById('edit-form-<?=intval($category['id'])?>').style.display='block'">შეცვლა</button>
                    <?php if ($category['post_count'] == 0): ?>
                        <form action="?navs" method="POST" style="display:inline;" onsubmit="return confirm('ნამდვილად გსურთ კატეგორიის წაშლა?')">
                            <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION['csrf_token'])?>">
                            <button type="submit" name="delete_category" value="<?=intval($category['id'])?>" class="delete-text-button">წაშლა</button>
                        </form>
                    <?php endif; ?>
                </div>

                <form id="edit-form-<?=intval($category['id'])?>" action="?navs" method="POST" class="edit-category-form" style="display:none;">
                    <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION['csrf_token'])?>">
                    <div class="form-field">
                        <label>კატეგორიის სახელი</label>
                        <input type="text" name="category_name" value="<?=htmlspecialchars($category['name'])?>" maxlength="30" required>
                    </div>
                    <div class="form-field">
                        <label>მოკლე აღწერა</label>
                        <textarea name="category_description" rows="3"><?=htmlspecialchars($category['navs_description'])?></textarea>
                    </div>
                    <input type="hidden" name="category_id" value="<?=intval($category['id'])?>">
                    <div class="inline-actions">
                        <button type="submit" class="form-submit-btn">შენახვა</button>
                        <button type="button" class="cancel-btn" onclick="document.getElementById('edit-form-<?=intval($category['id'])?>').style.display='none'">გაუქმება</button>
                    </div>
                </form>
            </div>
        <?php } ?>
    </div>
</div>
