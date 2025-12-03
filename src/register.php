<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if ($password !== $password_confirm) {
        echo "<p class='error-message'>Passwords do not match!</p>";
        exit();
    }
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $sql = "SELECT * FROM users WHERE email='$email' OR username='$username'";
    $res = mysqli_query($connect, $sql);

    if (mysqli_num_rows($res) > 0) {
        echo "<p class='error-message'>ესეთი მომხმარებელი არსებობს!</p>";
    } else {
        $sql = "INSERT INTO users (email, username, password) VALUES ('$email', '$username', '$hashed_password')";
        
        if (mysqli_query($connect, $sql)) {
            echo "<p class='success-message'>რეგისტრაცია წარმატებით დასრულდა!</p>";
            header("Location: ?login"); 
        } else {
            header("Location: ?login"); 
            echo "<p class='error-message'>შეცდომა: " . mysqli_error($connect) . "</p>";
        }
    }
}
?>
    <div class="custom-register-container">
        <div class="custom-register-form">
            <h2>ანგარიშის შექმნა</h2>
            <form method="POST">
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
                <p>გაქვს ანგარიში? <a href="?login">შესვლა</a></p>
            </div>
        </div>
    </div>

