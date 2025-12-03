<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sender_name = $_POST['name'];
    $sender_email = $_POST['email'];
    $message_text = $_POST['message'];

    $sql = "INSERT INTO messages (sender_name, sender_email, message_text) VALUES ('$sender_name', '$sender_email', '$message_text')";
    
    if (mysqli_query($connect, $sql)) {
        header("Location: /");
        exit();
    }
}
?>  
<br>
<br>
<form method="POST" class="contact-form" id="contactForm">
    <h2>შეტყობინების გაგზავნა</h2>
    
    <div class="form-group">
        <label for="name">სახელი:</label>
        <input type="text" name="name" id="name" placeholder="შეიყვანეთ სახელი" required>
    </div>
    
    <div class="form-group">
        <label for="email">ფოსტა:</label>
        <input type="email" name="email" id="email" placeholder="შეიყვანეთ ფოსტა" required>
    </div>
    
    <div class="form-group">
        <label for="message">შეტყობინება:</label>
        <textarea name="message" id="message" placeholder="შეიყვანეთ შეტყობინება" required></textarea>
    </div>
    
    <button type="submit" class="submit-button" id="submitBtn">გაგზავნა</button>
</form>