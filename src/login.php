<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = isset($_POST['email']) && is_string($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) && is_string($_POST['password']) ? $_POST['password'] : '';

    if (!isset($_POST['csrf_token']) || !is_string($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $login_error = "არასწორი მოთხოვნა.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($password)) {
        $login_error = "შეიყვანეთ სწორი ფოსტა და პაროლი.";
    } else {
        $login_statement = mysqli_prepare($connect, "SELECT id, username, password, is_admin FROM users WHERE email = ?");
        mysqli_stmt_bind_param($login_statement, "s", $email);
        mysqli_stmt_execute($login_statement);
        $res = mysqli_stmt_get_result($login_statement);

        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);

            if (password_verify($password, $row['password'])) {
                session_regenerate_id(true);
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['is_admin'] = $row['is_admin'];

                header("Location: /First-php-project/");
                exit();
            } else {
                $login_error = "ფოსტა ან პაროლი არასწორია!";
            }
        } else {
            $login_error = "ფოსტა ან პაროლი არასწორია!";
        }
    }
}
?>
    <div class="custom-login-container">
        <div class="custom-login-form">
            <h2>შესვლა</h2>
            <?php if (isset($login_error)): ?>
                <p class="custom-error-message"><?=htmlspecialchars($login_error)?></p>
            <?php endif; ?>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION['csrf_token'])?>">
                <div class="custom-form-group">
                    <label for="email">ფოსტა</label>
                    <input type="email" name="email" id="email" placeholder="შეიყვანეთ ფოსტა" required>
                </div>
                <div class="custom-form-group">
                    <label for="password">პაროლი</label>
                    <input type="password" name="password" id="password" placeholder="შეიყვანეთ პაროლი" required>
                </div>
                <button type="submit" class="custom-button">შესვლა</button>
            </form>
            <div class="custom-register-link">
                <p>არ გაქვთ ანგარიში? <a href="/First-php-project/register">რეგისტრაცია</a></p>
            </div>
        </div>
    </div>
