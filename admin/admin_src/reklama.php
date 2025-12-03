<?php
$ads_comment = mysqli_fetch_all($connect->query("SELECT id, imgs, link FROM reklama ORDER BY created_at DESC LIMIT 2"));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['ad1-img'], $_POST['ad1-link'])) {
        $ad1_id = 1; 
        $ad1_img = $_POST['ad1-img'];
        $ad1_link = $_POST['ad1-link'];

        $update_ad1 = $connect->query("UPDATE reklama SET imgs = '$ad1_img', link = '$ad1_link' WHERE id = $ad1_id");

        if ($update_ad1) {
            header("Location: /");
            exit();
        }
    }

    if (isset($_POST['ad2-img'], $_POST['ad2-link'])) {
        $ad2_id = 2;
        $ad2_img = $_POST['ad2-img'];
        $ad2_link = $_POST['ad2-link'];

        $update_ad2 = $connect->query("UPDATE reklama SET imgs = '$ad2_img', link = '$ad2_link' WHERE id = $ad2_id");

        if ($update_ad2) {
            header("Location: /");
            exit();
        }
    }
}
?>

<div class="reklama-form-container">
    <h2>რეკლამების მართვა</h2>
    
    <form action="" method="post">
        <div class="reklama">
            <p class="reklama-title"><b>რეკლამა "ახალი კომენტარების" ქვემოთ</b></p>
            
            <div class="reklama-item">
                <label for="ad1-img">სურათის ლინკი:</label>
                <input type="text" id="ad1-img" name="ad1-img" value="<?=$ads_comment[0][1]?>" class="reklama-input">
            </div>
            
            <div class="reklama-item">
                <label for="ad1-link">გადასასვლელი ლინკი:</label>
                <input type="text" id="ad1-link" name="ad1-link" value="<?=$ads_comment[0][2]?>" class="reklama-input">
            </div>

            <button type="submit" class="reklama-submit-button">განახლება</button>
        </div>
    </form>
    
    <hr>
    
    <form action="" method="post">
        <div class="reklama">
            <p class="reklama-title"><b>რეკლამა "ხშირად წაკითხულის" ქვემოთ</b></p>
            
            <div class="reklama-item">
                <label for="ad2-img">სურათის ლინკი:</label>
                <input type="text" id="ad2-img" name="ad2-img" value="<?=$ads_comment[1][1]?>" class="reklama-input">
            </div>
            
            <div class="reklama-item">
                <label for="ad2-link">გადასასვლელი ლინკი:</label>
                <input type="text" id="ad2-link" name="ad2-link" value="<?=$ads_comment[1][2]?>" class="reklama-input">
            </div>

            <button type="submit" class="reklama-submit-button">განახლება</button>
        </div>
    </form>
</div>
