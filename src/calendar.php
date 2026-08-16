<?php
$calendar_id = isset($_GET['calendar']) && is_scalar($_GET['calendar']) ? intval($_GET['calendar']) : 0;

$calendar_event = mysqli_fetch_assoc($connect->query("SELECT id, name, description, event_date, location, registration_url FROM posts WHERE id = $calendar_id AND is_approved = 1"));

if (empty($calendar_event) || empty($calendar_event['event_date'])) {
    ob_clean();
    header("Location: /tech-world/");
    exit();
}

$calendar_start = strtotime($calendar_event['event_date']);
$calendar_end = $calendar_start + 3600;

$calendar_name = str_replace(["\\", "\r\n", "\r", "\n", ",", ";"], ["\\\\", "\\n", "\\n", "\\n", "\\,", "\\;"], $calendar_event['name']);
$calendar_description = str_replace(["\\", "\r\n", "\r", "\n", ",", ";"], ["\\\\", "\\n", "\\n", "\\n", "\\,", "\\;"], $calendar_event['description']);
$calendar_location = str_replace(["\\", "\r\n", "\r", "\n", ",", ";"], ["\\\\", "\\n", "\\n", "\\n", "\\,", "\\;"], $calendar_event['location']);
$calendar_url = str_replace(["\\", "\r\n", "\r", "\n", ",", ";"], ["\\\\", "\\n", "\\n", "\\n", "\\,", "\\;"], $calendar_event['registration_url']);

$calendar_lines = [
    'BEGIN:VCALENDAR',
    'VERSION:2.0',
    'PRODID:-//TW Tech World//EN',
    'BEGIN:VEVENT',
    'UID:event-' . $calendar_event['id'] . '@techworld',
    'DTSTAMP:' . gmdate('Ymd\\THis\\Z'),
    'DTSTART:' . date('Ymd\\THis', $calendar_start),
    'DTEND:' . date('Ymd\\THis', $calendar_end),
    'SUMMARY:' . $calendar_name,
    'DESCRIPTION:' . $calendar_description,
    'LOCATION:' . $calendar_location
];

if (!empty($calendar_url)) {
    $calendar_lines[] = 'URL:' . $calendar_url;
}

$calendar_lines[] = 'END:VEVENT';
$calendar_lines[] = 'END:VCALENDAR';

$calendar_content = implode("\r\n", $calendar_lines) . "\r\n";
$calendar_filename = 'tw-event-' . $calendar_event['id'] . '.ics';

ob_clean();
header('Content-Type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $calendar_filename . '"');

echo $calendar_content;
exit();
?>
