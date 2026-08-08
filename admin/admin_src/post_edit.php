<?php
if(isset($_GET['post_edit'])){
    $idforedit = $_GET['post_edit'];
    $postedit = mysqli_fetch_all($connect->query("SELECT name, id, navs_id, imgs, small_description, description, organizer, location, event_date, registration_deadline, registration_url FROM posts WHERE id = '$idforedit'"), MYSQLI_ASSOC);
    $categories = mysqli_fetch_all($connect->query("SELECT id, name FROM navs ORDER BY id"), MYSQLI_ASSOC);
}

if (empty($postedit)) {
    echo "<div class='admin-empty-state'>ღონისძიება ვერ მოიძებნა.</div>";
    return;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category = $_POST['category'];
    $name = $_POST['name'];
    $small_description = $_POST['small_description'];
    $description = $_POST['description'];
    $imgs = $_POST['imgs'];
    $organizer = $_POST['organizer'] ?? '';
    $location = $_POST['location'] ?? '';
    $event_date = !empty($_POST['event_date']) ? "'" . $_POST['event_date'] . "'" : "NULL";
    $registration_deadline = !empty($_POST['registration_deadline']) ? "'" . $_POST['registration_deadline'] . "'" : "NULL";
    $registration_url = $_POST['registration_url'] ?? '';

    $updateResult = $connect->query("UPDATE posts SET name='$name', navs_id='$category', small_description='$small_description', description='$description', imgs='$imgs', organizer='$organizer', location='$location', event_date=$event_date, registration_deadline=$registration_deadline, registration_url='$registration_url' WHERE id='$idforedit'");
    header('Location: ?post_edit=' . $idforedit . '&updated=true');
    exit;
}

$updateSuccess = isset($_GET['updated']) && $_GET['updated'] == 'true';
?>

<div class="admin-page-heading compact-heading">
    <div>
        <a class="admin-back-link" href="?post_details=<?=$idforedit?>">← ღონისძიება</a>
        <p>რედაქტირება</p>
        <h1><?=$postedit[0]['name']?></h1>
    </div>
</div>

<?php if ($updateSuccess): ?>
    <div class="admin-success-message">ღონისძიება განახლებულია.</div>
<?php endif; ?>

<form action="?post_edit=<?=$idforedit?>" method="POST" class="admin-form edit-event-form">
    <div class="form-grid">
        <div class="form-field form-field-wide">
            <label for="name">ღონისძიების სახელი</label>
            <input type="text" id="name" name="name" value="<?=$postedit[0]['name']?>" required>
        </div>

        <div class="form-field">
            <label for="category">კატეგორია</label>
            <select id="category" name="category" required>
                <?php foreach($categories as $category): ?>
                    <option value="<?=$category['id']?>" <?=$category['id'] == $postedit[0]['navs_id'] ? 'selected' : ''?>><?=$category['name']?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-field">
            <label for="event_date">ღონისძიების თარიღი</label>
            <input type="datetime-local" id="event_date" name="event_date" value="<?=!empty($postedit[0]['event_date']) ? date('Y-m-d\TH:i', strtotime($postedit[0]['event_date'])) : ''?>" required>
        </div>

        <div class="form-field">
            <label for="location">ადგილმდებარეობა</label>
            <input type="text" id="location" name="location" value="<?=$postedit[0]['location']?>" required>
        </div>

        <div class="form-field">
            <label for="organizer">ორგანიზატორი</label>
            <input type="text" id="organizer" name="organizer" value="<?=$postedit[0]['organizer']?>">
        </div>

        <div class="form-field">
            <label for="registration_deadline">რეგისტრაციის ბოლო ვადა</label>
            <input type="datetime-local" id="registration_deadline" name="registration_deadline" value="<?=!empty($postedit[0]['registration_deadline']) ? date('Y-m-d\TH:i', strtotime($postedit[0]['registration_deadline'])) : ''?>">
        </div>

        <div class="form-field">
            <label for="registration_url">რეგისტრაციის ბმული</label>
            <input type="url" id="registration_url" name="registration_url" value="<?=$postedit[0]['registration_url']?>" placeholder="https://example.com/register">
        </div>

        <div class="form-field form-field-wide">
            <label for="imgs">სურათის ბმული</label>
            <input type="url" id="imgs" name="imgs" value="<?=$postedit[0]['imgs']?>" required>
        </div>

        <div class="form-field form-field-wide">
            <label for="small_description">მოკლე აღწერა</label>
            <textarea id="small_description" name="small_description" rows="3"><?=$postedit[0]['small_description']?></textarea>
        </div>

        <div class="form-field form-field-wide">
            <label for="description">სრული აღწერა</label>
            <textarea id="description" name="description" rows="8" required><?=$postedit[0]['description']?></textarea>
        </div>
    </div>

    <button type="submit" class="form-submit-btn">განახლება</button>
</form>
