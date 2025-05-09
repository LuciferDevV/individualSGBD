<?php
require_once __DIR__ . '/../src/helpers.php';

$recipes = getRecipes();
?>

<h2>Список рецептов</h2>
<?php if (empty($recipes)): ?>
    <p>Рецептов пока нет.</p>
<?php else: ?>
    <ul>
        <?php foreach ($recipes as $recipe): ?>
            <li>
                <strong><?= htmlspecialchars($recipe['title']) ?></strong>
                — Категория: <?= htmlspecialchars($recipe['category']) ?>
                — <?= nl2br(htmlspecialchars($recipe['description'])) ?>
                <a href="/show/<?= $recipe['id'] ?>" class="button">Подробнее</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
