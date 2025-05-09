<?php
require_once __DIR__ . '/../../src/db.php';
require_once __DIR__ . '/../../src/helpers.php'; // Для работы с Redis

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<p>ID рецепта не указан.</p>";
    return;
}

// Получаем данные рецепта
$pdo = getPDO();
$stmt = $pdo->prepare('
    SELECT recipes.*, categories.name AS category_name 
    FROM recipes 
    JOIN categories ON recipes.category = categories.id 
    WHERE recipes.id = ?
');
$stmt->execute([$id]);
$recipe = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$recipe) {
    echo "<p>Рецепт не найден.</p>";
    return;
}

// Увеличиваем счетчик просмотров (Redis)
incrementRecipeViews($id);
$viewsCount = getRecipeViews($id);
?>

<h2><?= htmlspecialchars($recipe['title']) ?></h2>
<p><strong>Просмотров:</strong> <?= $viewsCount ?></p>
<p><strong>Категория:</strong> <?= htmlspecialchars($recipe['category_name']) ?></p>
<p><strong>Ингредиенты:</strong> <?= nl2br(htmlspecialchars($recipe['ingredients'])) ?></p>
<p><strong>Описание:</strong> <?= nl2br(htmlspecialchars($recipe['description'])) ?></p>
<p><strong>Теги:</strong> <?= htmlspecialchars($recipe['tags']) ?></p>
<p><strong>Шаги приготовления:</strong></p>
<pre><?= nl2br(htmlspecialchars($recipe['steps'])) ?></pre>

<p>
    <a href="/edit?id=<?= $recipe['id'] ?>" class="button1">Редактировать</a>
    <a href="/export/<?= $recipe['id'] ?>" class="button1">Экспорт в PDF</a>
</p>

<form action="/delete" method="post" onsubmit="return confirm('Вы уверены, что хотите удалить этот рецепт?');">
    <input type="hidden" name="id" value="<?= $recipe['id'] ?>">
    <button type="submit">Удалить</button>
</form>

<a href="/">Назад</a>