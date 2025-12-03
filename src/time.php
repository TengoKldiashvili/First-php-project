<?php 
date_default_timezone_set("Asia/Tbilisi");

$months = [
    "January" => "იანვარი",
    "February" => "თებერვალი",
    "March" => "მარტი",
    "April" => "აპრილი",
    "May" => "მაისი",
    "June" => "ივნისი",
    "July" => "ივლისი",
    "August" => "აგვისტო",
    "September" => "სექტემბერი",
    "October" => "ოქტომბერი",
    "November" => "ნოემბერი",
    "December" => "დეკემბერი"
];

$days = [
    "Monday" => "ორშაბათი",
    "Tuesday" => "სამშაბათი",
    "Wednesday" => "ოთხშაბათი",
    "Thursday" => "ხუთშაბათი",
    "Friday" => "პარასკევი",
    "Saturday" => "შაბათი",
    "Sunday" => "კვირა"
];

$date = date("d F Y, l");
foreach ($months as $en => $ka) {
    $date = str_replace($en, $ka, $date);
}

foreach ($days as $en => $ka) {
    $date = str_replace($en, $ka, $date);
}

