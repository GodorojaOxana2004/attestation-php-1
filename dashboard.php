<?php
/**
 * Страница отображения всех результатов
 * Показывает таблицу с именами и процентами
 */

/**
 * Загружает результаты из JSON-файла
 * @return array Массив результатов
 */
function loadResults() {
    $file = 'data/results.json';
    return file_exists($file) ? json_decode(file_get_contents($file), true) : [];
}

$results = loadResults();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Дашборд</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Результаты всех пользователей</h1>
    <table>
        <tr>
            <th>Имя</th>
            <th>Процент</th>
        </tr>
        <?php if (empty($results)): ?>
            <tr><td colspan="2">Пока нет результатов</td></tr>
        <?php else: ?>
            <?php foreach ($results as $r): ?>
                <tr>
                    <td><?php echo htmlspecialchars($r['name']); ?></td>
                    <td><?php echo round($r['score'], 2); ?>%</td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
    <a href="index.php" class="button">Вернуться на главную</a>
</body>
</html>