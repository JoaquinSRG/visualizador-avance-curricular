import { useCallback, useEffect, useState } from "react";
import { apiFetch } from "../api";

const ETIQUETAS = {
  cursada: "Cursada",
  disponible: "Disponible",
  bloqueada: "Bloqueada",
};

// HU-14: requisitos para cursar una materia y materias seriadas
export default function Seriacion({ token, onSesionExpirada, materiaInicial }) {
  const [materias, setMaterias] = useState([]);
  const [seleccionada, setSeleccionada] = useState(materiaInicial ?? "");
  const [detalle, setDetalle] = useState(null);
  const [error, setError] = useState("");

  const manejarError = useCallback(
    (err) => {
      if (err.status === 401) {
        onSesionExpirada();
      } else {
        setError(err.message);
      }
    },
    [onSesionExpirada],
  );

  // Lista de materias para el selector
  useEffect(() => {
    apiFetch("/materias", { token }).then(setMaterias).catch(manejarError);
  }, [token, manejarError]);

  // Detalle de la materia elegida
  useEffect(() => {
    if (!seleccionada) return;

    apiFetch(`/materias/${seleccionada}/seriacion`, { token })
      .then(setDetalle)
      .catch(manejarError);
  }, [seleccionada, token, manejarError]);

  // Materias que piden al menos un requisito
  const seriadas = materias.filter(
    (materia) => materia.prerrequisitos.length > 0,
  );

  return (
    <section>
      <h2 className="titulo-seccion">Requisitos y seriación</h2>

      <label className="selector">
        Elige una materia
        <select
          value={seleccionada}
          onChange={(e) => {
            setDetalle(null);
            setSeleccionada(e.target.value);
          }}
        >
          <option value="">Selecciona...</option>
          {materias.map((materia) => (
            <option key={materia.id} value={materia.id}>
              {materia.clave} · {materia.nombre} (semestre {materia.semestre})
            </option>
          ))}
        </select>
      </label>

      {error && <p className="error aviso">{error}</p>}

      {detalle && (
        <div className="detalle-seriacion">
          <div className="materia-fila">
            <h3>
              {detalle.clave} · {detalle.nombre}
            </h3>
            <span className={`estado estado-${detalle.estado}`}>
              {ETIQUETAS[detalle.estado]}
            </span>
          </div>
          <p className="contador">
            Semestre {detalle.semestre} · {detalle.creditos} créditos
          </p>

          <h4>Requisitos para cursarla</h4>
          {detalle.requisitos.length === 0 ? (
            <p className="sin-resultados">
              No tiene requisitos, se puede cursar desde el inicio.
            </p>
          ) : (
            <ul className="lista-seriacion">
              {detalle.requisitos.map((requisito) => (
                <li
                  key={requisito.id}
                  className={requisito.cumplido ? "cumplido" : "pendiente"}
                >
                  {requisito.cumplido ? "✔" : "✖"} {requisito.clave} ·{" "}
                  {requisito.nombre} (semestre {requisito.semestre})
                  <span className="contador">
                    {requisito.cumplido
                      ? " ya la cursaste"
                      : " te falta cursarla"}
                  </span>
                </li>
              ))}
            </ul>
          )}

          <h4>Materias seriadas que se desbloquean con ella</h4>
          {detalle.desbloquea.length === 0 ? (
            <p className="sin-resultados">
              No es requisito de ninguna otra materia.
            </p>
          ) : (
            <ul className="lista-seriacion">
              {detalle.desbloquea.map((seriada) => (
                <li key={seriada.id}>
                  {seriada.clave} · {seriada.nombre} (semestre{" "}
                  {seriada.semestre}){" "}
                  <span className={`estado estado-${seriada.estado}`}>
                    {ETIQUETAS[seriada.estado]}
                  </span>
                </li>
              ))}
            </ul>
          )}
        </div>
      )}

      <h3 className="semestre-titulo">Todas las materias seriadas</h3>
      <ul className="lista-seriacion">
        {seriadas.map((materia) => (
          <li key={materia.id}>
            <button
              type="button"
              className="link"
              onClick={() => {
                setDetalle(null);
                setSeleccionada(String(materia.id));
              }}
            >
              {materia.clave} · {materia.nombre}
            </button>{" "}
            <span className="contador">
              requiere {materia.prerrequisitos.map((p) => p.nombre).join(", ")}
            </span>
          </li>
        ))}
      </ul>
    </section>
  );
}
