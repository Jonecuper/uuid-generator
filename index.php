<?php

// Подключаем файл с классом UUID
require_once 'UUID.php';

// Генерируем UUID версии 4
$uuid4 = UUID::getV4();

// Устанавливаем заголовок ответа в формате JSON
header('Content-Type: application/json');

// Возвращаем UUID в формате JSON
echo json_encode(['uuid' => $uuid4]);
