import { useCallback, useEffect, useState } from "react";
import { apiFetch } from "../api";
import ResumenAvance from "./ResumenAvance";

const ETIQUETAS = {
  cursada: "Cursada",
  disponible: "Disponible",
  bloqueada: "Bloqueada",
};

export default function MateriasList({ token, onSesionExpirada }) {
  const [materias, setMaterias] = useState([]);
  const [resumen, setResumen] = useState(null);
  const [cargando, setCargando] = useState(true);
  const [guardando, setGuardando] = useState(null);
  const [error, setError] = useState("");
  const [aviso, setAviso] = useState("");

  const manejarError = useCallback(
    (err, mostrar) => {
      if (err.status === 401) {
        onSesionExpirada();
      } else {
        mostrar(err.message);
      }
    },
    [onSesionExpirada],
  );

  // Trae las materias (con su estado según prerrequisitos) y el resumen
  const obtenerDatos = useCallback(
    () =>
      Promise.all([
        apiFetch("/materias", { token }),
        apiFetch("/resumen", { token }),
      ]),
    [token],
  );

  function mostrarDatos([listaMaterias, datosResumen]) {
    setMaterias(listaMaterias);
    setResumen(datosResumen);
  }

  useEffect(() => {
    obtenerDatos()
      .then(mostrarDatos)
      .catch((err) => manejarError(err, setError))
      .finally(() => setCargando(false));
  }, [obtenerDatos, manejarError]);

  // Marca o desmarca una materia. El backend valida los prerrequisitos (HU-05)
  // y después se recargan los datos para actualizar qué se desbloqueó.
  async function alternarCursada(materia) {
    setAviso("");
    setGuardando(materia.id);

    try {
      await apiFetch(`/materias/${materia.id}/cursada`, {
        method: materia.cursada ? "DELETE" : "POST",
        token,
      });
      mostrarDatos(await obtenerDatos());
    } catch (err) {
      manejarError(err, setAviso);
    } finally {
      setGuardando(null);
    }
  }

  if (cargando) return <p>Cargando materias...</p>;
  if (error) return <p className="error">{error}</p>;

  // Agrupa las materias por semestre: { 1: [...], 2: [...], ... }
  const porSemestre = materias.reduce((grupos, materia) => {
    (grupos[materia.semestre] ??= []).push(materia);
    return grupos;
  }, {});

  const semestres = Object.keys(porSemestre)
    .map(Number)
    .sort((a, b) => a - b);

  return (
    <section>
      <ResumenAvance resumen={resumen} />

      <h2 className="titulo-seccion">Plan de estudios</h2>

      {aviso && <p className="error aviso">{aviso}</p>}

      {semestres.map((semestre) => {
        const delSemestre = porSemestre[semestre];
        const cursadasSemestre = delSemestre.filter((m) => m.cursada).length;

        return (
          <section key={semestre} className="semestre">
            <h3 className="semestre-titulo">
              Semestre {semestre}{" "}
              <span className="contador">
                {cursadasSemestre} de {delSemestre.length} cursadas
              </span>
            </h3>

            <ul className="lista-materias">
              {delSemestre.map((materia) => (
                <li
                  key={materia.id}
                  className={`materia materia-${materia.estado}`}
                >
                  <div className="materia-info">
                    <div className="materia-fila">
                      <span className="clave">{materia.clave}</span>
                      <span className={`estado estado-${materia.estado}`}>
                        {ETIQUETAS[materia.estado]}
                      </span>
                    </div>
                    <strong>{materia.nombre}</strong>
                  </div>

                  <div className="materia-datos">
                    <span>{materia.creditos} créditos</span>
                  </div>

                  {materia.estado === "bloqueada" ? (
                    <p className="prerrequisitos">
                      Te falta:{" "}
                      {materia.faltantes.map((f) => f.nombre).join(", ")}
                    </p>
                  ) : (
                    materia.prerrequisitos.length > 0 && (
                      <p className="prerrequisitos prerrequisitos-ok">
                        Requiere:{" "}
                        {materia.prerrequisitos.map((p) => p.nombre).join(", ")}
                      </p>
                    )
                  )}

                  <label className="marcar-cursada">
                    <input
                      type="checkbox"
                      checked={materia.cursada}
                      disabled={
                        materia.estado === "bloqueada" || guardando !== null
                      }
                      onChange={() => alternarCursada(materia)}
                    />
                    {guardando === materia.id ? "Guardando..." : "Cursada"}
                  </label>
                </li>
              ))}
            </ul>
          </section>
        );
      })}
    </section>
  );
}
