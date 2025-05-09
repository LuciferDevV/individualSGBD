<?php
// Включим вывод ошибок для отладки (удалите в продакшене)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../helpers.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Получаем ID рецепта
$id = intval($_GET['id'] ?? 0);
$recipe = getRecipeById($id);

if (!$recipe) {
    header("HTTP/1.0 404 Not Found");
    exit;
}

// Настройки DomPDF
$options = new Options();
$options->set('defaultFont', 'DejaVu Sans'); // Шрифт с поддержкой кириллицы
$options->set('isRemoteEnabled', true);     // Разрешаем загрузку внешних ресурсов
$options->set('isHtml5ParserEnabled', true); // Включаем парсер HTML5

$dompdf = new Dompdf($options);

// HTML-шаблон с явным указанием кодировки
$html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { 
            font-family: 'DejaVu Sans', sans-serif;
            line-height: 1.6;
            padding: 20px;
        }
        h1 { 
            color: #8c5e4f;
            text-align: center;
        }
        .section { 
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .label { 
            font-weight: bold;
            color: #8c5e4f;
        }
    </style>
</head>
<body>
    <h1>{$recipe['title']}</h1>
    
    <div class="section">
        <span class="label">Категория:</span> {$recipe['category_name']}
    </div>
    
    <div class="section">
        <div class="label">Ингредиенты:</div>
        <div>{$recipe['ingredients']}</div>
    </div>
    
    <div class="section">
        <div class="label">Описание:</div>
        <div>{$recipe['description']}</div>
    </div>
    
    <div class="section">
        <div class="label">Теги:</div>
        <div>{$recipe['tags']}</div>
    </div>
    
    <div class="section">
        <div class="label">Шаги приготовления:</div>
        <div>{$recipe['steps']}</div>
    </div>
    
    <div class="footer" style="margin-top: 30px; font-size: 0.8em; text-align: center;">
        <p>Рецепт экспортирован: {$recipe['created_at']}</p>
    </div>
</body>
</html>
HTML;

// Загружаем HTML и указываем кодировку
$dompdf->loadHtml($html, 'UTF-8');

// Рендерим PDF
$dompdf->render();

// Очищаем буфер вывода
while (ob_get_level()) {
    ob_end_clean();
}

// Устанавливаем правильные заголовки ДЛЯ СКАЧИВАНИЯ (изменения здесь)
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="recipe_' . $id . '.pdf"'); // Изменили inline на attachment
header('Content-Length: ' . strlen($dompdf->output()));
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

// Выводим PDF с настройкой ДЛЯ СКАЧИВАНИЯ (изменения здесь)
$dompdf->stream("recipe_{$id}.pdf", [
    'Attachment' => true, // Изменили false на true для скачивания
    'compress' => true
]);

exit;