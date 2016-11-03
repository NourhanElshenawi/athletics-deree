<?php
namespace Nourhan\Services;

use Nourhan\Database\DB;


class Upload
{

    public function __construct()
    {
    }

    public function upload()
    {
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
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
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
// Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
                $DB = new DB();
                $name = $_FILES["fileToUpload"]["name"];
                $DB->uploadCarousel($name);

            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }

    }

    public function uploadFile($file)
    {
        $target_dir = __DIR__ . "/../storage/";

        $target_file = $target_dir . basename($file["name"]);

        $fileType = pathinfo($target_file, PATHINFO_EXTENSION);

        $result['success'] = true;
        $result['message'] = "";

        // Allow certain file formats
        if ($fileType != "json") {
            $result['success'] = false;
            $result['message'] = "Sorry, only JSON files are allowed.";
        }

        // Check if $uploadOk is set to 0 by an error
        if ($result['success'] != true) {

            return $result;
        } else { // if everything is ok, try to upload file
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                $result['success'] = true;
                $result['message'] = "The file " . basename($file["name"]) . " has been uploaded.";
            } else {
                $result['success'] = false;
                $result['message'] = "Sorry, there was an error uploading your file.";
            }
        }

        return $result;
    }

}


?>