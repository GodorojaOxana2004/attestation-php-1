<?php
/**
 * Страница обработки и отображения результатов теста
 * Подсчитывает правильные ответы и сохраняет результаты
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

/**
 * Загружает правильные ответы из JSON-файла
 * @return array Массив правильных ответов
 */
function loadAnswers() {
    $file = 'data/answers.json';
    if (!file_exists($file)) {
        die("Файл с ответами не найден!");
    }
    return json_decode(file_get_contents($file), true);
}

/**
 * Валидирует входные данные пользователя
 * @param array $data Данные из формы
 * @return bool Результат валидации
 */
function validateInput($data) {
    return isset($data['name']) && !empty($data['name']) && isset($data['answers']) && is_array($data['answers']);
}

/**
 * Подсчитывает количество правильных ответов
 * @param array $userAnswers Ответы пользователя
 * @param array $correctAnswers Правильные ответы
 * @return array Результаты: правильные ответы и общее количество вопросов
 */
function calculateResults($userAnswers, $correctAnswers) {
    $correct = 0;
    $total = count($correctAnswers);
    foreach ($correctAnswers as $ca) {
        $userAnswer = $userAnswers[$ca['id']] ?? (is_array($ca['correct']) ? [] : '');
        if (is_array($ca['correct'])) {
            // Для вопросов с несколькими ответами (checkbox)
            $userAnswer = is_array($userAnswer) ? $userAnswer : [];
            sort($userAnswer);
            sort($ca['correct']);
            if ($userAnswer === $ca['correct']) {
                $correct++;
            }
        } else {
            // Для вопросов с одним ответом (radio)
            if ($userAnswer === $ca['correct']) {
                $correct++;
            }
        }
    }
    return ['correct' => $correct, 'total' => $total];
}

/**
 * Сохраняет результаты пользователя в JSON-файл
 * @param string $name Имя пользователя
 * @param float $percent Процент правильных ответов
 */
function saveResult($name, $percent) {
    $file = 'data/results.json';
    $results = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    $results[] = ['name' => $name, 'score' => $percent];
    file_put_contents($file, json_encode($results));
}

// Обработка данных
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !validateInput($_POST)) {
    die("Ошибка: данные не отправлены или некорректны!");
}

$questions = loadQuestions();
$correctAnswers = loadAnswers();
$userAnswers = $_POST['answers'];
$result = calculateResults($userAnswers, $correctAnswers);
$percent = ($result['correct'] / $result['total']) * 100;
saveResult($_POST['name'], $percent);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Результаты</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Ваши результаты</h1>
    <div id="result-container">
        <p>Правильных ответов: <?php echo $result['correct']; ?> из <?php echo $result['total']; ?></p>
        <p>Процент: <?php echo round($percent, 2); ?>%</p>
        <a href="index.php" class="button">Вернуться на главную</a>
    </div>
</body>
</html>