import { useCallback, useEffect, useState } from "react";
import { apiFetch } from "./api";
import Login from "./components/Login";
import Register from "./components/Register";
import MateriasList from "./components/MateriasList";
import Kardex from "./components/Kardex";
import Seriacion from "./components/Seriacion";
import Propuesta from "./components/Propuesta";
import "./App.css";

const TOKEN_KEY = "token";

// HU-12: secciones del menú principal
const SECCIONES = [
  { valor: "plan", texto: "Plan de estudios" },
  { valor: "kardex", texto: "Kárdex" },
  { valor: "seriacion", texto: "Requisitos y seriación" },
  { valor: "propuesta", texto: "Propuesta de inscripción" },
];

function App() {
  const [token, setToken] = useState(() => localStorage.getItem(TOKEN_KEY));
  const [user, setUser] = useState(null);
  const [cargando, setCargando] = useState(() =>
    Boolean(localStorage.getItem(TOKEN_KEY)),
  );
  const [vista, setVista] = useState("login");
  const [seccion, setSeccion] = useState("plan");
  const [materiaSeleccionada, setMateriaSeleccionada] = useState(null);

  // Borra la sesión solo en el navegador
  const cerrarSesionLocal = useCallback(() => {
    localStorage.removeItem(TOKEN_KEY);
    setToken(null);
    setUser(null);
  }, []);

  // Al abrir la app, si hay un token guardado, revisamos que siga siendo válido
  useEffect(() => {
    if (!token) return;

    apiFetch("/me", { token })
      .then(setUser)
      .catch(() => cerrarSesionLocal())
      .finally(() => setCargando(false));
  }, [token, cerrarSesionLocal]);

  function iniciarSesion({ user, token }) {
    localStorage.setItem(TOKEN_KEY, token);
    setToken(token);
    setUser(user);
  }

  // Cierra la sesión en Laravel y en el navegador
  async function cerrarSesion() {
    try {
      await apiFetch("/logout", { method: "POST", token });
    } finally {
      cerrarSesionLocal();
      setVista("login");
      setSeccion("plan");
    }
  }

  // Desde el plan de estudios se puede abrir la seriación de una materia
  function verSeriacion(materiaId) {
    setMateriaSeleccionada(materiaId);
    setSeccion("seriacion");
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

      <nav className="menu" aria-label="Secciones">
        {SECCIONES.map(({ valor, texto }) => (
          <button
            key={valor}
            type="button"
            className={`menu-opcion${seccion === valor ? " menu-activo" : ""}`}
            aria-current={seccion === valor ? "page" : undefined}
            onClick={() => setSeccion(valor)}
          >
            {texto}
          </button>
        ))}
      </nav>

      <main className="contenido">
        {seccion === "plan" && (
          <MateriasList
            token={token}
            onSesionExpirada={cerrarSesionLocal}
            onVerSeriacion={verSeriacion}
          />
        )}
        {seccion === "kardex" && (
          <Kardex token={token} onSesionExpirada={cerrarSesionLocal} />
        )}
        {seccion === "seriacion" && (
          <Seriacion
            token={token}
            onSesionExpirada={cerrarSesionLocal}
            materiaInicial={materiaSeleccionada}
          />
        )}
        {seccion === "propuesta" && (
          <Propuesta token={token} onSesionExpirada={cerrarSesionLocal} />
        )}
      </main>
    </div>
  );
}

export default App;
