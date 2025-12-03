<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $res = mysqli_query($connect, $sql);

    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $hashed_password = $row['password'];

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['is_admin'] = $row['is_admin']; 

            header("Location: /");
        } else {
            echo "<p class='custom-error-message'>არასწორი პაროლი!</p>";
        }
    } else {
        echo "<p class='custom-error-message'>მომხმარებელი არ მოიძებნა!</p>";
    }
}
?>
    <div class="custom-login-container">
        <div class="custom-login-form">
            <h2>შესვლა</h2>
            <form method="POST">
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
                <p>არ გაქვთ ანგარიში? <a href="?register">რეგისტრაცია</a></p>
            </div>
        </div>
    </div>
