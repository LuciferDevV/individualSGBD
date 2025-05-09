<?php
require_once __DIR__ . '/../../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = getPDO();

    // Получаем данные из формы
    $title = $_POST['title'];
    $category = intval($_POST['category']);
    $ingredients = $_POST['ingredients'];
    $description = $_POST['description'];
    $tags = $_POST['tags'];
    $steps = $_POST['steps'];

    // Подготавливаем и выполняем запрос на добавление рецепта в базу данных
    $stmt = $pdo->prepare('
        INSERT INTO recipes (title, category, ingredients, description, tags, steps)
        VALUES (?, ?, ?, ?, ?, ?)
    ');

    $stmt->execute([
        $title,
        $category,
        $ingredients,
        $description,
        $tags,
        $steps
    ]);

    // Перенаправление на главную страницу после успешного добавления
    header('Location: /');
    exit;
}
?>
