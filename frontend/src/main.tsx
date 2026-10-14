import { createRoot } from 'react-dom/client';
import './style.css';

function App() {
  return <main><p className="eyebrow">Coding exercise</p><h1>Acme Widget Co</h1><p>Project setup is ready. Basket functionality is not implemented yet.</p></main>;
}

createRoot(document.getElementById('root')!).render(<App />);
