<?php

$name = isset($_POST['name']) ? $_POST['name'] : '';
$surname = isset($_POST['surname']) ? $_POST['surname'] : '';
$sex = isset($_POST['sex']) ? $_POST['sex'] : '';
$login = isset($_POST['login']) ? $_POST['login'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

$errors = array();

if (empty($name)) {
    $errors['name'] = "Поле 'Имя' не должно быть пустым";
}

if (empty($surname)) {
    $errors['surname'] = "Поле 'Фамилия' не должно быть пустым";
}

if ($sex === "ns") {
    $errors['sex'] = "Поле 'Пол' не должно быть пустым";
}

if (empty($login)) {
    $errors['login'] = "Поле 'Логин' не должно быть пустым";
}

if (empty($password)) {
    $errors['password'] = "Поле 'Пароль' не должно быть пустым";
}

if (count($errors) != 0) {
    echo json_encode(array('status' => 'error', 'errors' => $errors), JSON_UNESCAPED_UNICODE);
    return;
}

$russian = '/^[а-яА-Я]+$/u';
$invalid_characters = '/\W+/u';

if (strlen($name) > 0 && !preg_match($russian, $name)) {
    $errors['name'] = "Имя должно состоять только из кириллицы";
}

if (strlen($surname) > 0 && !preg_match($russian, $surname)) {
    $errors['surname'] = "Фамилия должна состоять только из кириллицы";
}

if (strlen($login) > 0 && preg_match($invalid_characters, $login)) {
    $errors['login'] = "Логин содержит запрещённые символы";
}

if (strlen($password) > 0 && strlen($password) < 8) {
    $errors['password'] = "Длина пароля должна быть более 8 символов";
}

if (count($errors) != 0) {
    echo json_encode(array('status' => 'error', 'errors' => $errors), JSON_UNESCAPED_UNICODE);
    return;
}

$fileData = file_get_contents('data/data.json');
$users = json_decode($fileData, true);

if (empty($users)) {
    $applications = array();

    $newUser = array(
        'name' => $name,
        'surname' => $surname,
        'sex' => $sex,
        'login' => $login,
        'password' => $password,
        'applications' => $applications 
    );

    $users[] = $newUser;

    $jsonData = json_encode($users, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    if (file_put_contents('data/data.json', $jsonData) !== false) {
        session_start();
        $_SESSION['user'] = $newUser;
        $response = array('status' => 'success', 'user' => $newUser);
        echo json_encode($response);
    } else {
        $response = array('status' => 'error', 'message' => 'Ошибка записи в файл');
        echo json_encode($response);
    }
} else {
    foreach ($users as $user) {
        if ($user['login'] === $login) {
            $errors['login'] = "Пользователь с таким логином уже существует";
            echo json_encode(array('status' => 'error', 'errors' => $errors), JSON_UNESCAPED_UNICODE);
            return;
        }
    }

    $applications = array();

    $newUser = array(
        'name' => $name,
        'surname' => $surname,
        'sex' => $sex,
        'login' => $login,
        'password' => $password,
        'applications' => $applications 
    );

    $users[] = $newUser;

    $jsonData = json_encode($users, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    if (file_put_contents('data/data.json', $jsonData) !== false) {
        session_start();
        $_SESSION['user'] = $newUser;
        $response = array('status' => 'success', 'user' => $newUser);
        echo json_encode($response);
    } else {
        $response = array('status' => 'error', 'message' => 'Ошибка записи в файл');
        echo json_encode($response);
    }
}