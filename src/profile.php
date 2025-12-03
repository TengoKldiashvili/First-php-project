<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ?login");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id='$user_id'";
$res = mysqli_query($connect, $sql);
$user = mysqli_fetch_assoc($res);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $update_query = "UPDATE users SET username='$username', email='$email'";
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update_query .= ", password='$hashed_password'";
    }
    $update_query .= " WHERE id='$user_id'";

    if (mysqli_query($connect, $update_query)) {
        $_SESSION['username'] = $username;
        $success_message = "Your profile has been updated successfully!";
    } else {
        $error_message = "Error updating profile. Please try again.";
    }
}
?>
<div class="custom-profile-container">
        <div class="custom-profile-form">
            <h2>პროფილის რედაქტირება</h2>
            <?php if (isset($success_message)): ?>
                <p class="custom-success-message"><?= $success_message ?></p>
            <?php elseif (isset($error_message)): ?>
                <p class="custom-error-message"><?= $error_message ?></p>
            <?php endif; ?>
            <form method="POST">
                <div class="custom-form-group">
                    <label for="username">სახელი</label>
                    <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                </div>
                <div class="custom-form-group">
                    <label for="email">ფოსტა</label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                <div class="custom-form-group">
                    <label for="password">ახალი პაროლი (არასავალდებულო)</label>
                    <input type="password" name="password" id="password" placeholder="შეიყვანეთ ახალი პაროლი">
                </div>
                <button type="submit" class="custom-button">განაახლე პროფილი</button>
            </form>
        </div>
    </div>