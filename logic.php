<?php
/**
 * Загружает вопросы из JSON
 * @return array
 */
function loadQuestions() {
    $json = file_get_contents('data/questions.json');
    return json_decode($json, true);
}

/**
 * Загружает результаты из JSON
 * @return array
 */
function loadResults() {
    if (file_exists('data/answers.json')) {
        $json = file_get_contents('data/answers.json');
    } else {
        $json = '[]';
    }
    return json_decode($json, true);
}

/**
 * Сохраняет результат теста
 * @param string $username Имя пользователя
 * @param int $correct Количество правильных ответов
 * @param int $total Всего вопросов
 * @param float $percentage Процент правильных ответов
 */
function saveResult($username, $correct, $total, $percentage) {
    $results = loadResults();
    $results[] = [
        'username' => $username,
        'correct' => $correct,
        'total' => $total,
        'percentage' => $percentage,
        'date' => date('Y-m-d H:i:s')
    ];
    file_put_contents('data/results.json', json_encode($results, JSON_PRETTY_PRINT));
}

/**
 * Проверяет правильный ввод данных
 * @param array $data Входные данные
 * @return bool
 */
function validateInput($data) {
    return !empty($data['username']) && isset($data['answers']);
}
?>