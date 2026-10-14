# Acme Widget Co

PHP basket with a simple React/TypeScript UI. You can add products, see quantities and get the total with discounts and delivery included.

## Setup

You need PHP 8.2+, Composer and Node.js 20.19+ or 22.12+.

From the project root:

```sh
composer install
cd frontend
npm ci
```

## Run

Open two terminals. In the first, from the project root:

```sh
composer serve
```

In the second, from the project root:

```sh
cd frontend
npm run dev
```

Open http://127.0.0.1:5173 (or the URL Vite prints).
PHP runs on port 8081. Both processes need to stay running.

## Tests

From the project root:

```sh
composer test
```

To check TypeScript and build the frontend:

```sh
cd frontend
npm run build
```

Tests cover the examples from the brief, delivery thresholds, discount pairs, unknown products and an empty basket.

## How it works

`Basket` receives a product catalogue, a delivery rule and optional offers. `add()` takes a product code and `total()` returns the total in cents.

The offer and delivery rules are separate from the basket so they can be changed without changing its calculation flow.

React keeps the selected product codes and sends them to PHP. Prices, discounts and delivery are calculated on the backend. There is no database or session; refreshing the page clears the basket.

## Assumptions

- Money is stored as integer cents.
- The half-price item is rounded down: half of $32.95 becomes $16.47. This matches the supplied totals.
- Delivery is based on the subtotal after discounts.
- An empty basket costs $0, with no delivery charge.
- Unknown product codes are rejected.
- Multiple offers add their discounts. If offers target the same products, we would need to agree on whether they can be combined.

The UI only adds products and shows the basket. I kept checkout, removal and persistence out of scope.
