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

function size($bytes)
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
        $output[$i]["size"] = size(filesize($file));
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
    <title>File Directory</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link rel="stylesheet" href="assets/font/iconsmind-s/css/iconsminds.css" />
    <link rel="stylesheet" href="assets/font/simple-line-icons/css/simple-line-icons.css" />
    <link rel="stylesheet" href="assets/css/vendor/dropzone.min.css" />
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.rtl.only.min.css" />
    <link rel="stylesheet" href="assets/css/vendor/component-custom-switch.min.css" />
    <link rel="stylesheet" href="assets/css/vendor/jquery.contextMenu.min.css" />
    <link rel="stylesheet" href="assets/css/vendor/perfect-scrollbar.css" />
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
    <div class="container-fluid library-app">
        <div class="row">
            <div class="col-12">
                <div class="mb-3">
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
                </div>

                <div class="mb-2">
                    <a class="btn pt-0 pl-0 d-inline-block d-md-none" data-toggle="collapse" href="#displayOptions"
                       role="button" aria-expanded="true" aria-controls="displayOptions">
                        Display Options
                        <i class="simple-icon-arrow-down align-middle"></i>
                    </a>
                    <div class="collapse d-md-block" id="displayOptions">
                        <div class="d-block d-md-inline-block">
                            <div class="btn-group float-md-left mr-1 mb-1">
                                <button class="btn btn-outline-dark btn-xs dropdown-toggle" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Order By
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                </div>
                            </div>
                            <div class="search-sm d-inline-block float-md-left mr-1 mb-1 align-top">
                                <input placeholder="Search...">
                            </div>
                        </div>
                        <div class="float-md-right">
                            <span class="text-muted text-small"><?php echo $i +1 ;?> files </span>
                            <button class="btn btn-outline-dark btn-xs dropdown-toggle" type="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                20
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">10</a>
                                <a class="dropdown-item active" href="#">20</a>
                                <a class="dropdown-item" href="#">30</a>
                                <a class="dropdown-item" href="#">50</a>
                                <a class="dropdown-item" href="#">100</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="separator mb-5"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-xl-4 drop-area-container">
                <div class="card drop-area">
                    <div class="card-body">
                        <form action="/upload.php" method="post">
                            <div class="dropzone">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-8 list disable-text-selection" data-check-all="checkAll">
                <?php
                    foreach ($output as $item){
                        if ($item["type"] == "Folder"){
                            echo '<div class="row">
                                    <div class="col-12">
                                        <div class="card d-flex flex-row mb-4 media-thumb-container">
                                            <a class="d-flex align-self-center media-thumbnail-icon"
                                               href="?dir=' . $item["path"] . '">
                                                <i class="iconsminds-folder-open"></i>
                                            </a>
                                            <div class="d-flex flex-grow-1 min-width-zero">
                                                <div
                                                        class="card-body align-self-center d-flex flex-column justify-content-between min-width-zero align-items-lg-center">
                                                    <a href="?dir=' . $item["path"] . '" class="w-100">
                                                        <p class="list-item-heading mb-1 truncate">' . $item["name"] . '</p>
                                                    </a>
                                                    <p class="mb-1 text-muted text-small w-100 truncate">' . $item["name"] . '</p>
                                                </div>
                                                <div class="pl-1 align-self-center">
                                                    <label class="custom-control custom-checkbox mb-0">
                                                        <input type="checkbox" class="custom-control-input">
                                                        <span class="custom-control-label"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                        } else {
                            echo '<div class="row"><div class=" col-12">
                                    <div class="card d-flex flex-row mb-4 media-thumb-container">
                                        <a class="d-flex align-self-center media-thumbnail-icon"
                                           href="' . $item["path"] . '">
                                            <i class="iconsminds-guitar"></i>
                                        </a>
                                        <div class="d-flex flex-grow-1 min-width-zero">
                                            <div
                                                class="card-body align-self-center d-flex flex-column justify-content-between min-width-zero align-items-lg-center">
                                                <a href="' . $item["path"] . '" class="w-100">
                                                    <p class="list-item-heading mb-1 truncate">' . $item["name"] . '</p>
                                                </a>
                                                <p class="mb-1 text-muted text-small w-100 truncate">' . $item["name"] . ' * ' . $item["size"] . '</p>
                                            </div>
                                            <div class="pl-1 align-self-center">
                                                <label class="custom-control custom-checkbox mb-0">
                                                    <input type="checkbox" class="custom-control-input">
                                                    <span class="custom-control-label"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                             </div>';
                        }

                    }
                ?>

            </div>
        </div>
        <div class="row">
            <div class="col-12 col-xl-8 offset-0 offset-xl-4">
                <nav class="mt-4 mb-3">
                    <ul class="pagination justify-content-center mb-0">
                        <li class="page-item ">
                            <a class="page-link first" href="#">
                                <i class="simple-icon-control-start"></i>
                            </a>
                        </li>
                        <li class="page-item ">
                            <a class="page-link prev" href="#">
                                <i class="simple-icon-arrow-left"></i>
                            </a>
                        </li>
                        <li class="page-item active">
                            <a class="page-link" href="#">1</a>
                        </li>
                        <li class="page-item ">
                            <a class="page-link" href="#">2</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">3</a>
                        </li>
                        <li class="page-item ">
                            <a class="page-link next" href="#" aria-label="Next">
                                <i class="simple-icon-arrow-right"></i>
                            </a>
                        </li>
                        <li class="page-item ">
                            <a class="page-link last" href="#">
                                <i class="simple-icon-control-end"></i>
                            </a>
                        </li>
                    </ul>
                </nav>

            </div>
        </div>
    </div>
</main>

<footer class="page-footer">
    <div class="footer-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <p class="mb-0 text-muted">hzhoanglee 2021 - Excution time: <?php echo round((microtime(true) - $time_start),5);?></p>
                </div>
                <div class="col-sm-6 d-none d-sm-block">
                    <ul class="breadcrumb pt-0 pr-0 float-right">
                        <li class="breadcrumb-item mb-0">
                            <a href="#" class="btn-link">Github</a>
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
<script src="assets/js/vendor/dropzone.min.js"></script>
<script src="assets/js/vendor/mousetrap.min.js"></script>
<script src="assets/js/vendor/jquery.contextMenu.min.js"></script>
<script src="assets/js/dore.script.js"></script>
<script src="assets/js/scripts.js"></script>
<script>
    /* Dropzone */
    if ($().dropzone && !$(".dropzone").hasClass("disabled")) {
        $(".dropzone").dropzone({
            url: "upload.php",
            method: "POST",
            paramName: "file",
            uploadMultiple: true,


            init: function () {
                this.on("addedfile", function(file) {
                    console.log(file);
                });
                this.on("sending", function(file, xhr, formData) {
                    formData.append("dropzone", "1"); // $_POST["dropzone"]
                });
                this.on("success", function (file, responseText) {
                    console.log(responseText);
                });
            },
            thumbnailWidth: 160,
            uploadprogress: function(file, progress, bytesSent) {
                if (file.previewElement) {
                    var progressElement = file.previewElement.querySelector("[data-dz-uploadprogress]");
                    progressElement.style.width = progress + "%";
                    progressElement.querySelector(".progress-text").textContent = progress + "%";
                }
            },
            previewTemplate: '<div class="dz-preview dz-file-preview mb-3"><div class="d-flex flex-row "><div class="p-0 w-30 position-relative"><div class="dz-error-mark"><span><i></i></span></div><div class="dz-success-mark"><span><i></i></span></div><div class="preview-container"><img data-dz-thumbnail class="img-thumbnail border-0" /><i class="simple-icon-doc preview-icon" ></i></div></div><div class="pl-3 pt-2 pr-2 pb-1 w-70 dz-details position-relative"><div><span data-dz-name></span></div><div class="text-primary text-extra-small" data-dz-size /><div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div><div class="dz-error-message"><span data-dz-errormessage></span></div></div></div><a href="#/" class="remove" data-dz-remove><i class="glyph-icon simple-icon-trash"></i></a></div><div class="progress"> <div class="progress-bar progress-bar-primary" role="progressbar" data-dz-uploadprogress> </div> </div>'
        });

    }
</script>
</body>

</html>