<?php
try {
    new PDO("mysql:host=" . getenv('DB_HOST') . ";dbname=" . getenv('DB_NAME'),
            getenv('DB_USER'), getenv('DB_PASSWORD'));
    echo "OK";
} catch (Exception $e) {
    http_response_code(500);
    echo "DB non raggiungibile";
}