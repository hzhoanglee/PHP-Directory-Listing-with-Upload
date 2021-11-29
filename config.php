<?php
//File excluding
$cfg_exclude = ["index.php", "assets"];
//PHP number of file display on one page
$cfg_page = 20; //0 for no pagination
// define absolute folder path
$dest_folder = 'upload/';


//***Functions
//Getsize
function getsize($bytes)
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $bytes = max($bytes, 0);
    $unit = floor(($bytes ? log($bytes) : 0) / log(1024));
    $unit = min($unit, count($units) - 1);
    $bytes /= (1 << (10 * $unit));
    return round($bytes, 2) . " " . $units[$unit];
}

//Rebuild URI
function build_page($new_value){
    $query = $_GET;
    $query['page'] = $new_value;
    return http_build_query($query);
}

