<?php

/**
 * Custom server script to handle multiple domains for Tency multi-tenancy
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

// Don't do anything if the request is for a real file
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

// Set the correct document root
$_SERVER['SCRIPT_NAME'] = '/index.php';

// Handle the request through Laravel
require_once __DIR__.'/public/index.php';
