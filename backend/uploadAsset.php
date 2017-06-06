<?php

include './oss.php';

$file = $_FILES['asset'];

$type = substr($file['type'], 0, 5);
$id = uniqid();
$ext = pathinfo($file['name'], PATHINFO_EXTENSION);

$name = "{$id}.{$ext}";

try
{
    $oss->uploadFile($bucket, $name, $file['tmp_name']);
    echo json_encode(['status' => 'fail', 'type' => $type, 'name' => $name]);
}catch(Exception $e){
    echo json_encode(['status' => 'fail']);
}
