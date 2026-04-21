<?php

class UploadService
{
    private static $_MAX_SIZE_UPLOAD = 2 * 1024 * 1024;
    private static $_TYPE_ALLOWED = ['image/jpeg', 'image/png', 'image/webp'];
    private static $_UPLOAD_DIR = __DIR__ . '/../../public/uploads/avatars/';

    public function __construct()
    {
        if (!is_dir($this::$_UPLOAD_DIR)) {
            mkdir($this::$_UPLOAD_DIR, 0777, true);
        }
    }

    public function uploadImage(array $file): string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new HttpException("Upload failed");
        }

        if ($file['size'] > $this::$_MAX_SIZE_UPLOAD) {
            throw new HttpException("File too large");
        }

        if (!getimagesize($file['tmp_name'])) {
            throw new HttpException("Invalid image");
        }

        if (!in_array($file['type'], $this::$_TYPE_ALLOWED)) {
            throw new Exception("Invalid type");
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('avatar_', true) . '.' . $ext;

        $fullPath = $this::$_UPLOAD_DIR . $fileName;

        move_uploaded_file($file['tmp_name'], $fullPath);

        return '/uploads/avatars/' . $fileName;
    }
}
