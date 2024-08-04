<?php

// index.php
// version 1.0

// We do sanity checks, cleaning, token validation and then pass off
// to whatever unit

require_once('vcrud.php');
require_once('config.php');

$userId = 0;
$crud = new Vcrud($DB_USER, $DB_PASS, $DB_HOST, $DB_NAME);
$output = [
    'status' => 'error',
    'message' => '[] undefined error'
];

// This is a bit of a security risk, so if you don't need it
// for your development environment remove it. All our development
// needs it and is air gapped.
if ($DEVELOPER) {
    header("Access-Control-Allow-Origin: *");
}

$unit = htmlspecialchars($_REQUEST['unit'] ?? '');

if ($unit == '') {
    $output['message'] = '[system] no unit specified';
} else {
    if (!file_exists($unit . '.php')) {
        $output['message'] = '[system] invalid unit';
    } else {
        require_once($unit . '.php');
        $api = new $unit($crud);
        $output = $api->process();
    }
}

header('Content-type: application/json');
print(json_encode($output));
