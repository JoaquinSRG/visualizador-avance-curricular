import { useCallback, useEffect, useState } from "react";
import { apiFetch } from "../api";

// HU-13: kárdex del alumno con calificaciones, créditos y promedio
export default function Kardex({ token, onSesionExpirada }) {
  const [kardex, setKardex] = useState(null);
  const [cargando, setCargando] = useState(true);
  const [error, setError] = useState("");
  const [aviso, setAviso] = useState("");
  const [guardando, setGuardando] = useState(null);

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

  const cargarKardex = useCallback(
    () => apiFetch("/kardex", { token }),
    [token],
  );

  useEffect(() => {
    cargarKardex()
      .then(setKardex)
      .catch((err) => manejarError(err, setError))
      .finally(() => setCargando(false));
  }, [cargarKardex, manejarError]);

  // Guarda la calificación cuando el alumno sale del campo
  async function guardarCalificacion(materia, texto) {
    setAviso("");
    const valor = texto.trim() === "" ? null : Number(texto);
    if (valor === materia.calificacion) return;

    setAviso("");
    setGuardando(materia.id);

    try {
      await apiFetch(`/materias/${materia.id}/calificacion`, {
        method: "PATCH",
        body: { calificacion: valor },
        token,
      });
      setKardex(await cargarKardex());
    } catch (err) {
      manejarError(err, setAviso);
    } finally {
      setGuardando(null);
    }
  }

  if (cargando) return <p>Cargando kárdex...</p>;
  if (error) return <p className="error">{error}</p>;

  return (
    <section className="reporte">
      <div className="reporte-encabezado">
        <div>
          <h2 className="titulo-seccion">Kárdex del alumno</h2>
          <p className="contador">Alumno: {kardex.alumno}</p>
        </div>
        <button
          type="button"
          className="boton-imprimir"
          onClick={() => window.print()}
        >
          Imprimir kárdex
        </button>
      </div>

      <ul className="tarjetas">
        <li>
          <span>Materias cursadas</span>
          <strong>
            {kardex.materias_cursadas} de {kardex.total_materias}
          </strong>
        </li>
        <li>
          <span>Créditos cursados</span>
          <strong>
            {kardex.creditos_cursados} de {kardex.creditos_totales}
          </strong>
        </li>
        <li>
          <span>Créditos faltantes</span>
          <strong>{kardex.creditos_faltantes}</strong>
        </li>
        <li>
          <span>Promedio general</span>
          <strong>{kardex.promedio_general ?? "Sin calificaciones"}</strong>
        </li>
      </ul>

      {aviso && <p className="error aviso">{aviso}</p>}

      {kardex.semestres.length === 0 && (
        <p className="sin-resultados">
          Todavía no tienes materias cursadas. Márcalas en el plan de estudios y
          aquí aparecerá tu kárdex.
        </p>
      )}

      {kardex.semestres.map((semestre) => (
        <section key={semestre.semestre} className="semestre">
          <h3 className="semestre-titulo">
            Semestre {semestre.semestre}{" "}
            <span className="contador">
              {semestre.creditos} créditos · promedio{" "}
              {semestre.promedio ?? "sin calificaciones"}
            </span>
          </h3>

          <table className="tabla-kardex">
            <thead>
              <tr>
                <th>Clave</th>
                <th>Materia</th>
                <th>Créditos</th>
                <th>Calificación</th>
              </tr>
            </thead>
            <tbody>
              {semestre.materias.map((materia) => (
                <tr key={materia.id}>
                  <td className="clave">{materia.clave}</td>
                  <td>{materia.nombre}</td>
                  <td>{materia.creditos}</td>
                  <td>
                    <input
                      type="number"
                      min="0"
                      max="100"
                      step="1"
                      placeholder="0 a 100"
                      className="input-calificacion"
                      aria-label={`Calificación de ${materia.nombre}`}
                      defaultValue={materia.calificacion ?? ""}
                      disabled={guardando !== null}
                      onBlur={(e) =>
                        guardarCalificacion(materia, e.target.value)
                      }
                    />
                    <span className="solo-impresion">
                      {materia.calificacion ?? "-"}
                    </span>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </section>
      ))}
    </section>
  );
}
