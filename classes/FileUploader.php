<?php
class FileUploader
{
    private $uploadDir;

    public function __construct()
    {
        $this->uploadDir = Config::UPLOAD_DIR;
        $this->checkUploadDirExist();
    }

    //check if exist if not then create a dir
    private function checkUploadDirExist()
    {
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    public function upload($file)
    {
        //check for file upload error
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        $fileName = uniqid() . '_' . basename($file['name']);
        $targetPath = $this->uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $fileName;
        }

        return false;
    }
}