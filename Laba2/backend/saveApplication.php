<?php

session_start();

$fileData = file_get_contents('data/data.json');
$users = json_decode($fileData, true);

if (!isset($_SESSION['user'])) {
    echo json_encode(array('status' => 'error', 'message' => 'Пользователь не авторизован'), JSON_UNESCAPED_UNICODE);
    return;
}

$login = $_SESSION['user']['login'];

$userFound = false;
foreach ($users as $user) {
    if ($user['login'] === $login) {
        $userFound = true;
        $applications = $user['applications'];
        break;
    }
}

$response = array('status' => 'success', 'applications' => $applications);
echo json_encode($response);