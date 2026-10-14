# Acme Widget Co basket

ThriveCart coding exercise — PHP backend and React/TypeScript frontend.

**Status: environment scaffold only. Basket logic, integration and business tests are not implemented.**

## Requirements

- PHP 8.2+ and Composer (8.2 is a local setup choice; the assignment only asks for modern PHP)
- Node.js 20.19+ or 22.12+ and npm

## Install

```sh
composer install
cd frontend
npm ci
```

## Run

From the repository root, in separate terminals:

```sh
composer serve
```

```sh
cd frontend
npm run dev
```

Open the URL printed by Vite (normally http://127.0.0.1:5173).
The PHP placeholder runs at http://127.0.0.1:8080. Vite proxies `/api` to PHP.

## Checks

```sh
composer test
cd frontend
npm run build
```

No business tests have been written yet; PHPUnit currently reports an empty suite.

## Assignment

Implement a basket initialised with a product catalogue, delivery rules and offers.
Provide `add(productCode)` and `total()`.

| Product | Code | Price |
| --- | --- | --- |
| Red Widget | R01 | $32.95 |
| Green Widget | G01 | $24.95 |
| Blue Widget | B01 | $7.95 |

Delivery: under $50 → $4.95; $50 to under $90 → $2.95; $90+ → free.
Offer: buy one red widget, get the second half price.

| Products | Expected total |
| --- | --- |
| B01, G01 | $37.85 |
| R01, R01 | $54.37 |
| R01, G01 | $60.85 |
| B01, B01, R01, R01, R01 | $98.27 |

Build a simple React/TypeScript UI. Explain design and assumptions here when implemented.
The brief expects a public GitHub repository. The email recommends 2–4 hours and highlights separation of concerns, small interfaces/components, dependency injection, extensible strategies and meaningful commits.

## Decisions to document during implementation

- Money representation and rounding: the supplied examples include half-cent discounts.
- Whether delivery thresholds use the discounted subtotal.
- Invalid product codes and empty-basket behaviour.
- How multiple offers compose.

## Implementation checklist

- [ ] Catalogue and basket model
- [ ] Offer and delivery rules
- [ ] Four supplied examples and useful edge-case tests
- [ ] PHP endpoint and React basket interface
- [ ] Document assumptions and trade-offs
- [ ] Review, commit and submit the completed repository
