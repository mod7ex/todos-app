<?php

namespace TODOS\LIB;

class UploadHandler
{
    private static $target_dir = USERS_PROFILES_IMAGES;

    // Check if image file is an actual image or fake image
    public static function check_type($file)
    {
        # here we want a filetype===img!
        $check = getimagesize($file["tmp_name"]);
        if($check !== false) {
            return true;
        }
        return false;
    }

    // Allow certain file formats
    public static function check_file_ext($file)
    {
        $arr = explode('.', basename($file["name"]));
        if (!empty($arr)){
            $fileExt = strtolower(end($arr));
        }
        # here we want just these forms of files
        if($fileExt != "jpg" && $fileExt != "png" && $fileExt != "jpeg" && $fileExt != "gif" ) {
            return false;
        }
        return true;
    }

    // Check file size
    public static function check_size($file)
    {
        if ($file["size"] > 500000) {
            return false;
        }
        return true;
    }

    // Check if file already exists
    public static function check_if_file_exists($file)
    {
        if (file_exists($file)) {
            return true;
        }
        return false;
    }

    public static function save_file($file, $file_path)
    {
        if (move_uploaded_file($file["tmp_name"], $file_path)) {
            return true;
        }
        return false;
    }
}



/*
 * These Lines Of Code Has Been Copied From W3School.com
 */

/*
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
*/