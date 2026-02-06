<?php
require_once 'UUID.php';
// Endpoint для генерации UUID
if (isset($_GET['version'])) {
    header('Content-Type: application/json');
    $version = $_GET['version'];
    $count = isset($_GET['count']) ? (int)$_GET['count'] : 1;
    $uuids = [];

    for ($i = 0; $i < $count; $i++) {
        switch ($version) {
            case 'v4':
                $uuids[] = UUID::getV4();
                break;
            case 'v3':
                $namespace = $_GET['namespace'] ?? '6ba7b811-9dad-11d1-80b4-00c04fd430c8';
                $name = $_GET['name'] ?? 'example';
                $uuids[] = UUID::getV3($namespace, $name);
                break;
            case 'v5':
                $namespace = $_GET['namespace'] ?? '6ba7b811-9dad-11d1-80b4-00c04fd430c8';
                $name = $_GET['name'] ?? 'example';
                $uuids[] = UUID::getV5($namespace, $name);
                break;
            default:
                echo json_encode(['error' => 'Unsupported version']);
                exit;
        }
    }

    echo json_encode(['uuids' => $uuids]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Генератор UUID</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 90%;
            max-width: 600px;
        }
        
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-weight: 600;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }
        
        select, input, button {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }
        
        select:focus, input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            cursor: pointer;
            font-weight: 600;
            margin-top: 10px;
            transition: transform 0.2s ease;
        }
        
        button:hover {
            transform: translateY(-2px);
        }
        
        button:active {
            transform: translateY(0);
        }
        
        #v3v5-fields {
            display: none;
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        textarea {
            width: 100%;
            height: 200px;
            padding: 12px;
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            resize: vertical;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }
        
        textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .copy-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
            transition: background 0.3s ease;
        }
        
        .copy-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Генератор UUID</h1>
        
        <div class="form-group">
            <label for="version">Версия UUID:</label>
            <select id="version" onchange="toggleFields()">
                <option value="v4">UUID v4 (случайный)</option>
                <option value="v3">UUID v3 (на основе имени и пространства имён)</option>
                <option value="v5">UUID v5 (на основе имени и пространства имён, SHA-1)</option>
            </select>
        </div>
        
        <div id="v3v5-fields">
            <div class="form-group">
                <label for="namespace">Пространство имён:</label>
                <input type="text" id="namespace" value="6ba7b811-9dad-11d1-80b4-00c04fd430c8">
            </div>
            
            <div class="form-group">
                <label for="name">Имя:</label>
                <input type="text" id="name" value="example">
            </div>
        </div>
        
        <div class="form-group">
            <label for="count">Количество UUID:</label>
            <input type="number" id="count" value="1" min="1" max="100">
        </div>
        
        <button onclick="generateUUID()">Сгенерировать UUID</button>
        
        <div class="form-group">
            <label for="result">Результат:</label>
            <textarea id="result" readonly></textarea>
            <button class="copy-btn" onclick="copyToClipboard()">Копировать в буфер обмена</button>
        </div>
    </div>

    <script>
        // Функция для переключения видимости полей v3/v5
        function toggleFields() {
            const version = document.getElementById('version').value;
            const fields = document.getElementById('v3v5-fields');
            
            if (version === 'v3' || version === 'v5') {
                fields.style.display = 'block';
            } else {
                fields.style.display = 'none';
            }
        }

        // Основная функция генерации UUID
        async function generateUUID() {
            const version = document.getElementById('version').value;
            const count = document.getElementById('count').value;
            const namespace = document.getElementById('namespace').value;
            const name = document.getElementById('name').value;
            const resultArea = document.getElementById('result');
            
            // Показываем индикатор загрузки
            resultArea.value = 'Генерация...';
            
            try {
                // Формируем URL с параметрами
                let url = `?version=${version}&count=${count}`;
                
                if (version === 'v3' || version === 'v5') {
                    url += `&namespace=${encodeURIComponent(namespace)}&name=${encodeURIComponent(name)}`;
                }
                
                // Выполняем запрос к серверу
                const response = await fetch(url);
                const data = await response.json();
                
                if (data.error) {
                    resultArea.value = `Ошибка: ${data.error}`;
                    return;
                }
                
                // Отображаем результат
                resultArea.value = data.uuids.join('\n');
                
            } catch (error) {
                resultArea.value = `Ошибка при генерации: ${error.message}`;
            }
        }

        // Функция копирования в буфер обмена
        function copyToClipboard() {
            const resultArea = document.getElementById('result');
            
            if (resultArea.value.trim() === '' || resultArea.value === 'Генерация...') {
                alert('Нечего копировать! Сначала сгенерируйте UUID.');
                return;
            }
            
            resultArea.select();
            document.execCommand('copy');
            
            // Визуальная обратная связь
            const copyBtn = document.querySelector('.copy-btn');
            const originalText = copyBtn.textContent;
            copyBtn.textContent = 'Скопировано!';
            
            setTimeout(() => {
                copyBtn.textContent = originalText;
            }, 2000);
        }

        // Инициализация при загрузке страницы
        document.addEventListener('DOMContentLoaded', function() {
            toggleFields(); // Установить начальное состояние полей
        });
    </script>
</body>
</html>