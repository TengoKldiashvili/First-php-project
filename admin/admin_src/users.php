<?php
if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];

    $delete_user = mysqli_query($connect, "DELETE FROM users WHERE id = $user_id");

    if ($delete_user) {
        header("Refresh: 2; url=?users");
        exit();
    }
}

if (isset($_POST['make_admin'])) {
    $user_id = $_POST['user_id'];
    $make_admin = mysqli_query($connect, "UPDATE users SET is_admin = 1 WHERE id = $user_id");
}
    if ($make_admin) {
        header("Refresh: 2; url=?users");
        exit();}

if (isset($_POST['revoke_admin'])) {
    $user_id = $_POST['user_id'];
    $revoke_admin = mysqli_query($connect, "UPDATE users SET is_admin = 0 WHERE id = $user_id");
}    if ($revoke_admin) {
    header("Refresh: 2; url=?users");
    exit();}
$download_users = "SELECT id, email, username, is_admin FROM users";
$users_result = mysqli_query($connect, $download_users);
?>

<h2>მომხმარებლები</h2>
<table>
    <thead>
        <tr>
            <th>სახელი</th>
            <th>ელ-ფოსტა</th>
            <th>ადმინისტრატორი</th>
            <th>მოქმედება</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($user = mysqli_fetch_assoc($users_result)): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td>
                    <?php echo $user['is_admin'] ? 'კი' : 'არა'; ?>
                </td>
                <td>
                    <form method="POST" action="" class="admin-actions" onsubmit="return confirmDelete()">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <?php if ($user['is_admin'] == 0): ?>
                            <button type="submit" name="make_admin" class="make-admin-btn">ადმინისტრატორი გახადეთ</button>
                        <?php else: ?>
                            <button type="submit" name="revoke_admin" class="revoke-admin-btn">ადმინისტრატორის სტატუსის ჩამორთმევა</button>
                        <?php endif; ?>
                        <button type="submit" name="delete_user" class="delete-btn">წაშლა</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<script>
    function confirmDelete() {
        return confirm("ნამდვილად გსურთ?");
    }
</script>
