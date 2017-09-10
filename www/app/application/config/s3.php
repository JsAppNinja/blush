<?php
/*
| -------------------------------------------------------------------
| Amazon S3 Configuration
| -------------------------------------------------------------------
*/

$config["accessKey"] = "AKIAI54XWRR6ELCPWLFQ";
$config["secretKey"] = "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";
$config["useSSL"] = FALSE;
$config['s3_bucket'] = 'joinblush';
$config['s3_url'] = 'https://d3rgfxfdvirl3c.cloudfront.net/';

if (IS_TEST) {
    $config["accessKey"] = "AKIAJLLKH5LRCSQFB6ZQ";
    $config["secretKey"] = "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";
    $config['s3_bucket'] = 'blushs3';
    $config['s3_url'] = 'https://s3.amazonaws.com/'.$config['s3_bucket'].'/';
}