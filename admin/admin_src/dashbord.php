<?php
include "../db/connect.php";

$post_stats_query = $connect->query("SELECT navs.name AS category_name, COUNT(posts.id) AS post_count FROM navs LEFT JOIN posts ON posts.navs_id = navs.id GROUP BY navs.id");

$post_stats = $post_stats_query->fetch_all(MYSQLI_ASSOC);

$categories = [];
$post_counts = [];
foreach ($post_stats as $stat) {
    $categories[] = $stat['category_name'];
    $post_counts[] = $stat['post_count'];
}

?>
<div class="dashboard">
    <div>
        <h2>სტატისტიკა</h2>
        <canvas id="postsChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const categories = <?= json_encode($categories); ?>;
    const postCounts = <?= json_encode($post_counts); ?>;

    console.log('Categories:', categories);
    console.log('Post Counts:', postCounts);

    const ctx = document.getElementById('postsChart').getContext('2d');
    const postsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: categories,
            datasets: [{
                label: 'პოსტების რაოდენობა',
                data: postCounts,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
</script>
