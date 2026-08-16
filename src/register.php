<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = isset($_POST['email']) && is_string($_POST['email']) ? trim($_POST['email']) : '';
    $username = isset($_POST['username']) && is_string($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) && is_string($_POST['password']) ? $_POST['password'] : '';
    $password_confirm = isset($_POST['password_confirm']) && is_string($_POST['password_confirm']) ? $_POST['password_confirm'] : '';

    if (!isset($_POST['csrf_token']) || !is_string($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $register_error = "არასწორი მოთხოვნა.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $register_error = "შეიყვანეთ სწორი ელფოსტა.";
    } elseif (empty($username)) {
        $register_error = "სახელი სავალდებულოა.";
    } elseif (strlen($password) < 6) {
        $register_error = "პაროლი უნდა შეიცავდეს მინიმუმ 6 სიმბოლოს.";
    } elseif ($password !== $password_confirm) {
        $register_error = "პაროლები ერთმანეთს არ ემთხვევა!";
    } else {
        $check_user = mysqli_prepare($connect, "SELECT id FROM users WHERE email = ? OR username = ?");
        mysqli_stmt_bind_param($check_user, "ss", $email, $username);
        mysqli_stmt_execute($check_user);
        $res = mysqli_stmt_get_result($check_user);

        if (mysqli_num_rows($res) > 0) {
            $register_error = "ასეთი მომხმარებელი უკვე არსებობს!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_user = mysqli_prepare($connect, "INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($insert_user, "sss", $email, $username, $hashed_password);

            if (mysqli_stmt_execute($insert_user)) {
                header("Location: /tech-world/login");
                exit();
            } else {
                $register_error = "რეგისტრაცია ვერ მოხერხდა.";
            }
        }
    }
}
?>
    <div class="custom-register-container">
        <div class="custom-register-form">
            <h2>ანგარიშის შექმნა</h2>
            <?php if (isset($register_error)): ?>
                <p class="error-message"><?=htmlspecialchars($register_error)?></p>
            <?php endif; ?>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION['csrf_token'])?>">
                <div class="custom-form-group">
                    <label for="email">ფოსტა</label>
                    <input type="email" name="email" id="email" placeholder="შეიყვანეთ ფოსტა" required>
                </div>
                <div class="custom-form-group">
                    <label for="username">სახელი</label>
                    <input type="text" name="username" id="username" placeholder="სახელი" required>
                </div>
                <div class="custom-form-group">
                    <label for="password">პაროლი</label>
                    <input type="password" name="password" id="password" placeholder="შეიყვანეთ პაროლი" required>
                </div>
                <div class="custom-form-group">
                    <label for="password_confirm">გაიმეორე პაროლი</label>
                    <input type="password" name="password_confirm" id="password_confirm" placeholder="გაიმეორე პაროლი" required>
                </div>
                <button type="submit" class="custom-button">რეგისტრაცია</button>
            </form>
            <div class="custom-login-link">
                <p>გაქვს ანგარიში? <a href="/tech-world/login">შესვლა</a></p>
            </div>
        </div>
    </div>
