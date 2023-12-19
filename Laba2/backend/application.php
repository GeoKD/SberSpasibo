<?php
 
session_start();

$age = $_POST['age'] ?? '';
$city = $_POST['city'] ?? '';
$variant = $_POST['variant'] ?? '';
$study = $_POST['study'] ?? '';
$me = $_POST['me'] ?? '';

$errors = [];

if (empty($age)) {
    $errors['age'] = "Поле 'Возраст' не должно быть пустым";
} else if (!is_numeric($age) || $age < 18 || $age > 35) {
    $errors['age'] = "Поле 'Возраст' должно быть числом от 18 до 35";
}

if (empty($city)) {
    $errors['city'] = "Поле 'Город' не должно быть пустым";
} else if (!preg_match('/^[А-ЯЁ][а-яё]*$/u', $city)) {
    $errors['city'] = "Поле 'Город' должно состоять только из русских букв и начинаться с заглавной буквы";
}

if (empty($variant)) {
    $errors['variant'] = "Поле 'Направление конкурса' должно быть выбрано";
}

if (empty($study)) {
    $errors['study'] = "Поле 'Ваше образование' должно быть выбрано";
}

if (empty($me)) {
    $errors['me'] = "Поле 'Краткое описание о себе' не должно быть пустым";
} else if (str_word_count($me) > 50) {
    $errors['me'] = "Поле 'Краткое описание о себе' должно содержать не более 50 слов";
}

if (!empty($errors)) {
    echo json_encode(['status' => 'error', 'errors' => $errors], JSON_UNESCAPED_UNICODE);
    return;
}

$newUser = $_SESSION['user'];

$applicationData = compact('age', 'city', 'variant', 'study', 'me');

$existingApplicationIndex = array_search($applicationData, $newUser['applications'], true);

if ($existingApplicationIndex !== false) {
    $applicationNumber = $existingApplicationIndex + 1;
    $response = ['status' => 'error', 'message' => 'Такая заявка уже существует', 'applicationNumber' => $applicationNumber];
    echo json_encode($response);
    return;
}

$newUser['applications'][] = $applicationData;

$_SESSION['user'] = $newUser;

$fileData = file_get_contents('data/data.json');
$users = json_decode($fileData, true);

$userFound = false;
foreach ($users as &$user) {
    if ($user['login'] === $newUser['login']) {
        $userFound = true;
        $user = $newUser;
        break;
    }
}

if ($userFound) {
    $jsonData = json_encode($users, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    if (file_put_contents('data/data.json', $jsonData) !== false) {
        $response = ['status' => 'success', 'user' => $newUser];
        echo json_encode($response);
    } else {
        $response = ['status' => 'error', 'message' => 'Ошибка записи в файл'];
        echo json_encode($response);
    }
} else {
    $response = ['status' => 'error', 'message' => 'Пользователь не найден'];
    echo json_encode($response);
}