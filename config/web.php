<?php

$config = [
    'appDir' => APP_DIR,    // Папка с рабочими файлами.
    'webDir' => WEB_DIR,    // Папка доступная из web.
    'site' => [
        'name' => 'Галерея изображений',    // Название сайта (можно использовать в заголовке).
    ],
    'log' => [
        'types' => [
            'all'   // 'all' - Сохранение всех типов логов.
        ]
    ],
    'gallery' => [
        // Максимальная высота изображения (в пикселях).
        'image_max_height' => 200,
        // Отступ между изображениями (в пикселях).
        'gap' => 8,
    ],
    'globalEncoding' => 'UTF-8',
    // Отладочный режим.
    'debug' => true,
];

// Переключение отладочного режима вручную (надо закомментировать на продакшене).
$config['debug'] = isset($_GET['debug']) ? ($_GET['debug'] ? true : false) : $config['debug'];

return $config;
