<?php
require_once __DIR__ . '/../../src/helpers.php';

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

// Получаем список категорий для выпадающего списка
$categories = getCategories();
?>

<h1>Редактировать рецепт</h1>
<form action="/update" method="post">
    <input type="hidden" name="id" value="<?= $recipe['id'] ?>">

    <label>Название рецепта: <input type="text" name="title" value="<?= htmlspecialchars($recipe['title']) ?>" required></label><br><br>

    <label>Категория:
        <select name="category" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['id'] ?>" <?= $category['id'] == $recipe['category'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($category['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label><br><br>

    <label>Ингредиенты: <textarea name="ingredients" required><?= htmlspecialchars($recipe['ingredients']) ?></textarea></label><br><br>

    <label>Описание: <textarea name="description" required><?= htmlspecialchars($recipe['description']) ?></textarea></label><br><br>

    <label>Теги: <input type="text" name="tags" value="<?= htmlspecialchars($recipe['tags']) ?>"></label><br><br>

    <label>Шаги: <textarea name="steps" required><?= htmlspecialchars($recipe['steps']) ?></textarea></label><br><br>

    <button type="submit">Сохранить изменения</button>
</form>
