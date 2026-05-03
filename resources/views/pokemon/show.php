<div class="card">
    <h1><?= escape($pokemon->displayName) ?></h1>
    <p><strong>ID:</strong> <?= escape((string) $pokemon->id) ?></p>
    <p><strong>Height:</strong> <?= escape((string) $pokemon->height) ?></p>
    <p><strong>Weight:</strong> <?= escape((string) $pokemon->weight) ?></p>
    <p><strong>Types:</strong>
        <?php foreach ($pokemon->types as $type): ?>
            <span class="badge"><?= escape($type) ?></span>
        <?php endforeach; ?>
    </p>
    <?php if (!empty($pokemon->sprites['front_default'])): ?>
        <img src="<?= escape($pokemon->sprites['front_default']) ?>" alt="<?= escape($pokemon->displayName) ?>">
    <?php endif; ?>
</div>
