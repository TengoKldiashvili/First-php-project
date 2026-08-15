<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: /First-php-project/login");
    exit();
}

$user_id = intval($_SESSION['user_id']);
$user_statement = mysqli_prepare($connect, "SELECT id, email, username, password FROM users WHERE id = ?");
mysqli_stmt_bind_param($user_statement, "i", $user_id);
mysqli_stmt_execute($user_statement);
$res = mysqli_stmt_get_result($user_statement);
$user = mysqli_fetch_assoc($res);

if (empty($user)) {
    session_unset();
    session_destroy();
    header("Location: /First-php-project/login");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['username']) && is_string($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) && is_string($_POST['email']) ? trim($_POST['email']) : '';
    $old_password = isset($_POST['old_password']) && is_string($_POST['old_password']) ? $_POST['old_password'] : '';
    $new_password = isset($_POST['new_password']) && is_string($_POST['new_password']) ? $_POST['new_password'] : '';
    $password_confirm = isset($_POST['password_confirm']) && is_string($_POST['password_confirm']) ? $_POST['password_confirm'] : '';
    $change_password = false;

    if (!isset($_POST['csrf_token']) || !is_string($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error_message = "არასწორი მოთხოვნა.";
    } elseif (empty($username)) {
        $error_message = "სახელი სავალდებულოა.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "შეიყვანეთ სწორი ელფოსტა.";
    } elseif (!empty($old_password) || !empty($new_password) || !empty($password_confirm)) {
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
            $change_password = true;
        }
    }

    if (!isset($error_message)) {
        if ($change_password) {
            $update_user = mysqli_prepare($connect, "UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
            mysqli_stmt_bind_param($update_user, "sssi", $username, $email, $hashed_password, $user_id);
        } else {
            $update_user = mysqli_prepare($connect, "UPDATE users SET username = ?, email = ? WHERE id = ?");
            mysqli_stmt_bind_param($update_user, "ssi", $username, $email, $user_id);
        }

        if (mysqli_stmt_execute($update_user)) {
            $_SESSION['username'] = $username;
            $user['username'] = $username;
            $user['email'] = $email;
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
                <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION['csrf_token'])?>">
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
