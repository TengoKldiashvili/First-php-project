<?php
$query = "SELECT * FROM messages ORDER BY sent_at DESC";
$res = mysqli_query($connect, $query);

$message_count = mysqli_num_rows($res);

if ($message_count > 0) {
    $message = mysqli_fetch_all($res, MYSQLI_ASSOC);
    echo "<p>სულ შეტყობინებები: {$message_count}</p>"; 
}
foreach ($message as $messages) {
?>
<div class="message">
    <p>ავტორი - <?=$messages['sender_name']?></p>
    <p>ფოსტა - <?=$messages['sender_email']?></p>
    <p>შეტყობინება - <?=$messages['message_text']?></p>
    <p>გამოგზავნის დრო - <?=$messages['sent_at']?></p>
</div>
<?php } ?>
