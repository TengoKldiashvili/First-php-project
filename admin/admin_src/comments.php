<?php
$download_comment = "SELECT id, name, comment, created_at, post_id FROM comments ORDER BY created_at DESC";
$comments = mysqli_fetch_all($connect->query($download_comment));

if (isset($_POST['delete_comment'])) {
    $comment_id = $_POST['comment_id'];

    $delete_comment = mysqli_query($connect, "DELETE FROM comments WHERE id = $comment_id");

    if ($delete_comment) {
        header("Location: ?comments");
        exit();
    }
}
for ($c = 0; $c < count($comments); $c++) { 
    $comment_id = $comments[$c][0]; 
    $post_id = $comments[$c][4]; 

    $get_post_title = "SELECT name FROM posts WHERE id = $post_id";
    $post_result = mysqli_query($connect, $get_post_title);
    $post = mysqli_fetch_assoc($post_result);
?>
    <div class="comment">
        <p><strong><?php echo htmlspecialchars($comments[$c][1]); ?></strong> <em><?php echo $comments[$c][3]; ?></em></p>
        <p><?php echo nl2br(htmlspecialchars($comments[$c][2])); ?></p>
        <p><strong>პოსტი:</strong> <a class="post_comment_details" href="?post_details=<?php echo $post_id; ?>"><?php echo htmlspecialchars($post['name']); ?></a></p>

        <form method="POST" action="?comments">
            <input type="hidden" name="comment_id" value="<?php echo $comment_id; ?>">
            <button type="submit" name="delete_comment" class="delete-button">წაშლა</button>
        </form>
    </div>
<?php } ?>
