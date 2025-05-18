import { useState } from "react";

export default function RutInput() {
  const [rut, setRut] = useState("");

  function formatearRut(rut) {
    let valor = rut.replace(/[^0-9kK]/g, "").toUpperCase();
    let cuerpo = valor.slice(0, -1);
    let dv = valor.slice(-1);
    let cuerpoFormateado = "";
    let contador = 0;

    for (let i = cuerpo.length - 1; i >= 0; i--) {
      cuerpoFormateado = cuerpo[i] + cuerpoFormateado;
      contador++;
      if (contador === 3 && i !== 0) {
        cuerpoFormateado = "." + cuerpoFormateado;
        contador = 0;
      }
    }

    return cuerpoFormateado + (dv ? "-" + dv : "");
  }

  const handleChange = (e) => {
    const val = e.target.value;
    const formateado = formatearRut(val);
    setRut(formateado);
  };

  return (
    <input
      type="text"
      name="rut"
      value={rut}
      onChange={handleChange}
      placeholder="12.345.678-9"
    />
  );
}
