import { useEffect, useState } from "react";
import { apiFetch } from "../api";

export default function MateriasList({ token, onSesionExpirada }) {
  const [materias, setMaterias] = useState([]);
  const [cargando, setCargando] = useState(true);
  const [error, setError] = useState("");

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
      <h2 className="titulo-seccion">
        Plan de estudios{" "}
        <span className="contador">{materias.length} materias</span>
      </h2>

      {semestres.map((semestre) => (
        <section key={semestre} className="semestre">
          <h3 className="semestre-titulo">
            Semestre {semestre}{" "}
            <span className="contador">
              {porSemestre[semestre].length} materias
            </span>
          </h3>

          <ul className="lista-materias">
            {porSemestre[semestre].map((materia) => (
              <li key={materia.id} className="materia">
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
              </li>
            ))}
          </ul>
        </section>
      ))}
    </section>
  );
}
