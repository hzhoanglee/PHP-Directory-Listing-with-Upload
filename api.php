<?php
//VERSION: 0.2_beta
$time_start = microtime(true);
header('Content-Type: application/json; charset=utf-8');
require_once ("config.php");
//Get Parameters
$dir = $_GET["dir"] ?? ".";
$page = $_GET["page"] ?? 1;
if (isset($_GET["pagination"])){
    $cfg_page = $_GET["pagination"];
}
// Get file list, pagination and couting
$files = array_diff(scandir($dir), array('.', '..'));
$files_total = count($files);
if ($cfg_page !== 0){
    $files = array_chunk($files, $cfg_page);
    $page_max = count($files);
    $files = $files[$page-1];
}
$i = 0;


foreach ($files as $file) {
    $file = $dir . "/" . $file;
    if (is_dir($file)) {
        $output[$i]["type"] = "Folder";
        $output[$i]["name"] = pathinfo($file)["basename"];
    } else {
        $output[$i]["type"] = "File";
        $output[$i]["name"] = pathinfo($file)["basename"];
        $output[$i]["extension"] = pathinfo($file)["extension"];
        $output[$i]["size"] = getsize(filesize($file));
    }
    $output[$i]["last_modify"] = date("Y-m-d H:i:s", filemtime($file));
    $output[$i]["last_open"] = date("Y-m-d H:i:s", fileatime($file));
    $output[$i]["path"] = $file;
    $i++;
}
$sort = array_column($output, 'type');
array_multisort($sort, SORT_DESC, $output);
$exetime = round((microtime(true) - $time_start),5);
//Print Final Result
$final["info"] = ["directory" => $dir,"current_page" => $page, "total_page" => $page_max,"pagination" => $cfg_page, "process_time" => $exetime, "total_files" => $files_total];
$final["files"] = $output;
echo json_encode($final,JSON_PRETTY_PRINT);