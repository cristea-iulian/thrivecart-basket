<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

header('Content-Type: application/json');
echo json_encode(['status' => 'ready', 'message' => 'Basket API not implemented yet'], JSON_THROW_ON_ERROR);
