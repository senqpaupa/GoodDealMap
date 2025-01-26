<?php

return [
    /*
     * Разрешенные пути для CORS
     */
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'register', 'logout'],

    /*
     * Разрешенные методы запросов
     */
    'allowed_methods' => ['*'],

    /*
     * Разрешенные origins (домены)
     */
    'allowed_origins' => ['http://localhost:5173'],

    /*
     * Разрешенные паттерны origins
     */
    'allowed_origins_patterns' => [],

    /*
     * Разрешенные заголовки
     */
    'allowed_headers' => ['*'],

    /*
     * Заголовки, которые можно показывать
     */
    'exposed_headers' => [],

    /*
     * Максимальное время кэширования preflight-запросов
     */
    'max_age' => 0,

    /*
     * Отправлять credentials с запросами
     */
    'supports_credentials' => true,
]; 