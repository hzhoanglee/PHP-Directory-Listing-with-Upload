<?php
$url = $_SERVER["REQUEST_URI"];
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
//$output["no_of_files"] = $i + 1;
//$output["exetime"] = round((microtime(true) - $time_start),5);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Table Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link rel="stylesheet" href="assets/font/iconsmind-s/css/iconsminds.css" />
    <link rel="stylesheet" href="assets/font/simple-line-icons/css/simple-line-icons.css" />
    <link rel="stylesheet" href="assets/css/vendor/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" href="assets/css/vendor/datatables.responsive.bootstrap4.min.css" />
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.rtl.only.min.css" />
    <link rel="stylesheet" href="assets/css/vendor/perfect-scrollbar.css" />
    <link rel="stylesheet" href="assets/css/vendor/component-custom-switch.min.css" />
    <link rel="stylesheet" href="assets/css/main.css" />
</head>

<body id="app-container" class="menu-sub-hidden show-spinner">
<nav class="navbar fixed-top">
    <div class="d-flex align-items-center navbar-left">
        <a href="#" class="menu-button d-none d-md-block">
            <svg class="main" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 17">
                <rect x="0.48" y="0.5" width="7" height="1" />
                <rect x="0.48" y="7.5" width="7" height="1" />
                <rect x="0.48" y="15.5" width="7" height="1" />
            </svg>
            <svg class="sub" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 17">
                <rect x="1.56" y="0.5" width="16" height="1" />
                <rect x="1.56" y="7.5" width="16" height="1" />
                <rect x="1.56" y="15.5" width="16" height="1" />
            </svg>
        </a>

        <a href="#" class="menu-button-mobile d-xs-block d-sm-block d-md-none">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26 17">
                <rect x="0.5" y="0.5" width="25" height="1" />
                <rect x="0.5" y="7.5" width="25" height="1" />
                <rect x="0.5" y="15.5" width="25" height="1" />
            </svg>
        </a>

    </div>


    <a class="navbar-logo" href="/">
        <!--<span class="logo d-none d-xs-block"></span>
        <span class="logo-mobile d-block d-xs-none"></span> -->
    </a>

    <div class="navbar-right">
        <div class="header-icons d-inline-block align-middle">
            <div class="d-none d-md-inline-block align-text-bottom mr-3">
                <div class="custom-switch custom-switch-primary-inverse custom-switch-small pl-1"
                     data-toggle="tooltip" data-placement="left" title="Dark Mode">
                    <input class="custom-switch-input" id="switchDark" type="checkbox" checked>
                    <label class="custom-switch-btn" for="switchDark"></label>
                </div>
            </div>

            <div class="position-relative d-none d-sm-inline-block">
                <button class="header-icon btn btn-empty" type="button" id="iconMenuButton" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                    <i class="simple-icon-grid"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right mt-3  position-absolute" id="iconMenuDropdown">
                    <a href="#" class="icon-menu-item">
                        <i class="iconsminds-equalizer d-block"></i>
                        <span>Mock</span>
                    </a>

                </div>
            </div>

            <div class="position-relative d-inline-block">
                <button class="header-icon btn btn-empty" type="button" id="notificationButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="simple-icon-bell"></i>
                    <span class="count">1</span>
                </button>
                <div class="dropdown-menu dropdown-menu-right mt-3 position-absolute" id="notificationDropdown">
                    <div class="scroll">
                        <div class="d-flex flex-row mb-3 pb-3 border-bottom">
                            <a href="#">
                                <img src="assets/img/profiles/l-2.jpg" alt="Notification Image"
                                     class="img-thumbnail list-thumbnail xsmall border-0 rounded-circle" />
                            </a>
                            <div class="pl-3">
                                <a href="#">
                                    <p class="font-weight-medium mb-1">Test notification</p>
                                    <p class="text-muted mb-0 text-small">09.04.2018 - 12:45</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button class="header-icon btn btn-empty d-none d-sm-inline-block" type="button" id="fullScreenButton">
                <i class="simple-icon-size-fullscreen"></i>
                <i class="simple-icon-size-actual"></i>
            </button>

        </div>

        <div class="user d-inline-block">
            <button class="btn btn-empty p-0" type="button" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                <span class="name">HSLIM</span>
                <span>
                        <img alt="Profile Picture" src="assets/img/profiles/l-2.jpg" />
                    </span>
            </button>

            <div class="dropdown-menu dropdown-menu-right mt-3">
                <a class="dropdown-item" href="#">Sign out</a>
            </div>
        </div>
    </div>
</nav>
<div class="menu">
    <div class="main-menu">
        <div class="scroll">
            <ul class="list-unstyled">
                <li>
                    <a href="#dashboard">
                        <i class="iconsminds-shop-4"></i>
                        <span>Functions</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="sub-menu">
        <div class="scroll">
            <ul class="list-unstyled" data-link="dashboard">
                <li>
                    <a href="index.php">
                        <i class="simple-icon-rocket"></i> <span class="d-inline-block">Default</span>
                    </a>
                </li>
                <li>
                    <a href="table.php">
                        <i class="simple-icon-pie-chart"></i> <span class="d-inline-block">Table</span>
                    </a>
                </li>
            </ul>

        </div>
    </div>
</div>
<main>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1>Listing for: <?php echo $dir;?></h1>
                <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                    <ol class="breadcrumb pt-0">
                        <li class="breadcrumb-item">
                            <a href="/">root</a>
                        </li>
                        <?php
                        $full_path = explode("/", $dir);
                        $pre_url = "?dir=.";
                        foreach ($full_path as $o_path){
                            if ($o_path !== ".") {
                                $pre_url .=  "/" . $o_path;
                                echo '<li class="breadcrumb-item">
                                                <a href="' . $pre_url . '">' . $o_path . '</a>
                                            </li>';
                            }

                        }
                        ?>
                    </ol>
                </nav>
                <div class="separator mb-5"></div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12 data-tables-hide-filter">
                <div class="card">
                    <div class="card-body">

                        <table class="data-table data-tables-pagination responsive nowrap"
                               data-order="[[ 1, &quot;desc&quot; ]]">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Size</th>
                                <th>File Type</th>
                                <th>Last modify</th>
                                <th>Last open</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($output as $key=>$item){
                                if ($item["type"] == "Folder"){
                                echo '<tr>
                                <td>
                                    <i class="iconsminds-folder-open"></i>
                                </td>
                                <td>
                                    <p class="list-item-heading"></p>
                                    <a href="table.php?dir=' . $item["path"] . '" class="list-item-heading">' . $item["name"] . '</a>
                                </td>
                                <td>
                                    <p class="text-muted">Folder :D</p>
                                </td>
                                <td>
                                    <p class="text-muted">Folder :D</p>
                                </td>
                                <td>
                                    <p class="text-muted">' . $item["last_modify"] . '</p>
                                </td>
                                <td>
                                    <p class="text-muted">' . $item["last_open"] . '</p>
                                </td>
                            </tr>';
                                } else {
                                    echo '<tr>
                                <td>
                                    <p class="text-muted">' . $key . '</p>
                                </td>
                                <td>
                                    <p class="list-item-heading"></p>
                                    <a href="' . $item["path"] . '" class="list-item-heading">' . $item["name"] . '</a>
                                </td>
                                <td>
                                    <p class="text-muted">' . $item["size"] . '</p>
                                </td>
                                <td>
                                    <p class="text-muted">' . $item["extension"] . '</p>
                                </td>
                                <td>
                                    <p class="text-muted">' . $item["last_modify"] . '</p>
                                </td>
                                <td>
                                    <p class="text-muted">' . $item["last_open"] . '</p>
                                </td>
                            </tr>';}}?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<footer class="page-footer">
    <div class="footer-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <p class="mb-0 text-muted">ColoredStrategies 2019 - Excution time: <?php echo round((microtime(true) - $time_start),5);?></p>
                </div>
                <div class="col-sm-6 d-none d-sm-block">
                    <ul class="breadcrumb pt-0 pr-0 float-right">
                        <li class="breadcrumb-item mb-0">
                            <a href="#" class="btn-link">Review</a>
                        </li>
                        <li class="breadcrumb-item mb-0">
                            <a href="#" class="btn-link">Purchase</a>
                        </li>
                        <li class="breadcrumb-item mb-0">
                            <a href="#" class="btn-link">Docs</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="assets/js/vendor/jquery-3.3.1.min.js"></script>
<script src="assets/js/vendor/bootstrap.bundle.min.js"></script>
<script src="assets/js/vendor/perfect-scrollbar.min.js"></script>
<script src="assets/js/vendor/datatables.min.js"></script>
<script src="assets/js/dore.script.js"></script>
<script src="assets/js/scripts.js"></script>

</body>

</html>