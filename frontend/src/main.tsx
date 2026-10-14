import { createRoot } from 'react-dom/client';
import { useEffect, useState } from 'react';
import './style.css';

type Product = {
  code: string;
  name: string;
  priceInCents: number;
};

function formatMoney(cents: number): string {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
  }).format(cents / 100);
}

function App() {
  const [products, setProducts] = useState<Product[]>([]);
  const [basketCodes, setBasketCodes] = useState<string[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [totalInCents, setTotalInCents] = useState<number | null>(0);
  const [totalError, setTotalError] = useState<string | null>(null);
  const [catalogueAttempt, setCatalogueAttempt] = useState(0);
  const [totalAttempt, setTotalAttempt] = useState(0);

  function retryProducts() {
    setError(null);
    setLoading(true);
    setCatalogueAttempt(attempt => attempt + 1);
  }

  function retryTotal() {
    setTotalError(null);
    setTotalInCents(null);
    setTotalAttempt(attempt => attempt + 1);
  }

  function addToBasket(code: string) {
    setTotalInCents(null);
    setTotalError(null);
    setBasketCodes(currentCodes => [...currentCodes, code]);
  }

  useEffect(() => {
    const controller = new AbortController();

    async function loadProducts() {
      try {
        const response = await fetch('/api/products', { signal: controller.signal });
        if (!response.ok) {
          throw new Error('Could not load products. Please try again.');
        }

        const data: { products: Product[] } = await response.json();
        setProducts(data.products);
      } catch (error) {
        if (!controller.signal.aborted) {
          setError(error instanceof Error ? error.message : 'Could not load products.');
        }
      } finally {
        if (!controller.signal.aborted) {
          setLoading(false);
        }
      }
    }

    void loadProducts();
    return () => controller.abort();
  }, [catalogueAttempt]);

  useEffect(() => {
    if (basketCodes.length === 0) {
      return;
    }

    const controller = new AbortController();

    async function calculateTotal() {
      try {
        const response = await fetch('/api/basket', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ items: basketCodes }),
          signal: controller.signal,
        });

        if (!response.ok) {
          throw new Error('Could not calculate the total. Please try again.');
        }

        const data: { totalInCents: number } = await response.json();
        if (!controller.signal.aborted) {
          setTotalInCents(data.totalInCents);
        }
      } catch (error) {
        if (!controller.signal.aborted) {
          setTotalError(error instanceof Error ? error.message : 'Could not calculate the total.');
        }
      }
    }

    void calculateTotal();
    return () => controller.abort();
  }, [basketCodes, totalAttempt]);

  const basketItems = products
    .map(product => {
      const quantity = basketCodes.filter(code => code === product.code).length;
      return { product, quantity };
    })
    .filter(item => item.quantity > 0);

  return (
    <main>
      <p className="eyebrow">Acme Widget Co</p>
      <h1>A widget for every idea.</h1>
      <p>Buy one red widget and get the second half price.</p>

      {loading && <p role="status">Loading products…</p>}
      {error && (
        <div>
          <p role="alert">{error}</p>
          <button type="button" onClick={retryProducts}>Retry loading products</button>
        </div>
      )}

      <section className="products" aria-label="Products">
        {products.map(product => (
          <article className="product" key={product.code}>
            <h2>{product.name}</h2>
            <p>{product.code}</p>
            <strong>{formatMoney(product.priceInCents)}</strong>
            <button type="button" onClick={() => addToBasket(product.code)}>
              Add to basket
            </button>
          </article>
        ))}
      </section>

      <section className="basket" aria-labelledby="basket-heading">
        <h2 id="basket-heading">Your basket ({basketCodes.length} items)</h2>
        {basketCodes.length === 0 ? (
          <p>Your basket is empty.</p>
        ) : (
          <ul>
            {basketItems.map(item => (
              <li key={item.product.code}>
                {item.product.name} × {item.quantity}
              </li>
            ))}
          </ul>
        )}
        {totalError ? (
          <div>
            <p role="alert">{totalError}</p>
            <button type="button" onClick={retryTotal}>Retry calculation</button>
          </div>
        ) : totalInCents === null ? (
          <p role="status">Calculating total…</p>
        ) : (
          <p className="total">Total including delivery: <strong>{formatMoney(totalInCents)}</strong></p>
        )}
      </section>
    </main>
  );
}

createRoot(document.getElementById('root')!).render(<App />);
