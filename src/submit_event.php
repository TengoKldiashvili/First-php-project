<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: /tech-world/login");
    exit();
}

if (!isset($connect)) {
    include "../db/connect.php";
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$categories = mysqli_fetch_all($connect->query("SELECT id, name FROM navs ORDER BY id"), MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_submit_event'])) {
    $post_name = isset($_POST['post_name']) && is_string($_POST['post_name']) ? trim($_POST['post_name']) : '';
    $post_category = isset($_POST['post_category']) && is_scalar($_POST['post_category']) ? intval($_POST['post_category']) : 0;
    $post_event_date_value = isset($_POST['post_event_date']) && is_string($_POST['post_event_date']) ? trim($_POST['post_event_date']) : '';
    $post_location = isset($_POST['post_location']) && is_string($_POST['post_location']) ? trim($_POST['post_location']) : '';
    $post_organizer = isset($_POST['post_organizer']) && is_string($_POST['post_organizer']) ? trim($_POST['post_organizer']) : '';
    $post_registration_deadline_value = isset($_POST['post_registration_deadline']) && is_string($_POST['post_registration_deadline']) ? trim($_POST['post_registration_deadline']) : '';
    $post_registration_url = isset($_POST['post_registration_url']) && is_string($_POST['post_registration_url']) ? trim($_POST['post_registration_url']) : '';
    $post_image = isset($_POST['post_image']) && is_string($_POST['post_image']) ? trim($_POST['post_image']) : '';
    $post_small_description = isset($_POST['post_small_description']) && is_string($_POST['post_small_description']) ? trim($_POST['post_small_description']) : '';
    $post_description = isset($_POST['post_description']) && is_string($_POST['post_description']) ? trim($_POST['post_description']) : '';

    $event_time = strtotime($post_event_date_value);
    $deadline_time = !empty($post_registration_deadline_value) ? strtotime($post_registration_deadline_value) : false;
    $image_scheme = parse_url($post_image, PHP_URL_SCHEME);
    $registration_scheme = !empty($post_registration_url) ? parse_url($post_registration_url, PHP_URL_SCHEME) : '';
    $category_exists = $post_category > 0 ? mysqli_fetch_assoc($connect->query("SELECT id FROM navs WHERE id = $post_category")) : null;

    if (!isset($_POST['csrf_token']) || !is_string($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $submission_error = "არასწორი მოთხოვნა.";
    } elseif (empty($post_name) || empty($post_location) || empty($post_description) || empty($post_image) || empty($post_event_date_value)) {
        $submission_error = "შეავსეთ ყველა სავალდებულო ველი.";
    } elseif (empty($category_exists)) {
        $submission_error = "აირჩიეთ სწორი კატეგორია.";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $post_event_date_value) || $event_time === false || date('Y-m-d\TH:i', $event_time) != $post_event_date_value) {
        $submission_error = "ღონისძიების თარიღი არასწორია.";
    } elseif (!empty($post_registration_deadline_value) && (!preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $post_registration_deadline_value) || $deadline_time === false || date('Y-m-d\TH:i', $deadline_time) != $post_registration_deadline_value)) {
        $submission_error = "რეგისტრაციის ბოლო ვადა არასწორია.";
    } elseif (!filter_var($post_image, FILTER_VALIDATE_URL) || !in_array($image_scheme, ['http', 'https'])) {
        $submission_error = "სურათის ბმული არასწორია.";
    } elseif (!empty($post_registration_url) && (!filter_var($post_registration_url, FILTER_VALIDATE_URL) || !in_array($registration_scheme, ['http', 'https']))) {
        $submission_error = "რეგისტრაციის ბმული არასწორია.";
    } else {
        $post_event_date = date('Y-m-d H:i:s', $event_time);
        $post_registration_deadline = $deadline_time !== false ? date('Y-m-d H:i:s', $deadline_time) : null;
        $submit_event = mysqli_prepare($connect, "INSERT INTO posts (name, navs_id, imgs, description, small_description, count, organizer, location, event_date, registration_deadline, registration_url, is_approved) VALUES (?, ?, ?, ?, ?, 0, ?, ?, ?, ?, ?, 0)");
        mysqli_stmt_bind_param($submit_event, "sissssssss", $post_name, $post_category, $post_image, $post_description, $post_small_description, $post_organizer, $post_location, $post_event_date, $post_registration_deadline, $post_registration_url);

        if (mysqli_stmt_execute($submit_event)) {
            $submission_success = "ღონისძიება გაიგზავნა და ელოდება ადმინისტრატორის დადასტურებას.";
        } else {
            $submission_error = "ღონისძიების გაგზავნა ვერ მოხერხდა.";
        }
    }
}
?>

<div class="custom-profile-container submit-event-container">
    <div class="custom-profile-form submit-event-form">
        <h2>ღონისძიების დამატება</h2>
        <p class="submit-event-intro">შეავსეთ ღონისძიების ინფორმაცია. გამოქვეყნებამდე მას ადმინისტრატორი გადაამოწმებს.</p>

        <?php if (isset($submission_success)): ?>
            <p class="custom-success-message"><?=$submission_success?></p>
        <?php elseif (isset($submission_error)): ?>
            <p class="custom-error-message"><?=$submission_error?></p>
        <?php endif; ?>

        <form action="?submit_event" method="POST">
            <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION['csrf_token'])?>">
            <div class="submit-event-grid">
                <div class="custom-form-group submit-event-field-wide">
                    <label for="post_name">ღონისძიების სახელი</label>
                    <input type="text" id="post_name" name="post_name" required>
                </div>

                <div class="custom-form-group">
                    <label for="post_category">კატეგორია</label>
                    <select id="post_category" name="post_category" required>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?=intval($category['id'])?>"><?=htmlspecialchars($category['name'])?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="custom-form-group">
                    <label for="post_event_date">ღონისძიების თარიღი</label>
                    <input type="datetime-local" id="post_event_date" name="post_event_date" required>
                </div>

                <div class="custom-form-group">
                    <label for="post_location">ადგილმდებარეობა</label>
                    <input type="text" id="post_location" name="post_location" required>
                </div>

                <div class="custom-form-group">
                    <label for="post_organizer">ორგანიზატორი</label>
                    <input type="text" id="post_organizer" name="post_organizer">
                </div>

                <div class="custom-form-group">
                    <label for="post_registration_deadline">რეგისტრაციის ბოლო ვადა</label>
                    <input type="datetime-local" id="post_registration_deadline" name="post_registration_deadline">
                </div>

                <div class="custom-form-group">
                    <label for="post_registration_url">რეგისტრაციის ბმული</label>
                    <input type="url" id="post_registration_url" name="post_registration_url" placeholder="https://example.com/register">
                </div>

                <div class="custom-form-group submit-event-field-wide">
                    <label for="post_image">სურათის ბმული</label>
                    <input type="url" id="post_image" name="post_image" placeholder="https://example.com/image.jpg" required>
                </div>

                <div class="custom-form-group submit-event-field-wide">
                    <label for="post_small_description">მოკლე აღწერა</label>
                    <textarea id="post_small_description" name="post_small_description" rows="3"></textarea>
                </div>

                <div class="custom-form-group submit-event-field-wide">
                    <label for="post_description">სრული აღწერა</label>
                    <textarea id="post_description" name="post_description" rows="7" required></textarea>
                </div>
            </div>

            <button type="submit" name="user_submit_event" class="custom-button">ღონისძიების გაგზავნა</button>
        </form>
    </div>
</div>
