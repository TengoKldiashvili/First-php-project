<?php
if(isset($_GET['post_details'])){
    $postdetailsid = $_GET['post_details'];
    $postdetails = mysqli_fetch_all($connect->query("SELECT name,id,navs_id,imgs,description,small_description,organizer,location,event_date,registration_deadline,registration_url FROM posts WHERE id = '$postdetailsid'"), MYSQLI_ASSOC);

    if (empty($postdetails)) {
        echo "<div class='admin-empty-state'>ღონისძიება ვერ მოიძებნა.</div>";
        return;
    }

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

<?php foreach($postdetails as $full): ?>
    <div class="admin-page-heading compact-heading">
        <div>
            <a class="admin-back-link" href="?post_before">← ღონისძიებები</a>
            <p><?=$category['name']?></p>
            <h1><?=$full['name']?></h1>
        </div>
        <div class="heading-actions">
            <a href="?post_edit=<?=$full['id']?>" class="primary-admin-button">რედაქტირება</a>
            <a href="?post_details=<?=$full['id']?>&delete_post=<?=$full['id']?>" onclick="return confirm('ნამდვილად გსურთ ღონისძიების წაშლა?')" class="danger-button">წაშლა</a>
        </div>
    </div>

    <div class="admin-event-detail">
        <img class="admin-event-cover" src="<?=$full['imgs']?>" alt="">

        <div class="admin-detail-grid">
            <div class="admin-description">
                <?php if (!empty($full['small_description'])): ?><strong><?=$full['small_description']?></strong><?php endif; ?>
                <p><?=$full['description']?></p>
            </div>

            <aside class="admin-facts">
                <?php if (!empty($full['event_date'])): ?><div><span>თარიღი</span><strong><?=date('d.m.Y · H:i', strtotime($full['event_date']))?></strong></div><?php endif; ?>
                <?php if (!empty($full['location'])): ?><div><span>ადგილმდებარეობა</span><strong><?=$full['location']?></strong></div><?php endif; ?>
                <?php if (!empty($full['organizer'])): ?><div><span>ორგანიზატორი</span><strong><?=$full['organizer']?></strong></div><?php endif; ?>
                <?php if (!empty($full['registration_deadline'])): ?><div><span>რეგისტრაციის ბოლო ვადა</span><strong><?=date('d.m.Y · H:i', strtotime($full['registration_deadline']))?></strong></div><?php endif; ?>
                <?php if (!empty($full['registration_url'])): ?><a href="<?=htmlspecialchars($full['registration_url'])?>" target="_blank" rel="noopener noreferrer">რეგისტრაციის ბმული ↗</a><?php endif; ?>
            </aside>
        </div>
    </div>
<?php endforeach; ?>
