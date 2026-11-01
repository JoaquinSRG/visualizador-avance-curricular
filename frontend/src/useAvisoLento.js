import { useEffect, useState } from "react";

export function useAvisoLento(activo, ms = 4000) {
  const [lento, setLento] = useState(false);

  useEffect(() => {
    if (!activo) return;
    const timer = setTimeout(() => setLento(true), ms);
    return () => {
      clearTimeout(timer);
      setLento(false);
    };
  }, [activo, ms]);

  return lento;
}
