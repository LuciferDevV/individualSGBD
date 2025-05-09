<?php
require_once __DIR__ . '/../../helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $title = trim($_POST['title']);
    $category = intval($_POST['category']);
    $ingredients = trim($_POST['ingredients']);
    $description = trim($_POST['description']);
    $tags = trim($_POST['tags']);
    $steps = trim($_POST['steps']);

    if ($title && $category && $ingredients && $description && $steps) {
        // Обновляем рецепт в базе данных
        updateRecipe($id, [
            'title' => $title,
            'category' => $category,
            'ingredients' => $ingredients,
            'description' => $description,
            'tags' => $tags,
            'steps' => $steps
        ]);
        // Перенаправление на страницу рецепта после успешного обновления
        header("Location: /");
        exit;
    } else {
        echo "Пожалуйста, заполните все обязательные поля.";
    }
}

if (!isset($_GET['id'])) {
    echo "ID рецепта не указан.";
    exit;
}

$id = intval($_GET['id']);
$recipe = getRecipeById($id);

if (!$recipe) {
    echo "Рецепт не найден.";
    exit;
}

require_once __DIR__ . '/../../../templates/recipe/edit.php';
