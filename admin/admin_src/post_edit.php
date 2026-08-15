<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../../index.php?login");
    exit();
}

if (!isset($connect)) {
    include __DIR__ . "/../../db/connect.php";
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$postedit = [];

if(isset($_GET['post_edit'])){
    $idforedit = is_scalar($_GET['post_edit']) ? intval($_GET['post_edit']) : 0;
    $postedit = mysqli_fetch_all($connect->query("SELECT name, id, navs_id, imgs, small_description, description, organizer, location, event_date, registration_deadline, registration_url FROM posts WHERE id = $idforedit"), MYSQLI_ASSOC);
    $categories = mysqli_fetch_all($connect->query("SELECT id, name FROM navs ORDER BY id"), MYSQLI_ASSOC);
}

if (empty($postedit)) {
    echo "<div class='admin-empty-state'>ღონისძიება ვერ მოიძებნა.</div>";
    return;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category = isset($_POST['category']) && is_scalar($_POST['category']) ? intval($_POST['category']) : 0;
    $name = isset($_POST['name']) && is_string($_POST['name']) ? trim($_POST['name']) : '';
    $small_description = isset($_POST['small_description']) && is_string($_POST['small_description']) ? trim($_POST['small_description']) : '';
    $description = isset($_POST['description']) && is_string($_POST['description']) ? trim($_POST['description']) : '';
    $imgs = isset($_POST['imgs']) && is_string($_POST['imgs']) ? trim($_POST['imgs']) : '';
    $organizer = isset($_POST['organizer']) && is_string($_POST['organizer']) ? trim($_POST['organizer']) : '';
    $location = isset($_POST['location']) && is_string($_POST['location']) ? trim($_POST['location']) : '';
    $event_date_value = isset($_POST['event_date']) && is_string($_POST['event_date']) ? trim($_POST['event_date']) : '';
    $registration_deadline_value = isset($_POST['registration_deadline']) && is_string($_POST['registration_deadline']) ? trim($_POST['registration_deadline']) : '';
    $registration_url = isset($_POST['registration_url']) && is_string($_POST['registration_url']) ? trim($_POST['registration_url']) : '';

    $event_time = strtotime($event_date_value);
    $deadline_time = !empty($registration_deadline_value) ? strtotime($registration_deadline_value) : false;
    $image_scheme = parse_url($imgs, PHP_URL_SCHEME);
    $registration_scheme = !empty($registration_url) ? parse_url($registration_url, PHP_URL_SCHEME) : '';
    $category_exists = $category > 0 ? mysqli_fetch_assoc($connect->query("SELECT id FROM navs WHERE id = $category")) : null;

    if (!isset($_POST['csrf_token']) || !is_string($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $update_error = "არასწორი მოთხოვნა.";
    } elseif (empty($name) || empty($description) || empty($imgs) || empty($location) || empty($event_date_value)) {
        $update_error = "შეავსეთ ყველა სავალდებულო ველი.";
    } elseif (empty($category_exists)) {
        $update_error = "აირჩიეთ სწორი კატეგორია.";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $event_date_value) || $event_time === false || date('Y-m-d\TH:i', $event_time) != $event_date_value) {
        $update_error = "ღონისძიების თარიღი არასწორია.";
    } elseif (!empty($registration_deadline_value) && (!preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $registration_deadline_value) || $deadline_time === false || date('Y-m-d\TH:i', $deadline_time) != $registration_deadline_value)) {
        $update_error = "რეგისტრაციის ბოლო ვადა არასწორია.";
    } elseif (!filter_var($imgs, FILTER_VALIDATE_URL) || !in_array($image_scheme, ['http', 'https'])) {
        $update_error = "სურათის ბმული არასწორია.";
    } elseif (!empty($registration_url) && (!filter_var($registration_url, FILTER_VALIDATE_URL) || !in_array($registration_scheme, ['http', 'https']))) {
        $update_error = "რეგისტრაციის ბმული არასწორია.";
    } else {
        $event_date = date('Y-m-d H:i:s', $event_time);
        $registration_deadline = $deadline_time !== false ? date('Y-m-d H:i:s', $deadline_time) : null;
        $update_post = mysqli_prepare($connect, "UPDATE posts SET name = ?, navs_id = ?, small_description = ?, description = ?, imgs = ?, organizer = ?, location = ?, event_date = ?, registration_deadline = ?, registration_url = ? WHERE id = ?");
        mysqli_stmt_bind_param($update_post, "sissssssssi", $name, $category, $small_description, $description, $imgs, $organizer, $location, $event_date, $registration_deadline, $registration_url, $idforedit);

        if (mysqli_stmt_execute($update_post)) {
            header('Location: ?post_edit=' . $idforedit . '&updated=true');
            exit;
        } else {
            $update_error = "ღონისძიების განახლება ვერ მოხერხდა.";
        }
    }
}

$updateSuccess = isset($_GET['updated']) && $_GET['updated'] == 'true';
?>

<div class="admin-page-heading compact-heading">
    <div>
        <a class="admin-back-link" href="?post_details=<?=intval($idforedit)?>">← ღონისძიება</a>
        <p>რედაქტირება</p>
        <h1><?=htmlspecialchars($postedit[0]['name'])?></h1>
    </div>
</div>

<?php if ($updateSuccess): ?>
    <div class="admin-success-message">ღონისძიება განახლებულია.</div>
<?php endif; ?>

<?php if (isset($update_error)): ?>
    <div class="admin-error-message"><?=htmlspecialchars($update_error)?></div>
<?php endif; ?>

<form action="?post_edit=<?=intval($idforedit)?>" method="POST" class="admin-form edit-event-form">
    <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION['csrf_token'])?>">
    <div class="form-grid">
        <div class="form-field form-field-wide">
            <label for="name">ღონისძიების სახელი</label>
            <input type="text" id="name" name="name" value="<?=htmlspecialchars($postedit[0]['name'])?>" required>
        </div>

        <div class="form-field">
            <label for="category">კატეგორია</label>
            <select id="category" name="category" required>
                <?php foreach($categories as $category): ?>
                    <option value="<?=intval($category['id'])?>" <?=$category['id'] == $postedit[0]['navs_id'] ? 'selected' : ''?>><?=htmlspecialchars($category['name'])?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-field">
            <label for="event_date">ღონისძიების თარიღი</label>
            <input type="datetime-local" id="event_date" name="event_date" value="<?=!empty($postedit[0]['event_date']) ? date('Y-m-d\TH:i', strtotime($postedit[0]['event_date'])) : ''?>" required>
        </div>

        <div class="form-field">
            <label for="location">ადგილმდებარეობა</label>
            <input type="text" id="location" name="location" value="<?=htmlspecialchars($postedit[0]['location'])?>" required>
        </div>

        <div class="form-field">
            <label for="organizer">ორგანიზატორი</label>
            <input type="text" id="organizer" name="organizer" value="<?=htmlspecialchars($postedit[0]['organizer'])?>">
        </div>

        <div class="form-field">
            <label for="registration_deadline">რეგისტრაციის ბოლო ვადა</label>
            <input type="datetime-local" id="registration_deadline" name="registration_deadline" value="<?=!empty($postedit[0]['registration_deadline']) ? date('Y-m-d\TH:i', strtotime($postedit[0]['registration_deadline'])) : ''?>">
        </div>

        <div class="form-field">
            <label for="registration_url">რეგისტრაციის ბმული</label>
            <input type="url" id="registration_url" name="registration_url" value="<?=htmlspecialchars($postedit[0]['registration_url'])?>" placeholder="https://example.com/register">
        </div>

        <div class="form-field form-field-wide">
            <label for="imgs">სურათის ბმული</label>
            <input type="url" id="imgs" name="imgs" value="<?=htmlspecialchars($postedit[0]['imgs'])?>" required>
        </div>

        <div class="form-field form-field-wide">
            <label for="small_description">მოკლე აღწერა</label>
            <textarea id="small_description" name="small_description" rows="3"><?=htmlspecialchars($postedit[0]['small_description'])?></textarea>
        </div>

        <div class="form-field form-field-wide">
            <label for="description">სრული აღწერა</label>
            <textarea id="description" name="description" rows="8" required><?=htmlspecialchars($postedit[0]['description'])?></textarea>
        </div>
    </div>

    <button type="submit" class="form-submit-btn">განახლება</button>
</form>
