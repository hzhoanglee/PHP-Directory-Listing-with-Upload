<?php

if (!empty($_FILES)) {

    // if dest folder doesen't exists, create it
    if(!file_exists($dest_folder) && !is_dir($dest_folder)) mkdir($dest_folder);

    /**
     *	Single File
     *	uploadMultiple = false
     *	@var $_FILES['file']['tmp_name'] string, file_name
     */
    // $tempFile = $_FILES['file']['tmp_name'];
    // $targetFile =  $dest_folder . $_FILES['file']['name'];
    // move_uploaded_file($tempFile,$targetFile);

    /**
     *  Multiple Files
     *  uploadMultiple = true
     *  @var $_FILES['file']['tmp_name'] array
     *
     */
    foreach($_FILES['file']['tmp_name'] as $key => $value) {
        $tempFile = $_FILES['file']['tmp_name'][$key];
        $targetFile =  $dest_folder. $_FILES['file']['name'][$key];
        move_uploaded_file($tempFile,$targetFile);
    }

    /**
     *	Response
     *	return json response to the dropzone
     *	@var data array
     */
    $data = [
        "file" => $_POST["file"],
        "dropzone" => $_POST["dropzone"],
    ];
    header('Content-type: application/json');
    echo json_encode($data);

    exit();

}