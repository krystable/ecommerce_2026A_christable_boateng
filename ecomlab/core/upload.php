<?php
// Returns the saved filename, '' if no file was uploaded, or false on error ($error is filled in).
function upload_product_images($file, &$error)
{
    if (!is_array($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return '';
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error = "Upload failed (error code " . $file['error'] . ").";
        return false;
    }
    if ($file['size'] > 5 * 1024 * 1024) {
        $error = "Image must be 5MB or smaller.";
        return false;
    }

    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (!in_array($finfo->file($file['tmp_name']), $allowed, true)) {
        $error = "Only JPG, PNG, GIF or WEBP images are allowed.";
        return false;
    }

    $filename = uniqid() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', basename($file['name']));
    $dest = __DIR__ . "/../images/products/" . $filename;

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        $error = "Could not save the image. Check that images/products exists and is writable.";
        return false;
    }
    return $filename;
}