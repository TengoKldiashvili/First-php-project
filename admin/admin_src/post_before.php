<?php
include "../db/connect.php";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['post_name'], $_POST['post_description'], $_POST['post_image'], $_POST['post_category'])) {
    $post_name = $_POST['post_name'];
    $post_description = $_POST['post_description'];
    $post_small_description = $_POST['post_small_description'] ?? '';
    $post_image = $_POST['post_image'];
    $post_category = $_POST['post_category'];
    $post_organizer = $_POST['post_organizer'] ?? '';
    $post_location = $_POST['post_location'] ?? '';
    $post_event_date = !empty($_POST['post_event_date']) ? "'" . $_POST['post_event_date'] . "'" : "NULL";
    $post_registration_deadline = !empty($_POST['post_registration_deadline']) ? "'" . $_POST['post_registration_deadline'] . "'" : "NULL";
    $post_registration_url = $_POST['post_registration_url'] ?? '';

    $insert_new_post = $connect->query("
        INSERT INTO posts (name, navs_id, imgs, description, small_description, count, organizer, location, event_date, registration_deadline, registration_url)
        VALUES ('$post_name', '$post_category', '$post_image', '$post_description', '$post_small_description', 0, '$post_organizer', '$post_location', $post_event_date, $post_registration_deadline, '$post_registration_url')
    ");

    if ($insert_new_post) {
        $post_added = true;
    }
}

$posts_admin = mysqli_fetch_all($connect->query("SELECT posts.name, posts.id, posts.imgs, posts.event_date, posts.location, navs.name AS category_name FROM posts LEFT JOIN navs ON posts.navs_id = navs.id ORDER BY posts.event_date IS NULL, posts.event_date ASC"), MYSQLI_ASSOC);
$categories = mysqli_fetch_all($connect->query("SELECT id, name FROM navs ORDER BY id"), MYSQLI_ASSOC);
?>

<div class="admin-page-heading">
    <div>
        <p>კონტენტის მართვა</p>
        <h1>ღონისძიებები</h1>
    </div>
    <button id="add-post-btn" class="primary-admin-button" type="button">ღონისძიების დამატება</button>
</div>

<?php if (isset($post_added)): ?>
    <div class="admin-success-message">ღონისძიება წარმატებით დაემატა.</div>
<?php endif; ?>

<div id="add-post-modal" class="modal">
    <div class="modal-content">
        <div class="modal-heading">
            <div>
                <p>ახალი ჩანაწერი</p>
                <h2>ღონისძიების დამატება</h2>
            </div>
            <button class="close" type="button" aria-label="დახურვა">&times;</button>
        </div>

        <form action="?post_before" method="POST" class="admin-form">
            <div class="form-grid">
                <div class="form-field form-field-wide">
                    <label for="post_name">ღონისძიების სახელი</label>
                    <input type="text" id="post_name" name="post_name" required>
                </div>

                <div class="form-field">
                    <label for="post_category">კატეგორია</label>
                    <select id="post_category" name="post_category" required>
                        <?php foreach ($categories as $category) { ?>
                            <option value="<?=$category['id']?>"><?=$category['name']?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-field">
                    <label for="post_event_date">ღონისძიების თარიღი</label>
                    <input type="datetime-local" id="post_event_date" name="post_event_date" required>
                </div>

                <div class="form-field">
                    <label for="post_location">ადგილმდებარეობა</label>
                    <input type="text" id="post_location" name="post_location" required>
                </div>

                <div class="form-field">
                    <label for="post_organizer">ორგანიზატორი</label>
                    <input type="text" id="post_organizer" name="post_organizer">
                </div>

                <div class="form-field">
                    <label for="post_registration_deadline">რეგისტრაციის ბოლო ვადა</label>
                    <input type="datetime-local" id="post_registration_deadline" name="post_registration_deadline">
                </div>

                <div class="form-field">
                    <label for="post_registration_url">რეგისტრაციის ბმული</label>
                    <input type="url" id="post_registration_url" name="post_registration_url" placeholder="https://example.com/register">
                </div>

                <div class="form-field form-field-wide">
                    <label for="post_image">სურათის ბმული</label>
                    <input type="url" id="post_image" name="post_image" required placeholder="https://example.com/image.jpg">
                </div>

                <div class="form-field form-field-wide">
                    <label for="post_small_description">მოკლე აღწერა</label>
                    <textarea id="post_small_description" name="post_small_description" rows="3"></textarea>
                </div>

                <div class="form-field form-field-wide">
                    <label for="post_description">სრული აღწერა</label>
                    <textarea id="post_description" name="post_description" rows="7" required></textarea>
                </div>
            </div>

            <button type="submit" class="form-submit-btn">დამატება</button>
        </form>
    </div>
</div>

<?php if (empty($posts_admin)): ?>
    <div class="admin-empty-state">ღონისძიებები ჯერ არ არის დამატებული.</div>
<?php else: ?>
    <div class="admin-events-grid">
        <?php foreach($posts_admin as $postdetails): ?>
            <article class="admin-event-card">
                <img src="<?=$postdetails['imgs']?>" alt="">
                <div>
                    <span><?=$postdetails['category_name']?></span>
                    <h2><?=$postdetails['name']?></h2>
                    <?php if (!empty($postdetails['event_date'])): ?><p><?=date('d.m.Y · H:i', strtotime($postdetails['event_date']))?></p><?php endif; ?>
                    <?php if (!empty($postdetails['location'])): ?><p><?=$postdetails['location']?></p><?php endif; ?>
                    <a href="?post_details=<?=$postdetails['id']?>" class="text-button">ღონისძიების ნახვა →</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<script>
    var modal = document.getElementById("add-post-modal");
    var btn = document.getElementById("add-post-btn");
    var closeButton = document.getElementsByClassName("close")[0];

    btn.onclick = function() {
        modal.style.display = "flex";
    }

    closeButton.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
