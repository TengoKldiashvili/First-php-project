<?php
include "../db/connect.php";
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: /"); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="adminstyle.css">
</head>
<body>
    <div class="navbar">
        <button class="toggle-sidebar">დამალვა/მაჩვენებელი</button>

        <div>
            <a href="/">გასვლა</a>
        </div>
    </div>
    <div class="sidebar">
        <ul>
            <li><a href="/admin">მთავარი</a></li>
            <li><a href="?users">მომხმარებლები</a></li>
            <li><a href="?messages">შეტყობინებები</a></li>
            <li><a href="?comments">კომენტარები</a></li>
            <li><a href="?post_before">პოსტები</a></li>
            <li><a href="?navs">ნავიგაციები</a></li>
            <li><a href="?reklama">რეკლამა</a></li>
        </ul>
    </div>
    <div class="content">
    <?php
    if(isset($_GET['post_before'])){
        include "admin_src/post_before.php";
    }elseif(isset($_GET['reklama'])){
        include "admin_src/reklama.php";
    }elseif(isset($_GET['messages'])){
        include "admin_src/messages.php";
    }
    elseif(isset($_GET['post_details'])){
        include "admin_src/post_after.php";
    }elseif(isset($_GET['maintheme'])){
        include "admin_src/maintheme.php";
    }elseif(isset($_GET['users'])){
        include "admin_src/users.php";
    }elseif(isset($_GET['comments'])){
            include "admin_src/comments.php";
    }elseif(isset($_GET['post_edit'])){
        include "admin_src/post_edit.php";
    }elseif(isset($_GET['navs'])){
        include "admin_src/navs.php";
    }  else{
        include "admin_src/dashbord.php";
    }
    ?>
    </div>
    <script>
    // toggle-sidebar ღილაკზე event listener
    document.querySelector('.toggle-sidebar').addEventListener('click', function(event) {
        const sidebar = document.querySelector('.sidebar');
        sidebar.style.display = (sidebar.style.display === 'none' || sidebar.style.display === '') ? 'block' : 'none';
        // event.stopPropagation() აიძულებს, რომ მხოლოდ toggle-sidebar ელემენტზე დაკლიკვა მართავდეს
        event.stopPropagation();
    });

    // გვერდზე ყველა სხვა ელემენტზე დაკლიკვა
    document.addEventListener('click', function(event) {
        const sidebar = document.querySelector('.sidebar');
        const toggleButton = document.querySelector('.toggle-sidebar');

        // თუ .sidebar ან .toggle-sidebar არ იყო დაკლიკული, დამალეთ sidebar
        if (!sidebar.contains(event.target) && event.target !== toggleButton) {
            sidebar.style.display = 'none';
        }
    });
</script>

</body>
</html>
