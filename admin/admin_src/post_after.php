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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_post'])) {
    if (!isset($_POST['csrf_token']) || !is_string($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        http_response_code(403);
        exit("არასწორი მოთხოვნა.");
    }

    $delete_post_id = is_scalar($_POST['delete_post']) ? intval($_POST['delete_post']) : 0;

    if ($delete_post_id > 0 && mysqli_query($connect, "DELETE FROM posts WHERE id = $delete_post_id")) {
        header("Location: ?post_before");
        exit();
    }
}

$postdetails = [];

if(isset($_GET['post_details'])){
    $postdetailsid = is_scalar($_GET['post_details']) ? intval($_GET['post_details']) : 0;
    $postdetails = mysqli_fetch_all($connect->query("SELECT name,id,navs_id,imgs,description,small_description,organizer,location,event_date,registration_deadline,registration_url,is_approved FROM posts WHERE id = $postdetailsid"), MYSQLI_ASSOC);

    if (empty($postdetails)) {
        echo "<div class='admin-empty-state'>ღონისძიება ვერ მოიძებნა.</div>";
        return;
    }

    $navs_id = intval($postdetails[0]['navs_id']);
    $category = mysqli_fetch_assoc($connect->query("SELECT name FROM navs WHERE id = $navs_id"));
}
?>

<?php foreach($postdetails as $full): ?>
    <?php
    $registration_scheme = !empty($full['registration_url']) ? parse_url($full['registration_url'], PHP_URL_SCHEME) : '';
    $safe_registration_url = !empty($full['registration_url']) && filter_var($full['registration_url'], FILTER_VALIDATE_URL) && in_array($registration_scheme, ['http', 'https']);
    ?>
    <div class="admin-page-heading compact-heading">
        <div>
            <a class="admin-back-link" href="?post_before">← ღონისძიებები</a>
            <p><?=htmlspecialchars($category['name'])?></p>
            <h1><?=htmlspecialchars($full['name'])?></h1>
        </div>
        <div class="heading-actions">
            <?php if ($full['is_approved'] == 0): ?>
                <form action="?post_before" method="POST" style="display:inline;">
                    <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION['csrf_token'])?>">
                    <button type="submit" name="approve_post" value="<?=intval($full['id'])?>" class="primary-admin-button">დადასტურება</button>
                </form>
            <?php endif; ?>
            <a href="?post_edit=<?=intval($full['id'])?>" class="primary-admin-button">რედაქტირება</a>
            <form action="?post_details=<?=intval($full['id'])?>" method="POST" style="display:inline;" onsubmit="return confirm('ნამდვილად გსურთ ღონისძიების წაშლა?')">
                <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION['csrf_token'])?>">
                <button type="submit" name="delete_post" value="<?=intval($full['id'])?>" class="danger-button">წაშლა</button>
            </form>
        </div>
    </div>

    <div class="admin-event-detail">
        <img class="admin-event-cover" src="<?=htmlspecialchars($full['imgs'])?>" alt="">

        <div class="admin-detail-grid">
            <div class="admin-description">
                <?php if (!empty($full['small_description'])): ?><strong><?=htmlspecialchars($full['small_description'])?></strong><?php endif; ?>
                <p><?=nl2br(htmlspecialchars($full['description']))?></p>
            </div>

            <aside class="admin-facts">
                <?php if (!empty($full['event_date'])): ?><div><span>თარიღი</span><strong><?=date('d.m.Y · H:i', strtotime($full['event_date']))?></strong></div><?php endif; ?>
                <?php if (!empty($full['location'])): ?><div><span>ადგილმდებარეობა</span><strong><?=htmlspecialchars($full['location'])?></strong></div><?php endif; ?>
                <?php if (!empty($full['organizer'])): ?><div><span>ორგანიზატორი</span><strong><?=htmlspecialchars($full['organizer'])?></strong></div><?php endif; ?>
                <?php if (!empty($full['registration_deadline'])): ?><div><span>რეგისტრაციის ბოლო ვადა</span><strong><?=date('d.m.Y · H:i', strtotime($full['registration_deadline']))?></strong></div><?php endif; ?>
                <?php if ($safe_registration_url): ?><a href="<?=htmlspecialchars($full['registration_url'])?>" target="_blank" rel="noopener noreferrer">რეგისტრაციის ბმული ↗</a><?php endif; ?>
            </aside>
        </div>
    </div>
<?php endforeach; ?>
