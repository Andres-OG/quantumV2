<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// Esto permite emular la funcionalidad "mod_rewrite" de Apache desde el servidor web integrado de PHP
// Esto proporciona una forma conveniente de probar una aplicación sin necesidad de un servidor web "real"
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

require_once __DIR__.'/public/index.php';
