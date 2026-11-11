import { useEffect, useState } from "react";
import { apiFetch } from "../api";

const OPCIONES = [1, 2, 3, 4, 5, 6, 7, 8];

// Fecha local del alumno, por ejemplo "10 de noviembre de 2026"
const FECHA_HOY = new Date().toLocaleDateString("es-MX", { dateStyle: "long" });

// HU-15: reporte de propuesta de inscripción para el siguiente ciclo
export default function Propuesta({ token, onSesionExpirada }) {
  const [maxMaterias, setMaxMaterias] = useState(6);
  const [propuesta, setPropuesta] = useState(null);
  const [error, setError] = useState("");

  useEffect(() => {
    apiFetch(`/propuesta?max_materias=${maxMaterias}`, { token })
      .then(setPropuesta)
      .catch((err) => {
        if (err.status === 401) {
          onSesionExpirada();
        } else {
          setError(err.message);
        }
      });
  }, [maxMaterias, token, onSesionExpirada]);

  if (error) return <p className="error">{error}</p>;
  if (!propuesta) return <p>Cargando propuesta...</p>;

  return (
    <section className="reporte">
      <div className="reporte-encabezado">
        <div>
          <h2 className="titulo-seccion">
            Reporte de propuesta de inscripción
          </h2>
          <p className="contador">
            Alumno: {propuesta.alumno} · Fecha: {FECHA_HOY}
          </p>
        </div>
        <button
          type="button"
          className="boton-imprimir"
          onClick={() => window.print()}
        >
          Imprimir reporte
        </button>
      </div>

      <label className="selector no-imprimir">
        ¿Cuántas materias quieres llevar?
        <select
          value={maxMaterias}
          onChange={(e) => setMaxMaterias(Number(e.target.value))}
        >
          {OPCIONES.map((n) => (
            <option key={n} value={n}>
              {n}
            </option>
          ))}
        </select>
      </label>

      <p>
        Tienes <strong>{propuesta.total_disponibles}</strong> materias
        disponibles. Se proponen primero las de semestres más bajos, porque son
        las que desbloquean a las demás.
      </p>

      {propuesta.materias.length === 0 ? (
        <p className="sin-resultados">
          No tienes materias disponibles para inscribir.
        </p>
      ) : (
        <>
          <ol className="lista-propuesta">
            {propuesta.materias.map((materia) => (
              <li key={materia.id}>
                <span className="clave">{materia.clave}</span> {materia.nombre}{" "}
                <span className="contador">
                  (semestre {materia.semestre}, {materia.creditos} créditos)
                </span>
              </li>
            ))}
          </ol>
          <p className="total-propuesta">
            Total: {propuesta.materias.length} materias y{" "}
            {propuesta.total_creditos} créditos
          </p>
        </>
      )}
    </section>
  );
}
