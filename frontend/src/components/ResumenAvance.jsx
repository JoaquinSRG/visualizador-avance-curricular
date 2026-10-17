// HU-10: panel con el resumen del avance del estudiante
export default function ResumenAvance({ resumen }) {
  if (!resumen) return null;

  return (
    <section className="resumen">
      <div className="resumen-encabezado">
        <h2>Mi avance</h2>
        <span className="resumen-porcentaje">{resumen.porcentaje}%</span>
      </div>

      <div
        className="barra"
        role="progressbar"
        aria-valuenow={resumen.porcentaje}
        aria-valuemin={0}
        aria-valuemax={100}
      >
        <div
          className="barra-relleno"
          style={{ width: `${resumen.porcentaje}%` }}
        />
      </div>

      <ul className="resumen-datos">
        <li>
          <strong>{resumen.cursadas}</strong> de {resumen.total_materias}{" "}
          materias cursadas
        </li>
        <li>
          <strong>{resumen.creditos_cursados}</strong> de{" "}
          {resumen.creditos_totales} créditos
        </li>
        <li className="dato-disponible">
          <strong>{resumen.disponibles}</strong> disponibles para inscribir
        </li>
        <li className="dato-bloqueada">
          <strong>{resumen.bloqueadas}</strong> bloqueadas por prerrequisitos
        </li>
      </ul>

      {resumen.para_inscribir.length > 0 && (
        <details className="para-inscribir">
          <summary>Ver materias que ya puedes inscribir</summary>
          <ul>
            {resumen.para_inscribir.map((m) => (
              <li key={m.id}>
                <span className="clave">{m.clave}</span> {m.nombre}{" "}
                <span className="contador">(semestre {m.semestre})</span>
              </li>
            ))}
          </ul>
        </details>
      )}
    </section>
  );
}
