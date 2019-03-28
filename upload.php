<?php
function get_unique_file_name($path, $filename) {
    $file_parts = explode(".", $filename);
    $ext = array_pop($file_parts);
    $name = implode(".", $file_parts);
 
    $i = 1;
    while (file_exists($path . $filename)) {
        $filename = $name . '-' . ($i++) . '.' . $ext;
    }
    return $filename;
}

function upload_file($path, $fileInput) {
    if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        return "Error: " . $_FILES[$fileInput]['error'];
    }
    $filename = $path . 
        get_unique_file_name($path, basename($_FILES[$fileInput]['name']));
    
    if (move_uploaded_file($_FILES[$fileInput]['tmp_name'], $filename)) {
        return $filename;
    }
    return false;
}

$path = __DIR__ . '/tmp/';

if (!empty($_FILES)) {
    $res = upload_file($path, 'userfile');         
    var_dump($res);
}

?>
<form enctype="multipart/form-data" action="" method="POST">
    <!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="300000" />
    Send this file: <input name="userfile" type="file" />
    <input type="submit" value="Send File" />
</form>
