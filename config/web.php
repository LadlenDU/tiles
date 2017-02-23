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
    'image' => [
        // Максимальный размер загружаемого изображения.
        'max_file_size' => '2097152',   // 2M - должно быть не больше чем upload_max_filesize в php.ini
        #'max_file_size' => '52',
        // Расширения поддерживаемых типов изображений.
        'types_allowed_extension' => ['jpg', 'jpeg', 'gif', 'png'],
        // MIME типы поддерживаемых изображений.
        'types_allowed_mime' => ['image/jpeg', 'image/pjpeg', 'image/gif', 'image/png', 'image/x-png'],
        // Максимальный размер миниатюры.
        'max_thumb_size' => ['width' => 150, 'height' => 150],
    ],
    'globalEncoding' => 'UTF-8',
    'csrf' => [
        'salt' => 'pfbr6as',
        'tokenName' => '_csrf',
    ],
    // Отладочный режим.
    'debug' => true,
];

// Переключение отладочного режима вручную (надо закомментировать на продакшене).
$config['debug'] = isset($_GET['debug']) ? ($_GET['debug'] ? true : false) : $config['debug'];

return $config;
