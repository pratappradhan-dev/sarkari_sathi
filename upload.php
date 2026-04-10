<?php

function uploadImage($file){

    if(isset($file) && $file['error'] == 0){

        $image_name = time() . "_" . basename($file['name']);
        $tmp_name = $file['tmp_name'];

        $upload_dir = __DIR__ . "/../uploads/";
        $upload_path = $upload_dir . $image_name;

        // Create folder if not exists
        if(!is_dir($upload_dir)){
            mkdir($upload_dir, 0777, true);
        }

        // Move file
        if(move_uploaded_file($tmp_name, $upload_path)){
            return $image_name; // success
        } else {
            return false;
        }
    }

    return "";
}
?>