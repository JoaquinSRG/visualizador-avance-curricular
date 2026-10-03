import { useEffect, useState } from "react";
import { apiFetch } from "../api";

export default function MateriasList({ token, onSesionExpirada }) {
  const [materias, setMaterias] = useState([]);
  const [cargando, setCargando] = useState(true);
  const [error, setError] = useState("");
  const [aviso, setAviso] = useState("");

  useEffect(() => {
    apiFetch("/materias", { token })
      .then(setMaterias)
      .catch((err) => {
        if (err.status === 401) {
          onSesionExpirada();
        } else {
          setError(err.message);
        }
      })
      .finally(() => setCargando(false));
  }, [token, onSesionExpirada]);

  // Cambia el estado "cursada" de una materia en la lista
  function actualizarCursada(id, cursada) {
    setMaterias((actuales) =>
      actuales.map((m) => (m.id === id ? { ...m, cursada } : m)),
    );
  }

  // Marca o desmarca una materia y lo guarda en la cuenta del estudiante
  async function alternarCursada(materia) {
    const nuevoEstado = !materia.cursada;
    setAviso("");
    actualizarCursada(materia.id, nuevoEstado);

    try {
      await apiFetch(`/materias/${materia.id}/cursada`, {
        method: nuevoEstado ? "POST" : "DELETE",
        token,
      });
    } catch (err) {
      // Si no se pudo guardar, se regresa al estado anterior
      actualizarCursada(materia.id, !nuevoEstado);
      if (err.status === 401) {
        onSesionExpirada();
      } else {
        setAviso("No se pudo guardar el cambio. Intenta de nuevo.");
      }
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

  const totalCursadas = materias.filter((m) => m.cursada).length;

  return (
    <section>
      <h2 className="titulo-seccion">
        Plan de estudios{" "}
        <span className="contador">
          {totalCursadas} de {materias.length} materias cursadas
        </span>
      </h2>

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
                  className={
                    materia.cursada ? "materia materia-cursada" : "materia"
                  }
                >
                  <div className="materia-info">
                    <span className="clave">{materia.clave}</span>
                    <strong>{materia.nombre}</strong>
                  </div>

                  <div className="materia-datos">
                    <span>{materia.creditos} créditos</span>
                  </div>

                  {materia.prerrequisitos.length > 0 && (
                    <p className="prerrequisitos">
                      Requiere:{" "}
                      {materia.prerrequisitos.map((p) => p.nombre).join(", ")}
                    </p>
                  )}

                  <label className="marcar-cursada">
                    <input
                      type="checkbox"
                      checked={materia.cursada}
                      onChange={() => alternarCursada(materia)}
                    />
                    Cursada
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
