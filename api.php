<pre>
<?php
//VERSION: 0.1_beta
$time_start = microtime(true);
if (isset($_GET["dir"])){
    $dir = $_GET["dir"];
} else {
    $dir = ".";
}
$files = array_diff(scandir($dir), array('.', '..'));
$i = 0;

function display_size($bytes)
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $bytes = max($bytes, 0);
    $unit = floor(($bytes ? log($bytes) : 0) / log(1024));
    $unit = min($unit, count($units) - 1);
    $bytes /= (1 << (10 * $unit));
    return round($bytes, 2) . " " . $units[$unit];
}

foreach ($files as $file) {
    $file = $dir . "/" . $file;
    //print_r(pathinfo($file));
    if (is_dir($file)) {
        $output[$i]["type"] = "Folder";
        $output[$i]["name"] = pathinfo($file)["basename"];
    } else {
        $output[$i]["type"] = "File";
        $output[$i]["name"] = pathinfo($file)["basename"];
        $output[$i]["extension"] = pathinfo($file)["extension"];
        $output[$i]["size"] = display_size(filesize($file));
    }
    $output[$i]["last_modify"] = date("Y-m-d H:i:s", filemtime($file));
    $output[$i]["last_open"] = date("Y-m-d H:i:s", fileatime($file));
    $output[$i]["path"] = $file;
    $i++;
}
$sort = array_column($output, 'type');
array_multisort($sort, SORT_DESC, $output);
$output["no_of_files"] = $i + 1;
$output["exetime"] = round((microtime(true) - $time_start),5);
print_r($output);