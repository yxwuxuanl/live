<?php

include __DIR__ . '/oss.php';

$name = uniqid() . '.txt';

try
{
    $oss->putObject($bucket, $name, $_GET['content'], []);
    echo json_encode(['status' => 'success', 'type' => 'text', 'name' => $name]);
} catch (OssException $e) {
    echo json_encode(['status' => 'fail', 'error' => $e->getMessage()]);
}
