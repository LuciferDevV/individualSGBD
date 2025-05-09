<?php
require_once __DIR__ . '/../../helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    if ($id > 0) {
        deleteRecipe($id);
        header("Location: /");
        exit;
    } else {
        echo "ID рецепта не указан.";
    }
} else {
    echo "Недопустимый метод запроса.";
}
