<?php
require_once __DIR__ . '/../src/helpers.php';
require_once __DIR__ . '/../vendor/autoload.php';

$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

ob_start();

switch ($url) {
    case '/':
    case '/index.php':
        require __DIR__ . '/../templates/index.php';
        break;
    case '/create':
        require __DIR__ . '/../templates/recipe/create.php';
        break;
    case '/store':
        require __DIR__ . '/../src/handlers/recipe/create.php';
        break;
    case (preg_match('#^/show/(\d+)$#', $url, $matches) ? true : false):
        $_GET['id'] = $matches[1];
        require __DIR__ . '/../templates/recipe/show.php';
        break;
    case '/delete':
        require __DIR__ . '/../src/handlers/recipe/delete.php';
        break;
    case '/edit':
        require __DIR__ . '/../src/handlers/recipe/edit.php';
        break;
    case '/update':
        require __DIR__ . '/../src/handlers/recipe/edit.php';
        break;
    case (preg_match('#^/export/(\d+)$#', $url, $matches) ? true : false):
        $_GET['id'] = $matches[1];
        require __DIR__ . '/../src/handlers/recipe/export.php';
        break;
    default:
        http_response_code(404);
        echo "<h1>404 - Страница не найдена</h1>";
        break;
}

$content = ob_get_clean();
require __DIR__ . '/../templates/layout.php';
