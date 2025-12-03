<?php
if(isset($_GET['post_edit'])){
    $idforedit = $_GET['post_edit'];
    $postedit = mysqli_fetch_all($connect->query("SELECT name, id, navs_id, imgs, small_description, description, small_description FROM posts WHERE id = '$idforedit'"), MYSQLI_ASSOC); 
    $categories = mysqli_fetch_all($connect->query("SELECT id, name FROM navs"), MYSQLI_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category = $_POST['category'];
    $name = $_POST['name'];
    $small_description = $_POST['small_description'];
    $description = $_POST['description'];
    $imgs = $_POST['imgs'];

    // post update
$updateResult = $connect->query("UPDATE posts SET name='$name', navs_id='$category', small_description='$small_description', description='$description', imgs='$imgs' WHERE id='$idforedit'");  
    header('Location: ?post_edit=' . $idforedit . '&updated=true');
    exit;

}

$updateSuccess = isset($_GET['updated']) && $_GET['updated'] == 'true';
?>

<form action="?post_edit=<?=$idforedit?>" method="POST" class="edit-post-form">
<?php if ($updateSuccess): ?>
    <div class="success-message">პოსტი განახლებულია!</div><br>
<?php endif; ?>
    <label for="name" class="form-label">სათაური:</label>
    <input type="text" id="name" name="name" value="<?=$postedit[0]['name']?>" class="form-input" required>
    
    <label for="category" class="form-label">კატეგორია:</label>
    <select id="category" name="category" class="form-input" required>
        <?php foreach($categories as $category): ?>
            <option value="<?=$category['id']?>" <?=$category['id'] == $postedit[0]['navs_id'] ? 'selected' : ''?>><?=$category['name']?></option>
        <?php endforeach; ?>
    </select>
    <label for="small_description" class="form-label">მოკლე აღწერა (არასავალდებულო):</label>
    <textarea id="small_description" name="small_description" class="form-textarea"><?=$postedit[0]['small_description']?></textarea>
    
    <label for="description" class="form-label">სრული აღწერა (სავალდებულო):</label>
    <textarea id="description" name="description" class="form-textarea" required><?=$postedit[0]['description']?></textarea>
    
    <label for="imgs" class="form-label">სურათის მისამართი:</label>
    <input type="url" id="imgs" name="imgs" class="form-input" value="<?=$postedit[0]['imgs']?>" placeholder="შეიყვანეთ სურათის მისამართი" required>
        
    <button type="submit" class="form-submit-btn" <?php echo $updateSuccess ? 'style="display:none;"' : ''; ?>>განაახლე პოსტი</button>
</form>
