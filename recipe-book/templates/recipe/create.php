<?php
require_once __DIR__ . '/../../src/helpers.php';

// Получаем список категорий для выпадающего списка
$categories = getCategories();
?>

<h2>Добавить новый рецепт</h2>
<form action="/store" method="post">
    <label>Название рецепта: <input type="text" name="title" required></label><br><br>

    <label>Категория:
        <select name="category" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </label><br><br>

    <label>Ингредиенты: <textarea name="ingredients" required></textarea></label><br><br>

    <label>Описание: <textarea name="description" required></textarea></label><br><br>

    <label>Теги: <input type="text" name="tags"></label><br><br>

    <label>Шаги: <textarea name="steps" required></textarea></label><br><br>

    <button type="submit">Сохранить</button>
</form>
