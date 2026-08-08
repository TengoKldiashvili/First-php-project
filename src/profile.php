<?php
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
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $password_confirm = $_POST['password_confirm'];

    $update_query = "UPDATE users SET username='$username', email='$email'";

    if (!empty($old_password) || !empty($new_password) || !empty($password_confirm)) {
        if (empty($old_password) || empty($new_password) || empty($password_confirm)) {
            $error_message = "პაროლის შესაცვლელად შეავსეთ სამივე ველი!";
        } elseif (!password_verify($old_password, $user['password'])) {
            $error_message = "ძველი პაროლი არასწორია!";
        } elseif ($new_password !== $password_confirm) {
            $error_message = "ახალი პაროლები ერთმანეთს არ ემთხვევა!";
        } elseif (strlen($new_password) < 6) {
            $error_message = "ახალი პაროლი უნდა შეიცავდეს მინიმუმ 6 სიმბოლოს!";
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_query .= ", password='$hashed_password'";
        }
    }

    if (!isset($error_message)) {
        $update_query .= " WHERE id='$user_id'";

        if (mysqli_query($connect, $update_query)) {
            $_SESSION['username'] = $username;
            $success_message = "პროფილი წარმატებით განახლდა!";
        } else {
            $error_message = "პროფილის განახლებისას შეცდომა მოხდა.";
        }
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
                    <label for="old_password">ძველი პაროლი (სავალდებულოა პაროლის შეცვლისას)</label>
                    <input type="password" name="old_password" id="old_password" placeholder="შეიყვანეთ ძველი პაროლი">
                </div>
                <div class="custom-form-group">
                    <label for="new_password">ახალი პაროლი</label>
                    <input type="password" name="new_password" id="new_password" minlength="6" placeholder="შეიყვანეთ ახალი პაროლი">
                </div>
                <div class="custom-form-group">
                    <label for="password_confirm">გაიმეორეთ ახალი პაროლი</label>
                    <input type="password" name="password_confirm" id="password_confirm" minlength="6" placeholder="გაიმეორეთ ახალი პაროლი">
                </div>
                <button type="submit" class="custom-button">განაახლე პროფილი</button>
            </form>
        </div>
    </div>
