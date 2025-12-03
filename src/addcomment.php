<form action="?post=<?= $postid ?>&comment-uploaded" method="post">
    <div class="comment-section">
        <?php if (!isset($_SESSION['user_id'])): ?>
            <h3>დაამატე კომენტარი</h3>
           <textarea name="text" placeholder="შეიყვანეთ თქვენი კომენტარი..." required></textarea>
            <input name="name" type="text" placeholder="თქვენი სახელი" required>
            <input name="email" type="email" placeholder="თქვენი ელ-ფოსტა" required>
        <?php else: ?>
            <h3>დაამატე კომენტარი<span style="font-size:larger; color:black;"><?= htmlspecialchars($_SESSION['username']) ?></span></h3>
            <input name="name" type="hidden" value="<?= htmlspecialchars($_SESSION['username']) ?>">
            <input name="email" type="hidden" value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>">
            <textarea name="text" placeholder="შეიყვანეთ თქვენი კომენტარი..." required></textarea>
        <?php endif; ?>
        <button name="upload_comment">კომენტარის გაგზავნა</button>
    </div>
</form>

