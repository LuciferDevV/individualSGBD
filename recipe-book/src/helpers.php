<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Инициализация Redis
function getRedis(): \Predis\Client {
    return new \Predis\Client([
        'scheme' => 'tcp',
        'host'   => '127.0.0.1',
        'port'   => 6379,
    ]);
}

// Получить количество просмотров рецепта
function getRecipeViews(int $recipeId): int {
    $redis = getRedis();
    return (int)$redis->get("recipe:views:{$recipeId}") ?: 0;
}

// Увеличить счетчик просмотров
function incrementRecipeViews(int $recipeId): void {
    $redis = getRedis();
    $redis->incr("recipe:views:{$recipeId}");
}

// Получить все рецепты с названием категории
function getRecipes(): array {
    $pdo = getPDO();
    $stmt = $pdo->query("
        SELECT r.*, c.name AS category_name
        FROM recipes r
        JOIN categories c ON r.category = c.id
        ORDER BY r.id DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Получить один рецепт по ID
function getRecipeById(int $id): ?array {
    $pdo = getPDO();
    $stmt = $pdo->prepare("
        SELECT r.*, c.name AS category_name
        FROM recipes r
        JOIN categories c ON r.category = c.id
        WHERE r.id = ?
    ");
    $stmt->execute([$id]);
    $recipe = $stmt->fetch(PDO::FETCH_ASSOC);
    return $recipe ?: null;
}

// Сохранить новый рецепт
function saveRecipe(array $recipe): bool {
    $pdo = getPDO();
    $stmt = $pdo->prepare("
        INSERT INTO recipes (title, category, ingredients, description, tags, steps)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    return $stmt->execute([
        $recipe['title'],
        $recipe['category'],
        $recipe['ingredients'],
        $recipe['description'],
        $recipe['tags'],
        $recipe['steps'],
    ]);
}

// Обновить рецепт
function updateRecipe(int $id, array $recipe): bool {
    $pdo = getPDO();
    $stmt = $pdo->prepare("
        UPDATE recipes
        SET title = ?, category = ?, ingredients = ?, description = ?, tags = ?, steps = ?
        WHERE id = ?
    ");
    return $stmt->execute([
        $recipe['title'],
        $recipe['category'],
        $recipe['ingredients'],
        $recipe['description'],
        $recipe['tags'],
        $recipe['steps'],
        $id
    ]);
}

// Удалить рецепт
function deleteRecipe(int $id): bool {
    $pdo = getPDO();
    $stmt = $pdo->prepare("DELETE FROM recipes WHERE id = ?");
    return $stmt->execute([$id]);
}

// Получить список всех категорий
function getCategories(): array {
    $pdo = getPDO();
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
