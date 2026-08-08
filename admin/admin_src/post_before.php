<?php
include "../db/connect.php";

$import_url = '';
$import_name = '';
$import_description = '';
$import_event_date = '';
$import_location = '';
$import_organizer = '';
$import_image = '';
$import_registration_url = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['import_event'])) {
    $import_url = trim($_POST['import_url']);
    $import_scheme = parse_url($import_url, PHP_URL_SCHEME);
    $import_host = parse_url($import_url, PHP_URL_HOST);
    $blocked_url = false;

    if (!filter_var($import_url, FILTER_VALIDATE_URL) || !in_array($import_scheme, ['http', 'https']) || empty($import_host)) {
        $import_error = "შეიყვანეთ სწორი http:// ან https:// ბმული.";
    } else {
        $import_host = strtolower(trim($import_host, '[]'));

        if ($import_host == 'localhost' || substr($import_host, -6) == '.local' || substr($import_host, -9) == '.internal' || substr($import_host, -10) == '.localhost' || strpos($import_host, '.') === false) {
            $blocked_url = true;
        }

        if (filter_var($import_host, FILTER_VALIDATE_IP)) {
            if (!filter_var($import_host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                $blocked_url = true;
            }
        } else {
            $host_ips = gethostbynamel($import_host);
            if ($host_ips) {
                foreach ($host_ips as $host_ip) {
                    if (!filter_var($host_ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                        $blocked_url = true;
                    }
                }
            }
        }

        if ($blocked_url) {
            $import_error = "ლოკალური ან შიდა მისამართის გამოყენება არ შეიძლება.";
        } else {
            $import_context = stream_context_create([
                'http' => [
                    'timeout' => 10,
                    'user_agent' => 'TW Tech World Event Import',
                    'follow_location' => 0,
                    'ignore_errors' => true
                ]
            ]);

            $page_content = @file_get_contents($import_url, false, $import_context);

            if ($page_content === false) {
                $import_error = "ბმულიდან ინფორმაციის წამოღება ვერ მოხერხდა.";
            } else {
                preg_match_all('/<script[^>]*type\s*=\s*["\']application\/ld\+json["\'][^>]*>(.*?)<\/script>/is', $page_content, $json_scripts);
                $event_data = null;

                foreach ($json_scripts[1] as $json_script) {
                    $json_data = json_decode(trim($json_script), true);

                    if (!is_array($json_data)) {
                        continue;
                    }

                    if (isset($json_data['@graph']) && is_array($json_data['@graph'])) {
                        $json_items = $json_data['@graph'];
                    } elseif (isset($json_data[0])) {
                        $json_items = $json_data;
                    } else {
                        $json_items = [$json_data];
                    }

                    foreach ($json_items as $json_item) {
                        if (!is_array($json_item) || !isset($json_item['@type'])) {
                            continue;
                        }

                        $event_types = is_array($json_item['@type']) ? $json_item['@type'] : [$json_item['@type']];

                        if (in_array('Event', $event_types) || in_array('https://schema.org/Event', $event_types) || in_array('http://schema.org/Event', $event_types)) {
                            $event_data = $json_item;
                            break 2;
                        }
                    }
                }

                if (empty($event_data)) {
                    $import_error = "ღონისძიების სტრუქტურირებული მონაცემები ვერ მოიძებნა.";
                } else {
                    if (isset($event_data['name']) && is_string($event_data['name'])) {
                        $import_name = $event_data['name'];
                    }

                    if (isset($event_data['description']) && is_string($event_data['description'])) {
                        $import_description = strip_tags($event_data['description']);
                    }

                    if (isset($event_data['startDate']) && is_string($event_data['startDate'])) {
                        if (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}/', $event_data['startDate'], $event_date_match)) {
                            $import_event_date = $event_date_match[0];
                        } elseif (preg_match('/^\d{4}-\d{2}-\d{2}/', $event_data['startDate'], $event_date_match)) {
                            $import_event_date = $event_date_match[0] . 'T00:00';
                        }
                    }

                    if (isset($event_data['location'])) {
                        if (is_string($event_data['location'])) {
                            $import_location = $event_data['location'];
                        } elseif (is_array($event_data['location'])) {
                            $location_parts = [];

                            if (!empty($event_data['location']['name'])) {
                                $location_parts[] = $event_data['location']['name'];
                            }

                            if (isset($event_data['location']['address'])) {
                                if (is_string($event_data['location']['address'])) {
                                    $location_parts[] = $event_data['location']['address'];
                                } elseif (is_array($event_data['location']['address'])) {
                                    foreach (['streetAddress', 'addressLocality', 'addressRegion', 'postalCode', 'addressCountry'] as $address_part) {
                                        if (!empty($event_data['location']['address'][$address_part]) && is_string($event_data['location']['address'][$address_part])) {
                                            $location_parts[] = $event_data['location']['address'][$address_part];
                                        }
                                    }
                                }
                            }

                            $import_location = implode(', ', array_unique($location_parts));
                        }
                    }

                    if (isset($event_data['organizer'])) {
                        if (is_string($event_data['organizer'])) {
                            $import_organizer = $event_data['organizer'];
                        } elseif (is_array($event_data['organizer']) && !empty($event_data['organizer']['name'])) {
                            $import_organizer = $event_data['organizer']['name'];
                        } elseif (is_array($event_data['organizer']) && !empty($event_data['organizer'][0]['name'])) {
                            $import_organizer = $event_data['organizer'][0]['name'];
                        }
                    }

                    if (isset($event_data['image'])) {
                        if (is_string($event_data['image'])) {
                            $import_image = $event_data['image'];
                        } elseif (is_array($event_data['image']) && !empty($event_data['image']['url'])) {
                            $import_image = $event_data['image']['url'];
                        } elseif (is_array($event_data['image']) && !empty($event_data['image'][0])) {
                            $import_image = is_array($event_data['image'][0]) ? ($event_data['image'][0]['url'] ?? '') : $event_data['image'][0];
                        }
                    }

                    if (isset($event_data['offers'])) {
                        if (is_array($event_data['offers']) && !empty($event_data['offers']['url'])) {
                            $import_registration_url = $event_data['offers']['url'];
                        } elseif (is_array($event_data['offers']) && !empty($event_data['offers'][0]['url'])) {
                            $import_registration_url = $event_data['offers'][0]['url'];
                        }
                    }

                    if (empty($import_registration_url) && !empty($event_data['url']) && is_string($event_data['url'])) {
                        $import_registration_url = $event_data['url'];
                    }

                    $import_success = "ინფორმაცია ფორმაში ჩაიწერა. გადაამოწმეთ და დააჭირეთ დამატებას.";
                }
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['post_name'], $_POST['post_description'], $_POST['post_image'], $_POST['post_category'])) {
    $post_name = $_POST['post_name'];
    $post_description = $_POST['post_description'];
    $post_small_description = $_POST['post_small_description'] ?? '';
    $post_image = $_POST['post_image'];
    $post_category = $_POST['post_category'];
    $post_organizer = $_POST['post_organizer'] ?? '';
    $post_location = $_POST['post_location'] ?? '';
    $post_event_date = !empty($_POST['post_event_date']) ? "'" . $_POST['post_event_date'] . "'" : "NULL";
    $post_registration_deadline = !empty($_POST['post_registration_deadline']) ? "'" . $_POST['post_registration_deadline'] . "'" : "NULL";
    $post_registration_url = $_POST['post_registration_url'] ?? '';

    $insert_new_post = $connect->query("
        INSERT INTO posts (name, navs_id, imgs, description, small_description, count, organizer, location, event_date, registration_deadline, registration_url)
        VALUES ('$post_name', '$post_category', '$post_image', '$post_description', '$post_small_description', 0, '$post_organizer', '$post_location', $post_event_date, $post_registration_deadline, '$post_registration_url')
    ");

    if ($insert_new_post) {
        $post_added = true;
    }
}

$posts_admin = mysqli_fetch_all($connect->query("SELECT posts.name, posts.id, posts.imgs, posts.event_date, posts.location, navs.name AS category_name FROM posts LEFT JOIN navs ON posts.navs_id = navs.id ORDER BY posts.event_date IS NULL, posts.event_date ASC"), MYSQLI_ASSOC);
$categories = mysqli_fetch_all($connect->query("SELECT id, name FROM navs ORDER BY id"), MYSQLI_ASSOC);
?>

<div class="admin-page-heading">
    <div>
        <p>კონტენტის მართვა</p>
        <h1>ღონისძიებები</h1>
    </div>
    <button id="add-post-btn" class="primary-admin-button" type="button">ღონისძიების დამატება</button>
</div>

<?php if (isset($post_added)): ?>
    <div class="admin-success-message">ღონისძიება წარმატებით დაემატა.</div>
<?php endif; ?>

<div id="add-post-modal" class="modal">
    <div class="modal-content">
        <div class="modal-heading">
            <div>
                <p>ახალი ჩანაწერი</p>
                <h2>ღონისძიების დამატება</h2>
            </div>
            <button class="close" type="button" aria-label="დახურვა">&times;</button>
        </div>

        <h3>ღონისძიების ბმულიდან წამოღება</h3>
        <form action="?post_before" method="POST" class="admin-form">
            <div class="form-field">
                <label for="import_url">ღონისძიების გვერდის ბმული</label>
                <input type="url" id="import_url" name="import_url" value="<?=htmlspecialchars($import_url)?>" placeholder="https://example.com/event" required>
            </div>
            <button type="submit" name="import_event" class="form-submit-btn">ინფორმაციის წამოღება</button>
        </form>

        <?php if (isset($import_error)): ?>
            <div class="admin-error-message"><?=$import_error?></div>
        <?php elseif (isset($import_success)): ?>
            <div class="admin-success-message"><?=$import_success?></div>
        <?php endif; ?>

        <br>
        <hr>
        <br>

        <form action="?post_before" method="POST" class="admin-form">
            <div class="form-grid">
                <div class="form-field form-field-wide">
                    <label for="post_name">ღონისძიების სახელი</label>
                    <input type="text" id="post_name" name="post_name" value="<?=htmlspecialchars($import_name)?>" required>
                </div>

                <div class="form-field">
                    <label for="post_category">კატეგორია</label>
                    <select id="post_category" name="post_category" required>
                        <?php foreach ($categories as $category) { ?>
                            <option value="<?=$category['id']?>"><?=$category['name']?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-field">
                    <label for="post_event_date">ღონისძიების თარიღი</label>
                    <input type="datetime-local" id="post_event_date" name="post_event_date" value="<?=htmlspecialchars($import_event_date)?>" required>
                </div>

                <div class="form-field">
                    <label for="post_location">ადგილმდებარეობა</label>
                    <input type="text" id="post_location" name="post_location" value="<?=htmlspecialchars($import_location)?>" required>
                </div>

                <div class="form-field">
                    <label for="post_organizer">ორგანიზატორი</label>
                    <input type="text" id="post_organizer" name="post_organizer" value="<?=htmlspecialchars($import_organizer)?>">
                </div>

                <div class="form-field">
                    <label for="post_registration_deadline">რეგისტრაციის ბოლო ვადა</label>
                    <input type="datetime-local" id="post_registration_deadline" name="post_registration_deadline">
                </div>

                <div class="form-field">
                    <label for="post_registration_url">რეგისტრაციის ბმული</label>
                    <input type="url" id="post_registration_url" name="post_registration_url" value="<?=htmlspecialchars($import_registration_url)?>" placeholder="https://example.com/register">
                </div>

                <div class="form-field form-field-wide">
                    <label for="post_image">სურათის ბმული</label>
                    <input type="url" id="post_image" name="post_image" value="<?=htmlspecialchars($import_image)?>" required placeholder="https://example.com/image.jpg">
                </div>

                <div class="form-field form-field-wide">
                    <label for="post_small_description">მოკლე აღწერა</label>
                    <textarea id="post_small_description" name="post_small_description" rows="3"></textarea>
                </div>

                <div class="form-field form-field-wide">
                    <label for="post_description">სრული აღწერა</label>
                    <textarea id="post_description" name="post_description" rows="7" required><?=htmlspecialchars($import_description)?></textarea>
                </div>
            </div>

            <button type="submit" class="form-submit-btn">დამატება</button>
        </form>
    </div>
</div>

<?php if (empty($posts_admin)): ?>
    <div class="admin-empty-state">ღონისძიებები ჯერ არ არის დამატებული.</div>
<?php else: ?>
    <div class="admin-events-grid">
        <?php foreach($posts_admin as $postdetails): ?>
            <article class="admin-event-card">
                <img src="<?=$postdetails['imgs']?>" alt="">
                <div>
                    <span><?=$postdetails['category_name']?></span>
                    <h2><?=$postdetails['name']?></h2>
                    <?php if (!empty($postdetails['event_date'])): ?><p><?=date('d.m.Y · H:i', strtotime($postdetails['event_date']))?></p><?php endif; ?>
                    <?php if (!empty($postdetails['location'])): ?><p><?=$postdetails['location']?></p><?php endif; ?>
                    <a href="?post_details=<?=$postdetails['id']?>" class="text-button">ღონისძიების ნახვა →</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<script>
    var modal = document.getElementById("add-post-modal");
    var btn = document.getElementById("add-post-btn");
    var closeButton = document.getElementsByClassName("close")[0];

    <?php if (isset($_POST['import_event'])) { ?>
        modal.style.display = "flex";
    <?php } ?>

    btn.onclick = function() {
        modal.style.display = "flex";
    }

    closeButton.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
