<?php

require_once __DIR__ . '/autoload.php';

$access_id = 'LTAI76BuMa21mojy';
$access_key = 'aFcCHs23099fqNnYkmamUx2UbWDwKb';
$endpoint = 'oss-cn-shenzhen.aliyuncs.com';
$bucket = 'hztilive';

$oss = new OSS\OssClient($access_id, $access_key, $endpoint, FALSE);
