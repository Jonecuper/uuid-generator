<?php

// Подключаем файл с классом UUID
require_once '../UUID.php';

// Устанавливаем заголовок ответа в формате JSON
header('Content-Type: application/json');

// Проверяем, переданы ли параметры namespace и name
if (!isset($_GET['namespace']) || !isset($_GET['name'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Parameters "namespace" and "name" are required for UUIDv5']);
    exit;
}

$namespace = $_GET['namespace'];
$name = $_GET['name'];

// Генерируем UUID версии 5
$uuid5 = UUID::getV5($namespace, $name);

// Проверяем, корректен ли сгенерированный UUID
if (!$uuid5) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid namespace format']);
    exit;
}


// Возвращаем UUID в формате JSON
echo json_encode(['uuid' => $uuid5]);
