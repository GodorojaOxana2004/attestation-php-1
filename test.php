<?php
/**
 * Страница прохождения теста
 * Отображает форму с вопросами и полем для имени
 */

/**
 * Загружает вопросы из JSON-файла
 * @return array Массив вопросов
 */
function loadQuestions() {
    $file = 'data/questions.json';
    if (!file_exists($file)) {
        die("Файл с вопросами не найден!");
    }
    return json_decode(file_get_contents($file), true);
}

$questions = loadQuestions();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Тест</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Пройдите тест</h1>
    <form action="result.php" method="POST">
        <div class="question">
            <p>Введите ваше имя</p>
            <input type="text" name="name" required>
        </div>
        <?php foreach ($questions as $q): ?>
            <div class="question">
                <p><?php echo htmlspecialchars($q['question']); ?></p>
                <?php if ($q['type'] == 'single'): ?>
                    <?php foreach ($q['options'] as $option): ?>
                        <label>
                            <input type="radio" name="answers[<?php echo $q['id']; ?>]" value="<?php echo htmlspecialchars($option); ?>" required>
                            <?php echo htmlspecialchars($option); ?>
                        </label><br>
                    <?php endforeach; ?>
                <?php elseif ($q['type'] == 'multiple'): ?>
                    <?php foreach ($q['options'] as $option): ?>
                        <label>
                            <input type="checkbox" name="answers[<?php echo $q['id']; ?>][]" value="<?php echo htmlspecialchars($option); ?>">
                            <?php echo htmlspecialchars($option); ?>
                        </label><br>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        <button type="submit">Завершить тест</button>
    </form>
</body>
</html>