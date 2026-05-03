<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= escape($title ?? 'Pokemon') ?></title>
    <style>
        body { font-family: system-ui, sans-serif; margin: 2rem; }
        .card { border: 1px solid #ddd; padding: 1.5rem; border-radius: 8px; max-width: 520px; }
        .badge { display: inline-block; background: #f5f5f5; padding: 0.25rem 0.5rem; border-radius: 4px; margin-right: 0.25rem; }
    </style>
</head>
<body>
    <?= $content ?? '' ?>
</body>
</html>
