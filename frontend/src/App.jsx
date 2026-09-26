import { useEffect, useState } from "react";
import { apiFetch } from "./api";
import Login from "./components/Login";
import Register from "./components/Register";
import "./App.css";

const TOKEN_KEY = "token";

function App() {
  const [token, setToken] = useState(() => localStorage.getItem(TOKEN_KEY));
  const [user, setUser] = useState(null);
  const [cargando, setCargando] = useState(() =>
    Boolean(localStorage.getItem(TOKEN_KEY)),
  );
  const [vista, setVista] = useState("login");

  // Al abrir la app, si hay un token guardado, revisamos que siga siendo válido
  useEffect(() => {
    if (!token) return;

    apiFetch("/me", { token })
      .then(setUser)
      .catch(() => cerrarSesionLocal())
      .finally(() => setCargando(false));
  }, [token]);

  function iniciarSesion({ user, token }) {
    localStorage.setItem(TOKEN_KEY, token);
    setToken(token);
    setUser(user);
  }

  function cerrarSesionLocal() {
    localStorage.removeItem(TOKEN_KEY);
    setToken(null);
    setUser(null);
  }

  async function cerrarSesion() {
    try {
      await apiFetch("/logout", { method: "POST", token });
    } finally {
      cerrarSesionLocal();
      setVista("login");
    }
  }

  if (cargando) {
    return <p className="centrado">Cargando...</p>;
  }

  // Sin sesión: solo se puede ver login o registro
  if (!token || !user) {
    return (
      <main className="auth-page">
        {vista === "login" ? (
          <Login
            onLogin={iniciarSesion}
            onIrARegistro={() => setVista("registro")}
          />
        ) : (
          <Register
            onRegister={iniciarSesion}
            onIrALogin={() => setVista("login")}
          />
        )}
      </main>
    );
  }

  // Con sesión: la app
  return (
    <div className="app">
      <header className="topbar">
        <h1>Visualizador de Avance Curricular</h1>
        <div className="usuario">
          <span>Hola, {user.name}</span>
          <button onClick={cerrarSesion}>Cerrar sesión</button>
        </div>
      </header>

      <main className="contenido">
        <p>Aquí va la lista de materias (HU-01).</p>
      </main>
    </div>
  );
}

export default App;
