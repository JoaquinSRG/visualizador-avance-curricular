import { useState } from "react";
import { apiFetch } from "../api";

export default function Login({ onLogin, onIrARegistro }) {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const [enviando, setEnviando] = useState(false);

  async function handleSubmit(e) {
    e.preventDefault();
    setError("");
    setEnviando(true);

    try {
      const data = await apiFetch("/login", {
        method: "POST",
        body: { email, password },
      });
      onLogin(data);
    } catch (err) {
      setError(err.errors?.email?.[0] ?? err.message);
    } finally {
      setEnviando(false);
    }
  }

  return (
    <form className="auth-card" onSubmit={handleSubmit}>
      <h1>Iniciar sesión</h1>

      <label>
        Correo
        <input
          type="email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
          required
        />
      </label>

      <label>
        Contraseña
        <input
          type="password"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
          required
        />
      </label>

      {error && <p className="error">{error}</p>}

      <button type="submit" disabled={enviando}>
        {enviando ? "Entrando..." : "Entrar"}
      </button>

      <p className="cambiar">
        ¿No tienes cuenta?{" "}
        <button type="button" className="link" onClick={onIrARegistro}>
          Regístrate
        </button>
      </p>
    </form>
  );
}
