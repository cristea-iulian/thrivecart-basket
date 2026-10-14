<?php

declare(strict_types=1);

use Acme\Basket;
use Acme\Product;
use Acme\ProductCatalogue;
use Acme\SecondItemHalfPrice;
use Acme\StandardDelivery;

require dirname(__DIR__) . '/vendor/autoload.php';

function respond(array $data, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_THROW_ON_ERROR);
    exit;
}

$catalogue = new ProductCatalogue(
    new Product('R01', 'Red Widget', 3295),
    new Product('G01', 'Green Widget', 2495),
    new Product('B01', 'Blue Widget', 795),
);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

if ($path === '/products') {
    if ($method !== 'GET') {
        header('Allow: GET');
        respond(['error' => 'Method not allowed'], 405);
    }

    respond(['products' => $catalogue->all()]);
}

if ($path === '/basket') {
    if ($method !== 'POST') {
        header('Allow: POST');
        respond(['error' => 'Method not allowed'], 405);
    }

    try {
        $input = json_decode(file_get_contents('php://input'), false, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException) {
        respond(['error' => 'Invalid JSON'], 400);
    }

    if (!$input instanceof stdClass || !isset($input->items) || !is_array($input->items)) {
        respond(['error' => 'Expected an object with an items array of product codes'], 422);
    }

    $basket = new Basket($catalogue, new StandardDelivery(), new SecondItemHalfPrice('R01'));

    try {
        foreach ($input->items as $code) {
            if (!is_string($code)) {
                respond(['error' => 'Each product code must be a string'], 422);
            }

            $basket->add($code);
        }
    } catch (InvalidArgumentException $exception) {
        respond(['error' => $exception->getMessage()], 422);
    }

    respond(['totalInCents' => $basket->total()]);
}

respond(['error' => 'Not found'], 404);
