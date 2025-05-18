import { useState, useEffect } from "react";
import "../styles/UnidadForm.css";

export default function UnidadForm({ proyectoId, unidad, onCancel, onSave, permitirNuevoCliente }) {
  const [clientes, setClientes] = useState([]);
  const [clienteSeleccionadoId, setClienteSeleccionadoId] = useState("");
  const [usandoNuevoCliente, setUsandoNuevoCliente] = useState(false);

  const [formData, setFormData] = useState({
    numero_unidad: "",
    tipo_unidad: "",
    metraje_cuadrado: "",
    precio_venta: "",
    estado: "",
    cliente: {
      rut: "",
      nombre: "",
      apellido: "",
      email: "",
      telefono_contacto: ""
    }
  });

  const [errors, setErrors] = useState({});
  const [validating, setValidating] = useState(false);

  useEffect(() => {
    if (unidad) {
      setFormData({
        numero_unidad: unidad.numero_unidad || "",
        tipo_unidad: unidad.tipo_unidad || "",
        metraje_cuadrado: unidad.metraje_cuadrado || "",
        precio_venta: unidad.precio_venta || "",
        estado: unidad.estado || "",
        cliente: unidad.cliente || {
          rut: "",
          nombre: "",
          apellido: "",
          email: "",
          telefono_contacto: ""
        }
      });
      if (unidad.cliente && unidad.cliente.id) {
        setClienteSeleccionadoId(unidad.cliente.id);
        setUsandoNuevoCliente(false);
      }
    }
  }, [unidad]);

  useEffect(() => {
    if (permitirNuevoCliente) {
      const token = localStorage.getItem("token");
      fetch("/api/clientes", {
        headers: {
          Authorization: `Bearer ${token}`,
          Accept: "application/json",
        },
      })
        .then((res) => res.json())
        .then((data) => setClientes(data))
        .catch(() => setClientes([]));
    }
  }, [permitirNuevoCliente]);

  function formatRut(value) {
    const clean = value.replace(/[^0-9kK]/g, "").toUpperCase();
    if (!clean) return "";
    let body = clean.slice(0, -1);
    let dv = clean.slice(-1);
    let formattedBody = "";
    let counter = 0;
    for (let i = body.length - 1; i >= 0; i--) {
      formattedBody = body[i] + formattedBody;
      counter++;
      if (counter === 3 && i !== 0) {
        formattedBody = "." + formattedBody;
        counter = 0;
      }
    }
    return `${formattedBody}-${dv}`;
  }

  const validate = async () => {
    const token = localStorage.getItem("token");
    const newErrors = {};

    if (!formData.numero_unidad) {
      newErrors.numero_unidad = "Este campo es obligatorio";
    } else if (!unidad || formData.numero_unidad !== unidad.numero_unidad) {
      try {
        const res = await fetch(`/api/unidades/validar-numero/${formData.numero_unidad}/${proyectoId}`, {
          headers: {
            Authorization: `Bearer ${token}`,
            Accept: "application/json",
          },
        });
        if (!res.ok) throw new Error("Error en la validación");
        const data = await res.json();
        if (data.exists) {
          newErrors.numero_unidad = "El número de unidad ya existe";
        }
      } catch {
        newErrors.numero_unidad = "No se pudo validar el número de unidad";
      }
    }

    if (!formData.metraje_cuadrado || isNaN(formData.metraje_cuadrado)) {
      newErrors.metraje_cuadrado = "Debe ingresar un número válido";
    }

    if (!formData.precio_venta || isNaN(formData.precio_venta)) {
      newErrors.precio_venta = "Debe ingresar un precio numérico";
    }

    if (!formData.estado) {
      newErrors.estado = "Debe seleccionar un estado";
    }

    if (permitirNuevoCliente && usandoNuevoCliente) {
      const rutRegex = /^\d{1,2}\.\d{3}\.\d{3}-[\dkK]$/;
      if (!rutRegex.test(formData.cliente.rut)) {
        newErrors.rut = "RUT inválido. Ej: 12.345.678-9";
      } else {
        try {
          const res = await fetch(`/api/clientes/validar-rut/${formData.cliente.rut}`, {
            headers: {
              Authorization: `Bearer ${token}`,
              Accept: "application/json",
            },
          });
          if (!res.ok) throw new Error("Error en la validación");
          const data = await res.json();
          if (data.exists) {
            newErrors.rut = "El RUT ya existe";
          }
        } catch {
          newErrors.rut = "No se pudo validar el RUT";
        }
      }

      const letrasRegex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
      if (!letrasRegex.test(formData.cliente.nombre)) {
        newErrors.nombre = "Solo letras permitidas";
      }

      if (!letrasRegex.test(formData.cliente.apellido)) {
        newErrors.apellido = "Solo letras permitidas";
      }

      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(formData.cliente.email)) {
        newErrors.email = "Email inválido";
      }

      if (!/^\d+$/.test(formData.cliente.telefono_contacto)) {
        newErrors.telefono_contacto = "Solo números permitidos";
      }
    }

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    if (name === "rut") {
      const formattedRut = formatRut(value);
      setFormData((prev) => ({
        ...prev,
        cliente: { ...prev.cliente, rut: formattedRut },
      }));
    } else if (name in formData.cliente) {
      setFormData((prev) => ({
        ...prev,
        cliente: { ...prev.cliente, [name]: value },
      }));
    } else {
      setFormData((prev) => ({ ...prev, [name]: value }));
    }
  };

  const handleClienteSelect = (e) => {
    const selectedId = e.target.value;
    setClienteSeleccionadoId(selectedId);
    if (selectedId === "nuevo") {
      setUsandoNuevoCliente(true);
      setFormData((prev) => ({
        ...prev,
        cliente: {
          rut: "",
          nombre: "",
          apellido: "",
          email: "",
          telefono_contacto: "",
        },
      }));
    } else {
      setUsandoNuevoCliente(false);
      const cliente = clientes.find((c) => c.id.toString() === selectedId);
      if (cliente) {
        setFormData((prev) => ({
          ...prev,
          cliente,
        }));
      }
    }
  };

const handleSubmit = async (e) => {
  e.preventDefault();
  setValidating(true);
  const isValid = await validate();
  setValidating(false);
  if (!isValid) return;

  const token = localStorage.getItem("token");

  // Preparar el body para enviar
  const body = {
    numero_unidad: formData.numero_unidad,
    tipo_unidad: formData.tipo_unidad,
    metraje_cuadrado: formData.metraje_cuadrado,
    precio_venta: formData.precio_venta,
    estado: formData.estado,
    cliente_id: usandoNuevoCliente ? null : clienteSeleccionadoId
  };
if (usandoNuevoCliente && !unidad?.id) {
  body.cliente = formData.cliente;
}
  try {
    let res;
    if (unidad && unidad.id) {
      // Editar unidad - PUT
      res = await fetch(`/api/proyectos/${proyectoId}/unidades/${unidad.id}`, {
        method: "PUT",
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${token}`,
          Accept: "application/json",
        },
        body: JSON.stringify(body),
      });
    } else {
      // Crear unidad - POST
        res = await fetch(`/api/proyectos/${proyectoId}/unidades`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${token}`,
          Accept: "application/json",
        },
        body: JSON.stringify(body),
      });
    }

    if (!res.ok) {
      const errorData = await res.json();
      // Aquí podrías mostrar errores desde backend si quieres
      alert("Error guardando la unidad: " + (errorData.message || res.statusText));
      return;
    }

    const data = await res.json();

    // Llamar a onSave con la respuesta para que el padre actualice la UI
    onSave(data);
  } catch (error) {
    alert("Error al comunicarse con el servidor.");
  }
};


  return (
    <div className="unidad-form">
      <h4>{unidad ? "Editar Unidad" : "Nueva Unidad"}</h4>
      <form onSubmit={handleSubmit} noValidate>
        <label>Número de unidad</label>
        <input name="numero_unidad" value={formData.numero_unidad} onChange={handleChange} required />
        {errors.numero_unidad && <span className="error-msg">{errors.numero_unidad}</span>}

        <label>Tipo de unidad</label>
        <input name="tipo_unidad" value={formData.tipo_unidad} onChange={handleChange} required />

        <label>Metraje cuadrado</label>
        <input name="metraje_cuadrado" value={formData.metraje_cuadrado} onChange={handleChange} />
        {errors.metraje_cuadrado && <span className="error-msg">{errors.metraje_cuadrado}</span>}

        <label>Precio de venta (UF)</label>
        <input name="precio_venta" value={formData.precio_venta} onChange={handleChange} />
        {errors.precio_venta && <span className="error-msg">{errors.precio_venta}</span>}

        <label>Estado</label>
        <select name="estado" value={formData.estado} onChange={handleChange}>
          <option value="">Seleccione</option>
          <option value="Disponible">Disponible</option>
          <option value="Reservado">Reservado</option>
          <option value="Vendido">Vendido</option>
        </select>
        {errors.estado && <span className="error-msg">{errors.estado}</span>}

        {permitirNuevoCliente && (
          <>
            <hr />
            <label>Cliente</label>
            <select value={clienteSeleccionadoId} onChange={handleClienteSelect}>
              <option value="">Seleccione un cliente</option>
              {clientes.map((cliente) => (
                <option key={cliente.id} value={cliente.id}>
                  {cliente.nombre} {cliente.apellido} - {cliente.rut}
                </option>
              ))}
              <option value="nuevo">Nuevo Cliente</option>
            </select>

            {usandoNuevoCliente && (
              <>
                <hr />
                <h5>Datos del Cliente Nuevo</h5>

                <label>RUT</label>
                <input name="rut" value={formData.cliente.rut} onChange={handleChange} placeholder="12.345.678-9" />
                {errors.rut && <span className="error-msg">{errors.rut}</span>}

                <label>Nombre</label>
                <input name="nombre" value={formData.cliente.nombre} onChange={handleChange} />
                {errors.nombre && <span className="error-msg">{errors.nombre}</span>}

                <label>Apellido</label>
                <input name="apellido" value={formData.cliente.apellido} onChange={handleChange} />
                {errors.apellido && <span className="error-msg">{errors.apellido}</span>}

                <label>Email</label>
                <input name="email" value={formData.cliente.email} onChange={handleChange} />
                {errors.email && <span className="error-msg">{errors.email}</span>}

                <label>Teléfono</label>
                <input name="telefono_contacto" value={formData.cliente.telefono_contacto} onChange={handleChange} />
                {errors.telefono_contacto && <span className="error-msg">{errors.telefono_contacto}</span>}
              </>
            )}
          </>
        )}

        <div className="form-actions">
          <button type="submit" disabled={validating}>
            {unidad ? "Guardar cambios" : "Crear unidad"}
          </button>
          <button type="button" onClick={onCancel}>Cancelar</button>
        </div>
      </form>
    </div>
  );
}
