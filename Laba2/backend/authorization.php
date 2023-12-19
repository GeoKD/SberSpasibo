<?php

session_start();

$login = isset($_POST['login']) ? $_POST['login'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (empty($login) || empty($password)) {
    $errors = array('login' => 'Логин и пароль не могут быть пустыми');
    echo json_encode(array('status' => 'error', 'errors' => $errors), JSON_UNESCAPED_UNICODE);
    return;
}

$fileData = file_get_contents('data/data.json');
$users = json_decode($fileData, true);

if (empty($users)) {
    $errors = array('login' => 'Не одного пользователя ещё не зарегистрировано');
    echo json_encode(array('status' => 'error', 'errors' => $errors), JSON_UNESCAPED_UNICODE);
    return;
}

$userFound = false;
$userData = array();
foreach ($users as $user) {
    if ($user['login'] === $login && $user['password'] === $password) {
        $userFound = true;
        $userData = $user;
        break;
    }
}

if ($userFound) {
    $_SESSION['user'] = $userData;
    $response = array('status' => 'success', 'user' => $userData);
    echo json_encode($response);
} else {
    $errors = array('login' => 'Неверный логин или пароль');
    echo json_encode(array('status' => 'error', 'errors' => $errors), JSON_UNESCAPED_UNICODE);
}