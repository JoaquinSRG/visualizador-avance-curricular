import { useState } from "react";
import { apiFetch } from "../api";

export default function Register({ onRegister, onIrALogin }) {
  const [form, setForm] = useState({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
  });
  const [error, setError] = useState("");
  const [enviando, setEnviando] = useState(false);

  function handleChange(e) {
    setForm({ ...form, [e.target.name]: e.target.value });
  }

  async function handleSubmit(e) {
    e.preventDefault();
    setError("");
    setEnviando(true);

    try {
      const data = await apiFetch("/register", { method: "POST", body: form });
      onRegister(data);
    } catch (err) {
      const primerError = Object.values(err.errors).flat()[0];
      setError(primerError ?? err.message);
    } finally {
      setEnviando(false);
    }
  }

  return (
    <form className="auth-card" onSubmit={handleSubmit}>
      <h1>Crear cuenta</h1>

      <label>
        Nombre
        <input name="name" value={form.name} onChange={handleChange} required />
      </label>

      <label>
        Correo
        <input
          type="email"
          name="email"
          value={form.email}
          onChange={handleChange}
          required
        />
      </label>

      <label>
        Contraseña
        <input
          type="password"
          name="password"
          value={form.password}
          onChange={handleChange}
          minLength={8}
          required
        />
      </label>

      <label>
        Confirmar contraseña
        <input
          type="password"
          name="password_confirmation"
          value={form.password_confirmation}
          onChange={handleChange}
          minLength={8}
          required
        />
      </label>

      {error && <p className="error">{error}</p>}

      <button type="submit" disabled={enviando}>
        {enviando ? "Creando cuenta..." : "Registrarme"}
      </button>

      <p className="cambiar">
        ¿Ya tienes cuenta?{" "}
        <button type="button" className="link" onClick={onIrALogin}>
          Inicia sesión
        </button>
      </p>
    </form>
  );
}
